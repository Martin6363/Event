<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\api\Auth\PasswordForgotAndResetController;
use App\Http\Controllers\api\UserController;

Route::prefix('v1')->group(function () {

    // Public routes
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);

        Route::post('forgot-password', [PasswordForgotAndResetController::class, 'forgotPassword']);
        Route::post('reset-password', [PasswordForgotAndResetController::class, 'resetPassword']);
    });

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('auth')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
        });
    });

    // User routes
    Route::middleware(['auth:sanctum'])->controller(UserController::class)->group(function () {
        Route::prefix('profile')->group(function () {
            Route::put('update', 'updateProfile');
            Route::patch('update/password', 'updatePassword');
            Route::put('update/settings', 'updateSettings');
        });
        Route::get('user', [UserController::class, 'profile']);
    });
});
