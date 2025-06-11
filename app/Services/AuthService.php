<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function register(array $data): array
    {
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        $user->assignRole('user');
        $token = $user->createToken('token')->plainTextToken;

        return [
            'token' => $token,
            'user' => $user,
        ];
    }

    public function login(array $credentials): array|bool
    {
        if (!auth()->attempt($credentials)) {
            return false;
        }

        $user = auth()->user();
        $token = $user->createToken('token')->plainTextToken;

        return [
            'token' => $token,
            'user' => $user,
        ];
    }

    public function logout($user): void
    {
        $user->tokens()->delete();
    }
}