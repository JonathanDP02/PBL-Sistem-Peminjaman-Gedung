<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

if (!function_exists('dashboardRoute')) {
    function dashboardRoute()
    {
        $role = Auth::user()?->role->name ?? null;
        
        return match ($role) {
            'SuperAdmin', 'Admin_Unit' => route('admin.dashboard'),
            default => route('user.dashboard'),
        };
    }
}

if (!function_exists('isDashboardRoute')) {
    function isDashboardRoute()
    {
        return request()->routeIs('admin.dashboard', 'user.dashboard');
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
            'cari-ruangan' => 'Cari Ruangan',
            'jadwal-saya' => 'Jadwal Saya',
            'riwayat' => 'Riwayat',
            'profile.edit' => 'Profil',
            'approve' => 'Persetujuan',
        ];
        
        $currentRoute = Route::currentRouteName();
        
        return $titles[$currentRoute] ?? 'Dashboard';
    }
}
