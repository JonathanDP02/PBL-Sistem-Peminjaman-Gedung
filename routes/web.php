<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;


Route::get('/', function () {
    return view('welcome');
});

//siapapun yang masuk , aslinya ada di dasboard saja ini
Route::middleware(['auth'])->group(function () {
    Route::get('/cari-ruangan', function () {
        return view('cari-ruangan');
    })->name('cari-ruangan');
    
    Route::get('/jadwal-saya', function () {
        return view('user.jadwal-saya');
    })->name('jadwal-saya');
    
    Route::get('/riwayat', function () {
        return view('riwayat');
    })->name('riwayat');
});

// Fungsi prefix('kata') di Laravel digunakan untuk menambahkan "kata" tersebut di bagian paling depan dari semua URL yang ada di dalam grup tersebut.
Route::middleware(['auth', 'checkRole:SuperAdmin,AdminUnit'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
});

Route::middleware(['auth', 'checkRole:Approver'])->prefix('approver')->group(function () {
    Route::get('/approve', function () {
        return view('approver.approve');
    })->name('approve');
});

Route::middleware(['auth', 'checkRole:User'])->prefix('user')->group(function () {
    Route::get('/peminjaman', function () {
        return view('user.peminjaman');
    })->name('peminjaman'); //nama dari route ini nanti dipakai di AuthController untuk redirect setelah login sesuai role
});

// ini route untuk halaman profile, hanya bisa diakses kalau sudah login (middleware auth)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
