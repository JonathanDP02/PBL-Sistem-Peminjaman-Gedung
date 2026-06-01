<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\BookingAttachment;
use App\Models\BookingStep;
use App\Models\Room;
use App\Models\User;
use App\Models\Workflow;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ApproverWorkflowEnhancedTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->approver = User::where('email', 'kaprodi.ti@spacein.test')->first();
        $this->borrower = User::where('email', 'user@spacein.test')->first();

        $this->room = Room::where('room_name', 'Ruang Kelas TI')->first();
        $this->workflow = Workflow::where('name', 'Peminjaman JTI')->first();

        if (! $this->room || ! $this->workflow) {
            throw new \Exception('Room or Workflow not found in seeded data. Check DatabaseSeeder.');
        }

        // Cache the kaprodi position_id for creating booking_steps
        $this->approverPositionId = $this->approver->position_id;
    }

    /**
     * Helper: create booking_steps from the workflow's steps for a given booking.
     * Simulates what WorkflowBridgeService::buildAndPersistChain() does.
     */
    private function createBookingStepsFor(Booking $booking): void
    {
        $this->workflow->steps->each(function ($step, $index) use ($booking) {
            BookingStep::create([
                'booking_id' => $booking->id,
                'position_id' => $step->position_id,
                'step_order' => $step->step_order,
                'requires_attachment' => $step->requires_attachment,
                'tier_label' => 'Test Tier '.($index + 1),
            ]);
        });
    }

    public function test_approver_rejection_removes_booking_from_pending_list()
    {
        // 1. Create a pending booking
        $booking = Booking::create([
            'user_id' => $this->borrower->id,
            'room_id' => $this->room->id,
            'workflow_id' => $this->workflow->id,
            'event_name' => 'Test Event Rejection',
            'booking_date' => now()->addDays(5)->format('Y-m-d'),
            'start_time' => '08:00',
            'end_time' => '10:00',
            'status' => 'Pending',
            'current_step' => 1,
            'event_scope' => 'Internal',
        ]);
        $this->createBookingStepsFor($booking);

        // 2. Check if it appears in Meja Kerja (Pending list)
        $response = $this->actingAs($this->approver)->get(route('meja-kerja'));
        $response->assertStatus(200);
        $response->assertSee('Test Event Rejection');

        // 3. Approver rejects the booking
        $response = $this->actingAs($this->approver)->postJson(route('approval.reject', $booking->id), [
            'notes' => 'Tolong revisi proposal.',
        ]);
        $response->assertStatus(200);

        $booking->refresh();
        $this->assertEquals('Revising', $booking->status);

        // 4. Check if it DISAPPEARS from Meja Kerja
        $response = $this->actingAs($this->approver)->get(route('meja-kerja'));
        $response->assertStatus(200);
        $response->assertDontSee('Test Event Rejection');
    }

    public function test_borrower_revision_restores_booking_to_approver_pending_list()
    {
        Storage::fake('private');

        // 1. Create a Revising booking (originally at step 2)
        $booking = Booking::create([
            'user_id' => $this->borrower->id,
            'room_id' => $this->room->id,
            'workflow_id' => $this->workflow->id,
            'event_name' => 'Test Event Revision',
            'booking_date' => now()->addDays(5)->format('Y-m-d'),
            'start_time' => '08:00',
            'end_time' => '10:00',
            'status' => 'Revising',
            'current_step' => 2, // Sebelumnya ditolak di langkah 2
            'event_scope' => 'Internal',
        ]);
        // Booking_steps must exist even for revising bookings so rebuild works
        $this->createBookingStepsFor($booking);

        // 2. Ensure it's NOT in Meja Kerja of step 1 approver yet (because it's Revising)
        $response = $this->actingAs($this->approver)->get(route('meja-kerja'));
        $response->assertDontSee('Test Event Revision');

        // 3. Borrower submits revision
        $reqs = $this->workflow->requirements;
        $params = [
            'event_name' => 'Updated Event Name',
            'event_description' => 'Updated Desc',
        ];
        foreach ($reqs as $req) {
            $params['requirement_'.$req->id] = UploadedFile::fake()->create('doc.pdf', 100);
        }

        $response = $this->actingAs($this->borrower)->post(route('booking.revise', $booking->id), $params);
        $response->assertStatus(200);

        $booking->refresh();
        $this->assertEquals('Pending', $booking->status);
        $this->assertEquals(1, $booking->current_step); // DIRESET KE LANGKAH 1 (sesuai spesifikasi)

        // 4. Ensure it RE-APPEARS in Meja Kerja of the step 1 approver
        $response = $this->actingAs($this->approver)->get(route('meja-kerja'));
        $response->assertSee('Updated Event Name');
    }

    public function test_approver_history_shows_recorded_decisions()
    {
        // 1. Create a booking and approve it
        $booking = Booking::create([
            'user_id' => $this->borrower->id,
            'room_id' => $this->room->id,
            'workflow_id' => $this->workflow->id,
            'event_name' => 'History Test Event',
            'booking_date' => now()->addDays(5)->format('Y-m-d'),
            'start_time' => '08:00',
            'end_time' => '10:00',
            'status' => 'Pending',
            'current_step' => 1,
            'event_scope' => 'Internal',
        ]);
        $this->createBookingStepsFor($booking);

        $this->actingAs($this->approver)->postJson(route('approval.approve', $booking->id), [
            'notes' => 'Sesuai prosedur.',
        ]);

        // 2. Check history page
        $response = $this->actingAs($this->approver)->get(route('riwayat'));
        $response->assertStatus(200);
        $response->assertSee('History Test Event');
        $response->assertSee('Sesuai prosedur.');
        $response->assertSee('Approved');
    }

    public function test_approver_can_access_attachment_file()
    {
        Storage::fake('private');

        // 1. Create booking and attachment
        $booking = Booking::create([
            'user_id' => $this->borrower->id,
            'room_id' => $this->room->id,
            'workflow_id' => $this->workflow->id,
            'event_name' => 'File Access Test',
            'booking_date' => now()->addDays(5)->format('Y-m-d'),
            'start_time' => '08:00',
            'end_time' => '10:00',
            'status' => 'Pending',
            'current_step' => 1,
            'event_scope' => 'Internal',
        ]);
        $this->createBookingStepsFor($booking);

        $file = UploadedFile::fake()->create('proposal.pdf', 50);
        $path = $file->store('attachments/'.$booking->id, 'private');

        $attachment = BookingAttachment::create([
            'booking_id' => $booking->id,
            'uploader_id' => $this->borrower->id,
            'requirement_id' => $this->workflow->requirements->first()->id,
            'document_type' => 'Proposal',
            'file_path' => $path,
        ]);

        // 2. Access via secure route as Approver
        $url = route('booking.attachment.show', ['id' => $booking->id, 'attachmentId' => $attachment->id]);
        $response = $this->actingAs($this->approver)->get($url);

        $response->assertStatus(200);
        // Sometimes fake storage returns application/x-empty or something else if content is small,
        // we just care it's 200 and not 403
    }
}
