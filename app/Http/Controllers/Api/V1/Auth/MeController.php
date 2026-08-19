<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MeController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        return ApiResponse::success(new UserResource($request->user()), 'User profile retrieved.');
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'avatar' => ['nullable', 'string'],
        ]);

        $user->update($request->only('name', 'avatar'));

        return ApiResponse::success(new UserResource($user), 'Profil berhasil diperbarui.');
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $user = $request->user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return ApiResponse::success(null, 'Kata sandi berhasil diubah.');
    }
}
