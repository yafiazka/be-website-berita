<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use App\Http\Requests\Article\StoreArticleRequest;
use App\Http\Requests\Article\UpdateArticleRequest;
use App\Http\Resources\ArticleListResource;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ArticleController extends Controller
{
    // ==========================================
    // PUBLIC ENDPOINTS
    // ==========================================

    public function index(Request $request): JsonResponse
    {
        $query = Article::published()
            ->with(['category', 'author', 'tags'])
            ->latest('published_at');

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('tag')) {
            $query->whereHas('tags', fn($q) => $q->where('slug', $request->tag));
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $perPage = (int) $request->input('per_page', 12);
        $articles = $query->paginate($perPage);

        return ApiResponse::paginated(ArticleListResource::collection($articles));
    }

    public function show(string $slug, Request $request): JsonResponse
    {
        $article = Article::where('slug', $slug)
            ->with([
                'category',
                'author',
                'tags',
                'approvedComments.user',
                'approvedComments.replies.user',
            ])
            ->first();

        if (!$article) {
            return ApiResponse::error('Artikel tidak ditemukan.', 404);
        }

        // Increment view count with debounce per IP
        $ip = $request->ip();
        $cacheKey = "viewed_article_{$article->id}_{$ip}";
        if (!Cache::has($cacheKey)) {
            $article->increment('views_count');
            Cache::put($cacheKey, true, now()->addMinutes(30));
        }

        return ApiResponse::success(new ArticleResource($article), 'Detail artikel berhasil diambil.');
    }

    public function trending(): JsonResponse
    {
        $articles = Article::trending()
            ->with(['category', 'author', 'tags'])
            ->take(6)
            ->get();

        return ApiResponse::success(ArticleListResource::collection($articles), 'Trending articles retrieved.');
    }

    public function breaking(): JsonResponse
    {
        $articles = Article::breaking()
            ->with(['category', 'author', 'tags'])
            ->take(5)
            ->get();

        return ApiResponse::success(ArticleListResource::collection($articles), 'Breaking news articles retrieved.');
    }

    public function byCategory(string $slug, Request $request): JsonResponse
    {
        $category = Category::where('slug', $slug)->first();
        if (!$category) {
            return ApiResponse::error('Kategori tidak ditemukan.', 404);
        }

        $articles = Article::published()
            ->where('category_id', $category->id)
            ->with(['category', 'author', 'tags'])
            ->latest('published_at')
            ->paginate((int)$request->input('per_page', 12));

        return ApiResponse::paginated(ArticleListResource::collection($articles));
    }

    public function byTag(string $slug, Request $request): JsonResponse
    {
        $tag = Tag::where('slug', $slug)->first();
        if (!$tag) {
            return ApiResponse::error('Tag tidak ditemukan.', 404);
        }

        $articles = $tag->articles()
            ->published()
            ->with(['category', 'author', 'tags'])
            ->latest('published_at')
            ->paginate((int)$request->input('per_page', 12));

        return ApiResponse::paginated(ArticleListResource::collection($articles));
    }

    public function related(string $slug): JsonResponse
    {
        $article = Article::where('slug', $slug)->first();
        if (!$article) {
            return ApiResponse::error('Artikel tidak ditemukan.', 404);
        }

        $related = Article::published()
            ->where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->with(['category', 'author', 'tags'])
            ->latest('published_at')
            ->take(4)
            ->get();

        return ApiResponse::success(ArticleListResource::collection($related), 'Related articles retrieved.');
    }

    // ==========================================
    // DASHBOARD ENDPOINTS
    // ==========================================

    public function dashboardIndex(Request $request): JsonResponse
    {
        $user = $request->user();
        $query = Article::with(['category', 'author', 'tags'])->latest();

        // Penulis can only view own articles unless they have 'view articles' for all
        if ($user->hasRole('Penulis') && !$user->hasAnyRole(['Admin', 'Editor'])) {
            $query->where('author_id', $user->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }

        $articles = $query->paginate((int)$request->input('per_page', 15));

        return ApiResponse::paginated(ArticleListResource::collection($articles));
    }

    public function dashboardShow(int $id): JsonResponse
    {
        $article = Article::with(['category', 'author', 'tags'])->find($id);
        if (!$article) {
            return ApiResponse::error('Artikel tidak ditemukan.', 404);
        }

        return ApiResponse::success(new ArticleResource($article), 'Detail artikel untuk dashboard.');
    }

    public function store(StoreArticleRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['author_id'] = $request->user()->id;
        $data['status'] = $data['status'] ?? 'draft';

        if (empty($data['author_source'])) {
            $data['author_source'] = $request->user()->name;
        }

        if (empty($data['excerpt']) && !empty($data['content'])) {
            $data['excerpt'] = \Illuminate\Support\Str::limit(strip_tags($data['content']), 160);
        }

        // Handle uploaded image file, base64, or URL string with auto-compression
        $rawThumbnail = $request->file('thumbnail_file')
            ?? $request->file('thumbnail')
            ?? $request->file('image')
            ?? $data['thumbnail']
            ?? $data['thumbnail_url']
            ?? null;

        $data['thumbnail'] = \App\Services\ImageService::processAndStore($rawThumbnail, 'articles');

        unset($data['thumbnail_file'], $data['image'], $data['thumbnail_url']);

        $tags = $data['tags'] ?? [];
        unset($data['tags']);

        $article = Article::create($data);

        if (!empty($tags)) {
            $article->tags()->sync($tags);
        }

        return ApiResponse::success(new ArticleResource($article->load(['category', 'author', 'tags'])), 'Artikel berhasil dibuat.', 201);
    }

    public function update(UpdateArticleRequest $request, int $id): JsonResponse
    {
        $article = Article::find($id);
        if (!$article) {
            return ApiResponse::error('Artikel tidak ditemukan.', 404);
        }

        $user = $request->user();
        if ($user->hasRole('Penulis') && !$user->hasAnyRole(['Admin', 'Editor']) && $article->author_id !== $user->id) {
            return ApiResponse::error('Anda tidak memiliki izin mengedit artikel ini.', 403);
        }

        $data = $request->validated();

        // Handle uploaded image file, base64, or URL string with auto-compression
        if ($request->hasFile('thumbnail_file') || $request->hasFile('thumbnail') || $request->hasFile('image') || isset($data['thumbnail']) || isset($data['thumbnail_url'])) {
            $rawThumbnail = $request->file('thumbnail_file')
                ?? $request->file('thumbnail')
                ?? $request->file('image')
                ?? $data['thumbnail']
                ?? $data['thumbnail_url']
                ?? null;

            $data['thumbnail'] = \App\Services\ImageService::processAndStore($rawThumbnail, 'articles');
        }

        unset($data['thumbnail_file'], $data['image'], $data['thumbnail_url']);

        $tags = $data['tags'] ?? null;
        unset($data['tags']);

        $article->update($data);

        if ($tags !== null) {
            $article->tags()->sync($tags);
        }

        return ApiResponse::success(new ArticleResource($article->fresh(['category', 'author', 'tags'])), 'Artikel berhasil diperbarui.');
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $article = Article::find($id);
        if (!$article) {
            return ApiResponse::error('Artikel tidak ditemukan.', 404);
        }

        $user = $request->user();
        if ($user->hasRole('Penulis') && !$user->hasAnyRole(['Admin', 'Editor']) && $article->author_id !== $user->id) {
            return ApiResponse::error('Anda tidak memiliki izin menghapus artikel ini.', 403);
        }

        $article->delete();

        return ApiResponse::success(null, 'Artikel berhasil dihapus.');
    }

    public function submitReview(Request $request, int $id): JsonResponse
    {
        $article = Article::find($id);
        if (!$article) {
            return ApiResponse::error('Artikel tidak ditemukan.', 404);
        }

        $article->update(['status' => 'review']);

        return ApiResponse::success(new ArticleResource($article), 'Artikel berhasil diajukan untuk review.');
    }

    public function publish(Request $request, int $id): JsonResponse
    {
        $article = Article::find($id);
        if (!$article) {
            return ApiResponse::error('Artikel tidak ditemukan.', 404);
        }

        $article->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

        return ApiResponse::success(new ArticleResource($article), 'Artikel berhasil dipublikasikan.');
    }

    public function archive(Request $request, int $id): JsonResponse
    {
        $article = Article::find($id);
        if (!$article) {
            return ApiResponse::error('Artikel tidak ditemukan.', 404);
        }

        $article->update(['status' => 'archived']);

        return ApiResponse::success(new ArticleResource($article), 'Artikel berhasil diarsipkan.');
    }

    public function schedule(Request $request, int $id): JsonResponse
    {
        $request->validate(['published_at' => 'required|date|after:now']);

        $article = Article::find($id);
        if (!$article) {
            return ApiResponse::error('Artikel tidak ditemukan.', 404);
        }

        $article->update([
            'status' => 'published',
            'published_at' => $request->published_at,
        ]);

        return ApiResponse::success(new ArticleResource($article), 'Jadwal publikasi artikel berhasil disimpan.');
    }
}
