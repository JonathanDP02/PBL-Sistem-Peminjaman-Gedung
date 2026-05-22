<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        // 1. Ambil data dengan struktur pohon (Root = Pusat)
        $rootUnits = Unit::whereNull('parent_id')
            ->with('children.children')
            ->orderBy('unit_name', 'asc')
            ->get();

        // 2. Ambil SEMUA unit untuk pilihan Dropdown di Form
        $allUnits = Unit::orderBy('unit_name', 'asc')->get();

        // 3. Kalkulasi Statistik sesuai Terminologi
        $totalUnit = Unit::count();
        $totalPusat = Unit::where('level', 'Pusat')->count();
        $totalJurusan = Unit::where('level', 'Jurusan')->count(); 
        $totalOrganisasi = Unit::where('level', 'Organisasi')->count(); 

        // Menghitung Kedalaman Level
        $maxLevel = 0;
        if ($totalPusat > 0) $maxLevel = 1;
        if ($totalJurusan > 0) $maxLevel = 2;
        if ($totalOrganisasi > 0) $maxLevel = 3;

        return view('user.superadmin.unit', compact(
            'rootUnits', 'allUnits', 'totalUnit', 'totalPusat', 'totalJurusan', 'totalOrganisasi', 'maxLevel'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit_name' => 'required|string|max:150',
            'description' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:units,id',
            'level' => 'required|in:Pusat,Jurusan,Organisasi',
        ]);

        Unit::create([
            'unit_name' => $request->unit_name,
            'description' => $request->description,
            'parent_id' => $request->parent_id,
            'level' => $request->level,
        ]);

        return redirect()->back()->with('success', 'Unit baru berhasil ditambahkan ke dalam hierarki!');
    }
}