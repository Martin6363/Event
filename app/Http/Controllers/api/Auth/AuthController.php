<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\Request;
use App\Services\AuthService;

class AuthController extends Controller
{

    public function __construct(readonly AuthService $authService){}

    public function register(RegisterRequest $registerRequest)
    {
        $data = $this->authService->register($registerRequest->validated());

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully.',
            'token' => $data['token'],
            'user' => $data['user'],
        ]);
    }

    public function login(LoginRequest $loginRequest)
    {
        $data = $this->authService->login($loginRequest->validated());

        if (!$data) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'token' => $data['token'],
            'user' => $data['user'],
        ]);
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return response()->json([
            'success' => true,
            'message' => 'Logout successful.',
        ]);
    }
}