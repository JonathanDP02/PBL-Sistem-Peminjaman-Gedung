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
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UnitController;
use App\Models\Booking;
use App\Models\BookingLog;
use App\Models\Building;
use App\Models\Position;
use App\Models\Role;
use App\Models\Room;
use App\Models\Workflow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

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

    $rooms = collect();
    if (Schema::hasTable('rooms')) {
        $rooms = Room::with('building')->latest()->take(3)->get();
    }

    return view('welcome', compact('bookings', 'weekDates', 'rooms'));
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

        $stats = ['approved' => 0, 'pending' => 0, 'rejected' => 0];
        $recentBookings = collect();
        $notifications = collect();

        if ($user->role->name === 'User') {
            // Statistik
            $stats['approved'] = Booking::where('user_id', $user->id)->where('status', 'Approved')->count();
            $stats['pending'] = Booking::where('user_id', $user->id)->where('status', 'Pending')->count();
            $stats['rejected'] = Booking::where('user_id', $user->id)->where('status', 'Rejected')->count();

            // Ambil 5 booking terbaru
            $recentBookings = Booking::with('room')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            // Ambil 5 notifikasi terbaru (dari log booking milik user)
            $notifications = BookingLog::whereHas('booking', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        }

        $view = match ($user->role->name) {
            'SuperAdmin' => 'user.superadmin.dashboard',
            'Admin_Unit' => 'user.admin_unit.dashboard',
            'User' => 'user.peminjam.dashboard',
            default => 'user.peminjam.dashboard',
        };

        return view($view, compact('stats', 'recentBookings', 'notifications'));
    })->name('dashboard');

    // Riwayat (Shared between Approver & User)
    Route::get('/riwayat', function () {
        $user = Auth::user();

        if ($user->role->name === 'Approver') {
            return app(ApprovalController::class)->history(request());
        }

        // Ambil data dari database untuk User (Peminjam)
        $bookings = Booking::with(['room.building'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Hitung statistik untuk sidebar
        $statusCounts = [
            'Approved' => $bookings->where('status', 'Approved')->count(),
            'Pending' => $bookings->where('status', 'Pending')->count(),
            'Rejected' => $bookings->whereIn('status', ['Rejected', 'Cancelled'])->count(),
        ];

        return view('user.peminjam.riwayat', compact('bookings', 'statusCounts'));
    })->name('riwayat');

    // Kelola User (Shared between SuperAdmin & Admin_Unit)
    Route::get('/kelola-user', function () {
        $view = match (Auth::user()->role->name) {
            'SuperAdmin', 'Admin_Unit' => 'user.admin.kelola-user',
            default => 'user.peminjam.kelola-user',
        };

        return view($view);
    })->name('kelola-user');

// --- INTERNAL API FOR AJAX (Uses Web Session) ---
    Route::prefix('admin/api')->group(function () {
        // Units
        Route::get('/units', [App\Http\Controllers\Admin\UnitController::class, 'index']);
        Route::post('/units', [App\Http\Controllers\Admin\UnitController::class, 'store']);
        Route::put('/units/{id}', [App\Http\Controllers\Admin\UnitController::class, 'update']);
        Route::delete('/units/{id}', [App\Http\Controllers\Admin\UnitController::class, 'destroy']);
        Route::get('/units/{id}/positions', [App\Http\Controllers\Admin\UnitController::class, 'positions']);

        // Users
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);

        // Rooms (create via API)
        Route::post('/rooms', [RoomController::class, 'store']);

        // Roles
        Route::get('/roles', function () {
            return response()->json([
                'success' => true,
                'data' => Role::all(),
            ]);
        });

        // Positions
        Route::get('/positions', function () {
            return response()->json([
                'success' => true,
                'data' => Position::all(),
            ]);
        });
    });

    // --- SHARED FILE ACCESS (All Auth Roles with Internal Auth Logic) ---
    Route::get('/user/bookings/{id}/attachments/{attachmentId}', [BookingAttachmentController::class, 'show'])->name('booking.attachment.show');
    Route::get('/user/bookings/{id}/download-pdf', [BookingPdfController::class, 'generate'])->name('booking.pdf');

    // --- SUPER ADMIN SECTION ---
    Route::middleware('checkRole:SuperAdmin')->prefix('superadmin')->group(function () {
        Route::get('/fasilitas', fn () => view('user.superadmin.fasilitas'))->name('fasilitas');
        Route::get('/unit', fn () => view('user.superadmin.unit'))->name('unit');
        Route::post('/user', [UserController::class, 'store'])->name('tambah-user.store');
    });

    // --- SHARED ADMIN API (SuperAdmin & Admin_Unit) ---
    Route::middleware('checkRole:SuperAdmin')->prefix('superadmin')->group(function () {
        Route::get('/fasilitas', [FacilityController::class, 'index'])->name('fasilitas');
        Route::post('/fasilitas', [FacilityController::class, 'store'])->name('fasilitas.store');

        // Rute Unit yang sudah diperbarui menggunakan Controller
        Route::get('/unit', [UnitController::class, 'index'])->name('unit');
        Route::post('/unit', [UnitController::class, 'store'])->name('unit.store');

        Route::post('/user', [UserController::class, 'store'])->name('tambah-user.store');

        // Form Standard untuk Superadmin mengelola Rooms
        Route::post('/rooms', [RoomController::class, 'store'])->name('superadmin.rooms.store');
        Route::put('/rooms/{id}', [RoomController::class, 'update'])->name('superadmin.rooms.update');
        Route::delete('/api/rooms/{room}', [RoomController::class, 'destroy'])->name('superadmin.rooms.destroy');

        // Form Standard untuk Superadmin mengelola Buildings
        Route::post('/buildings', [BuildingController::class, 'store'])->name('superadmin.buildings.store');
        Route::put('/buildings/{building}', [BuildingController::class, 'update'])->name('superadmin.buildings.update');
        Route::delete('/api/buildings/{building}', [BuildingController::class, 'destroy'])->name('superadmin.buildings.destroy');
    });

    // --- ADMIN UNIT SECTION ---
    Route::middleware('checkRole:Admin_Unit')->prefix('admin_unit')->group(function () {

        // Views
        Route::get('/laporan', fn () => view('user.admin_unit.laporan'))->name('laporan');
        Route::get('/bookings/bulk-pdf', [BookingPdfController::class, 'bulkDownload'])->name('booking.pdf.bulk');
        Route::get('/manajemen-ruangan', function () {
            $rooms = Room::where('unit_id', Auth::user()->unit_id)->with(['building', 'facilities', 'workflow'])->get();
            $workflows = Workflow::where('unit_id', Auth::user()->unit_id)->get();

            return view('user.admin_unit.manajemenRuangan', compact('rooms', 'workflows'));
        })->name('manajemenRuangan');

        Route::get('/pemblokiran-ruangan', function () {
            $user = Auth::user();
            $rooms = Room::where('unit_id', $user->unit_id)->with('building')->get();

            // Mengambil jadwal maintenance mendatang dan mengelompokkannya
            $rawBlockings = Booking::with('room')
                ->whereHas('room', fn ($q) => $q->where('unit_id', $user->unit_id))
                ->where('event_name', 'LIKE', '[MAINTENANCE HARD-LOCK]%')
                ->whereDate('booking_date', '>=', now())
                ->orderBy('booking_date', 'asc')
                ->get();

            $activeBlockings = $rawBlockings->groupBy('event_name')->map(function ($group) {
                $first = $group->first();

                return (object) [
                    'id' => $first->id,
                    'event_name' => $first->event_name,
                    'room' => $first->room,
                    'event_description' => $first->event_description,
                    'start_date' => $group->min('booking_date'),
                    'end_date' => $group->max('booking_date'),
                    'is_range' => $group->count() > 1,
                    'count' => $group->count(),
                ];
            })->take(5);

            return view('user.admin_unit.pemblokiranRuangan', compact('rooms', 'activeBlockings'));
        })->name('pemblokiranRuangan');

        Route::get('/workflows-builder', fn () => view('user.admin_unit.workflowsBuilder'))->name('workflowsBuilder');
        Route::get('/workflows-index', fn () => view('user.admin_unit.workflowsIndex'))->name('workflowsIndex');

        // Form Standard (Redirect Back)
        Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
        Route::put('/rooms/{id}', [RoomController::class, 'update'])->name('rooms.update');
        Route::post('/pemblokiran-ruangan', [RoomController::class, 'blockRoom'])->name('pemblokiran.store');
        Route::delete('/pemblokiran-ruangan', [RoomController::class, 'unblockRoom'])->name('pemblokiran.destroy');

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
        Route::get('/booking', [BookingController::class, 'showBookingForm'])->name('booking');
        Route::get('/jadwal-saya', [BookingController::class, 'showJadwalSaya'])->name('jadwal-saya');
        Route::get('/peminjaman', fn () => view('user.peminjam.peminjaman'))->name('peminjaman');
        Route::get('/detail/{id}', [BookingController::class, 'detail'])->name('detail');

        Route::get('/bookings', [BookingController::class, 'index'])->name('booking.index');
        Route::get('/bookings/create', [BookingController::class, 'create'])->name('booking.create');
        Route::post('/bookings', [BookingController::class, 'store'])->name('booking.store');
        Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('booking.show');
        Route::patch('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');
        Route::post('/bookings/{id}/revise', [BookingController::class, 'revise'])->name('booking.revise');
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
