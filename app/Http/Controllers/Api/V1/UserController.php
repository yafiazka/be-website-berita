<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = User::with('roles')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->role($request->role);
        }

        $users = $query->paginate((int)$request->input('per_page', 15));

        return ApiResponse::paginated(UserResource::collection($users));
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $data = $request->validated();
        $roles = $data['roles'] ?? ['Penulis'];
        unset($data['roles']);

        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        $user->syncRoles($roles);

        return ApiResponse::success(new UserResource($user), 'Pengguna berhasil dibuat.', 201);
    }

    public function show(int $id): JsonResponse
    {
        $user = User::with('roles')->find($id);
        if (!$user) {
            return ApiResponse::error('Pengguna tidak ditemukan.', 404);
        }

        return ApiResponse::success(new UserResource($user), 'Detail pengguna.');
    }

    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        $user = User::find($id);
        if (!$user) {
            return ApiResponse::error('Pengguna tidak ditemukan.', 404);
        }

        $data = $request->validated();
        $roles = $data['roles'] ?? null;
        unset($data['roles']);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        if ($roles !== null) {
            $user->syncRoles($roles);
        }

        return ApiResponse::success(new UserResource($user->fresh('roles')), 'Pengguna berhasil diperbarui.');
    }

    public function destroy(int $id): JsonResponse
    {
        $user = User::find($id);
        if (!$user) {
            return ApiResponse::error('Pengguna tidak ditemukan.', 404);
        }

        if ($user->id === auth()->id()) {
            return ApiResponse::error('Anda tidak dapat menghapus akun Anda sendiri.', 400);
        }

        $user->delete();

        return ApiResponse::success(null, 'Pengguna berhasil dihapus.');
    }
}
