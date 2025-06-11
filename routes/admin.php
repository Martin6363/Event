<?php
use App\Http\Controllers\api\Admin\EventController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/admin')->middleware(['auth:sanctum', 'role:admin|supervisor'])->group(function () {
    Route::apiResource('events', EventController::class)->names('admin.events');

    Route::patch('events/{event}/status', [EventController::class, 'approve'])
        ->middleware('permission:approve_event');
});