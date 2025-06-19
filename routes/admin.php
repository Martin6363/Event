<?php

use App\Http\Controllers\api\Admin\Auth\AuthController;
use App\Http\Controllers\api\Admin\EventController;
use Illuminate\Support\Facades\Route;

# Secure Routes
Route::prefix('v1/admin')->middleware(['auth:sanctum', 'role:admin|supervisor'])->name('admin.')->group(function () {
    Route::apiResource('events', EventController::class)->names('events');
    Route::patch('events/{event}/status', [EventController::class, 'approve'])
        ->middleware('permission:approve_event');
});

# Open Routes
Route::prefix('v1/admin')->name('admin.')->group(function () {
    # Auth Login Route
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});
