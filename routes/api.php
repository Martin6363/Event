<?php

use App\Http\Controllers\api\EventController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::apiResource('events', EventController::class);
});

require __DIR__ . '/admin.php';
require __DIR__ . '/auth.php';


Route::fallback(function() {
    return response()->json([
        'error' => true,
    ], 404);
});
