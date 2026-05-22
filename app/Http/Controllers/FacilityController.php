<?php

/**
 * @deprecated PARTIALLY UNUSED
 * Controller ini sebagian besar tidak digunakan lagi karena sistem beralih menggunakan master data Gedung & Ruangan langsung.
 * Metode `index` masih digunakan untuk menyajikan halaman Master Data Gedung & Ruangan Superadmin (/superadmin/fasilitas)
 * sesuai konfigurasi rute saat ini. Metode `store` dan penyimpanan data tabel `facilities` tidak lagi digunakan.
 */

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Room;
use App\Models\Unit;
use App\Models\Workflow;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    /**
     * Menampilkan halaman Manajemen Ruangan untuk Superadmin (sebelumnya dasbor fasilitas)
     */
    public function index(Request $request)
    {
        $rooms = Room::with(['building', 'unit', 'workflow'])->get();
        $buildings = Building::withCount('rooms')->get();
        $workflows = Workflow::all();
        $units = Unit::all();

        return view('user.superadmin.fasilitas', compact('rooms', 'buildings', 'workflows', 'units'));
    }
}
