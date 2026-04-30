<?php

namespace App\Http\Controllers;

use App\Models\Booking;

class BookingValidationController extends Controller
{
    public function show(int $bookingId): mixed
    {
        $booking = Booking::with([
            'room',
            'user',
            'workflow',
            'approvals.step.position',
            'approvals.approver',
        ])->findOrFail($bookingId);

        return response()->json([
            'booking_id'   => $booking->id,
            'event_name'   => $booking->event_name,
            'booking_date' => $booking->booking_date->format('d F Y'),
            'start_time'   => \Carbon\Carbon::parse($booking->start_time)->format('H:i'),
            'end_time'     => \Carbon\Carbon::parse($booking->end_time)->format('H:i'),
            'room'         => $booking->room->name ?? '-',
            'peminjam'     => $booking->user->name ?? '-',
            'status'       => $booking->status,
        ]);
    }
}