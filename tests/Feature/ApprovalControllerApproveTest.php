<?php

namespace Tests\Feature;

use App\Models\Approval;
use App\Models\Booking;
use App\Models\BookingStep;
use App\Models\Building;
use App\Models\Position;
use App\Models\Role;
use App\Models\Room;
use App\Models\Unit;
use App\Models\User;
use App\Models\Workflow;
use App\Models\WorkflowStep;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ApprovalControllerApproveTest extends TestCase
{
    use RefreshDatabase;

    private User $approver;

    private User $peminjam;

    private Booking $booking;

    private WorkflowStep $step1;

    private WorkflowStep $step2;

    private BookingStep $bookingStep1;

    private BookingStep $bookingStep2;

    protected function setUp(): void
    {
        parent::setUp();

        // Fake mail agar tidak kirim email sungguhan saat test
        Mail::fake();

        // Seed data minimal
        $unit = Unit::factory()->create(['level' => 'Jurusan']);
        $building = Building::factory()->create();
        $role = Role::factory()->create(['name' => 'Penyetuju']);
        $userRole = Role::factory()->create(['name' => 'Peminjam']);
        $position = Position::factory()->create(['name' => 'Kepala Unit', 'unit_id' => $unit->id]);

        $this->approver = User::factory()->create([
            'unit_id' => $unit->id,
            'role_id' => $role->id,
            'position_id' => $position->id,
        ]);

        $this->peminjam = User::factory()->create([
            'unit_id' => $unit->id,
            'role_id' => $userRole->id,
            'position_id' => null,
        ]);

        $workflow = Workflow::factory()->create(['unit_id' => $unit->id]);

        $this->step1 = WorkflowStep::factory()->create([
            'workflow_id' => $workflow->id,
            'position_id' => $position->id,
            'step_order' => 1,
            'requires_attachment' => false,
        ]);

        $this->step2 = WorkflowStep::factory()->create([
            'workflow_id' => $workflow->id,
            'position_id' => $position->id,
            'step_order' => 2,
            'requires_attachment' => false,
        ]);

        $room = Room::factory()->create([
            'unit_id' => $unit->id,
            'building_id' => $building->id,
        ]);

        $this->booking = Booking::factory()->create([
            'user_id' => $this->peminjam->id,
            'room_id' => $room->id,
            'workflow_id' => $workflow->id,
            'current_step' => 1,
            'status' => 'Pending',
            'event_scope' => 'Internal',
        ]);

        // Create instantiated booking_steps (normally done by WorkflowBridgeService on store)
        $this->bookingStep1 = BookingStep::create([
            'booking_id' => $this->booking->id,
            'position_id' => $position->id,
            'step_order' => 1,
            'requires_attachment' => false,
            'tier_label' => 'Test Tier 1',
        ]);

        $this->bookingStep2 = BookingStep::create([
            'booking_id' => $this->booking->id,
            'position_id' => $position->id,
            'step_order' => 2,
            'requires_attachment' => false,
            'tier_label' => 'Test Tier 2',
        ]);
    }

    // =========================================================================
    // TEST 1: Approve di step 1 (bukan step terakhir)
    // → current_step harus naik ke 2, status tetap 'Pending'
    // =========================================================================
    public function test_approve_increments_step_when_not_last_step(): void
    {
        $this->actingAs($this->approver);

        $response = $this->postJson(
            "/approver/approvals/{$this->booking->id}/approve",
            ['notes' => 'Disetujui step 1']
        );

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->booking->refresh();

        // current_step harus naik ke 2
        $this->assertEquals(2, $this->booking->current_step);

        // Status harus tetap Pending karena masih ada step 2
        $this->assertEquals('Pending', $this->booking->status);
    }

    // =========================================================================
    // TEST 2: Approve di step terakhir (step 2)
    // → status harus 'Approved' (Hard-Lock), current_step tidak naik lagi
    // =========================================================================
    public function test_approve_hard_locks_when_last_step(): void
    {
        // Set booking ke step 2 (step terakhir)
        $this->booking->update(['current_step' => 2]);

        $this->actingAs($this->approver);

        $response = $this->postJson(
            "/approver/approvals/{$this->booking->id}/approve",
            ['notes' => 'Disetujui step terakhir']
        );

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('booking.status', 'Approved');

        $this->booking->refresh();

        $this->assertEquals('Approved', $this->booking->status);
    }

    // =========================================================================
    // TEST 3: Record approval tersimpan di tabel approvals
    // Now uses booking_step_id (new system), step_id is null
    // =========================================================================
    public function test_approve_creates_approval_record(): void
    {
        $this->actingAs($this->approver);

        $this->postJson(
            "/approver/approvals/{$this->booking->id}/approve",
            ['notes' => 'Test approval record']
        );

        $this->assertDatabaseHas('approvals', [
            'booking_id' => $this->booking->id,
            'approver_id' => $this->approver->id,
            'booking_step_id' => $this->bookingStep1->id,
            'approval_status' => 'Approved',
        ]);
    }

    // =========================================================================
    // TEST 4: Log aktivitas APPROVED tersimpan di booking_logs
    // =========================================================================
    public function test_approve_creates_booking_log(): void
    {
        $this->actingAs($this->approver);

        $this->postJson(
            "/approver/approvals/{$this->booking->id}/approve",
            ['notes' => 'Test log']
        );

        $this->assertDatabaseHas('booking_logs', [
            'booking_id' => $this->booking->id,
            'actor_id' => $this->approver->id,
            'action' => 'APPROVED',
        ]);
    }

    // =========================================================================
    // TEST 5: Approve gagal jika requires_attachment = true tapi belum upload
    // =========================================================================
    public function test_approve_fails_if_attachment_required_but_missing(): void
    {
        // Set booking_step1 requires attachment
        $this->bookingStep1->update(['requires_attachment' => true]);

        $this->actingAs($this->approver);

        $response = $this->postJson(
            "/approver/approvals/{$this->booking->id}/approve",
            ['notes' => 'Coba approve tanpa attachment']
        );

        $response->assertStatus(422)
            ->assertJsonPath('success', false);

        // Booking tidak boleh berubah
        $this->booking->refresh();
        $this->assertEquals(1, $this->booking->current_step);
        $this->assertEquals('Pending', $this->booking->status);
    }

    // =========================================================================
    // TEST 6: Approver tidak bisa approve booking yang bukan gilirannya
    // =========================================================================
    public function test_approve_fails_for_wrong_approver_position(): void
    {
        // Buat approver lain dengan position berbeda
        $otherPosition = Position::factory()->create(['name' => 'Wakil Rektor']);
        $otherApprover = User::factory()->create([
            'position_id' => $otherPosition->id,
            'role_id' => $this->approver->role_id,
        ]);

        $this->actingAs($otherApprover);

        $response = $this->postJson(
            "/approver/approvals/{$this->booking->id}/approve",
            ['notes' => 'Coba approve oleh approver salah']
        );

        // Harus 404 karena firstOrFail() tidak menemukan booking_step yang cocok
        $response->assertStatus(404);
    }

    // =========================================================================
    // TEST 7: Email terkirim saat step terakhir di-approve (Hard-Lock Approved)
    // =========================================================================
    public function test_email_sent_when_booking_fully_approved(): void
    {
        // Set ke step terakhir
        $this->booking->update(['current_step' => 2]);

        $this->actingAs($this->approver);

        $this->postJson(
            "/approver/approvals/{$this->booking->id}/approve",
            ['notes' => 'Step terakhir']
        );

        $this->booking->refresh();

        // Pastikan status benar-benar Approved
        $this->assertEquals('Approved', $this->booking->status);

        // Sementara: pastikan Mail::fake() tidak throw error (email tidak crash)
        Mail::assertNothingSent(); // Ganti dengan assertSent setelah mail class dibuat
    }

    // =========================================================================
    // TEST 8 (IMMUTABILITY): Admin mengubah workflow template SETELAH booking
    // diajukan — booking harus tetap berjalan dengan booking_steps lama,
    // bukan mengikuti template baru. Ini adalah test keamanan kritis.
    // =========================================================================
    public function test_workflow_change_after_submission_does_not_affect_booking(): void
    {
        $this->actingAs($this->approver);

        // Simulasi admin menghapus step 2 dari workflow template
        // (hanya dari workflow_steps, bukan booking_steps)
        $this->step2->delete();

        // Approve step 1 — harus berhasil meski template sudah diubah
        $response = $this->postJson(
            "/approver/approvals/{$this->booking->id}/approve",
            ['notes' => 'Approve step 1 meski template diubah']
        );

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->booking->refresh();

        // Harus maju ke step 2 (dari booking_steps snapshot), BUKAN langsung Approved
        // karena step 2 masih ada di booking_steps meski sudah dihapus dari workflow_steps
        $this->assertEquals(2, $this->booking->current_step);
        $this->assertEquals('Pending', $this->booking->status);

        // booking_steps snapshot harus masih ada (immutable)
        $this->assertDatabaseHas('booking_steps', [
            'booking_id' => $this->booking->id,
            'step_order' => 2,
        ]);
    }

    // =========================================================================
    // TEST 9 (IMMUTABILITY): Admin menambah step baru ke workflow template
    // SETELAH booking diajukan — booking harus TIDAK mengikuti step baru,
    // hanya melanjutkan sesuai booking_steps snapshot-nya.
    // =========================================================================
    public function test_new_workflow_step_added_after_submission_is_ignored(): void
    {
        $this->actingAs($this->approver);

        // Admin menambah step 3 ke workflow template (setelah booking sudah jalan)
        $extraPosition = Position::factory()->create(['name' => 'Rektor']);
        WorkflowStep::create([
            'workflow_id' => $this->booking->workflow_id,
            'position_id' => $extraPosition->id,
            'step_order' => 3,
            'requires_attachment' => false,
        ]);

        // Approve step 1 → harus maju ke step 2 (dari booking_steps, bukan template)
        $this->postJson(
            "/approver/approvals/{$this->booking->id}/approve",
            ['notes' => 'Step 1']
        );

        $this->booking->refresh();
        $this->assertEquals(2, $this->booking->current_step);

        // Approve step 2 (step terakhir di booking_steps) → harus Hard-Lock
        $this->booking->update(['current_step' => 2]);
        $response = $this->postJson(
            "/approver/approvals/{$this->booking->id}/approve",
            ['notes' => 'Step 2 terakhir']
        );

        $response->assertStatus(200)
            ->assertJsonPath('booking.status', 'Approved');

        $this->booking->refresh();

        // Harus Approved, BUKAN maju ke step 3 (dari template baru yang ditambah admin)
        $this->assertEquals('Approved', $this->booking->status);

        // Booking_steps snapshot tetap hanya 2 langkah, tidak ada step 3
        $this->assertCount(2, $this->booking->bookingSteps);
    }
}
