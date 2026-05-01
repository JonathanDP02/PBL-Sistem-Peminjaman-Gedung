<?php

namespace App\Http\Controllers;

use App\Models\Booking;

class BookingValidationController extends Controller
{
    /**
     * Display booking validation/verification page
     * Public route - anyone can access to verify QR code authenticity
     */
    public function show(int $bookingId)
    {
        $booking = Booking::with([
            'room.building',
            'user',
            'workflow',
            'approvals.step.position',
            'approvals.approver',
        ])->findOrFail($bookingId);

        return view('booking.validate', [
            'booking' => $booking,
        ]);
    }
}