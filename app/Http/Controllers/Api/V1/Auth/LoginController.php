<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Helpers\ApiResponse;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $loginInput = $request->input('login') ?? $request->input('email') ?? $request->input('username');

        if (!$loginInput) {
            return ApiResponse::error('Email atau username wajib diisi.', 422);
        }

        $user = User::where('email', $loginInput)
            ->orWhere('username', $loginInput)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return ApiResponse::error('Email/username atau kata sandi tidak cocok.', 401);
        }

        if (!$user->is_active) {
            return ApiResponse::error('Akun Anda dinonaktifkan. Silakan hubungi admin.', 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return ApiResponse::success([
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'roles' => $user->getRoleNames(),
                'permissions' => $user->getAllPermissions()->pluck('name'),
            ],
        ], 'Login berhasil.');
    }
}
