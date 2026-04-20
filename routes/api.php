<?php

use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\UserController;
use App\Models\Role;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->get('/user', function (Request $request) {
    return $request->user();
});

use App\Http\Controllers\Api\WorkflowController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\BookingController;

Route::middleware('auth')->group(function () {

    // Weekly Tasks (Jo UI requirements)
    Route::get('/workflows/{id}/requirements', [WorkflowController::class, 'requirements']);
    Route::get('/rooms/available', [RoomController::class, 'available']);
    Route::get('/bookings/{id}/timeline', [BookingController::class, 'timeline']);

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
