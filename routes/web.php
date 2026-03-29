<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;



Route::get('/login', function () {
    return view('login'); 
})->name('login');//
    
Route::get('/dashboard', function () {
    return view('dashboard'); 
})->middleware('auth');

// Contoh Route yang diamankan Middleware (Anggap middleware-mu bernama 'checkRole')
// Route::middleware(['auth', 'checkRole:SuperAdmin'])->group(function () {
//     Route::get('/superadmin/dashboard', function () {
//         return 'Selamat datang SuperAdmin! (Tampilan Figma nanti diurus Jo)';
//     })->name('dashboard');
// });

Route::middleware(['auth', 'checkRole:User'])->group(function () {
    Route::get('/peminjaman', function () {
        return 'Selamat datang Mahasiswa! Form peminjaman ada di sini nanti.';
    })->name('peminjaman');
});


Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);