<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WorkflowController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Guest
Route::get('/', function () {
    return view('welcome');
});

// General Dashboard
Route::get('/dashboard', function () {
    $view = match (Auth::user()->role->name) {
        'SuperAdmin' => 'user.superadmin.dashboard',
        'Approver' => 'user.approver.dashboard',
        'User' => 'user.peminjam.dashboard',
        default => 'user.superadmin.dashboard',
    };
    return view($view);
})->middleware('auth')->name('dashboard');

// General kelola user route
Route::get('/kelola-user', function () {
    $view = match (Auth::user()->role->name) {
        'SuperAdmin','Admin_Unit' => 'user.admin.kelola-user',
        default => 'user.peminjam.kelola-user',
    };
    return view($view);
})->middleware('auth')->name('kelola-user');

// ======
// SUPER ADMIN
Route::middleware(['auth', 'checkRole:SuperAdmin'])->prefix('admin')->group(function () {
    Route::get('/fasilitas', function () {
        return view('user.superadmin.fasilitas');
    })->name('fasilitas');

    Route::get('/unit', function () {
        return view('user.superadmin.unit');
    })->name('unit');

    Route::post('/user', [UserController::class, 'store'])->name('tambah-user.store');
});

// API Routes for User Management - Accessible to SuperAdmin and Admin_Unit
Route::middleware(['auth', 'checkRole:SuperAdmin,Admin_Unit'])->prefix('admin/api')->group(function () {
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

Route::middleware(['auth', 'checkRole:Admin_Unit'])->prefix('admin')->group(function () {
    Route::get('/laporan', function () {
        return view('user.admin_unit.laporan');
    })->name('laporan');

    Route::get('/manajemen-ruangan', function () {
        return view('user.admin_unit.manajemenRuangan');
    })->name('manajemenRuangan');

    Route::get('/pemblokiran-ruangan', function () {
        return view('user.admin_unit.pemblokiranRuangan');
    })->name('pemblokiranRuangan');

    Route::get('/workflow-builder', function () {
        return view('user.admin_unit.workflowBuilder');
    })->name('workflowBuilder');
});

// APPROVER
Route::middleware(['auth', 'checkRole:Approver'])->prefix('approver')->group(function () {
    Route::get('/meja-kerja', function () {
        return view('user.approver.meja-kerja');
    })->name('meja-kerja');

    Route::get('/riwayat', function () {
        return view('user.approver.riwayat');
    })->name('approver.riwayat');
});

// USER / PEMINJAM
Route::middleware(['auth', 'checkRole:User'])->prefix('user')->group(function () {
    Route::get('/cari-ruangan', function () {
        return view('user.peminjam.cari-ruangan');
    })->name('cari-ruangan');

    Route::get('/jadwal-saya', function () {
        return view('user.peminjam.jadwal-saya');
    })->name('jadwal-saya');

    Route::get('/peminjaman', function () {
        return view('user.peminjam.peminjaman');
    })->name('peminjaman');

    Route::get('/riwayat', function () {
        return view('user.peminjam.riwayat');
    })->name('peminjam.riwayat');
});

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/rooms/{id}', [RoomController::class, 'showApi']);
