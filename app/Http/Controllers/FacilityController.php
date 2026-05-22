<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacilityController extends Controller
{
    /**
     * Menampilkan halaman Dasbor Fasilitas beserta data tabel dan statistik
     */
    public function index(Request $request)
    {
        // 1. Tangkap input filter status dari URL (Berasal dari Dropdown Filter)
        $statusFilter = $request->input('status');

        // 2. Query dasar fasilitas dengan relasi ruangan (agar nama ruangannya terbaca)
        $query = Facility::with('room');

        // 3. Jika filter status dipilih oleh SuperAdmin, saring datanya di tabel
        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        // 4. Ambil hasil query untuk ditampilkan di tabel utama
        $facilities = $query->orderBy('created_at', 'desc')->get();
        
        // 5. Ambil daftar ruangan untuk pilihan dropdown di Form Tambah (Modal)
        $rooms = Room::orderBy('room_name', 'asc')->get();

        // ====================================================================
        // BAGIAN STATISTIK (Dibuat tetap global agar dasbor selalu akurat)
        // ====================================================================

        // 6. Kalkulasi Statistik Atas (Kartu Info)
        $totalFasilitas = Facility::sum('quantity'); 
        $kategoriCount = Facility::distinct('category')->count('category');
        $tersedia = Facility::where('status', 'Tersedia')->sum('quantity');
        $maintenance = Facility::whereIn('status', ['Maintenance', 'Rusak'])->sum('quantity');

        // 7. Kalkulasi Sidebar Kategori (Group by Category dan jumlahkan total barang)
        $kategoriStats = Facility::select('category', DB::raw('SUM(quantity) as total'))
            ->groupBy('category')
            ->orderBy('total', 'desc')
            ->get();

        // 8. Fitur Sidebar "Perhatian" (Hanya ambil fasilitas yang Rusak / Maintenance)
        $perhatian = Facility::with('room')
            ->whereIn('status', ['Maintenance', 'Rusak'])
            ->orderBy('updated_at', 'desc')
            ->take(4) // Batasi hanya tampil 4 yang terbaru
            ->get();

        // 9. Lempar semua variabel ke tampilan (View) Blade
        return view('user.superadmin.fasilitas', compact(
            'facilities', 
            'rooms', 
            'totalFasilitas', 
            'kategoriCount', 
            'tersedia', 
            'maintenance', 
            'kategoriStats', 
            'perhatian'
        ));
    }

    /**
     * Menyimpan data fasilitas baru ke dalam database
     */
    public function store(Request $request)
    {
        // 1. Validasi inputan dari form modal
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'name' => 'required|string|max:150',
            'category' => 'required|string|max:100',
            'quantity' => 'required|integer|min:1',
            'status' => 'required|in:Tersedia,Dipakai,Maintenance,Rusak',
        ]);

        // 2. Simpan ke database
        Facility::create([
            'room_id' => $request->room_id,
            'name' => $request->name,
            'category' => $request->category, // Misal: IT, Audio, Visual, Furnitur
            'quantity' => $request->quantity,
            'status' => $request->status,
        ]);

        // 3. Kembalikan ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Fasilitas baru berhasil ditambahkan ke dalam inventaris!');
    }
}