<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class BookingController extends Controller
{
    public function timeline(string $id)
    {
        $booking = Booking::with('logs.actor', 'logs.step')->findOrFail($id);

        return response()->json($booking->logs);
    }
}
