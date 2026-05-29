<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\Workflow;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = Room::with(['building', 'workflows' => function ($q) use ($user) {
            $q->where('unit_id', $user->unit_id);
        }]);

        if ($user->role->name === 'Administrator Unit') {
            $unitIds = [$user->unit_id];
            if ($user->unit && $user->unit->parent_id) {
                $unitIds[] = $user->unit->parent_id;
            }
            $query->whereIn('unit_id', $unitIds);
        }

        $rooms = $query->get()->map(function ($room) {
            return [
                'id' => $room->id,
                'room_name' => $room->room_name,
                'capacity' => $room->capacity,
                'description' => $room->description,
                'workflow_id' => $room->workflows->first()?->id,
                'building_id' => $room->building_id,
                'building_name' => $room->building?->building_name,
                'unit_id' => $room->unit_id,
            ];
        });

        return response()->json($rooms);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Validasi input (description tetap dipertahankan, tambah image)
        $validated = $request->validate([
            'building_id' => 'required',
            'room_name' => 'required',
            'capacity' => 'required|integer',
            'description' => 'nullable',
            'unit_id' => $user->role->name === 'Administrator Unit' ? 'nullable' : 'required',
            'workflow_id' => 'nullable|exists:workflows,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($user->role->name === 'Administrator Unit') {
            $validated['unit_id'] = $user->unit_id;
        }

        // Proses upload gambar
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('rooms', 'public');
        }

        $room = Room::create($validated);

        return redirect()->back()->with('success', 'Ruangan berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        $validationRules = [
            'building_id' => 'required',
            'room_name' => 'required',
            'capacity' => 'required|integer',
            'description' => 'nullable',
            'workflow_id' => 'nullable|exists:workflows,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];

        if (Auth::user()->role->name === 'Administrator Utama') {
            $validationRules['unit_id'] = 'required|exists:units,id';
        }

        $validated = $request->validate($validationRules);

        // Proses upload gambar baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($room->image) {
                Storage::disk('public')->delete($room->image);
            }
            $validated['image'] = $request->file('image')->store('rooms', 'public');
        }

        $room->update($validated);

        // If JSON request (API), return JSON response
        if (request()->expectsJson() || request()->is('admin_unit/api/*')) {
            return response()->json(['message' => 'Ruangan berhasil diperbarui', 'room' => $room]);
        }

        return redirect()->back()->with('success', 'Data ruangan berhasil diperbarui');
    }

    /**
     * Assign or unassign a workflow from a room via JSON API.
     */
    public function assignWorkflow(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        $request->validate([
            'workflow_id' => 'nullable|exists:workflows,id',
        ]);

        $workflowId = $request->input('workflow_id');
        $user = Auth::user();

        if ($workflowId) {
            $workflow = \App\Models\Workflow::findOrFail($workflowId);

            // Validasi: 1 unit = 1 workflow per ruangan
            $existingWorkflow = \App\Models\Workflow::where('unit_id', $workflow->unit_id)
                ->where('room_id', $id)
                ->where('id', '!=', $workflow->id)
                ->first();

            if ($existingWorkflow) {
                return response()->json(['message' => 'Unit Anda sudah memiliki workflow lain yang terhubung ke ruangan ini. Lepas hubungan workflow tersebut terlebih dahulu.'], 422);
            }

            // Validasi: 1 workflow hanya bisa terhubung maksimal ke 1 ruangan
            if ($workflow->room_id && $workflow->room_id != $id) {
                return response()->json(['message' => 'Workflow ini sudah terhubung dengan ruangan lain. Lepas hubungan ruangan sebelumnya terlebih dahulu.'], 422);
            }

            $workflow->update(['room_id' => $id]);
        } else {
            // Jika workflow_id null (unassign), temukan workflow milik unit ini yang terhubung ke ruangan ini
            $workflowToUnassign = \App\Models\Workflow::where('unit_id', $user->unit_id)
                ->where('room_id', $id)
                ->first();
                
            if ($workflowToUnassign) {
                $workflowToUnassign->update(['room_id' => null]);
            }
        }

        return response()->json(['message' => 'Penugasan ruangan untuk workflow berhasil diperbarui']);
    }

    public function show($id)
    {
        $room = Room::findOrFail($id);

        return response()->json($room);
    }

    public function destroy(Request $request, $id)
    {
        $user = Auth::user();
        $room = Room::findOrFail($id);

        // Authorization check
        if ($user->role->name === 'Administrator Unit') {
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

        if ($room->image) {
            Storage::disk('public')->delete($room->image);
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
            'workflows.steps.position',
            'workflows.requirements',
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
