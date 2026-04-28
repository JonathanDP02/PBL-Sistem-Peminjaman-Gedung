<?php

use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\UserController;
use App\Models\Position;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->get('/user', function (Request $request) {
    return $request->user();
});

use App\Http\Controllers\Api\ApprovalController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\WorkflowController;

Route::middleware('auth')->group(function () {

    // Weekly Tasks (Jo UI requirements)
    Route::get('/workflows/{id}/requirements', [WorkflowController::class, 'requirements']);
    Route::get('/rooms/available', [RoomController::class, 'available']);
    Route::get('/bookings/{id}/timeline', [BookingController::class, 'timeline']);

    // Approvals (Dasbor Pejabat - Approver Dashboard)
    Route::get('/approvals/pending', [ApprovalController::class, 'pending']);
    Route::get('/approvals/{booking_id}', [ApprovalController::class, 'show'])->where('booking_id', '[0-9]+');
    Route::post('/approvals/{booking_id}/approve', [ApprovalController::class, 'approve'])->where('booking_id', '[0-9]+');
    Route::post('/approvals/{booking_id}/reject', [ApprovalController::class, 'reject'])->where('booking_id', '[0-9]+');

    // Units
    Route::get('/units', [UnitController::class, 'index']);
    Route::post('/units', [UnitController::class, 'store']);
    Route::put('/units/{id}', [UnitController::class, 'update']);
    Route::delete('/units/{id}', [UnitController::class, 'destroy']);
    Route::get('/units/{id}/positions', [UnitController::class, 'positions']);

    // Users
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

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
