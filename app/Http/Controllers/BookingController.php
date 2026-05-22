<?php

namespace App\Http\Controllers;

use App\Mail\ApprovalNeededMail;
use App\Mail\BookingSubmittedMail;
use App\Models\Booking;
use App\Models\BookingAttachment;
use App\Models\BookingLog;
use App\Models\Building;
use App\Models\Room;
use App\Models\User;
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
        // Mengambil data gedung beserta relasi ruangan, unit, dan alur kerjanya
        $buildings = Building::with([
            'rooms.unit',
            'rooms.workflow.steps.position',
            'rooms.workflow.requirements',
        ])->get();

        // Mengambil data alur (workflow) beserta langkah dan syaratnya
        $workflows = Workflow::with(['steps.position', 'requirements'])->get();

        // Mengirimkan variabel $buildings dan $workflows ke halaman view
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
            'event_name' => 'required|string|max:200',
            'event_description' => 'nullable|string',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $room = Room::findOrFail($validated['room_id']);
        if (! $room->workflow_id) {
            return response()->json([
                'error' => "Ruangan '{$room->room_name}' belum memiliki alur persetujuan (workflow) yang dikonfigurasi. Silakan hubungi pengelola unit.",
            ], 422);
        }

        $validated['workflow_id'] = $room->workflow_id;

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

            // Dispatch Notifications outside transaction
            try {
                $currentStep = WorkflowStep::where('workflow_id', $booking->workflow_id)
                    ->where('step_order', $booking->current_step)
                    ->first();

                if ($currentStep) {
                    $approvers = User::where('position_id', $currentStep->position_id)->get();
                    \Log::info('Attempting to send email to '.$approvers->count().' approvers for booking #'.$booking->id);
                    foreach ($approvers as $approver) {
                        Mail::to($approver->email)->send(new ApprovalNeededMail($booking, $approver));
                        \Log::info('Email sent to approver: '.$approver->email);
                    }
                }

                \Log::info('Attempting to send confirmation email to borrower: '.Auth::user()->email);
                Mail::to(Auth::user()->email)->send(new BookingSubmittedMail($booking));
                \Log::info('Confirmation email sent to borrower.');
            } catch (\Exception $e) {
                // Log mail error but don't fail the request
                \Log::error('Mail Error (Booking #'.$booking->id.'): '.$e->getMessage());
                \Log::error($e->getTraceAsString());
            }

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

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Gagal membuat booking: '.$e->getMessage(),
            ], 409);
        }
    }

    public function show($id)
    {
        $booking = Booking::with([
            'room.building',
            'user',
            'workflow.requirements',
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

        try {
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

                $booking->update([
                    'status' => 'Pending',
                    'revision_count' => ($booking->revision_count ?? 0) + 1,
                    'event_name' => $request->input('event_name', $booking->event_name),
                    'event_description' => $request->input('event_description', $booking->event_description),
                ]);

                LoggerService::logAction($booking->id, 'REVISED', null, 'Dokumen direvisi dan diajukan ulang oleh peminjam.');
            });

            // Kirim email notifikasi ke approver di step saat ini (outside transaction)
            try {
                $currentStep = WorkflowStep::where('workflow_id', $booking->workflow_id)
                    ->where('step_order', $booking->current_step)
                    ->first();

                if ($currentStep) {
                    $approvers = User::where('position_id', $currentStep->position_id)->get();
                    \Log::info('Attempting to send revision email to '.$approvers->count().' approvers for booking #'.$booking->id);
                    foreach ($approvers as $approver) {
                        Mail::to($approver->email)->send(new ApprovalNeededMail($booking, $approver));
                        \Log::info('Revision email sent to approver: '.$approver->email);
                    }
                }
            } catch (\Exception $e) {
                // Log mail error but don't fail the request
                \Log::error('Mail Error (Revision #'.$booking->id.'): '.$e->getMessage());
                \Log::error($e->getTraceAsString());
            }
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Gagal mengirim revisi: '.$e->getMessage(),
            ], 409);
        }

        return response()->json(['message' => 'Revisi berhasil dikirim. Booking dikembalikan ke status Pending.']);
    }

    // INI ADALAH FUNGSI YANG SEBELUMNYA HILANG
    public function showJadwalSaya()
    {
        $month = request('month', now()->month);
        $year = request('year', now()->year);

        $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfDay();
        $endOfMonth = $startOfMonth->clone()->endOfMonth()->endOfDay();

        // Ambil semua booking di bulan tersebut (untuk kalender umum)
        $allBookings = Booking::with(['room', 'user'])
            ->whereBetween('booking_date', [$startOfMonth, $endOfMonth])
            ->whereIn('status', ['Approved', 'Pending'])
            ->get();

        // Ambil booking khusus milik user yang sedang login (untuk statistik)
        $userBookings = Booking::with('room')
            ->where('user_id', Auth::id())
            ->whereBetween('booking_date', [$startOfMonth, $endOfMonth])
            ->whereIn('status', ['Approved', 'Pending'])
            ->get();

        // Hitung statistik user
        $hoursUsed = 0;
        $complianceScore = 4.8; // Nilai default

        foreach ($userBookings as $booking) {
            $start = Carbon::parse($booking->start_time);
            $end = Carbon::parse($booking->end_time);
            $hours = $end->diffInHours($start);
            $hoursUsed += $hours;
        }

        // Kelompokkan booking berdasarkan ruangan dan tanggal untuk mempermudah render di kalender
        $bookingsByRoomAndDate = $allBookings->groupBy(function ($booking) {
            return $booking->room_id.'_'.$booking->booking_date;
        });

        return view('user.peminjam.jadwal-saya', [
            'allBookings' => $allBookings,
            'userBookings' => $userBookings,
            'bookingsByRoomAndDate' => $bookingsByRoomAndDate,
            'hoursUsed' => $hoursUsed,
            'complianceScore' => $complianceScore,
            'month' => $month,
            'year' => $year,
            'monthName' => $startOfMonth->translatedFormat('F'),
        ]);
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

    public function detail($id)
    {
        $booking = Booking::with([
            'room.building',
            'user',
            'workflow.requirements',
            'workflow.steps.position',
            'approvals',
            'logs.actor',
            'attachments',
        ])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('user.peminjam.detail', compact('booking'));
    }
}
