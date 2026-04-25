<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\LoggerService;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['room', 'workflow'])
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return response()->json($bookings);
    }

    public function create()
    {
        session(['booking_draft_opened_at' => now()->toISOString()]);

        return response()->json(['message' => 'Form peminjaman dibuka. Soft-lock aktif.']);
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'room_id'           => 'required|exists:rooms,id',
        'workflow_id'       => 'required|exists:workflows,id',
        'event_name'        => 'required|string|max:200',
        'event_description' => 'nullable|string',
        'booking_date'      => 'required|date|after_or_equal:today',
        'start_time'        => 'required|date_format:H:i',
        'end_time'          => 'required|date_format:H:i|after:start_time',
    ]);

    // Cek mandatory requirements sebelum transaksi
    $mandatoryRequirements = \App\Models\WorkflowRequirement::where('workflow_id', $validated['workflow_id'])
        ->where('is_mandatory', true)
        ->get();

    foreach ($mandatoryRequirements as $requirement) {
        if (!$request->hasFile('requirement_' . $requirement->id)) {
            return response()->json([
                'error' => "File wajib '{$requirement->document_name}' tidak ditemukan.",
            ], 422);
        }
    }

    try {
        $booking = DB::transaction(function () use ($validated, $request, $mandatoryRequirements) {

            $conflict = Booking::where('room_id', $validated['room_id'])
                ->where('booking_date', $validated['booking_date'])
                ->whereNotIn('status', ['Rejected', 'Cancelled'])
                ->where(function ($q) use ($validated) {
                    $q->where('start_time', '<', $validated['end_time'])
                      ->where('end_time', '>', $validated['start_time']);
                })
                ->lockForUpdate()
                ->first();

            if ($conflict) {
                throw new \Exception(
                    "Ruangan sudah dibooking pada waktu tersebut. " .
                    "Konflik dengan booking #{$conflict->id} " .
                    "({$conflict->start_time} - {$conflict->end_time})."
                );
            }

            $isMaintenance = strtolower(trim($validated['event_name'])) === 'maintenance';

            $booking = Booking::create([
                'user_id'           => Auth::id(),
                'room_id'           => $validated['room_id'],
                'workflow_id'       => $validated['workflow_id'],
                'event_name'        => $validated['event_name'],
                'event_description' => $validated['event_description'] ?? null,
                'booking_date'      => $validated['booking_date'],
                'start_time'        => $validated['start_time'],
                'end_time'          => $validated['end_time'],
                'current_step'      => $isMaintenance ? 0 : 1,
                'status'            => $isMaintenance ? 'Approved' : 'Pending',
                'revision_count'    => 0,
            ]);

            // Upload mandatory attachments
            foreach ($mandatoryRequirements as $requirement) {
                $file = $request->file('requirement_' . $requirement->id);
                $path = $file->store("attachments/{$booking->id}", 'private');

                \App\Models\BookingAttachment::create([
                    'booking_id'    => $booking->id,
                    'requirement_id' => $requirement->id,
                    'uploader_id'   => Auth::id(),
                    'document_type' => $requirement->document_name,
                    'file_path'     => $path,
                ]);
            }

            LoggerService::logAction(
    $booking->id,
    $isMaintenance ? 'BYPASS_APPROVED' : 'SUBMITTED',
    null,
    $isMaintenance ? 'Booking Maintenance di-bypass approval dan langsung disetujui.' : 'Booking baru dibuat.'
);

            session()->forget(['booking_draft_opened_at', 'booking_draft_id']);

            return $booking;
        });

        return response()->json([
            'message' => 'Booking berhasil dibuat.',
            'data'    => $booking->load(['room', 'workflow']),
        ], 201);

    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 409);
    }
}

    public function show($id)
    {
        $booking = Booking::with([
            'room',
            'workflow.steps.position',
            'approvals',
            'logs.actor',
            'attachments',
        ])->findOrFail($id);

        return response()->json($booking);
    }

    public function cancel($id)
    {
        $booking = Booking::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (!in_array($booking->status, ['Pending', 'Draft', 'Revising'])) {
            return response()->json([
                'error' => "Booking tidak dapat dibatalkan karena statusnya '{$booking->status}'.",
            ], 422);
        }

        DB::transaction(function () use ($booking) {
            $booking->update(['status' => 'Cancelled']);

            LoggerService::logAction($booking->id, 'CANCELLED', null, 'Booking dibatalkan oleh peminjam.');
        });

        return response()->json(['message' => 'Booking berhasil dibatalkan.']);
    }
}
