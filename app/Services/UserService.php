<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function updateProfile(User $user, array $data): User
    {
        $user->update($data);
        return $user;
    }

    public function updatePassword(User $user, string $newPassword): void
    {
        $user->update([
            'password' => Hash::make($newPassword),
        ]);
    }

    public function updateSettings(User $user, array $settings): void
    {
        $user->settings()->updateOrCreate(
            ['user_id' => $user->id],
            $settings
        );
    }
}
