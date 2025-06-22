<?php

use App\Http\Controllers\api\EventController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('events', [EventController::class, 'index'])->name('events.index');

    # Private Routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('events', EventController::class)->except(['index']);
    });
});

require __DIR__ . '/admin.php';
require __DIR__ . '/auth.php';


Route::fallback(function() {
    return response()->json([
        'error' => true,
        'message' => 'Not Found.'
    ], 404);
});

