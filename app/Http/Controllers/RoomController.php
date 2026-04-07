<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role->name === 'Admin_Unit') {
            return Room::where('unit_id', $user->unit_id)->get();
        }

        return Room::all();
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'building_id' => 'required',
            'room_name' => 'required',
            'capacity' => 'required|integer',
            'description' => 'nullable',
            'unit_id' => $user->role->name === 'SuperAdmin' ? 'required' : 'nullable',
        ]);

        if ($user->role->name === 'Admin_Unit') {
            $validated['unit_id'] = $user->unit_id;
        }

        if ($user->role->name === 'SuperAdmin' && ! $request->unit_id) {
            return response()->json(['message' => 'unit_id wajib untuk SuperAdmin'], 422);
        }

        return Room::create($validated);
    }

    public function update(Request $request, Room $room)
    {
        $user = Auth::user();

        if ($user->role->name === 'Admin_Unit') {
            if ($room->unit_id != $user->unit_id) {
                abort(403);
            }
        }

        $validated = $request->validate([
            'building_id' => 'required',
            'room_name' => 'required',
            'capacity' => 'required|integer',
            'description' => 'nullable',
        ]);

        $room->update($validated);

        return $room;
    }

    public function showApi($id)
    {
        $room = Room::with([
            'building',
            'unit',
            'bookings.workflow',
        ])->findOrFail($id);

        return response()->json($room);
    }
}
