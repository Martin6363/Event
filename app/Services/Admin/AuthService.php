<?php

namespace App\Services\Admin;

use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function login(array $credentials): array|bool|string
    {
        $admin = Admin::where('email', $credentials['email'])->first();

        if (!$admin || !Hash::check($credentials['password'], $admin->password)) {
            return false;
        }

        $token = $admin->createToken('admin-token')->plainTextToken;

        return [
            'token' => $token,
            'user' => $admin
        ];
    }

    public function logout(): bool
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            return false;
        }

        if ($admin->currentAccessToken()) {
            $admin->currentAccessToken()->delete();
            return true;
        }

        return false;
    }
}
