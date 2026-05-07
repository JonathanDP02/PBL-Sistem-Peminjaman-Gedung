<?php

namespace App\Http\Controllers;

use App\Mail\ApprovalNeededMail;
use App\Models\Booking;
use App\Models\BookingAttachment;
use App\Models\BookingLog;
use App\Models\Building;
use App\Models\Workflow;
use App\Models\WorkflowRequirement;
use App\Models\WorkflowStep;
use App\Services\LoggerService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with([
            'room.building',
            'user',
            'workflow.steps',
            'workflow.requirements',
            'attachments',
        ])
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return response()->json($bookings);
    }

    public function showBookingForm()
    {
        // Fetch all buildings with their rooms
        $buildings = Building::with('rooms.unit')->get();

        // Fetch all workflows with their steps and requirements
        $workflows = Workflow::with(['steps.position', 'requirements'])->get();

        return view('user.peminjam.booking', compact('buildings', 'workflows'));
    }

    public function create()
    {
        session(['booking_draft_opened_at' => now()->toISOString()]);

        return response()->json(['message' => 'Form peminjaman dibuka. Soft-lock aktif.']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'workflow_id' => 'required|exists:workflows,id',
            'event_name' => 'required|string|max:200',
            'event_description' => 'nullable|string',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        // Cek mandatory requirements sebelum transaksi
        $mandatoryRequirements = WorkflowRequirement::where('workflow_id', $validated['workflow_id'])
            ->where('is_mandatory', true)
            ->get();

        foreach ($mandatoryRequirements as $requirement) {
            if (! $request->hasFile('requirement_'.$requirement->id)) {
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
                    // throw new \Exception(
                    //     'Ruangan sudah dibooking pada waktu tersebut. '.
                    //     "Konflik dengan booking #{$conflict->id} ".
                    //     "({$conflict->start_time} - {$conflict->end_time})."
                    // );
                    $isBlocked = $conflict->event_name === 'Libur Nasional';
                    $message = $isBlocked
                        ? "Tanggal {$validated['booking_date']} diblokir sebagai Libur Nasional. Peminjaman tidak dapat dilakukan."
                        : "Ruangan sudah dibooking pada waktu tersebut. Konflik dengan booking #{$conflict->id} ({$conflict->start_time} - {$conflict->end_time}).";

                    throw new \Exception($message);
                }

                $isMaintenance = strtolower(trim($validated['event_name'])) === 'maintenance';

                $booking = Booking::create([
                    'user_id' => Auth::id(),
                    'room_id' => $validated['room_id'],
                    'workflow_id' => $validated['workflow_id'],
                    'event_name' => $validated['event_name'],
                    'event_description' => $validated['event_description'] ?? null,
                    'booking_date' => $validated['booking_date'],
                    'start_time' => $validated['start_time'],
                    'end_time' => $validated['end_time'],
                    'current_step' => $isMaintenance ? 0 : 1,
                    'status' => $isMaintenance ? 'Approved' : 'Pending',
                    'revision_count' => 0,
                ]);

                // Upload mandatory attachments
                foreach ($mandatoryRequirements as $requirement) {
                    $file = $request->file('requirement_'.$requirement->id);
                    $path = $file->store("attachments/{$booking->id}", 'private');

                    BookingAttachment::create([
                        'booking_id' => $booking->id,
                        'requirement_id' => $requirement->id,
                        'uploader_id' => Auth::id(),
                        'document_type' => $requirement->document_name,
                        'file_path' => $path,
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
                'data' => $booking->load([
                    'room.building',
                    'user',
                    'workflow.steps',
                    'workflow.requirements',
                    'attachments',
                ]),
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 409);
        }
    }

    public function show($id)
    {
        $booking = Booking::with([
            'room.building',
            'user',
            'workflow.requirements',
            // 'room',
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

        if (! in_array($booking->status, ['Pending', 'Draft', 'Revising'])) {
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

    public function revise(Request $request, int $id): mixed
    {
        $booking = Booking::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($booking->status !== 'Revising') {
            return response()->json([
                'error' => "Booking tidak dapat direvisi karena statusnya '{$booking->status}'.",
            ], 422);
        }

        $mandatoryRequirements = WorkflowRequirement::where('workflow_id', $booking->workflow_id)
            ->where('is_mandatory', true)
            ->get();

        foreach ($mandatoryRequirements as $requirement) {
            if (! $request->hasFile('requirement_'.$requirement->id)) {
                return response()->json([
                    'error' => "File wajib '{$requirement->document_name}' tidak ditemukan.",
                ], 422);
            }
        }

        DB::transaction(function () use ($request, $booking, $mandatoryRequirements) {
            // Hapus attachment lama milik user ini
            BookingAttachment::where('booking_id', $booking->id)
                ->where('uploader_id', Auth::id())
                ->delete();

            // Upload attachment baru
            foreach ($mandatoryRequirements as $requirement) {
                $file = $request->file('requirement_'.$requirement->id);
                $path = $file->store("attachments/{$booking->id}", 'private');

                BookingAttachment::create([
                    'booking_id' => $booking->id,
                    'requirement_id' => $requirement->id,
                    'uploader_id' => Auth::id(),
                    'document_type' => $requirement->document_name,
                    'file_path' => $path,
                ]);
            }

            $booking->update(['status' => 'Pending']);

            LoggerService::logAction($booking->id, 'REVISED', null, 'Dokumen direvisi dan diajukan ulang oleh peminjam.');

            // Kirim email notifikasi ke approver di step saat ini
            $currentStep = WorkflowStep::where('workflow_id', $booking->workflow_id)
                ->where('step_order', $booking->current_step)
                ->first();

            if ($currentStep) {
                $approvers = User::where('position_id', $currentStep->position_id)->get();

                foreach ($approvers as $approver) {
                    Mail::to($approver->email)->queue(new ApprovalNeededMail($booking, $approver));
                }
            }
        });

        return response()->json(['message' => 'Revisi berhasil dikirim. Booking dikembalikan ke status Pending.']);
    }

    public function timeline($id)
    {
        $bookingLogs = BookingLog::with(['actor', 'step'])
            ->where('booking_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $bookingLogs,
        ]);
    }
}
