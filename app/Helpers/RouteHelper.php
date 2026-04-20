<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

if (!function_exists('dashboardRoute')) {
    function dashboardRoute()
    {
        $role = Auth::user()?->role->name ?? null;
        
        return match ($role) {
            'Admin_Unit' => route('laporan'),
            'Approver' => route('meja-kerja'),
            default => route('dashboard'),
        };
    }
}


if (!function_exists('getPageTitle')) {
    /**
     * Get page title based on current route name
     * Mengambil dari route yang sedang aktif
     */
    function getPageTitle()
    {
        $titles = [
            'admin.dashboard' => 'Dashboard',
            'user.dashboard' => 'Dashboard',
            'peminjam.booking' => 'Booking',
            'jadwal-saya' => 'Jadwal Saya',
            'riwayat' => 'Riwayat',
            'profile.edit' => 'Profil',
            'approve' => 'Persetujuan',
        ];
        
        $currentRoute = Route::currentRouteName();
        
        return $titles[$currentRoute] ?? 'Dashboard';
    }
}
