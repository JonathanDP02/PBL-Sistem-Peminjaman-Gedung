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
        $room = Room::findOrFail($workflow->room_id);
        $kajur = User::where('email', 'kajur.ti@spacein.test')->firstOrFail();

        // Create 3 pending bookings that need approval at Step 1 (Kajur TI)
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

            // Create approval for first step (Kajur TI) - Status: Pending
            Approval::create([
                'booking_id' => $booking->id,
                'approver_id' => $kajur->id,
                'step_id' => $step1->id,
                'approval_status' => 'Pending',
                'notes' => null,
                'attempt' => 1,
            ]);

            $this->command->info("Created booking #{$booking->id} pending Kajur approval");
        }
    }
}
