<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ApprovalNeededMail;
use App\Mail\BookingRejectedMail;
use App\Models\Approval;
use App\Models\Booking;
use App\Models\BookingLog;
use App\Models\BookingStep;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

/**
 * API v1 ApprovalController
 * Handles approval operations for Approver role
 * All endpoints return JSON responses matching API_DOCUMENTATION.md
 *
 * IMPORTANT: All approval logic reads from `booking_steps` (immutable snapshot
 * created at booking submission time), NOT from `workflow_steps` (mutable template).
 * This ensures changes to workflow templates after a booking is submitted have no
 * effect on in-flight bookings.
 */
class ApprovalController extends Controller
{
    /**
     * GET /api/approvals/pending
     * Fetch pending approvals for current approver (Meja Kerja / Work Desk)
     * Query params: sort, filter
     */
    public function pending(Request $request)
    {
        $approver = Auth::user();
        $positionId = $approver->position_id;

        if (! $positionId) {
            return response()->json([
                'success' => false,
                'message' => 'User does not have a position assigned',
            ], 403);
        }

        $bookings = Booking::with([
            'room.building',
            'user.unit',
            'workflow.steps.position',
            'bookingSteps.position',
            'attachments',
            'approvals.approver.position',
            'approvals.bookingStep',
        ])
            ->whereIn('status', ['Pending', 'Revising'])
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
     * GET /api/approvals/{booking_id}
     * Get detailed booking for approval review
     */
    public function show($bookingId)
    {
        $approver = Auth::user();
        $booking = Booking::with([
            'room.building',
            'user.unit',
            'workflow.steps.position',
            'bookingSteps.position',
            'attachments',
            'approvals.approver.position',
            'approvals.bookingStep',
        ])->findOrFail($bookingId);

        // Verify approver has access to this booking via instantiated booking_steps
        $currentStep = $booking->bookingSteps
            ->where('step_order', $booking->current_step)
            ->first();

        if (! $currentStep || $currentStep->position_id !== $approver->position_id) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to view this booking',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'booking' => $this->formatDetailResponse($booking),
        ]);
    }

    /**
     * POST /api/approvals/{booking_id}/approve
     * Approve a booking at current step
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

                // Use immutable booking_steps snapshot — not the mutable workflow template
                $currentStep = BookingStep::where('booking_id', $booking->id)
                    ->where('step_order', $booking->current_step)
                    ->where('position_id', $positionId)
                    ->firstOrFail();

                // Check if there is a next step in the instantiated chain
                $nextStep = BookingStep::where('booking_id', $booking->id)
                    ->where('step_order', '>', $booking->current_step)
                    ->orderBy('step_order')
                    ->first();

                if ($nextStep) {
                    $booking->update([
                        'current_step' => $nextStep->step_order,
                        'status' => 'Pending',
                    ]);
                } else {
                    $booking->update([
                        'status' => 'Approved',
                    ]);
                }

                $approval = Approval::create([
                    'booking_id' => $booking->id,
                    'approver_id' => $approver->id,
                    'booking_step_id' => $currentStep->id,
                    'step_id' => null,
                    'approval_status' => 'Approved',
                    'notes' => $request->notes,
                    'approved_at' => now(),
                    'attempt' => ($booking->revision_count ?? 0) + 1,
                ]);

                BookingLog::create([
                    'booking_id' => $booking->id,
                    'actor_id' => $approver->id,
                    'step_id' => null,
                    'action' => 'APPROVED',
                    'notes' => $request->notes ?? 'Disetujui.',
                ]);
            });

            $booking->refresh();

            // Dispatch intermediate email notifications outside transaction
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
                'message' => 'Booking berhasil disetujui.',
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
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * POST /api/approvals/{booking_id}/reject
     * Reject a booking (requires notes)
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

                // Use immutable booking_steps snapshot — not the mutable workflow template
                $currentStep = BookingStep::where('booking_id', $booking->id)
                    ->where('step_order', $booking->current_step)
                    ->where('position_id', $positionId)
                    ->firstOrFail();

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
                    'approved_at' => now(),
                    'attempt' => ($booking->revision_count ?? 0) + 1,
                ]);

                BookingLog::create([
                    'booking_id' => $booking->id,
                    'actor_id' => $approver->id,
                    'step_id' => null,
                    'action' => 'REJECTED',
                    'notes' => $request->notes,
                ]);
            });

            $booking->refresh();

            // Send email notification to borrower (outside transaction)
            try {
                Mail::to($booking->user->email)->send(new BookingRejectedMail($booking, $request->notes));
                \Log::info('Rejection email sent to borrower: '.$booking->user->email);
            } catch (\Exception $e) {
                \Log::error('Mail Error (Rejection Notification Booking #'.$booking->id.'): '.$e->getMessage());
            }

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
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Format single booking menjadi approval response format
     * Sesuai API_DOCUMENTATION.md
     * Prefers booking_steps (immutable) over workflow.steps (mutable template).
     */
    private function formatApprovalResponse($booking, $positionId)
    {
        // Prefer instantiated booking_steps; fallback to workflow template
        $currentStep = $booking->bookingSteps
            ->where('step_order', $booking->current_step)
            ->where('position_id', $positionId)
            ->first()
            ?? $booking->workflow?->steps
                ->where('step_order', $booking->current_step)
                ->where('position_id', $positionId)
                ->first();

        $previousApprovals = $booking->approvals
            ->sortBy('created_at')
            ->map(function ($approval) {
                $stepRecord = $approval->bookingStep ?? $approval->step;

                return [
                    'step_order' => $stepRecord?->step_order ?? null,
                    'position' => $stepRecord?->position?->name ?? null,
                    'approver_name' => $approval->approver?->name,
                    'approval_status' => ucfirst($approval->approval_status),
                    'approved_at' => $approval->approved_at->toIso8601String(),
                    'notes' => $approval->notes,
                ];
            })
            ->filter(function ($approval) use ($currentStep) {
                return $currentStep && $approval['step_order'] < $currentStep->step_order;
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

        // Total steps from immutable booking chain
        $totalSteps = $booking->bookingSteps->count() ?: $booking->workflow?->steps->count() ?? 0;

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
                'room_code' => $booking->room->room_code ?? null,
                'capacity' => $booking->room->capacity,
                'building' => [
                    'id' => $booking->room->building->id,
                    'building_name' => $booking->room->building->building_name,
                    'building_code' => $booking->room->building->building_code ?? null,
                ],
            ],
            'peminjam' => [
                'id' => $booking->user->id,
                'name' => $booking->user->name,
                'email' => $booking->user->email,
                'unit' => [
                    'id' => $booking->user->unit->id,
                    'name' => $booking->user->unit->unit_name,
                ],
            ],
            'workflow' => [
                'id' => $booking->workflow->id,
                'name' => $booking->workflow->name,
                'total_steps' => $totalSteps,
            ],
            'current_approver_required' => [
                'step_order' => $currentStep?->step_order,
                'position' => [
                    'id' => $currentStep?->position_id,
                    'name' => $currentStep?->position?->name,
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
     * Format detail response for GET /api/approvals/{id}
     */
    private function formatDetailResponse($booking)
    {
        // Use immutable booking_steps for the steps list
        $steps = $booking->bookingSteps->count()
            ? $booking->bookingSteps->map(fn ($step) => [
                'step_order' => $step->step_order,
                'tier_label' => $step->tier_label,
                'position' => $step->position?->name,
                'requires_attachment' => $step->requires_attachment,
            ])
            : $booking->workflow->steps->map(fn ($step) => [
                'step_order' => $step->step_order,
                'tier_label' => null,
                'position' => $step->position->name,
                'requires_attachment' => $step->requires_attachment,
            ]);

        return [
            'id' => $booking->id,
            'event_name' => $booking->event_name,
            'event_description' => $booking->event_description,
            'booking_date' => $booking->booking_date->format('Y-m-d'),
            'start_time' => $booking->start_time->format('H:i'),
            'end_time' => $booking->end_time->format('H:i'),
            'status' => $booking->status,
            'current_step' => $booking->current_step,
            'revision_count' => $booking->revision_count,
            'room' => [
                'id' => $booking->room->id,
                'room_name' => $booking->room->room_name,
                'room_code' => $booking->room->room_code ?? null,
                'capacity' => $booking->room->capacity,
                'floor' => $booking->room->floor ?? null,
                'building' => [
                    'id' => $booking->room->building->id,
                    'building_name' => $booking->room->building->building_name,
                    'address' => $booking->room->building->address ?? null,
                ],
            ],
            'peminjam' => [
                'id' => $booking->user->id,
                'name' => $booking->user->name,
                'email' => $booking->user->email,
                'phone' => $booking->user->phone ?? null,
                'unit' => [
                    'id' => $booking->user->unit->id,
                    'name' => $booking->user->unit->unit_name,
                ],
            ],
            'workflow' => [
                'id' => $booking->workflow->id,
                'name' => $booking->workflow->name,
                'total_steps' => $steps->count(),
                'steps' => $steps->values(),
            ],
            'approval_timeline' => $booking->approvals
                ->sortBy('created_at')
                ->map(function ($approval) {
                    $stepRecord = $approval->bookingStep ?? $approval->step;

                    return [
                        'step_order' => $stepRecord?->step_order,
                        'position' => $stepRecord?->position?->name,
                        'approver_name' => $approval->approver?->name,
                        'approval_status' => ucfirst($approval->approval_status),
                        'approved_at' => $approval->approved_at?->toIso8601String(),
                        'notes' => $approval->notes,
                    ];
                })
                ->values(),
            'documents' => $booking->attachments->map(fn ($doc) => [
                'id' => $doc->id,
                'document_name' => $doc->document_name,
                'file_type' => pathinfo($doc->file_path, PATHINFO_EXTENSION),
                'file_size' => $this->formatFileSize($doc->file_size ?? 0),
                'file_url' => $doc->file_path,
                'uploaded_at' => $doc->created_at->toIso8601String(),
            ]),
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
}
