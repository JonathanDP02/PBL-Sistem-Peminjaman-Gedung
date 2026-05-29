<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

if (! function_exists('dashboardRoute')) {
    function dashboardRoute()
    {
        $role = Auth::user()?->role->name ?? null;

        return match ($role) {
            'Administrator Unit' => route('laporan'),
            'Penyetuju' => route('meja-kerja'),
            default => route('dashboard'),
        };
    }
}

if (! function_exists('getPageTitle')) {
    /**
     * Get page title based on current route name
     * Mengambil dari route yang sedang aktif
     */
    function getPageTitle(): string
    {
        $titles = [
            'dashboard' => 'Dasbor',
            'ruangan' => 'Daftar Ruangan',
            'booking' => 'Pesan Ruangan',
            'jadwal-saya' => 'Jadwal Saya',
            'riwayat' => (Auth::user()?->role?->name ?? '') === 'Peminjam' ? 'Riwayat Peminjaman' : 'Riwayat Persetujuan',
            'peminjaman' => 'Permohonan Peminjaman',
            'meja-kerja' => 'Meja Kerja',
            'manajemenRuangan' => 'Manajemen Ruangan',
            'pemblokiranRuangan' => 'Pemblokiran Ruangan',
            'workflowsBuilder' => 'Kelola Alur Kerja',
            'workflowsIndex' => 'Kelola Alur Kerja',
            'laporan' => 'Laporan Peminjaman',
            'fasilitas' => 'Kelola Fasilitas',
            'unit' => 'Kelola Unit',
            'kelola-user' => 'Kelola Pengguna',
            'profile.edit' => 'Profil Saya',
            'approvals.show' => 'Detail Persetujuan',
            'booking.show' => 'Detail Peminjaman',
            'detail' => 'Detail Peminjaman',
            'booking.validate' => 'Validasi Dokumen',
        ];

        $currentRoute = Route::currentRouteName();

        return $titles[$currentRoute] ?? 'Dashboard';
    }
}
