<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;



Route::get('/login', function () {
    return view('login'); 
})->name('login');//
    
// Route::get('/dashboard', function () {
//     return view('dashboard'); 
// })->middleware('auth');

// Contoh Route yang diamankan Middleware (Anggap middleware-mu bernama 'checkRole')
Route::middleware(['auth', 'checkRole:SuperAdmin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('/admin/dashboard');
    })->name('dashboard');
});

Route::middleware(['auth', 'checkRole:User'])->group(function () {
    Route::get('/user/peminjaman', function () {
        return view('/user/peminjaman');
    })->name('peminjaman');
});


Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);