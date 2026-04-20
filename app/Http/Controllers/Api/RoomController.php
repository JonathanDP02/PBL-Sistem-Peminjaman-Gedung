<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function available(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'start' => 'required|date_format:H:i',
            'end' => 'required|date_format:H:i|after:start',
        ]);

        $date = $request->date;
        $start = $request->start;
        $end = $request->end;

        $availableRooms = Room::whereDoesntHave('bookings', function ($query) use ($date, $start, $end) {
            $query->where('booking_date', $date)
                  ->whereIn('status', ['Pending', 'Approved'])
                  ->where(function ($q) use ($start, $end) {
                      $q->where('start_time', '<', $end)
                        ->where('end_time', '>', $start);
                  });
        })->get();

        return response()->json($availableRooms);
    }
}
