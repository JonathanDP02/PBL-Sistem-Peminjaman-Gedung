<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingStep;
use App\Models\Unit;
use App\Models\Workflow;
use App\Models\WorkflowStep;
use Illuminate\Support\Collection;

/**
 * WorkflowBridgeService
 *
 * Implements the 3-tier Dynamic Workflow Bridging algorithm.
 *
 * Rules:
 * - Tier 1 (Internal): Workflow milik unit peminjam (ormawa/jurusan itu sendiri).
 *                       Hanya berlaku jika unit peminjam == level 'Organisasi'.
 * - Tier 2 (BEM/Pemilik): Jika peminjam adalah 'Organisasi' dan scope 'Lintas Jurusan',
 *                          sisipkan workflow BEM Polinema (unit level 'Organisasi' tanpa parent jurusan).
 *                          Kemudian SELALU sisipkan workflow unit pemilik ruangan.
 * - Tier 3 (Pusat): Jika scope 'Lintas Jurusan' dan pemilik ruangan bukan Pusat,
 *                   sisipkan workflow Pusat (unit level 'Pusat') di akhir.
 *
 * Contoh kasus:
 * HMTI (Organisasi, parent: JTI) meminjam Lab TI (pemilik: JTI), scope Internal:
 *   → Humas HMTI → Ketua HMTI → Kajur TI
 *
 * HMTI meminjam Lab TI, scope Lintas Jurusan:
 *   → Humas HMTI → Ketua HMTI → Ketua BEM → Kajur TI → Wadir 3
 *
 * Formadiksi (Organisasi, tanpa parent jurusan) meminjam Lab TI, scope Lintas Jurusan:
 *   → Ketua Formadiksi → Ketua BEM → Kajur TI → Wadir 3
 *
 * Dosen (level Jurusan, unit = JTI) meminjam Lab TI, scope Internal:
 *   → Kajur TI   (hanya pemilik ruangan, tidak ada Tier 1 ormawa)
 */
class WorkflowBridgeService
{
    /**
     * Build and persist the instantiated booking_steps chain for a booking.
     * Call this inside a DB::transaction() right after Booking::create().
     *
     * @param  string  $eventScope  'Internal' | 'Lintas Jurusan'
     * @return Collection<BookingStep> Ordered list of created booking steps
     */
    public function buildAndPersistChain(Booking $booking, string $eventScope): Collection
    {
        $steps = $this->resolveStepChain($booking, $eventScope);

        // Persist each resolved step
        $createdSteps = collect();
        foreach ($steps as $index => $step) {
            $createdSteps->push(BookingStep::create([
                'booking_id' => $booking->id,
                'position_id' => $step['position_id'],
                'step_order' => $index + 1,
                'requires_attachment' => $step['requires_attachment'],
                'tier_label' => $step['tier_label'],
            ]));
        }

        return $createdSteps;
    }

    /**
     * Resolve the ordered step chain as a plain array of step data.
     * This is the pure logic (easily unit-testable without DB side effects).
     *
     * @return array<int, array{position_id: int, requires_attachment: bool, tier_label: string}>
     */
    public function resolveStepChain(Booking $booking, string $eventScope): array
    {
        $booking->load(['room.unit', 'user.unit']);

        $borrowerUnit = $booking->user->unit;
        $roomOwnerUnit = $booking->room->unit;

        $steps = [];

        // ─── Tier 1: Internal ormawa steps ──────────────────────────────────
        // Only apply when the borrower's unit is level 'Organisasi'
        if ($borrowerUnit->level === 'Organisasi') {
            $tier1Steps = $this->fetchWorkflowSteps($borrowerUnit->id, 'Internal');
            foreach ($tier1Steps as $s) {
                $steps[] = [
                    'position_id' => $s->position_id,
                    'requires_attachment' => $s->requires_attachment,
                    'tier_label' => 'Internal ('.$borrowerUnit->unit_name.')',
                ];
            }
        }

        // ─── Tier 2a: BEM Polinema ───────────────────────────────────────────
        // Insert BEM steps when:
        //   - Borrower is an 'Organisasi'
        //   - AND scope is 'Lintas Jurusan'
        //   - AND borrower is NOT the BEM itself
        if (
            $borrowerUnit->level === 'Organisasi'
            && $eventScope === 'Lintas Jurusan'
        ) {
            $bemUnit = $this->findBemUnit();
            if ($bemUnit && $bemUnit->id !== $borrowerUnit->id) {
                $bemSteps = $this->fetchWorkflowSteps($bemUnit->id, 'Internal');
                foreach ($bemSteps as $s) {
                    $steps[] = [
                        'position_id' => $s->position_id,
                        'requires_attachment' => $s->requires_attachment,
                        'tier_label' => 'BEM ('.$bemUnit->unit_name.')',
                    ];
                }
            }
        }

        // ─── Tier 2b: Pemilik Ruangan (always last or second-to-last) ────────
        // Avoid duplicating if borrower == room owner (e.g. Kajur meminjam ruangannya sendiri)
        if ($roomOwnerUnit->id !== $borrowerUnit->id) {
            $ownerSteps = $this->fetchWorkflowSteps($roomOwnerUnit->id, 'Internal');
            foreach ($ownerSteps as $s) {
                $steps[] = [
                    'position_id' => $s->position_id,
                    'requires_attachment' => $s->requires_attachment,
                    'tier_label' => 'Pemilik Ruangan ('.$roomOwnerUnit->unit_name.')',
                ];
            }
        }

        // ─── Tier 3: Pusat (Wadir III) ─────────────────────────────────────────
        // Append Pusat workflow only when:
        //   - Scope is 'Lintas Jurusan'
        //   - AND room owner is not already Pusat (no double-counting)
        if ($eventScope === 'Lintas Jurusan' && $roomOwnerUnit->level !== 'Pusat') {
            $pusatUnit = $this->findPusatUnit();
            if ($pusatUnit) {
                $pusatSteps = $this->fetchWorkflowSteps($pusatUnit->id, 'Internal');
                $wadir3Step = null;
                foreach ($pusatSteps as $s) {
                    $pos = \App\Models\Position::find($s->position_id);
                    if ($pos && str_contains(strtolower($pos->name), 'iii')) {
                        $wadir3Step = $s;
                        break;
                    }
                }
                
                // Fallback to the last step if name 'III' not matched
                if (! $wadir3Step && $pusatSteps->isNotEmpty()) {
                    $wadir3Step = $pusatSteps->last();
                }

                if ($wadir3Step) {
                    $steps[] = [
                        'position_id' => $wadir3Step->position_id,
                        'requires_attachment' => $wadir3Step->requires_attachment,
                        'tier_label' => 'Pusat ('.$pusatUnit->unit_name.')',
                    ];
                }
            }
        }

        // Edge case: no steps resolved at all — fallback to borrower's own unit workflow
        if (empty($steps)) {
            $fallbackSteps = $this->fetchWorkflowSteps($borrowerUnit->id, 'Internal');
            foreach ($fallbackSteps as $s) {
                $steps[] = [
                    'position_id' => $s->position_id,
                    'requires_attachment' => $s->requires_attachment,
                    'tier_label' => 'Internal ('.$borrowerUnit->unit_name.')',
                ];
            }
        }

        return $steps;
    }

    /**
     * Rebuild the booking_steps chain (called on revision/resubmit).
     * Deletes all existing steps for the booking and creates a fresh chain.
     */
    public function rebuildChain(Booking $booking, string $eventScope): Collection
    {
        BookingStep::where('booking_id', $booking->id)->delete();

        return $this->buildAndPersistChain($booking, $eventScope);
    }

    /**
     * Fetch ordered workflow steps for a given unit.
     * Picks the first workflow linked to the unit (general internal workflow).
     *
     * @return \Illuminate\Database\Eloquent\Collection<WorkflowStep>
     */
    private function fetchWorkflowSteps(int $unitId, string $context = 'Internal'): \Illuminate\Database\Eloquent\Collection
    {
        $workflow = Workflow::where('unit_id', $unitId)
            ->whereNull('room_id') // unit-level general workflow (not room-specific)
            ->first();

        if (! $workflow) {
            return collect();
        }

        return WorkflowStep::where('workflow_id', $workflow->id)
            ->orderBy('step_order', 'asc')
            ->get();
    }

    /**
     * Find the BEM Polinema unit — an 'Organisasi' level unit with no parent
     * or explicitly named "BEM Polinema" / "BEM".
     */
    private function findBemUnit(): ?Unit
    {
        $operator = config('database.default') === 'sqlite' ? 'like' : 'ilike';

        // Strategy 1: Find by name pattern
        $bem = Unit::where('level', 'Organisasi')
            ->where(function ($q) use ($operator) {
                $q->where('unit_name', $operator, '%BEM Polinema%')
                    ->orWhere('unit_name', $operator, '%BEM%');
            })
            ->whereNull('parent_id')
            ->first();

        // Strategy 2: Fallback - Organisasi with no parent jurusan
        if (! $bem) {
            $bem = Unit::where('level', 'Organisasi')
                ->whereNull('parent_id')
                ->first();
        }

        return $bem;
    }

    /**
     * Find the Pusat unit (root unit, Wadir 3 lives here).
     */
    private function findPusatUnit(): ?Unit
    {
        return Unit::where('level', 'Pusat')
            ->whereNull('parent_id')
            ->first();
    }
}
