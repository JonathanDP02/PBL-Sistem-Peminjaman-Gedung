<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\Workflow;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
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

        // 1. Parsing Tanggal dan Waktu menggunakan Carbon
        $startDateTime = Carbon::parse($request->mulai_dari);
        $endDateTime = Carbon::parse($request->hingga);

        $diffInDays = $startDateTime->diffInDays($endDateTime);
        if ($diffInDays > 366) {
            return redirect()->back()->withErrors([
                'hingga' => 'Durasi pemblokiran maksimal 1 tahun ',
            ])->withInput();
        }

        // Pisahkan Jam Mulai dan Jam Selesai
        $startTime = $startDateTime->format('H:i');
        $endTime = $endDateTime->format('H:i');

        // Jika jam end_time sama dengan jam start_time atau lebih kecil (karena melewati hari), kita set full day
        // Namun jika mereka memblokir di hari yang sama, gunakan jam yang spesifik
        $isSameDay = $startDateTime->isSameDay($endDateTime);

        // Mulai Looping dari Hari Pertama sampai Hari Terakhir
        $currentDate = $startDateTime->copy()->startOfDay();
        $lastDate = $endDateTime->copy()->startOfDay();

        // Tambahkan Batch ID untuk pengelompokan (Grouping)
        $batchId = strtoupper(substr(uniqid(), -6));

        while ($currentDate->lte($lastDate)) {

            // Logika Penentuan Jam per Harinya
            $dayStartTime = '00:00';
            $dayEndTime = '23:59';

            if ($isSameDay) {
                // Jika hanya 1 hari, patuhi jam awal dan akhir
                $dayStartTime = $startTime;
                $dayEndTime = $endTime;
            } else {
                // Jika multi-hari
                if ($currentDate->isSameDay($startDateTime)) {
                    // Hari pertama, mulai dari jam yang ditentukan sampai akhir hari
                    $dayStartTime = $startTime;
                } elseif ($currentDate->isSameDay($endDateTime)) {
                    // Hari terakhir, mulai dari awal hari sampai jam yang ditentukan
                    $dayEndTime = $endTime;
                }
                // Jika hari di tengah-tengah, biarkan full day (00:00 - 23:59)
            }

            // Simpan ke Database
            Booking::create([
                'room_id' => $room->id,
                'user_id' => $user->id,
                'booking_date' => $currentDate->format('Y-m-d'), // Simpan format Y-m-d
                'start_time' => $dayStartTime,
                'end_time' => $dayEndTime,
                // Tambahkan workflow_id dummy (biarkan null/0 jika database mengizinkan, atau ambil workflow pertama)
                'workflow_id' => Workflow::where('unit_id', $room->unit_id)->value('id') ?? 1,
                'event_name' => "[MAINTENANCE HARD-LOCK] #$batchId",
                'event_description' => $request->alasan,
                'status' => 'Approved', // Otomatis disetujui agar langsung memblokir kalender
            ]);

            // Lanjut ke hari berikutnya
            $currentDate->addDay();
        }

        return redirect()->back()->with('success', 'Jadwal ruangan berhasil diblokir secara Hard-Lock!');
    }

    public function unblockRoom(Request $request): RedirectResponse
    {
        $eventName = urldecode($request->input('event_name', ''));
        $eventName = trim($eventName);
        $user = Auth::user();

        // Ambil data untuk verifikasi (opsional, untuk pesan sukses)
        $blockings = Booking::where('event_name', $eventName)
            ->whereHas('room', function ($q) use ($user) {
                $q->where('unit_id', $user->unit_id);
            });

        $count = $blockings->count();

        if ($count === 0) {
            return redirect()->back()->withErrors(['message' => 'Jadwal pemblokiran tidak ditemukan atau Anda tidak memiliki akses.']);
        }

        $blockings->delete();

        return redirect()->back()->with('success', "Pemblokiran berhasil dibatalkan. $count slot jadwal telah dibuka kembali.");
    }
}
