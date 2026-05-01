<?php

namespace Database\Seeders;

use App\Models\Approval;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use App\Models\Workflow;
use App\Models\WorkflowStep;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TestApprovalDataSeeder extends Seeder
{
    public function run(): void
    {
        // Get test data
        $peminjam = User::where('email', 'user@spacein.test')->firstOrFail();
        $workflow = Workflow::where('name', 'Peminjaman JTI')->firstOrFail();
        $room = Room::firstOrFail();
        $kaprodi = User::where('email', 'kaprodi.ti@spacein.test')->firstOrFail();
        $kajur = User::where('email', 'kajur.ti@spacein.test')->firstOrFail();
        $wadir = User::where('email', 'wadir@spacein.test')->firstOrFail();

        // Create 3 pending bookings that need approval at Step 1 (Kaprodi)
        $step1 = WorkflowStep::where('workflow_id', $workflow->id)->where('step_order', 1)->first();

        for ($i = 1; $i <= 3; $i++) {
            $booking = Booking::create([
                'user_id' => $peminjam->id,
                'room_id' => $room->id,
                'workflow_id' => $workflow->id,
                'event_name' => "Event Diskusi #{$i}",
                'event_description' => "Diskusi teknologi terkini bagian {$i}",
                'booking_date' => Carbon::now()->addDays($i + 2),
                'start_time' => '10:00:00',
                'end_time' => '12:00:00',
                'current_step' => 1,
                'status' => 'Pending',
                'revision_count' => 0,
            ]);

            // Create approval for first step (Kaprodi) - Status: Pending
            Approval::create([
                'booking_id' => $booking->id,
                'approver_id' => $kaprodi->id,
                'step_id' => $step1->id,
                'approval_status' => 'Pending',
                'notes' => null,
                'attempt' => 1,
            ]);

            $this->command->info("Created booking #{$booking->id} pending Kaprodi approval");
        }

        // Create 1 booking with approval from Kaprodi (move to Kajur - Step 2)
        $step2 = WorkflowStep::where('workflow_id', $workflow->id)->where('step_order', 2)->first();

        $booking = Booking::create([
            'user_id' => $peminjam->id,
            'room_id' => $room->id,
            'workflow_id' => $workflow->id,
            'event_name' => 'Event Seminar Besar',
            'event_description' => 'Seminar dengan pembicara tamu internasional',
            'booking_date' => Carbon::now()->addDay(10),
            'start_time' => '14:00:00',
            'end_time' => '16:00:00',
            'current_step' => 2,
            'status' => 'Pending',
            'revision_count' => 0,
        ]);

        // Approval from Kaprodi (already approved)
        Approval::create([
            'booking_id' => $booking->id,
            'approver_id' => $kaprodi->id,
            'step_id' => $step1->id,
            'approval_status' => 'Approved',
            'approved_at' => now()->subDay(),
            'notes' => 'Setuju untuk kegiatan ini',
            'attempt' => 1,
        ]);

        // Approval now pending at Kajur (step 2)
        Approval::create([
            'booking_id' => $booking->id,
            'approver_id' => $kajur->id,
            'step_id' => $step2->id,
            'approval_status' => 'Pending',
            'notes' => null,
            'attempt' => 1,
        ]);

        $this->command->info("Created booking #{$booking->id} pending Kajur approval (Kaprodi already approved)");
    }
}
