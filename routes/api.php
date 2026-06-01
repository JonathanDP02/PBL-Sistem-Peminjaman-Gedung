<?php

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
});