<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateApprovalCertificateJob;
use App\Mail\ApprovalNeededMail;
use App\Mail\BookingRejectedMail;
use App\Models\Approval;
use App\Models\Booking;
use App\Models\BookingAttachment;
use App\Models\BookingStep;
use App\Models\Unit;
use App\Models\User;
use App\Services\LoggerService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ApprovalController extends Controller
{
    /**
     * GET /api/approvals/pending
     * Fetch pending approvals for current approver (Meja Kerja / Work Desk)
     * Response format matches API_DOCUMENTATION.md mock response
     */
    public function index(Request $request)
    {
        $approver = Auth::user();
        $positionId = $approver->position_id;

        $bookings = Booking::with([
            'room.building',
            'user',
            'workflow.steps.position',
            'bookingSteps.position',
            'attachments',
            'approvals.approver.position',
            'approvals.bookingStep',
        ])
            ->where('status', 'Pending')
            ->whereHas('bookingSteps', function ($q) use ($positionId) {
                $q->where('position_id', $positionId)
                    ->whereColumn('step_order', 'bookings.current_step');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Transform bookings ke format response yang match dokumentasi
        $approvals = $bookings->map(function ($booking) use ($positionId) {
            return $this->formatApprovalResponse($booking, $positionId);
        });

        return response()->json([
            'success' => true,
            'count' => $approvals->count(),
            'data' => $approvals->values(),
        ]);
    }

    /**
     * Format single booking menjadi approval response format
     * Sesuai API_DOCUMENTATION.md
     */
    private function formatApprovalResponse($booking, $positionId)
    {
        // Prefer instantiated booking_steps; fallback ke workflow_steps template
        $currentStep = $booking->bookingSteps
            ?->where('step_order', $booking->current_step)
            ->where('position_id', $positionId)
            ->first()
            ?? $booking->workflow?->steps
                ->where('step_order', $booking->current_step)
                ->where('position_id', $positionId)
                ->first();

        $approvalHistory = $booking->approvals
            ->sortBy('created_at')
            ->map(function ($approval) {
                // Try bookingStep first (new system), fallback to step (legacy)
                $stepRecord = $approval->bookingStep ?? $approval->step;

                return [
                    'step_order' => $stepRecord?->step_order ?? null,
                    'position' => $stepRecord?->position?->name ?? 'System',
                    'approver_name' => $approval->approver?->name ?? 'System',
                    'approval_status' => ucfirst($approval->approval_status),
                    'approved_at' => $approval->created_at->toIso8601String(),
                    'approved_at_formatted' => $approval->created_at->format('d M Y, H:i'),
                    'notes' => $approval->notes,
                    'attempt' => $approval->attempt ?? 1,
                ];
            })
            ->values();

        $documentsUploaded = $booking->attachments->map(function ($attachment) use ($booking) {
            return [
                'id' => $attachment->id,
                'document_name' => $attachment->document_name,
                'document_type' => $attachment->document_type,
                'file_path' => route('booking.attachment.show', ['id' => $booking->id, 'attachmentId' => $attachment->id]),
                'file_size' => $this->formatFileSize($attachment->file_size ?? 0),
                'uploaded_at' => $attachment->created_at->toIso8601String(),
                'uploaded_by' => $booking->user->name,
            ];
        })->values();

        return [
            'id' => $this->generateApprovalId($booking->id, $currentStep?->id),
            'booking' => [
                'id' => $booking->id,
                'event_name' => $booking->event_name,
                'event_description' => $booking->event_description,
                'event_scope' => $booking->event_scope,
                'booking_date' => $booking->booking_date->format('Y-m-d'),
                'start_time' => $booking->start_time->format('H:i'),
                'end_time' => $booking->end_time->format('H:i'),
                'status' => $booking->status,
                'current_step' => $booking->current_step,
                'revision_count' => $booking->revision_count,
                'created_at' => $booking->created_at->toIso8601String(),
            ],
            'room' => [
                'id' => $booking->room->id,
                'room_name' => $booking->room->room_name,
                'room_code' => $booking->room->room_code,
                'capacity' => $booking->room->capacity,
                'building' => [
                    'id' => $booking->room->building->id,
                    'building_name' => $booking->room->building->building_name,
                    'building_code' => $booking->room->building->building_code,
                ],
            ],
            'peminjam' => [
                'id' => $booking->user->id,
                'name' => $booking->user->name,
                'nim' => $booking->user->profile_data['nim'] ?? null,
                'email' => $booking->user->email,
                'unit' => [
                    'id' => $booking->user->unit->id,
                    'name' => $booking->user->unit->unit_name,
                ],
            ],
            'workflow' => [
                'id' => $booking->workflow->id,
                'name' => $booking->workflow->name,
                'total_steps' => $booking->bookingSteps->count() ?: $booking->workflow->steps->count(),
            ],
            'current_approver_required' => [
                'step_order' => $currentStep?->step_order,
                'tier_label' => $currentStep instanceof BookingStep ? $currentStep->tier_label : null,
                'position' => [
                    'id' => $currentStep?->position_id,
                    'name' => $currentStep?->position?->name,
                ],
                'requires_attachment' => (bool) $currentStep?->requires_attachment,
                'attachment_type' => $currentStep?->attachment_type ?? null,
            ],
            'approval_history' => $approvalHistory,
            'documents_uploaded' => $documentsUploaded,
            'priority_indicator' => $this->calculatePriority($booking),
            'time_remaining' => $this->calculateTimeRemaining($booking),
        ];
    }

    /**
     * Calculate priority based on booking date proximity
     */
    private function calculatePriority($booking): string
    {
        $daysUntil = now()->diffInDays($booking->booking_date, false);

        if ($daysUntil <= 1) {
            return 'urgent';
        } elseif ($daysUntil <= 3) {
            return 'high';
        }

        return 'normal';
    }

    /**
     * Calculate human-readable time remaining
     */
    private function calculateTimeRemaining($booking): string
    {
        $days = now()->diffInDays($booking->booking_date, false);

        if ($days < 0) {
            return 'Expired';
        } elseif ($days === 0) {
            return 'Today';
        } elseif ($days === 1) {
            return '1 day';
        }

        return "$days days";
    }

    /**
     * Generate unique approval ID (booking_id + step_id hash)
     */
    private function generateApprovalId($bookingId, $stepId): int
    {
        return (int) ($bookingId.substr((string) $stepId, -2));
    }

    /**
     * Format file size to human-readable format
     */
    private function formatFileSize($bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2).' '.$units[$pow];
    }

    /**
     * POST /api/approvals/{booking_id}/approve
     * Approve a booking at current step
     * If final step: dispatch async job to generate PDF & email
     * Response format matches API_DOCUMENTATION.md
     */
    public function approve(Request $request, $bookingId)
    {
        $request->validate([
            'notes' => 'nullable|string',
        ]);

        $approver = Auth::user();
        $positionId = $approver->position_id;

        $booking = null;
        $approval = null;

        try {
            DB::transaction(function () use ($request, $bookingId, $approver, $positionId, &$booking, &$approval) {
                $booking = Booking::lockForUpdate()->findOrFail($bookingId);

                // Find the active instantiated step for this approver
                $currentStep = BookingStep::where('booking_id', $booking->id)
                    ->where('step_order', $booking->current_step)
                    ->where('position_id', $positionId)
                    ->firstOrFail();

                if ($currentStep->requires_attachment) {
                    $hasAttachment = BookingAttachment::where('booking_id', $booking->id)
                        ->where('uploader_id', $approver->id)
                        ->exists();

                    if (! $hasAttachment) {
                        throw new \Exception(
                            'Step ini memerlukan lampiran file balasan. '
                            .'Harap upload dokumen terlebih dahulu sebelum menyetujui.'
                        );
                    }
                }

                // Check if there is a next step in the instantiated chain
                $nextStep = BookingStep::where('booking_id', $booking->id)
                    ->where('step_order', '>', $booking->current_step)
                    ->orderBy('step_order')
                    ->first();

                if ($nextStep) {
                    // Advance to next step in the chain
                    $booking->update([
                        'current_step' => $nextStep->step_order,
                        'status' => 'Pending',
                    ]);
                } else {
                    // Last step approved — Hard-Lock
                    $booking->update(['status' => 'Approved']);
                    dispatch(new GenerateApprovalCertificateJob($booking->id));
                }

                $approval = Approval::create([
                    'booking_id' => $booking->id,
                    'approver_id' => $approver->id,
                    'booking_step_id' => $currentStep->id,
                    'step_id' => null, // legacy field, unused for new bookings
                    'approval_status' => 'Approved',
                    'notes' => $request->notes,
                    'attempt' => ($booking->revision_count ?? 0) + 1,
                ]);

                LoggerService::logAction($booking->id, 'APPROVED', null, $request->notes);
            });
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'error' => 'Data tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 422);
        }

        /** @var Booking $booking */
        /** @var Approval $approval */
        $booking->refresh();

        // Send notification to next approver (outside transaction)
        try {
            if ($booking->status === 'Pending') {
                $nextBookingStep = BookingStep::where('booking_id', $booking->id)
                    ->where('step_order', $booking->current_step)
                    ->first();

                if ($nextBookingStep) {
                    $nextApprovers = User::where('position_id', $nextBookingStep->position_id)->get();
                    \Log::info('Attempting to send intermediate email to '.$nextApprovers->count().' approvers for booking #'.$booking->id);
                    foreach ($nextApprovers as $nextApprover) {
                        Mail::to($nextApprover->email)->send(new ApprovalNeededMail($booking, $nextApprover));
                        \Log::info('Intermediate email sent to approver: '.$nextApprover->email);
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('Mail Error (Intermediate Approval Needed booking #'.$booking->id.'): '.$e->getMessage());
        }

        // Build next_approver info from booking_steps
        $nextStepInfo = null;
        if ($booking->status !== 'Approved') {
            $nextBs = BookingStep::where('booking_id', $booking->id)
                ->where('step_order', $booking->current_step)
                ->with('position')
                ->first();
            $nextStepInfo = $nextBs ? [
                'step_order' => $nextBs->step_order,
                'tier_label' => $nextBs->tier_label,
                'position' => $nextBs->position?->name,
                'requires_attachment' => $nextBs->requires_attachment,
            ] : null;
        }

        return response()->json([
            'success' => true,
            'message' => $booking->status === 'Approved'
                ? 'Booking disetujui! Surat izin sedang digenerate dan akan dikirim ke email peminjam dalam beberapa saat.'
                : 'Booking berhasil dilanjutkan ke pejabat berikutnya.',
            'booking' => [
                'id' => $booking->id,
                'status' => $booking->status,
                'current_step' => $booking->current_step,
                'next_approver' => $nextStepInfo,
            ],
            'log' => [
                'id' => $approval->id,
                'booking_id' => $booking->id,
                'action' => 'APPROVED',
                'actor' => $approver->name,
                'notes' => $request->notes,
                'created_at' => $approval->created_at->toIso8601String(),
            ],
        ]);
    }

    /**
     * POST /api/approvals/{booking_id}/reject
     * Reject a booking (requires notes)
     * Response format matches API_DOCUMENTATION.md
     */
    public function reject(Request $request, $bookingId)
    {
        $request->validate([
            'notes' => 'required|string|min:5',
        ]);

        $approver = Auth::user();
        $positionId = $approver->position_id;

        $booking = null;
        $approval = null;

        try {
            DB::transaction(function () use ($request, $bookingId, $approver, $positionId, &$booking, &$approval) {
                $booking = Booking::lockForUpdate()->findOrFail($bookingId);

                // Find the active instantiated step for this approver
                $currentStep = BookingStep::where('booking_id', $booking->id)
                    ->where('step_order', $booking->current_step)
                    ->where('position_id', $positionId)
                    ->firstOrFail();

                if ($currentStep->requires_attachment) {
                    $hasAttachment = BookingAttachment::where('booking_id', $booking->id)
                        ->where('uploader_id', $approver->id)
                        ->exists();

                    if (! $hasAttachment) {
                        throw new \Exception(
                            'Step ini memerlukan lampiran file balasan. '
                            .'Harap upload dokumen terlebih dahulu sebelum menyetujui.'
                        );
                    }
                }

                $booking->update([
                    'status' => 'Revising',
                    'revision_count' => $booking->revision_count + 1,
                ]);

                $approval = Approval::create([
                    'booking_id' => $booking->id,
                    'approver_id' => $approver->id,
                    'booking_step_id' => $currentStep->id,
                    'step_id' => null,
                    'approval_status' => 'Rejected',
                    'notes' => $request->notes,
                    'attempt' => ($booking->revision_count ?? 0) + 1,
                ]);

                LoggerService::logAction($booking->id, 'REJECTED', null, $request->notes);
            });
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'error' => 'Data tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 422);
        }

        /** @var Booking $booking */
        /** @var Approval $approval */
        $booking->refresh();

        // Send email notification to borrower (outside transaction)
        try {
            Mail::to($booking->user->email)->send(new BookingRejectedMail($booking, $request->notes));
            \Log::info('Rejection email sent to borrower: '.$booking->user->email);
        } catch (\Exception $e) {
            \Log::error('Mail Error (Rejection Notification Booking #'.$booking->id.'): '.$e->getMessage());
        }

        // Fetch rejecting step info for response
        $rejectedStepInfo = BookingStep::where('booking_id', $booking->id)
            ->where('step_order', $booking->current_step)
            ->with('position')
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'Booking ditolak. Menunggu revisi dari peminjam.',
            'booking' => [
                'id' => $booking->id,
                'status' => $booking->status,
                'current_step' => $booking->current_step,
                'revision_count' => $booking->revision_count,
            ],
            'rejection_log' => [
                'id' => $approval->id,
                'booking_id' => $booking->id,
                'action' => 'REJECTED',
                'actor' => $approver->name,
                'actor_position' => $approver->position?->name ?? null,
                'tier_label' => $rejectedStepInfo?->tier_label,
                'notes' => $request->notes,
                'created_at' => $approval->created_at->toIso8601String(),
            ],
        ]);
    }

    /**
     * GET /dashboard (Approver)
     * Render dashboard view dengan data dari API
     */
    public function dashboard(Request $request)
    {
        $approver = Auth::user();
        $positionId = $approver->position_id;

        // Fetch pending approvals using booking_steps (new system)
        $bookings = Booking::with([
            'room.building',
            'user.unit',
            'workflow.steps.position',
            'bookingSteps.position',
            'attachments',
            'approvals.approver.position',
            'approvals.bookingStep',
        ])
            ->where('status', 'Pending')
            ->whereHas('bookingSteps', function ($q) use ($positionId) {
                $q->where('position_id', $positionId)
                    ->whereColumn('step_order', 'bookings.current_step');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Transform ke format approval response
        $approvals = $bookings->map(function ($booking) use ($positionId) {
            return $this->formatApprovalResponse($booking, $positionId);
        });

        // Calculate summary stats
        $stats = [
            'pending_count' => $approvals->count(),
            'urgent_count' => $approvals->filter(fn ($a) => $a['priority_indicator'] === 'urgent')->count(),
            'high_count' => $approvals->filter(fn ($a) => $a['priority_indicator'] === 'high')->count(),
        ];

        // Get approval history for this month
        $monthlyApprovals = Approval::where('approver_id', $approver->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('approval_status', 'Approved')
            ->count();

        $monthlyRejections = Approval::where('approver_id', $approver->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('approval_status', 'Rejected')
            ->count();

        return view('user.approver.dashboard', [
            'approvals' => $approvals->values(),
            'stats' => $stats,
            'monthlyApprovals' => $monthlyApprovals,
            'monthlyRejections' => $monthlyRejections,
            'approver' => $approver,
        ]);
    }

    /**
     * GET /meja-kerja
     * Render Meja Kerja (Work Desk) view with all pending approvals for current approver
     */
    public function mejaKerja(Request $request)
    {
        $approver = Auth::user();
        $positionId = $approver->position_id;

        // 1. Ambil input filter
        $search = $request->input('search');
        $unitFilter = $request->input('unit_id');

        // 2. Query dasar pengajuan pending sesuai posisi pejabat
        $query = Booking::with([
            'room.building',
            'user.unit',
            'workflow.steps.position',
            'bookingSteps.position',
            'attachments',
            'approvals.approver.position',
            'approvals.bookingStep',
        ])
            ->where('status', 'Pending')
            ->whereHas('bookingSteps', function ($q) use ($positionId) {
                $q->where('position_id', $positionId)
                    ->whereColumn('step_order', 'bookings.current_step');
            });

        // 3. Logika Pencarian (Event, Peminjam, atau Ruangan)
        if ($search) {
            $operator = config('database.default') === 'sqlite' ? 'like' : 'ilike';
            $query->where(function ($q) use ($search, $operator) {
                $q->where('event_name', $operator, "%$search%")
                    ->orWhereHas('user', function ($qu) use ($search, $operator) {
                        $qu->where('name', $operator, "%$search%");
                    })
                    ->orWhereHas('room', function ($qr) use ($search, $operator) {
                        $qr->where('room_name', $operator, "%$search%");
                    });
            });
        }

        // 4. Logika Filter Berdasarkan Unit
        if ($unitFilter) {
            $query->whereHas('user', function ($q) use ($unitFilter) {
                $q->where('unit_id', $unitFilter);
            });
        }

        $mode = $request->input('mode');
        if ($mode === 'satset') {
            $bookings = $query->orderBy('booking_date', 'asc')
                ->orderBy('start_time', 'asc')
                ->get();
        } else {
            $bookings = $query->orderBy('created_at', 'desc')->get();
        }

        // 5. Transformasi data ke format yang dibutuhkan Blade
        $approvals = $bookings->map(function ($booking) use ($positionId) {
            return $this->formatApprovalResponse($booking, $positionId);
        });

        // 6. Hitung Statistik untuk Dashboard Meja Kerja
        $reviewedCount = Approval::where('approver_id', $approver->id)->count();
        $stats = [
            'pending_count' => $approvals->count(),
            'urgent_count' => $approvals->filter(fn ($a) => $a['priority_indicator'] === 'urgent')->count(),
            'high_count' => $approvals->filter(fn ($a) => $a['priority_indicator'] === 'high')->count(),
            'reviewed_count' => $reviewedCount,
            'total_count' => $approvals->count() + $reviewedCount,
        ];

        // 6a. Hitung Rata-rata Durasi Penyelesaian Per-Berkas
        $approvedBookings = Booking::where('status', 'Approved')
            ->withCount('approvals')
            ->get();

        $totalMinutesPerStep = 0;
        $validBookingsCount = 0;

        foreach ($approvedBookings as $b) {
            if ($b->approvals_count > 0) {
                $duration = $b->created_at->diffInMinutes($b->updated_at);
                $totalMinutesPerStep += ($duration / $b->approvals_count);
                $validBookingsCount++;
            }
        }

        $avgMinutes = $validBookingsCount > 0 ? (int) round($totalMinutesPerStep / $validBookingsCount) : 12;

        if ($avgMinutes < 1) {
            $estimationTime = 'Kurang dari 1 Menit';
        } else {
            $days = floor($avgMinutes / 1440);
            $hours = floor(($avgMinutes % 1440) / 60);
            $remainingMinutes = $avgMinutes % 60;

            $parts = [];
            if ($days > 0) {
                $parts[] = $days.' Hari';
            }
            if ($hours > 0) {
                $parts[] = $hours.' Jam';
            }
            if ($remainingMinutes > 0 || empty($parts)) {
                $parts[] = $remainingMinutes.' Menit';
            }

            $estimationTime = implode(' ', $parts);
        }

        // 7. Ambil daftar unit untuk Dropdown (FIX: Pakai unit_name)
        $units = Unit::orderBy('unit_name', 'asc')->get();

        return view('user.approver.meja-kerja', [
            'approvals' => $approvals->values(),
            'stats' => $stats,
            'approver' => $approver,
            'units' => $units,
            'estimation_time' => $estimationTime,
        ]);
    }

    /**
     * GET /approver/approvals/{id}
     * Show detail view for single approval/booking
     */
    public function show($id)
    {
        $approver = Auth::user();
        $positionId = $approver->position_id;

        // Fetch booking with all relations
        $booking = Booking::with([
            'room.building',
            'user.unit',
            'workflow.steps.position',
            'bookingSteps.position',
            'attachments',
            'approvals.approver.position',
            'approvals.bookingStep',
        ])->findOrFail($id);

        // Verify approver can access this booking via instantiated booking_steps or history
        $hasAccess = $booking->bookingSteps
            ->where('position_id', $positionId)
            ->isNotEmpty() || $booking->approvals->where('approver_id', $approver->id)->isNotEmpty();

        if (! $hasAccess) {
            abort(403, 'Anda tidak memiliki akses untuk meninjau pengajuan ini.');
        }

        // Format approval response
        $approval = $this->formatApprovalResponse($booking, $positionId);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $approval,
            ]);
        }

        return view('user.approver.detail', [
            'approval' => $approval,
            'booking' => $booking,
            'approver' => $approver,
        ]);
    }

    /**
     * GET /approver/history
     * Show history of approvals/rejections by this approver
     */
    public function history(Request $request)
    {
        $approver = Auth::user();

        // 1. Ambil input dari request
        $search = $request->input('search');
        $unitFilter = $request->input('unit_id');

        // 2. Query dasar: Hanya ambil riwayat milik Pejabat yang sedang login
        $query = Approval::with(['booking.room.building', 'booking.user.unit', 'step.position'])
            ->where('approver_id', $approver->id);

        // 3. Logika SEARCH (Nama Event, Nama Peminjam, atau Nama Ruangan)
        if ($search) {
            $operator = config('database.default') === 'sqlite' ? 'like' : 'ilike';
            $query->whereHas('booking', function ($q) use ($search, $operator) {
                $q->where('event_name', $operator, "%$search%")
                    ->orWhereHas('user', function ($qu) use ($search, $operator) {
                        $qu->where('name', $operator, "%$search%");
                    })
                    ->orWhereHas('room', function ($qr) use ($search, $operator) {
                        $qr->where('room_name', $operator, "%$search%");
                    });
            });
        }

        // 4. Logika FILTER UNIT (Berdasarkan unit asal peminjam)
        if ($unitFilter) {
            $query->whereHas('booking.user', function ($q) use ($unitFilter) {
                $q->where('unit_id', $unitFilter);
            });
        }

        // 5. Eksekusi Paginate
        $approvals = $query->orderBy('created_at', 'desc')->paginate(10);

        // 6. Ambil daftar unit untuk dropdown (Fix column: unit_name)
        $units = Unit::orderBy('unit_name', 'asc')->get();

        return view('user.approver.riwayat', [
            'approvals' => $approvals,
            'units' => $units,
        ]);
    }
}
