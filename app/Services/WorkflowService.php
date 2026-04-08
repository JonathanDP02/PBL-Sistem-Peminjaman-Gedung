<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\WorkflowStep;
use App\Models\WorkflowRequirement;
use App\Models\BookingAttachment;
use Illuminate\Database\Eloquent\Collection;

class WorkflowService
{
    /**
     * Get the next approver for a booking based on current step.
     * 
     * Queries workflow_steps to find the approver at current_step+1,
     * then returns the User (approver) at that position.
     * 
     * @param int $bookingId
     * @return \App\Models\User|null
     */
    public function getNextApprover(int $bookingId): ?\App\Models\User
    {
        $booking = Booking::with(['workflow', 'room.unit'])->findOrFail($bookingId);
        
        $nextStep = WorkflowStep::where('workflow_id', $booking->workflow_id)
            ->where('step_order', $booking->current_step + 1)
            ->first();
        
        if (!$nextStep) {
            return null;
        }
        
        // Find user dengan position_id yang sesuai dan unit_id dari room owner
        return \App\Models\User::where('position_id', $nextStep->position_id)
            ->where('unit_id', $booking->room->unit_id)
            ->first();
    }

    /**
     * Validate if all workflow requirements have been uploaded for a booking.
     * 
     * Checks workflow_requirements table and ensures all mandatory documents
     * exist in booking_attachments before status changes from Draft to Pending.
     * 
     * @param int $bookingId
     * @return array ['valid' => bool, 'missing' => Collection]
     */
    public function validateRequirements(int $bookingId): array
    {
        $booking = Booking::findOrFail($bookingId);
        
        // Get all workflow requirements
        $requirements = WorkflowRequirement::where('workflow_id', $booking->workflow_id)
            ->where('is_mandatory', true)
            ->pluck('id')
            ->toArray();
        
        if (empty($requirements)) {
            return ['valid' => true, 'missing' => collect()];
        }
        
        // Get uploaded attachments for this booking
        $uploadedRequirements = BookingAttachment::where('booking_id', $bookingId)
            ->whereIn('requirement_id', $requirements)
            ->pluck('requirement_id')
            ->toArray();
        
        // Find missing requirements
        $missingIds = array_diff($requirements, $uploadedRequirements);
        
        if (empty($missingIds)) {
            return ['valid' => true, 'missing' => collect()];
        }
        
        // Get missing requirement names
        $missing = WorkflowRequirement::whereIn('id', $missingIds)->get();
        
        return ['valid' => false, 'missing' => $missing];
    }

    /**
     * Get all workflow steps for a booking in order.
     * 
     * @param int $bookingId
     * @return Collection
     */
    public function getWorkflowSteps(int $bookingId): Collection
    {
        $booking = Booking::findOrFail($bookingId);
        
        return WorkflowStep::where('workflow_id', $booking->workflow_id)
            ->with(['position'])
            ->orderBy('step_order')
            ->get();
    }

    /**
     * Check if next approver requires attachment for their approval.
     * 
     * @param int $bookingId
     * @return bool
     */
    public function nextApproverRequiresAttachment(int $bookingId): bool
    {
        $booking = Booking::findOrFail($bookingId);
        
        $nextStep = WorkflowStep::where('workflow_id', $booking->workflow_id)
            ->where('step_order', $booking->current_step + 1)
            ->first();
        
        return $nextStep?->requires_attachment ?? false;
    }
}
