<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WorkflowController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
// use App\Models\Position;
// use App\Models\Role;
// use App\Models\Unit;
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
//SUPER ADMIN
Route::middleware(['auth', 'checkRole:SuperAdmin'])->prefix('admin')->group(function () {
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

    // API Routes for User Management
    Route::prefix('api')->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);

        Route::get('/units', [UserController::class, 'getUnitsDropdown']);
        Route::get('/roles', [UserController::class, 'getRolesDropdown']);
        Route::get('/positions', [UserController::class, 'getPositionsDropdown']);

        // Workflow API Routes
        Route::get('/workflows/{id}/requirements', [WorkflowController::class, 'showRequirements']);
    });
});

//AdminUnit
Route::middleware(['checkRole:Admin_Unit'])->prefix('admin')->group(function () {
    Route::get('/laporan', function () {
        return view('admin.laporan'); 
    })->name('laporan');

    Route::get('/manajemen-ruangan', function () {
        return view('admin.manajemenRuangan');
    })->name('manajemenRuangan');

    Route::get('/pemblokiran-ruangan', function () {
        return view('admin.pemblokiranRuangan');
    })->name('pemblokiranRuangan');

    Route::get('/workflow-builder', function () {
        return view('admin.workflowBuilder'); 
    })->name('workflowBuilder');

});

//Approver
Route::middleware(['auth', 'checkRole:Approver'])->prefix('approver')->group(function () {
    Route::get('/dashboard', function () {
        return view('approver.dashboard');
    })->name('approver.dashboard');
    Route::get('/meja-kerja', function () {
        return view('approver.meja-kerja');
    })->name('meja-kerja');
});

//User biasa atau Peminjam
Route::middleware(['auth', 'checkRole:User'])->prefix('user')->group(function () {
    Route::get('/cari-ruangan', function () {
        return view('user.cari-ruangan');
    })->name('cari-ruangan');
    Route::get('/jadwal-saya', function () {
        return view('user.jadwal-saya');
    })->name('jadwal-saya');
    Route::get('/peminjaman', function () {
        return view('user.peminjaman');
    })->name('peminjaman');

});

// ini route untuk halaman profile, hanya bisa diakses kalau sudah login (middleware auth)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/rooms/{id}', [RoomController::class, 'showApi']);
