<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingLog;
use App\Models\Room;
use App\Services\LoggerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $recentLogs = BookingLog::with(['booking.room', 'actor'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->map(fn ($log) => [
                'id'         => $log->id,
                'action'     => $log->action,
                'notes'      => $log->notes,
                'actor'      => $log->actor?->name ?? 'System',
                'booking_id' => $log->booking_id,
                'event_name' => $log->booking?->event_name,
                'room'       => $log->booking?->room?->room_name,
                'created_at' => $log->created_at->toIso8601String(),
            ]);

        return response()->json([
            'success'     => true,
            'recent_logs' => $recentLogs,
        ]);
    }

    public function blockDate(Request $request)
    {
        $validated = $request->validate([
            'block_date'  => 'required|date|after_or_equal:today',
            'start_time'  => 'required|date_format:H:i',
            'end_time'    => 'required|date_format:H:i|after:start_time',
            'description' => 'nullable|string|max:500',
            'room_ids'    => 'nullable|array',       
            'room_ids.*'  => 'exists:rooms,id',
        ]);

        try {
            $createdCount = 0;
            $skippedCount = 0;

            DB::transaction(function () use ($validated, &$createdCount, &$skippedCount) {

                $roomQuery = Room::query();
                if (!empty($validated['room_ids'])) {
                    $roomQuery->whereIn('id', $validated['room_ids']);
                }
                $rooms = $roomQuery->get();

                $defaultWorkflowId = \App\Models\Workflow::first()?->id ?? 1;

                foreach ($rooms as $room) {

                    $alreadyBlocked = Booking::where('room_id', $room->id)
                        ->where('booking_date', $validated['block_date'])
                        ->where('event_name', 'Libur Nasional')
                        ->where('start_time', $validated['start_time'])
                        ->where('end_time', $validated['end_time'])
                        ->exists();

                    if ($alreadyBlocked) {
                        $skippedCount++;
                        continue;
                    }

                    $conflict = Booking::where('room_id', $room->id)
                        ->where('booking_date', $validated['block_date'])
                        ->whereNotIn('status', ['Rejected', 'Cancelled'])
                        ->where(function ($q) use ($validated) {
                            $q->where('start_time', '<', $validated['end_time'])
                              ->where('end_time', '>', $validated['start_time']);
                        })
                        ->lockForUpdate()
                        ->first();

                    if ($conflict && $conflict->event_name !== 'Libur Nasional') {
                        $conflict->update(['status' => 'Cancelled']);
                        LoggerService::logAction(
                            $conflict->id,
                            'FORCE_CANCELLED',
                            null,
                            "Dibatalkan otomatis karena Global Blocking Libur Nasional tanggal {$validated['block_date']}."
                        );
                    }

                    $blocking = Booking::create([
                        'user_id'           => Auth::id(),
                        'room_id'           => $room->id,
                        'workflow_id'       => $defaultWorkflowId,
                        'event_name'        => 'Libur Nasional',
                        'event_description' => $validated['description'] ?? 'Global Blocking oleh Admin.',
                        'booking_date'      => $validated['block_date'],
                        'start_time'        => $validated['start_time'],
                        'end_time'          => $validated['end_time'],
                        'current_step'      => 0,           // Bypass approval
                        'status'            => 'Approved',  // Hard-Lock
                        'revision_count'    => 0,
                    ]);

                    LoggerService::logAction(
                        $blocking->id,
                        'GLOBAL_BLOCK',
                        null,
                        "Tanggal {$validated['block_date']} diblokir sebagai Libur Nasional oleh Admin."
                    );

                    $createdCount++;
                }
            });

            return response()->json([
                'success' => true,
                'message' => "Global Blocking berhasil. {$createdCount} room diblokir, {$skippedCount} room dilewati (sudah diblokir).",
                'data'    => [
                    'block_date'    => $validated['block_date'],
                    'start_time'    => $validated['start_time'],
                    'end_time'      => $validated['end_time'],
                    'rooms_blocked' => $createdCount,
                    'rooms_skipped' => $skippedCount,
                ],
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ], 409);
        }
    }

    public function unblockDate(Request $request)
    {
        $validated = $request->validate([
            'block_date' => 'required|date',
            'room_ids'   => 'nullable|array',
            'room_ids.*' => 'exists:rooms,id',
        ]);

        $query = Booking::where('event_name', 'Libur Nasional')
            ->where('booking_date', $validated['block_date'])
            ->where('status', 'Approved');

        if (!empty($validated['room_ids'])) {
            $query->whereIn('room_id', $validated['room_ids']);
        }

        $count = $query->count();
        $query->delete();

        return response()->json([
            'success' => true,
            'message' => "{$count} Global Blocking berhasil dihapus untuk tanggal {$validated['block_date']}.",
        ]);
    }
}
