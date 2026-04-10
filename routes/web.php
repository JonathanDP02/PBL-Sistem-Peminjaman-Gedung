<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\WorkflowController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Guest
Route::get('/', function () {
    return view('welcome');
});

// Dashboard
Route::get('/dashboard', function () {
    $view = match (Auth::user()->role->name) {
        'SuperAdmin', 'Admin_Unit' => 'admin.dashboard',
        'Approver' => 'approver.dashboard',
        default => 'user.dashboard',
    };

    return view($view);
})
    ->middleware('auth')
    ->name('dashboard');

Route::get('/riwayat', function () {
    $view = match (Auth::user()->role->name) {
        'User' => 'user.riwayat',
        'Approver' => 'approver.riwayat',
        default => 'user.riwayat',
    };

    return view($view);
})
    ->middleware('auth')
    ->name('riwayat');

// By Role
// Fungsi prefix('kata') di Laravel digunakan untuk menambahkan "kata" tersebut di bagian paling depan dari semua URL yang ada di dalam grup tersebut.
Route::middleware(['auth', 'checkRole:SuperAdmin,AdminUnit'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    Route::get('/fasilitas', function () {
        return view('admin.fasilitas');
    })->name('fasilitas');
    Route::get('/unit', function () {
        return view('admin.unit');
    })->name('unit');
    Route::get('/kelola-user', function () {
        return view('admin.kelola-user');
    })->name('kelola-user');

    Route::post('/user', [UserController::class, 'store'])->name('tambah-user.store');
});

Route::middleware(['auth', 'checkRole:Approver'])->prefix('approver')->group(function () {
    Route::get('/dashboard', function () {
        return view('approver.dashboard');
    })->name('approver.dashboard');
    Route::get('/meja-kerja', function () {
        return view('approver.meja-kerja');
    })->name('meja-kerja');
});

Route::middleware(['auth', 'checkRole:User'])->prefix('user')->group(function () {
    Route::get('/cari-ruangan', function () {
        return view('user.cari-ruangan');
    })->name('cari-ruangan');

    Route::get('/jadwal-saya', function () {
        return view('user.jadwal-saya');
    })->name('jadwal-saya');

});

// ini route untuk halaman profile, hanya bisa diakses kalau sudah login (middleware auth)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// API Endpoints
Route::middleware('auth')->group(function () {
    Route::get('/api/workflows/{id}/requirements', [WorkflowController::class, 'showRequirements']);
});

Route::get('/rooms/{id}', [RoomController::class, 'showApi']);
