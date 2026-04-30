<?php

namespace App\Http\Controllers;

use App\Models\Approval;
use App\Models\Booking;
use App\Models\BookingAttachment;
use App\Models\BookingLog;
use App\Models\WorkflowStep;
use App\Services\LoggerService;
use App\Services\WorkflowService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            'attachments',
            'approvals.approver.position',
            'approvals.step',
        ])
            ->whereIn('status', ['Pending', 'Revising'])
            ->whereHas('workflow.steps', function ($q) use ($positionId) {
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
        $currentStep = $booking->workflow->steps
            ->where('step_order', $booking->current_step)
            ->where('position_id', $positionId)
            ->first();

        $previousApprovals = $booking->approvals
            ->where('step_id', '<', $currentStep?->id)
            ->sortBy('created_at')
            ->map(function ($approval) {
                return [
                    'step_order' => $approval->step->step_order,
                    'position' => $approval->step->position->name ?? null,
                    'approver_name' => $approval->approver->name,
                    'approval_status' => ucfirst($approval->approval_status),
                    'approved_at' => $approval->approved_at->toIso8601String(),
                    'notes' => $approval->notes,
                ];
            })
            ->values();

        $documentsUploaded = $booking->attachments->map(function ($attachment) use ($booking) {
            return [
                'id' => $attachment->id,
                'document_name' => $attachment->document_name,
                'document_type' => $attachment->document_type,
                'file_path' => $attachment->file_path,
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
                    'name' => $booking->user->unit->name,
                ],
            ],
            'workflow' => [
                'id' => $booking->workflow->id,
                'name' => $booking->workflow->name,
                'total_steps' => $booking->workflow->steps->count(),
            ],
            'current_approver_required' => [
                'step_order' => $currentStep?->step_order,
                'position' => [
                    'id' => $currentStep?->position_id,
                    'name' => $currentStep?->position->name,
                ],
                'requires_attachment' => (bool) $currentStep?->requires_attachment,
                'attachment_type' => $currentStep?->attachment_type ?? null,
            ],
            'previous_approvals' => $previousApprovals,
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

                $currentStep = WorkflowStep::where('workflow_id', $booking->workflow_id)
                    ->where('step_order', $booking->current_step)
                    ->where('position_id', $positionId)
                    ->firstOrFail();

                if ($currentStep->requires_attachment) {
                    $hasAttachment = BookingAttachment::where('booking_id', $booking->id)
                        ->where('uploader_id', $approver->id)
                        ->exists();

                    if (! $hasAttachment) {
                        throw new \Exception(
                            'Step ini memerlukan lampiran file balasan. '.
                            'Harap upload dokumen terlebih dahulu sebelum menyetujui.'
                        );
                    }
                }

                // $nextStep = WorkflowStep::where('workflow_id', $booking->workflow_id)
                //     ->where('step_order', '>', $booking->current_step)
                //     ->orderBy('step_order')
                //     ->first();

                // if ($nextStep) {
                //     $booking->update([
                //         'current_step' => $nextStep->step_order,
                //         'status' => 'Pending',
                //     ]);
                // } else {
                //     $booking->update([
                //         'status' => 'Approved',
                //     ]);
                // }

                $nextApprover = app(WorkflowService::class)->getNextApprover($booking->id);

                $nextStep = WorkflowStep::where('workflow_id', $booking->workflow_id)
                    ->where('step_order', '>', $booking->current_step)
                    ->orderBy('step_order')
                    ->first();

                if ($nextApprover && $nextStep) {
                    $booking->update([
                        'current_step' => $nextStep->step_order,
                        'status' => 'Pending',
                    ]);
                } else {
                    $booking->update(['status' => 'Approved']);
                }
                $approval = Approval::create([
                    'booking_id' => $booking->id,
                    'approver_id' => $approver->id,
                    'step_id' => $currentStep->id,
                    'approval_status' => 'Approved',
                    'notes' => $request->notes,
                ]);

                if ($currentStep->requires_attachment) {
                    $hasAttachment = BookingAttachment::where('booking_id', $booking->id)
                        ->where('uploader_id', $approver->id)
                        ->exists();

                    if (! $hasAttachment) {
                        throw new \Exception(
                            'Step ini memerlukan lampiran file balasan. '.
                            'Harap upload dokumen terlebih dahulu sebelum menyetujui.'
                        );
                    }
                }

                // BookingLog::create([
                //     'booking_id' => $booking->id,
                //     'actor_id' => $approver->id,
                //     'step_id' => $currentStep->id,
                //     'action' => 'APPROVED',
                //     'notes' => $request->notes ?? 'Disetujui.',
                // ]);

                LoggerService::logAction($booking->id, 'APPROVED', $currentStep->id, $request->notes);
            });
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 422);
        }

        $booking->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Booking berhasil disetujui.',
            'booking' => [
                'id' => $booking->id,
                'status' => $booking->status,
                'current_step' => $booking->current_step,
                'next_approver' => $booking->status !== 'Approved' ? [
                    'step_order' => $booking->workflow->steps
                        ->where('step_order', $booking->current_step)
                        ->first()?->step_order,
                    'position' => $booking->workflow->steps
                        ->where('step_order', $booking->current_step)
                        ->first()?->position->name,
                    'requires_attachment' => $booking->workflow->steps
                        ->where('step_order', $booking->current_step)
                        ->first()?->requires_attachment,
                ] : null,
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

                $currentStep = WorkflowStep::where('workflow_id', $booking->workflow_id)
                    ->where('step_order', $booking->current_step)
                    ->where('position_id', $positionId)
                    ->firstOrFail();

                if ($currentStep->requires_attachment) {
                    $hasAttachment = BookingAttachment::where('booking_id', $booking->id)
                        ->where('uploader_id', $approver->id)
                        ->exists();

                    if (! $hasAttachment) {
                        throw new \Exception(
                            'Step ini memerlukan lampiran file balasan. '.
                            'Harap upload dokumen terlebih dahulu sebelum menyetujui.'
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
                    'step_id' => $currentStep->id,
                    'approval_status' => 'Rejected',
                    'notes' => $request->notes,
                ]);

                // BookingLog::create([
                //     'booking_id' => $booking->id,
                //     'actor_id' => $approver->id,
                //     'step_id' => $currentStep->id,
                //     'action' => 'REJECTED',
                //     'notes' => $request->notes,
                // ]);

                LoggerService::logAction($booking->id, 'REJECTED', $currentStep->id, $request->notes);

            });
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 422);
        }

        $booking->refresh();

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
                'actor_position' => $approver->position->name ?? null,
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

        // Fetch pending approvals
        $bookings = Booking::with([
            'room.building',
            'user.unit',
            'workflow.steps.position',
            'attachments',
            'approvals.approver.position',
            'approvals.step',
        ])
            ->whereIn('status', ['Pending', 'Revising'])
            ->whereHas('workflow.steps', function ($q) use ($positionId) {
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

        // Fetch all pending approvals (sama seperti API index tapi render sebagai view)
        $bookings = Booking::with([
            'room.building',
            'user.unit',
            'workflow.steps.position',
            'attachments',
            'approvals.approver.position',
            'approvals.step',
        ])
            ->whereIn('status', ['Pending', 'Revising'])
            ->whereHas('workflow.steps', function ($q) use ($positionId) {
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

        return view('user.approver.meja-kerja', [
            'approvals' => $approvals->values(),
            'stats' => $stats,
            'approver' => $approver,
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
            'attachments',
            'approvals.approver.position',
            'approvals.step',
        ])->findOrFail($id);

        // Verify approver can access this booking (must have position in workflow)
        $hasAccess = $booking->workflow->steps
            ->where('position_id', $positionId)
            ->isNotEmpty();

        if (! $hasAccess) {
            abort(403, 'Anda tidak memiliki akses untuk meninjau pengajuan ini.');
        }

        // Format approval response
        $approval = $this->formatApprovalResponse($booking, $positionId);

        return view('user.approver.detail', [
            'approval' => $approval,
            'booking' => $booking,
            'approver' => $approver,
        ]);
    }
}
