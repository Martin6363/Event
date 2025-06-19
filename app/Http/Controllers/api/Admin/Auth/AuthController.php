<?php

namespace App\Http\Controllers\api\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\LoginRequest;
use App\Services\Admin\AuthService;

class AuthController extends Controller
{
    public function __construct(readonly AuthService $authService){}

    public function login(LoginRequest $loginRequest)
    {
        return response()->json([
            'success' => true,
            'data' => $this->authService->login($loginRequest->validated()),
        ]);
    }

    public function logout()
    {
        $logoutSuccess = $this->authService->logout();

        if (!$logoutSuccess) {
            return response()->json([
                'success' => false,
                'message' => 'You are not logged in or already logged out.'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Logout successful.'
        ]);
    }
}
