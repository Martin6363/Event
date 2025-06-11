<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdatePasswordRequest;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Requests\User\UpdateSettingsRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function __construct(protected UserService $userService) {}

    public function profile(Request $request): mixed
    {
        if (!$request->user()) {
            return response()->noContent();
        }
        return response()->json([
            'success' => true,
            'user' => new UserResource(auth()->user()->load('settings')),
        ]);
    }

    public function updateProfile(UpdateProfileRequest $updateProfileRequest)
    {
        $user = $this->userService->updateProfile($updateProfileRequest->user(), $updateProfileRequest->validated());

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'user' => $user,
        ]);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $this->userService->updatePassword($request->user(), $request->validated()['password']);

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully.',
        ]);
    }

    public function updateSettings(UpdateSettingsRequest $request)
    {
        $this->userService->updateSettings($request->user(), $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Settings updated successfully.',
        ]);
    }
}
