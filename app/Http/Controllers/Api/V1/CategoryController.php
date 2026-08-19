<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use App\Http\Requests\Category\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::withCount(['articles' => fn($q) => $q->published()])
            ->with('children')
            ->whereNull('parent_id')
            ->get();

        return ApiResponse::success(CategoryResource::collection($categories), 'Daftar kategori berhasil diambil.');
    }

    public function show(string $slug): JsonResponse
    {
        $category = Category::where('slug', $slug)
            ->withCount(['articles' => fn($q) => $q->published()])
            ->with('children')
            ->first();

        if (!$category) {
            return ApiResponse::error('Kategori tidak ditemukan.', 404);
        }

        return ApiResponse::success(new CategoryResource($category), 'Detail kategori.');
    }

    // Dashboard endpoints
    public function dashboardIndex(): JsonResponse
    {
        $categories = Category::with('parent')->withCount('articles')->latest()->get();
        return ApiResponse::success(CategoryResource::collection($categories), 'Daftar kategori dashboard.');
    }

    public function store(CategoryRequest $request): JsonResponse
    {
        $data = $request->validated();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $category = Category::create($data);

        return ApiResponse::success(new CategoryResource($category), 'Kategori berhasil dibuat.', 201);
    }

    public function update(CategoryRequest $request, int $id): JsonResponse
    {
        $category = Category::find($id);
        if (!$category) {
            return ApiResponse::error('Kategori tidak ditemukan.', 404);
        }

        $data = $request->validated();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $category->update($data);

        return ApiResponse::success(new CategoryResource($category), 'Kategori berhasil diperbarui.');
    }

    public function destroy(int $id): JsonResponse
    {
        $category = Category::find($id);
        if (!$category) {
            return ApiResponse::error('Kategori tidak ditemukan.', 404);
        }

        $category->delete();

        return ApiResponse::success(null, 'Kategori berhasil dihapus.');
    }
}
