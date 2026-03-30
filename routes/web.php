<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;



Route::get('/login', function () {
    return view('login'); 
})->name('login');//

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

// Fungsi prefix('kata') di Laravel digunakan untuk menambahkan "kata" tersebut di bagian paling depan dari semua URL yang ada di dalam grup tersebut.
Route::middleware(['auth', 'checkRole:SuperAdmin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
});

Route::middleware(['auth', 'checkRole:User'])->prefix('user')->group(function () {
    Route::get('/peminjaman', function () {
        return view('user.peminjaman');
    })->name('peminjaman'); //nama dari route ini nanti dipakai di AuthController untuk redirect setelah login sesuai role
});
