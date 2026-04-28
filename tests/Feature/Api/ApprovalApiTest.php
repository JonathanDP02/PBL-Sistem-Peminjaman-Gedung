<?php

namespace Tests\Feature\Api;

use App\Models\Booking;
use App\Models\Building;
use App\Models\Position;
use App\Models\Role;
use App\Models\Room;
use App\Models\Unit;
use App\Models\User;
use App\Models\Workflow;
use App\Models\WorkflowStep;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApprovalApiTest extends TestCase
{
    use RefreshDatabase;

    private User $approver;

    private Booking $booking;

    private Workflow $workflow;

    private Room $room;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        $userRole = Role::factory()->create(['name' => 'User']);
        $approverRole = Role::factory()->create(['name' => 'Approver']);

        // Create position
        $position = Position::factory()->create(['name' => 'Kaprodi']);

        // Create building
        $building = Building::factory()->create();

        // Create unit
        $unit = Unit::factory()->create(['unit_name' => 'Jurusan TI', 'level' => 'Jurusan']);

        // Create approver user
        $this->approver = User::factory()->create([
            'role_id' => $approverRole->id,
            'position_id' => $position->id,
            'unit_id' => $unit->id,
        ]);

        // Create room with building
        $this->room = Room::factory()->create([
            'building_id' => $building->id,
            'unit_id' => $unit->id,
            'room_name' => 'Auditorium Utama',
            'capacity' => 500,
        ]);

        // Create workflow with steps
        $this->workflow = Workflow::factory()->create([
            'unit_id' => $unit->id,
            'name' => 'Peminjaman Auditorium',
        ]);

        WorkflowStep::factory()->create([
            'workflow_id' => $this->workflow->id,
            'position_id' => $position->id,
            'step_order' => 1,
            'requires_attachment' => false,
        ]);

        // Create peminjam
        $peminjam = User::factory()->create([
            'role_id' => $userRole->id,
            'unit_id' => $unit->id,
        ]);

        // Create booking
        $this->booking = Booking::factory()->create([
            'room_id' => $this->room->id,
            'user_id' => $peminjam->id,
            'workflow_id' => $this->workflow->id,
            'current_step' => 1,
            'status' => 'Pending',
            'event_name' => 'Seminar Cybersecurity 2026',
            'booking_date' => now()->addDays(5)->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '12:00:00',
        ]);
    }

    /**
     * Test GET /api/approvals/pending
     * Verify response structure matches API_DOCUMENTATION.md
     */
    public function test_get_pending_approvals_returns_correct_structure(): void
    {
        $response = $this->actingAs($this->approver)
            ->getJson('/api/approvals/pending');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'count',
            'data' => [
                '*' => [
                    'id',
                    'booking' => [
                        'id',
                        'event_name',
                        'event_description',
                        'booking_date',
                        'start_time',
                        'end_time',
                        'status',
                        'current_step',
                        'revision_count',
                        'created_at',
                    ],
                    'room' => [
                        'id',
                        'room_name',
                        'room_code',
                        'capacity',
                        'building',
                    ],
                    'peminjam' => [
                        'id',
                        'name',
                        'email',
                        'unit',
                    ],
                    'workflow' => [
                        'id',
                        'name',
                        'total_steps',
                    ],
                    'current_approver_required' => [
                        'step_order',
                        'position',
                        'requires_attachment',
                        'attachment_type',
                    ],
                    'previous_approvals',
                    'documents_uploaded',
                    'priority_indicator',
                    'time_remaining',
                ],
            ],
        ]);

        $data = $response->json('data');
        $this->assertCount(1, $data);

        $approval = $data[0];
        $this->assertEquals($this->booking->id, $approval['booking']['id']);
        $this->assertEquals('Seminar Cybersecurity 2026', $approval['booking']['event_name']);
        $this->assertEquals('Pending', $approval['booking']['status']);
        $this->assertEquals(1, $approval['booking']['current_step']);
        $this->assertEquals('Auditorium Utama', $approval['room']['room_name']);
        $this->assertContains($approval['priority_indicator'], ['urgent', 'high', 'normal']);
    }

    /**
     * Test GET /api/approvals/{booking_id}
     * Verify detail response structure
     */
    public function test_get_approval_detail_returns_correct_structure(): void
    {
        $response = $this->actingAs($this->approver)
            ->getJson("/api/approvals/{$this->booking->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'booking' => [
                'id',
                'event_name',
                'booking_date',
                'start_time',
                'end_time',
                'status',
                'current_step',
                'room',
                'peminjam',
                'workflow',
                'approval_timeline',
                'documents',
            ],
        ]);

        $booking = $response->json('booking');
        $this->assertEquals($this->booking->id, $booking['id']);
    }

    /**
     * Test POST /api/approvals/{booking_id}/approve
     */
    public function test_approve_booking_updates_status_and_returns_response(): void
    {
        // Create a second step for testing
        WorkflowStep::factory()->create([
            'workflow_id' => $this->workflow->id,
            'position_id' => Position::factory()->create()->id,
            'step_order' => 2,
            'requires_attachment' => false,
        ]);

        $response = $this->actingAs($this->approver)
            ->postJson("/api/approvals/{$this->booking->id}/approve", [
                'notes' => 'Disetujui dengan catatan.',
            ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'booking',
            'log',
        ]);

        // Verify booking was updated
        $this->assertDatabaseHas('approvals', [
            'booking_id' => $this->booking->id,
            'approver_id' => $this->approver->id,
            'approval_status' => 'Approved',
        ]);
    }

    /**
     * Test POST /api/approvals/{booking_id}/reject
     */
    public function test_reject_booking_changes_status_to_revising(): void
    {
        $response = $this->actingAs($this->approver)
            ->postJson("/api/approvals/{$this->booking->id}/reject", [
                'notes' => 'Proposal belum memenuhi standar. Perbaiki dan kirim ulang.',
            ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'booking',
            'rejection_log',
        ]);

        $response->assertJson([
            'booking' => [
                'status' => 'Revising',
                'revision_count' => 1,
            ],
        ]);

        // Verify booking was updated
        $this->assertDatabaseHas('bookings', [
            'id' => $this->booking->id,
            'status' => 'Revising',
            'revision_count' => 1,
        ]);

        // Verify approval was created
        $this->assertDatabaseHas('approvals', [
            'booking_id' => $this->booking->id,
            'approval_status' => 'Rejected',
        ]);
    }

    /**
     * Test reject validation - notes is required
     */
    public function test_reject_booking_requires_notes(): void
    {
        $response = $this->actingAs($this->approver)
            ->postJson("/api/approvals/{$this->booking->id}/reject", [
                'notes' => '',
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('notes');
    }

    /**
     * Test unauthenticated user cannot access
     */
    public function test_unauthenticated_user_cannot_access(): void
    {
        $response = $this->getJson('/api/approvals/pending');
        $response->assertStatus(401);
    }

    /**
     * Test approver without position cannot access
     */
    public function test_approver_without_position_cannot_access(): void
    {
        $approverRole = Role::where('name', 'Approver')->firstOrFail();
        /* @var User $userWithoutPosition */
        $userWithoutPosition = User::factory()->create([
            'role_id' => $approverRole->id,
            'position_id' => null,
        ]);

        $response = $this->actingAs($userWithoutPosition)
            ->getJson('/api/approvals/pending');

        $response->assertStatus(403);
        $response->assertJson(['success' => false]);
    }

    /**
     * Test API returns correct data format for mock consumption
     * This validates that documentationmatches actual implementation
     */
    public function test_response_format_matches_documentation(): void
    {
        $response = $this->actingAs($this->approver)
            ->getJson('/api/approvals/pending');

        $approval = $response->json('data.0');

        // Verify all required fields from documentation are present
        $this->assertArrayHasKey('id', $approval);
        $this->assertArrayHasKey('booking', $approval);
        $this->assertArrayHasKey('room', $approval);
        $this->assertArrayHasKey('peminjam', $approval);
        $this->assertArrayHasKey('workflow', $approval);
        $this->assertArrayHasKey('current_approver_required', $approval);
        $this->assertArrayHasKey('previous_approvals', $approval);
        $this->assertArrayHasKey('documents_uploaded', $approval);
        $this->assertArrayHasKey('priority_indicator', $approval);
        $this->assertArrayHasKey('time_remaining', $approval);

        // Verify nested structures
        $this->assertIsArray($approval['booking']);
        $this->assertIsArray($approval['room']);
        $this->assertIsArray($approval['peminjam']);
        $this->assertIsArray($approval['workflow']);
        $this->assertIsArray($approval['current_approver_required']);
        $this->assertIsArray($approval['previous_approvals']);
        $this->assertIsArray($approval['documents_uploaded']);

        // Verify string values match expected format
        $this->assertIsString($approval['priority_indicator']);
        $this->assertIsString($approval['time_remaining']);
    }
}
