<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingStep;
use App\Models\Position;
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
            if ($tier1Steps->isEmpty()) {
                throw new \Exception("Unit Anda ({$borrowerUnit->unit_name}) belum mengonfigurasi alur persetujuan umum.");
            }
            foreach ($tier1Steps as $s) {
                $steps[] = [
                    'position_id' => $s->position_id,
                    'requires_attachment' => $s->requires_attachment,
                    'tier_label' => 'Internal ('.$borrowerUnit->unit_name.')',
                ];
            }
        }

        // Jika memiliki unit induk yang juga bertipe Organisasi (e.g. HMTI sebagai induk WRI)
        if ($borrowerUnit->parent_id) {
            $parentOrg = Unit::find($borrowerUnit->parent_id);
            if ($parentOrg && $parentOrg->level === 'Organisasi') {
                $parentSteps = $this->fetchWorkflowSteps($parentOrg->id, 'Internal');
                $lastParentStep = $parentSteps->last();
                if ($lastParentStep) {
                    $steps[] = [
                        'position_id' => $lastParentStep->position_id,
                        'requires_attachment' => $lastParentStep->requires_attachment,
                        'tier_label' => 'Induk ('.$parentOrg->unit_name.')',
                    ];
                }
            }
        }

        // ─── Tier 2a: BEM Polinema (Mekanisme Dinamis) ─────────────
        // Ormawa (kecuali BEM & DPM) selalu melewati BEM Polinema (Internal & Lintas Jurusan)
        if (
            $borrowerUnit->level === 'Organisasi'
            && ! str_contains(strtolower($borrowerUnit->unit_name), 'bem')
            && ! str_contains(strtolower($borrowerUnit->unit_name), 'perwakilan')
        ) {
            $bemUnit = $this->findBemUnit();
            if (! $bemUnit) {
                throw new \Exception('Unit BEM Polinema tidak ditemukan.');
            }
            if ($bemUnit->id !== $borrowerUnit->id) {
                $bemSteps = $this->fetchWorkflowSteps($bemUnit->id, 'Internal');
                if ($bemSteps->isEmpty()) {
                    throw new \Exception("Unit BEM Polinema ({$bemUnit->unit_name}) belum mengonfigurasi alur persetujuan umum.");
                }
                foreach ($bemSteps as $s) {
                    $posName = $s->position ? $s->position->name : '';
                    if (str_contains(strtolower($posName), 'pembina') || str_contains(strtolower($posName), 'dpk')) {
                        continue; // Skip BEM DPK for other units
                    }
                    $steps[] = [
                        'position_id' => $s->position_id,
                        'requires_attachment' => $s->requires_attachment,
                        'tier_label' => 'BEM ('.$bemUnit->unit_name.')',
                    ];
                }
            }
        }

        // ─── Tier 2a (Part 2): Pembina (DPK) ─────────────────────────────────
        if ($borrowerUnit->level === 'Organisasi') {
            $operator = config('database.default') === 'sqlite' ? 'like' : 'ilike';
            $departmentUnit = null;
            $currentUnit = $borrowerUnit;
            while ($currentUnit) {
                if ($currentUnit->level === 'Jurusan') {
                    $departmentUnit = $currentUnit;
                    break;
                }
                $currentUnit = $currentUnit->parent_id ? Unit::find($currentUnit->parent_id) : null;
            }

            if ($departmentUnit) {
                $pembinaPos = Position::where('unit_id', $departmentUnit->id)
                    ->where(function ($q) use ($operator) {
                        $q->where('name', $operator, '%Pembina%')
                            ->orWhere('name', $operator, '%DPK%');
                    })
                    ->whereHas('users')
                    ->first();
                $labelSuffix = $departmentUnit->unit_name;
                if (! $pembinaPos) {
                    throw new \Exception("Jabatan DPK (Pembina) untuk {$departmentUnit->unit_name} belum dikonfigurasi atau tidak memiliki user aktif.");
                }
            } else {
                $pembinaPos = Position::where('unit_id', $borrowerUnit->id)
                    ->where(function ($q) use ($operator) {
                        $q->where('name', $operator, '%Pembina%')
                            ->orWhere('name', $operator, '%DPK%');
                    })
                    ->whereHas('users')
                    ->whereIn('id', function ($query) use ($borrowerUnit) {
                        $query->select('position_id')
                            ->from('workflow_steps')
                            ->join('workflows', 'workflow_steps.workflow_id', '=', 'workflows.id')
                            ->where('workflows.unit_id', $borrowerUnit->id)
                            ->whereNull('workflows.room_id');
                    })
                    ->first();
                $labelSuffix = $borrowerUnit->unit_name;
                if (! $pembinaPos) {
                    throw new \Exception("Jabatan DPK (Pembina) untuk {$borrowerUnit->unit_name} belum dikonfigurasi, tidak memiliki user aktif, atau belum terdaftar di SOP alur persetujuan umum.");
                }
            }

            if ($pembinaPos) {
                $steps[] = [
                    'position_id' => $pembinaPos->id,
                    'requires_attachment' => false,
                    'tier_label' => 'DPK ('.$labelSuffix.')',
                ];
            }
        }

        // ─── Tier 2b: Pemilik Ruangan (always last or second-to-last) ────────
        // Avoid duplicating if borrower == room owner (e.g. Kajur meminjam ruangannya sendiri)
        if ($roomOwnerUnit->id !== $borrowerUnit->id) {
            $ownerSteps = $this->fetchWorkflowSteps($roomOwnerUnit->id, 'Internal');
            if ($ownerSteps->isEmpty()) {
                throw new \Exception("Unit pemilik ruangan ({$roomOwnerUnit->unit_name}) belum mengonfigurasi alur persetujuan umum.");
            }
            foreach ($ownerSteps as $s) {
                $steps[] = [
                    'position_id' => $s->position_id,
                    'requires_attachment' => $s->requires_attachment,
                    'tier_label' => 'Pemilik Ruangan ('.$roomOwnerUnit->unit_name.')',
                ];
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

        // De-duplicate steps by position_id to prevent redundant consecutive approvals from the same position
        $uniqueSteps = [];
        $seenPositions = [];
        foreach ($steps as $step) {
            if (! in_array($step['position_id'], $seenPositions)) {
                $uniqueSteps[] = $step;
                $seenPositions[] = $step['position_id'];
            }
        }

        return $uniqueSteps;
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
            return new \Illuminate\Database\Eloquent\Collection;
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
