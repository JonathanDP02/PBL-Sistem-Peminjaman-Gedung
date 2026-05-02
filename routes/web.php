<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WorkflowController;
use App\Http\Controllers\Admin\WorkflowRequirementController;
use App\Http\Controllers\Admin\WorkflowStepController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\BookingAttachmentController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingPdfController;
use App\Http\Controllers\BookingValidationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Models\Booking;
use App\Models\Building;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route;

// --- GUEST / PUBLIC ROUTES ---
Route::get('/', function () {
    $startOfWeek = Carbon::now()->startOfWeek();
    $endOfWeek = Carbon::now()->endOfWeek();

    $bookings = collect();
    if (Schema::hasTable('bookings')) {
        $bookings = Booking::with('room')
            ->whereBetween('booking_date', [$startOfWeek, $endOfWeek])
            ->whereIn('status', ['Approved', 'Pending'])
            ->orderBy('start_time')
            ->get();
    }

    $weekDates = collect();
    for ($i = 0; $i < 7; $i++) {
        $weekDates->push($startOfWeek->copy()->addDays($i));
    }

    return view('welcome', compact('bookings', 'weekDates'));
})->name('welcome');

Route::get('ruangan', function () {
    $buildings = collect();
    if (Schema::hasTable('buildings')) {
        $buildings = Building::with('rooms')->get();
    }

    return view('ruangan', compact('buildings'));
})->name('ruangan');


// --- AUTHENTICATED ROUTES ---
Route::middleware('auth')->group(function () {

    // General Dashboard (Role-based redirection)
    Route::get('/dashboard', function () {
        $user = Auth::user();

        if ($user->role->name === 'Approver') {
            return app(ApprovalController::class)->dashboard(request());
        }

        $view = match ($user->role->name) {
            'SuperAdmin' => 'user.superadmin.dashboard',
            'Admin_Unit' => 'user.admin_unit.dashboard',
            'User'       => 'user.peminjam.dashboard',
            default      => 'user.peminjam.dashboard',
        };

        return view($view);
    })->name('dashboard');

    // Riwayat (Shared between Approver & User)
    Route::get('/riwayat', function () {
        $view = match (Auth::user()->role->name) {
            'Approver' => 'user.approver.riwayat',
            'User'     => 'user.peminjam.riwayat',
            default    => 'user.peminjam.riwayat',
        };
        return view($view);
    })->name('riwayat');

    // Kelola User (Shared between SuperAdmin & Admin_Unit)
    Route::get('/kelola-user', function () {
        $view = match (Auth::user()->role->name) {
            'SuperAdmin', 'Admin_Unit' => 'user.admin.kelola-user',
            default                    => 'user.peminjam.kelola-user',
        };
        return view($view);
    })->name('kelola-user');


    // --- SUPER ADMIN SECTION ---
    Route::middleware('checkRole:SuperAdmin')->prefix('superadmin')->group(function () {
        Route::get('/fasilitas', fn() => view('user.superadmin.fasilitas'))->name('fasilitas');
        Route::get('/unit', fn() => view('user.superadmin.unit'))->name('unit');
        Route::post('/user', [UserController::class, 'store'])->name('tambah-user.store');
    });


    // --- SHARED ADMIN API (SuperAdmin & Admin_Unit) ---
    Route::middleware('checkRole:SuperAdmin,Admin_Unit')->prefix('admin/api')->group(function () {
        Route::apiResource('users', UserController::class);
        Route::get('/units', [UserController::class, 'getUnitsDropdown']);
        Route::get('/roles', [UserController::class, 'getRolesDropdown']);
        Route::get('/positions', [UserController::class, 'getPositionsDropdown']);
    });


    // --- ADMIN UNIT SECTION ---
    Route::middleware('checkRole:Admin_Unit')->prefix('admin_unit')->group(function () {
        
        // Views
        Route::get('/laporan', fn() => view('user.admin_unit.laporan'))->name('laporan');
        
        Route::get('/manajemen-ruangan', function () {
            $rooms = Room::where('unit_id', Auth::user()->unit_id)->with('building')->get();
            return view('user.admin_unit.manajemenRuangan', compact('rooms'));
        })->name('manajemenRuangan');

        Route::get('/pemblokiran-ruangan', function () {
            $user = Auth::user();
            $rooms = Room::where('unit_id', $user->unit_id)->with('building')->get();
            
            // Mengambil 5 jadwal maintenance mendatang (event_name logic)
            $activeBlockings = Booking::with('room')
                ->whereHas('room', fn($q) => $q->where('unit_id', $user->unit_id))
                ->where('event_name', 'LIKE', '[MAINTENANCE HARD-LOCK]%')
                ->whereDate('booking_date', '>=', now())
                ->orderBy('booking_date', 'asc')
                ->take(5)
                ->get();

            return view('user.admin_unit.pemblokiranRuangan', compact('rooms', 'activeBlockings'));
        })->name('pemblokiranRuangan');

        Route::get('/workflows-builder', fn() => view('user.admin_unit.workflowsBuilder'))->name('workflowsBuilder');
        Route::get('/workflows-index', fn() => view('user.admin_unit.workflowsIndex'))->name('workflowsIndex');

        // Form Standard (Redirect Back)
        Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
        Route::put('/rooms/{id}', [RoomController::class, 'update'])->name('rooms.update');
        Route::post('/pemblokiran-ruangan', [RoomController::class, 'blockRoom'])->name('pemblokiran.store');

        // Admin Unit API (Fetch/Async)
        Route::prefix('api')->group(function () {
            Route::get('/positions', [WorkflowController::class, 'getPositions']);
            
            // Rooms API
            Route::get('/rooms', [RoomController::class, 'index']);
            Route::delete('/rooms/{room}', [RoomController::class, 'destroy']);

            // Workflow API
            Route::get('/workflows', [WorkflowController::class, 'index']);
            Route::post('/workflows', [WorkflowController::class, 'store']);
            Route::get('/workflows/{id}', [WorkflowController::class, 'show'])->where('id', '[0-9]+');
            Route::put('/workflows/{id}', [WorkflowController::class, 'update'])->where('id', '[0-9]+');
            Route::delete('/workflows/{id}', [WorkflowController::class, 'destroy'])->where('id', '[0-9]+');
            Route::post('/workflows/{id}/sync-details', [WorkflowController::class, 'syncDetails'])->where('id', '[0-9]+');
            Route::get('/workflows/{id}/requirements', [WorkflowController::class, 'showRequirements'])->where('id', '[0-9]+');

            // Requirements & Steps
            Route::apiResource('workflow-requirements', WorkflowRequirementController::class)->except(['index', 'show']);
            Route::apiResource('workflow-steps', WorkflowStepController::class)->except(['index', 'show']);
        });
    });


    // --- APPROVER SECTION ---
    Route::middleware('checkRole:Approver')->prefix('approver')->group(function () {
        Route::get('/meja-kerja', [ApprovalController::class, 'mejaKerja'])->name('meja-kerja');
        Route::get('/approvals/{id}', [ApprovalController::class, 'show'])->name('approvals.show');
        Route::get('/approvals', [ApprovalController::class, 'index'])->name('approval.index');
        Route::post('/approvals/{booking_id}/approve', [ApprovalController::class, 'approve'])->name('approval.approve');
        Route::post('/approvals/{booking_id}/reject', [ApprovalController::class, 'reject'])->name('approval.reject');
    });


    // --- USER / PEMINJAM SECTION ---
    Route::middleware('checkRole:User')->prefix('user')->group(function () {
        Route::get('/booking', fn() => view('user.peminjam.booking'))->name('booking');
        Route::get('/jadwal-saya', fn() => view('user.peminjam.jadwal-saya'))->name('jadwal-saya');
        Route::get('/peminjaman', fn() => view('user.peminjam.peminjaman'))->name('peminjaman');
        Route::get('/detail', fn() => view('user.peminjam.detail'))->name('detail');

        Route::get('/bookings', [BookingController::class, 'index'])->name('booking.index');
        Route::get('/bookings/create', [BookingController::class, 'create'])->name('booking.create');
        Route::post('/bookings', [BookingController::class, 'store'])->name('booking.store');
        Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('booking.show');
        Route::patch('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');
        Route::get('/bookings/{id}/attachments/{attachmentId}', [BookingAttachmentController::class, 'show'])->name('booking.attachment.show');
        Route::get('/bookings/{id}/download-pdf', [BookingPdfController::class, 'generate'])->name('booking.pdf');
    });


    // --- PROFILE ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});


// --- GLOBAL UTILITIES ---
require __DIR__.'/auth.php';

Route::get('/validate/{bookingId}', [BookingValidationController::class, 'show'])->name('booking.validate');
Route::get('/rooms/{id}', [RoomController::class, 'showApi']);
Route::middleware('auth')->get('/preview-surat/{bookingId}', [BookingPdfController::class, 'preview'])->name('booking.pdf.preview');
Route::get('/api/bookings/{id}/timeline', [BookingController::class, 'timeline'])->name('api.booking.timeline');