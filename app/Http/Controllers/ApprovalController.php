<?php

namespace App\Http\Controllers;

use App\Models\Approval;
use App\Models\Booking;
use App\Models\BookingLog;
use App\Models\WorkflowStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApprovalController extends Controller
{
    public function index()
    {
        $approver   = Auth::user();
        $positionId = $approver->position_id;

        $bookings = Booking::with(['room', 'user', 'workflow.steps.position'])
            ->whereIn('status', ['Pending', 'Revising'])
            ->whereHas('workflow.steps', function ($q) use ($positionId) {
                $q->where('position_id', $positionId)
                  ->whereColumn('step_order', 'bookings.current_step');
            })
            ->orderBy('booking_date')
            ->get();

        return response()->json($bookings);
    }

    public function approve(Request $request, $bookingId)
    {
        $request->validate([
            'notes' => 'nullable|string',
        ]);

        $approver   = Auth::user();
        $positionId = $approver->position_id;

        DB::transaction(function () use ($request, $bookingId, $approver, $positionId) {
            $booking = Booking::lockForUpdate()->findOrFail($bookingId);

            $currentStep = WorkflowStep::where('workflow_id', $booking->workflow_id)
                ->where('step_order', $booking->current_step)
                ->where('position_id', $positionId)
                ->firstOrFail();

            $nextStep = WorkflowStep::where('workflow_id', $booking->workflow_id)
                ->where('step_order', '>', $booking->current_step)
                ->orderBy('step_order')
                ->first();

            if ($nextStep) {
                $booking->update([
                    'current_step' => $nextStep->step_order,
                    'status'       => 'Pending',
                ]);
            } else {
                $booking->update([
                    'status' => 'Approved',
                ]);
            }

            Approval::create([
                'booking_id'  => $booking->id,
                'approver_id' => $approver->id,
                'step_id'     => $currentStep->id,
                'status'      => 'Approved',
                'notes'       => $request->notes,
            ]);

            BookingLog::create([
                'booking_id' => $booking->id,
                'actor_id'   => $approver->id,
                'step_id'    => $currentStep->id,
                'action'     => 'approved',
                'notes'      => $request->notes ?? 'Disetujui.',
            ]);
        });

        return response()->json(['message' => 'Booking berhasil disetujui.']);
    }

    public function reject(Request $request, $bookingId)
    {
        $request->validate([
            'notes' => 'required|string',
        ]);

        $approver   = Auth::user();
        $positionId = $approver->position_id;

        DB::transaction(function () use ($request, $bookingId, $approver, $positionId) {
            $booking = Booking::lockForUpdate()->findOrFail($bookingId);

            $currentStep = WorkflowStep::where('workflow_id', $booking->workflow_id)
                ->where('step_order', $booking->current_step)
                ->where('position_id', $positionId)
                ->firstOrFail();

            $booking->update([
                'status'         => 'Revising',
                'revision_count' => $booking->revision_count + 1,
            ]);

            Approval::create([
                'booking_id'  => $booking->id,
                'approver_id' => $approver->id,
                'step_id'     => $currentStep->id,
                'status'      => 'Rejected',
                'notes'       => $request->notes,
            ]);

            BookingLog::create([
                'booking_id' => $booking->id,
                'actor_id'   => $approver->id,
                'step_id'    => $currentStep->id,
                'action'     => 'rejected',
                'notes'      => $request->notes,
            ]);
        });

        return response()->json([
            'message' => 'Booking ditolak. Menunggu revisi dari peminjam.',
        ]);
    }
}
