<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role->name === 'Admin_Unit') {
            return response()->json(Room::where('unit_id', $user->unit_id)->get());
        }

        return response()->json(Room::all());
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $validated = $request->validate([
            'building_id' => 'required',
            'room_name' => 'required',
            'capacity' => 'required|integer',
            'description' => 'nullable',
            // unit_id must be provided unless the user is Admin_Unit (we'll auto-assign their unit)
            'unit_id' => $user->role->name === 'Admin_Unit' ? 'nullable' : 'required',
        ]);

        // Atur unit_id sesuai role
        if ($user->role->name === 'Admin_Unit') {
            $validated['unit_id'] = $user->unit_id;
        }

        if ($user->role->name === 'SuperAdmin' && ! $request->unit_id) {
            return redirect()->back()->withErrors(['unit_id' => 'unit_id wajib untuk SuperAdmin']);
        }

        // Buat ruangan
        Room::create($validated);

        // Kembali ke halaman sebelumnya karena disubmit via form HTML
        return redirect()->back()->with('success', 'Ruangan berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $room = Room::findOrFail($id);

        // Authorization check
        if ($user->role->name === 'Admin_Unit') {
            if ($room->unit_id != $user->unit_id) {
                abort(403, 'Unauthorized');
            }
        }

        // Validasi input
        $validated = $request->validate([
            'building_id' => 'required|exists:buildings,id',
            'room_name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        // Update ruangan
        $room->update($validated);

        // Kembali ke halaman sebelumnya karena disubmit via form HTML
        return redirect()->back()->with('success', 'Ruangan berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $user = Auth::user();
        $room = Room::findOrFail($id);

        // Authorization check
        if ($user->role->name === 'Admin_Unit') {
            if ($room->unit_id != $user->unit_id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
        }

        // Cek jika ada booking yang belum selesai
        $activeBookings = Booking::where('room_id', $room->id)
            ->whereNotIn('status', ['Rejected', 'Cancelled', 'Approved'])
            ->count();

        if ($activeBookings > 0) {
            return response()->json([
                'message' => 'Tidak dapat menghapus ruangan yang masih memiliki peminjaman aktif',
            ], 422);
        }

        $room->delete();

        // Mengembalikan JSON karena disubmit via JavaScript (Fetch)
        return response()->json(['message' => 'Ruangan berhasil dihapus']);
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

    public function blockRoom(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'mulai_dari' => 'required|date',
            'hingga' => 'required|date|after:mulai_dari',
            'alasan' => 'required|string',
        ]);

        $user = Auth::user();
        
        // Pastikan ruangan benar-benar milik unit admin ini (Keamanan)
        $room = Room::where('id', $request->room_id)->where('unit_id', $user->unit_id)->firstOrFail();

        // Simpan sebagai booking khusus dengan awalan [MAINTENANCE]
        // Sesuaikan nama kolom ini dengan tabel 'bookings' Anda
        // Di dalam RoomController.php fungsi blockRoom
        Booking::create([
            'room_id' => $room->id,
            'user_id' => $user->id,
            'booking_date' => date('Y-m-d', strtotime($request->mulai_dari)),
            'start_time' => date('H:i', strtotime($request->mulai_dari)),
            'end_time' => date('H:i', strtotime($request->hingga)),
            'event_name' => '[MAINTENANCE HARD-LOCK]', // Masuk ke event_name
            'event_description' => $request->alasan,    // Masuk ke event_description
            'status' => 'Approved',
        ]);

        return redirect()->back()->with('success', 'Jadwal ruangan berhasil diblokir!');
    }
}