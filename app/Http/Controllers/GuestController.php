<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Building;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class GuestController extends Controller
{
    public function welcome(Request $request)
    {
        $selectedDate = $request->query('date') ? Carbon::parse($request->query('date')) : Carbon::now();
        $startOfWeek = $selectedDate->copy()->startOfWeek();
        $endOfWeek = $selectedDate->copy()->endOfWeek();

        $bookings = collect();
        $allBookings = collect();
        $rooms = collect();
        $selectedRoomId = $request->query('room_id');

        $popularRooms = collect();
        if (Schema::hasTable('rooms')) {
            $rooms = Room::with('building')->orderBy('room_name')->get();
            $popularRooms = Room::with('building')->take(3)->get();
        }

        if (Schema::hasTable('bookings')) {
            // Fetch bookings for the weekly timetable grid
            $weeklyQuery = Booking::with(['room.building', 'user.unit'])
                ->where('booking_date', '<=', $endOfWeek)
                ->where('booking_end_date', '>=', $startOfWeek)
                ->whereIn('status', ['Approved', 'Pending']);

            if ($selectedRoomId) {
                $weeklyQuery->where('room_id', $selectedRoomId);
            }
            $bookings = $weeklyQuery->orderBy('start_time')->get();

            // Fetch bookings in the selected month for FullCalendar (if room is not selected)
            if (! $selectedRoomId) {
                $startOfMonth = $selectedDate->copy()->startOfMonth()->startOfDay();
                $endOfMonth = $selectedDate->copy()->endOfMonth()->endOfDay();
                $allBookings = Booking::with(['room.building', 'user.unit'])
                    ->whereBetween('booking_date', [$startOfMonth, $endOfMonth])
                    ->whereIn('status', ['Approved', 'Pending'])
                    ->get();
            }
        }

        $weekDates = collect();
        for ($i = 0; $i < 7; $i++) {
            $weekDates->push($startOfWeek->copy()->addDays($i));
        }

        return view('welcome', compact('bookings', 'weekDates', 'rooms', 'popularRooms', 'allBookings', 'selectedRoomId'));
    }

    public function ruangan()
    {
        $buildings = collect();
        if (Schema::hasTable('buildings')) {
            $buildings = Building::with('rooms')->get();
        }

        return view('ruangan', compact('buildings'));
    }
}
