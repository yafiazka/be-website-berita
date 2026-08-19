<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use App\Http\Requests\Tag\TagRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index(): JsonResponse
    {
        $tags = Tag::withCount(['articles' => fn($q) => $q->published()])->get();
        return ApiResponse::success(TagResource::collection($tags), 'Daftar tag publik.');
    }

    public function dashboardIndex(): JsonResponse
    {
        $tags = Tag::withCount('articles')->latest()->get();
        return ApiResponse::success(TagResource::collection($tags), 'Daftar tag dashboard.');
    }

    public function store(TagRequest $request): JsonResponse
    {
        $data = $request->validated();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $tag = Tag::create($data);

        return ApiResponse::success(new TagResource($tag), 'Tag berhasil dibuat.', 201);
    }

    public function update(TagRequest $request, int $id): JsonResponse
    {
        $tag = Tag::find($id);
        if (!$tag) {
            return ApiResponse::error('Tag tidak ditemukan.', 404);
        }

        $data = $request->validated();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $tag->update($data);

        return ApiResponse::success(new TagResource($tag), 'Tag berhasil diperbarui.');
    }

    public function destroy(int $id): JsonResponse
    {
        $tag = Tag::find($id);
        if (!$tag) {
            return ApiResponse::error('Tag tidak ditemukan.', 404);
        }

        $tag->delete();

        return ApiResponse::success(null, 'Tag berhasil dihapus.');
    }
}
