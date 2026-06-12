<?php

use App\Http\Controllers\Admin\AdminApiController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WorkflowController;
use App\Http\Controllers\Admin\WorkflowRequirementController;
use App\Http\Controllers\Admin\WorkflowStepController;
use App\Http\Controllers\AdminUnitController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\BookingAttachmentController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingPdfController;
use App\Http\Controllers\BookingValidationController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UnitController;
use App\Models\Role;
use App\Models\Unit;
use App\Models\Workflow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// --- GUEST / PUBLIC ROUTES ---
Route::get('/', [GuestController::class, 'welcome'])->name('welcome');
Route::get('ruangan', [GuestController::class, 'ruangan'])->name('ruangan');

// --- AUTHENTICATED ROUTES ---
Route::middleware('auth')->group(function () {

    // General Dashboard (Role-based redirection)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Riwayat (Shared between all authenticated roles)
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat');

    // Laporan Peminjaman (Shared between SuperAdmin & Admin_Unit)
    Route::middleware('checkRole:Administrator Utama,Administrator Unit')->group(function () {
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    });

    // Kelola User (Shared between SuperAdmin & Admin_Unit)
    Route::get('/kelola-user', [UserController::class, 'kelolaView'])->name('kelola-user');

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
        Route::get('/roles', [AdminApiController::class, 'roles']);

        // Positions
        Route::get('/positions', [AdminApiController::class, 'positions']);
    });

    // --- SHARED FILE ACCESS (All Auth Roles with Internal Auth Logic) ---
    Route::get('/peminjam/bookings/{id}/attachments/{attachmentId}', [BookingAttachmentController::class, 'show'])->name('booking.attachment.show');
    Route::get('/peminjam/bookings/{id}/download-pdf', [BookingPdfController::class, 'generate'])->name('booking.pdf');
    Route::get('/peminjam/detail/{id}', [BookingController::class, 'detail'])->name('detail');

    // --- SHARED ADMIN API (SuperAdmin & Admin_Unit) ---
    Route::middleware('checkRole:Administrator Utama')->prefix('superadmin')->group(function () {
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
    Route::middleware('checkRole:Administrator Unit')->prefix('admin_unit')->group(function () {

        // Views
        Route::get('/bookings/bulk-pdf', [BookingPdfController::class, 'bulkDownload'])->name('booking.pdf.bulk');
        Route::get('/manajemen-ruangan', [AdminUnitController::class, 'manajemenRuangan'])->name('manajemenRuangan');
        Route::get('/pemblokiran-ruangan', [AdminUnitController::class, 'pemblokiranRuangan'])->name('pemblokiranRuangan');

        Route::view('/workflows-builder', 'user.admin_unit.workflowsBuilder')->name('workflowsBuilder');
        Route::view('/workflows-index', 'user.admin_unit.workflowsIndex')->name('workflowsIndex');

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
            Route::patch('/rooms/{id}/assign-workflow', [RoomController::class, 'assignWorkflow']);

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
    Route::middleware('checkRole:Penyetuju')->prefix('approver')->group(function () {

        Route::get('/meja-kerja', [ApprovalController::class, 'mejaKerja'])->name('meja-kerja');
        Route::get('/bookings/{id}/preview-disposisi', [ApprovalController::class, 'previewDisposisi'])->name('approvals.preview-disposisi');
        Route::get('/approvals/{id}', [ApprovalController::class, 'show'])->name('approvals.show');
        Route::get('/approvals', [ApprovalController::class, 'index'])->name('approval.index');
        Route::post('/approvals/{booking_id}/approve', [ApprovalController::class, 'approve'])->name('approval.approve');
        Route::post('/approvals/{booking_id}/reject', [ApprovalController::class, 'reject'])->name('approval.reject');
    });

    // --- PEMINJAM SECTION ---
    Route::middleware('checkRole:Peminjam')->prefix('peminjam')->group(function () {
        Route::get('/booking', [BookingController::class, 'showBookingForm'])->name('booking');
        Route::get('/jadwal-saya', [BookingController::class, 'showJadwalSaya'])->name('jadwal-saya');
        Route::view('/peminjaman', 'user.peminjam.peminjaman')->name('peminjaman');

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

Route::get('/scan', [BookingValidationController::class, 'scanner'])->name('booking.scan');
Route::get('/validate/{bookingId}', [BookingValidationController::class, 'show'])->name('booking.validate');
Route::get('/rooms/{id}', [RoomController::class, 'showApi']);
Route::middleware('auth')->get('/preview-surat/{bookingId}', [BookingPdfController::class, 'preview'])->name('booking.pdf.preview');
Route::get('/api/bookings/{id}/timeline', [BookingController::class, 'timeline'])->name('api.booking.timeline');
