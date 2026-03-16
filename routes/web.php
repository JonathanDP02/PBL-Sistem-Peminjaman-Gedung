<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return view('login'); 
})->name('login');//

Route::get('/dashboard', function () {
    return view('dashboard'); 
})->middleware('auth');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);