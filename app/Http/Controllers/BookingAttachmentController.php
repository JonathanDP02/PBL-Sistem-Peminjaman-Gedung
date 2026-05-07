<?php

namespace App\Http\Controllers;

use App\Models\BookingAttachment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BookingAttachmentController extends Controller
{
    public function show(int $id, int $attachmentId): mixed
    {
        $user = Auth::user();
        \Log::info("Accessing attachment {$attachmentId} for booking {$id}. User: {$user->email} (Role: {$user->role->name})");

        $attachment = BookingAttachment::with(['booking.workflow.steps', 'booking.room'])
            ->where('id', $attachmentId)
            ->where('booking_id', $id)
            ->firstOrFail();

        $booking = $attachment->booking;

        // 1. Borrower Check
        $isBorrower = $booking->user_id === $user->id;

        // 2. Approver Check (Must have a position in the workflow)
        $isApprover = $user->role->name === 'Approver' && 
            $booking->workflow->steps->pluck('position_id')->contains($user->position_id);

        // 3. Admin Unit Check (Must manage the room's unit)
        $isAdminUnit = $user->role->name === 'Admin_Unit' && 
            $booking->room->unit_id === $user->unit_id;

        // 4. SuperAdmin Check
        $isSuperAdmin = $user->role->name === 'SuperAdmin';

        \Log::info("Auth check - Borrower: ".($isBorrower?'Y':'N').", Approver: ".($isApprover?'Y':'N').", AdminUnit: ".($isAdminUnit?'Y':'N').", SuperAdmin: ".($isSuperAdmin?'Y':'N'));

        if (!$isBorrower && !$isApprover && !$isAdminUnit && !$isSuperAdmin) {
            \Log::warning("Access denied for user {$user->email} to attachment {$attachmentId}");
            abort(403, 'Anda tidak memiliki akses untuk melihat dokumen ini.');
        }

        if (!Storage::disk('private')->exists($attachment->file_path)) {
            \Log::error("File not found on disk: " . $attachment->file_path);
            abort(404, 'File tidak ditemukan.');
        }

        $fullPath = Storage::disk('private')->path($attachment->file_path);
        \Log::info("Serving file: " . $fullPath);
        
        return response()->file($fullPath);
    }
}