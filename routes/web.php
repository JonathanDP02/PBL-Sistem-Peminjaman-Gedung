<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WorkflowController;
use App\Http\Controllers\Admin\WorkflowRequirementController;
use App\Http\Controllers\Admin\WorkflowStepController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\BookingAttachmentController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Models\Booking;
use App\Models\Building;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Guest
Route::get('/', function () {
    $startOfWeek = Carbon::now()->startOfWeek();
    $endOfWeek = Carbon::now()->endOfWeek();

    $bookings = Booking::with('room')
        ->whereBetween('booking_date', [$startOfWeek, $endOfWeek])
        ->whereIn('status', ['Approved', 'Pending'])
        ->orderBy('start_time')
        ->get();

    $weekDates = collect();
    for ($i = 0; $i < 7; $i++) {
        $weekDates->push($startOfWeek->copy()->addDays($i));
    }

    return view('welcome', compact('bookings', 'weekDates'));
})->name('welcome');

Route::get('ruangan', function () {
    $buildings = Building::with('rooms')->get();

    return view('ruangan', compact('buildings'));
})->name('ruangan');

// General Dashboard
Route::get('/dashboard', function () {
    $view = match (Auth::user()->role->name) {
        'SuperAdmin' => 'user.superadmin.dashboard',
        'Admin_Unit' => 'user.admin_unit.dashboard',
        'Approver' => 'user.approver.dashboard',
        'User' => 'user.peminjam.dashboard',
    };

    return view($view);
})->middleware('auth')->name('dashboard');

// riwayat user route
Route::get('/riwayat', function () {
    $view = match (Auth::user()->role->name) {
        'Approver' => 'user.approver.riwayat',
        'User' => 'user.peminjam.riwayat',
        default => 'user.peminjam.riwayat',
    };

    return view($view);
})->middleware('auth')->name('riwayat');

//
Route::get('/kelola-user', function () {
    $view = match (Auth::user()->role->name) {
        'SuperAdmin','Admin_Unit' => 'user.admin.kelola-user',
        default => 'user.peminjam.kelola-user',
    };

    return view($view);
})->middleware('auth')->name('kelola-user');

// ======
// SUPER ADMIN
Route::middleware(['auth', 'checkRole:SuperAdmin'])->prefix('superadmin')->group(function () {
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
});

Route::middleware(['auth', 'checkRole:Admin_Unit'])->prefix('admin_unit')->group(function () {
    Route::get('/laporan', function () {
        return view('user.admin_unit.laporan');
    })->name('laporan');

    Route::get('/manajemen-ruangan', function () {
        return view('user.admin_unit.manajemenRuangan');
    })->name('manajemenRuangan');

    Route::get('/pemblokiran-ruangan', function () {
        return view('user.admin_unit.pemblokiranRuangan');
    })->name('pemblokiranRuangan');

    Route::get('/workflows-builder', function () {
        return view('user.admin_unit.workflowsBuilder');
    })->name('workflowsBuilder');

    Route::get('/workflows-index', function () {
        return view('user.admin_unit.workflowsIndex');
    })->name('workflowsIndex');

    Route::prefix('api')->group(function () {
        // Master Data
        Route::get('/positions', [WorkflowController::class, 'getPositions']);
        
        // Workflow Core
        Route::get('/workflows', [WorkflowController::class, 'index']);
        Route::post('/workflows', [WorkflowController::class, 'store']);
        
        // Workflow Parameterized (Dilindungi Regex Mutlak)
        Route::get('/workflows/{id}', [WorkflowController::class, 'show'])->where('id', '[0-9]+');
        Route::put('/workflows/{id}', [WorkflowController::class, 'update'])->where('id', '[0-9]+');
        Route::delete('/workflows/{id}', [WorkflowController::class, 'destroy'])->where('id', '[0-9]+');
        Route::post('/workflows/{id}/sync-details', [WorkflowController::class, 'syncDetails'])->where('id', '[0-9]+');
        Route::get('/workflows/{id}/requirements', [WorkflowController::class, 'showRequirements'])->where('id', '[0-9]+');

        // Jika Ota/Febri masih butuh Controller Terpisah untuk API spesifik, pastikan ada Regex:
        Route::post('/workflow-requirements', [WorkflowRequirementController::class, 'store']);
        Route::put('/workflow-requirements/{id}', [WorkflowRequirementController::class, 'update'])->where('id', '[0-9]+');
        Route::delete('/workflow-requirements/{id}', [WorkflowRequirementController::class, 'destroy'])->where('id', '[0-9]+');

        Route::post('/workflow-steps', [WorkflowStepController::class, 'store']);
        Route::put('/workflow-steps/{id}', [WorkflowStepController::class, 'update'])->where('id', '[0-9]+');
        Route::delete('/workflow-steps/{id}', [WorkflowStepController::class, 'destroy'])->where('id', '[0-9]+');
    });
});

// APPROVER
Route::middleware(['auth', 'checkRole:Approver'])->prefix('approver')->group(function () {
    Route::get('/meja-kerja', function () {
        return view('user.approver.meja-kerja');
    })->name('meja-kerja');

    Route::get('/approvals', [ApprovalController::class, 'index'])->name('approval.index');
    Route::post('/approvals/{id}/approve', [ApprovalController::class, 'approve'])->name('approval.approve');
    Route::post('/approvals/{id}/reject', [ApprovalController::class, 'reject'])->name('approval.reject');
});

// USER / PEMINJAM
Route::middleware(['auth', 'checkRole:User'])->prefix('user')->group(function () {
    Route::get('/booking', function () {
        return view('user.peminjam.booking');
    })->name('booking');

    Route::get('/jadwal-saya', function () {
        return view('user.peminjam.jadwal-saya');
    })->name('jadwal-saya');

    Route::get('/peminjaman', function () {
        return view('user.peminjam.peminjaman');
    })->name('peminjaman');

    Route::get('/detail', function () {
        return view('user.peminjam.detail');
    })->name('detail');

    Route::get('/bookings', [BookingController::class, 'index'])->name('booking.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('booking.show');
    Route::patch('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');
    Route::get('/bookings/{id}/attachments/{attachmentId}', [BookingAttachmentController::class, 'show'])->name('booking.attachment.show');
});

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/rooms/{id}', [RoomController::class, 'showApi']);
