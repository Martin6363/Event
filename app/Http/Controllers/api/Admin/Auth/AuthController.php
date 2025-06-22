<?php

namespace App\Http\Controllers\api\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\LoginRequest;
use App\Services\Admin\AuthService;

class AuthController extends Controller
{
    public function __construct(readonly AuthService $authService){}

    /**
     * @OA\Post(
     *     path="/api/v1/admin/login",
     *     tags={"Auth Admin"},
     *     summary="Admin Login",
     *     description="Login with email and password to get a Bearer token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="admin@example.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="Bearer eyJ0eXAiOiJK..."),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function login(LoginRequest $loginRequest)
    {
        return response()->json([
            'success' => true,
            'data' => $this->authService->login($loginRequest->validated()),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/admin/logout",
     *     tags={"Auth Admin"},
     *     summary="Admin Logout",
     *     description="Logs out the authenticated admin",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Logout successful.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Not logged in or already logged out",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="You are not logged in or already logged out.")
     *         )
     *     )
     * )
     */
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
