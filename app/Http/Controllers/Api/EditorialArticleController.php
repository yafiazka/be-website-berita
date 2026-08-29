<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class EditorialArticleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        Gate::authorize('viewAny', Article::class);
        $user = $request->user();

        $query = Article::with(['category', 'tags', 'author'])->latest('created_at');

        // Jika bukan Super Administrator / Admin, hanya ambil artikel miliknya sendiri
        if (!$user->hasAnyRole(['Admin', 'Super Administrator', 'super-admin'])) {
            $query->where('author_id', $user->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('author_source', 'like', "%{$search}%");
            });
        }

        $perPage = (int) $request->input('per_page', 15);
        $articles = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => $articles,
        ]);
    }

    public function show(Request $request, $id): JsonResponse
    {
        $article = Article::with(['category', 'tags', 'author'])->findOrFail($id);
        Gate::authorize('view', $article);

        return response()->json([
            'success' => true,
            'data'    => $article,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        Gate::authorize('create', Article::class);

        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'category_id'   => 'required|exists:categories,id',
            'excerpt'       => 'nullable|string|max:500',
            'content'       => 'required|string',
            'status'        => 'required|in:draft,published,review,archived',
            'author_source' => 'nullable|string|max:255',
            'source'        => 'nullable|string|max:255',
            'thumbnail'     => 'nullable',
            'thumbnail_url' => 'nullable|string',
            'thumbnail_file' => 'nullable|file|image|mimes:jpeg,png,jpg,webp,gif,avif,svg|max:5120',
            'image'         => 'nullable|file|image|mimes:jpeg,png,jpg,webp,gif,avif,svg|max:5120',
            'video_url'     => 'nullable|string',
            'is_featured'   => 'nullable|boolean',
            'is_breaking'   => 'nullable|boolean',
            'tags'          => 'nullable|array',
            'tags.*'        => 'exists:tags,id',
        ]);

        return DB::transaction(function () use ($request, $validated) {
            $slug  = Str::slug($validated['title']);
            $count = Article::where('slug', 'like', "{$slug}%")->count();
            if ($count > 0) {
                $slug .= '-' . ($count + 1);
            }

            $excerpt = $validated['excerpt'] ?? Str::limit(strip_tags($validated['content']), 160);

            // Handle image upload if provided as file
            $thumbnail = $validated['thumbnail'] ?? $validated['thumbnail_url'] ?? null;
            if ($request->hasFile('thumbnail_file')) {
                $thumbnail = $request->file('thumbnail_file')->store('articles', 'public');
            } elseif ($request->hasFile('image')) {
                $thumbnail = $request->file('image')->store('articles', 'public');
            } elseif ($request->hasFile('thumbnail')) {
                $thumbnail = $request->file('thumbnail')->store('articles', 'public');
            }

            $article = Article::create([
                'title'         => $validated['title'],
                'slug'          => $slug,
                'category_id'   => $validated['category_id'],
                'author_id'     => $request->user()->id,
                'author_source' => $validated['author_source'] ?? $request->user()->name,
                'source'        => $validated['source'] ?? null,
                'excerpt'       => $excerpt,
                'content'       => $validated['content'],
                'status'        => $validated['status'],
                'thumbnail'     => $thumbnail,
                'video_url'     => $validated['video_url'] ?? null,
                'is_featured'   => (bool) ($validated['is_featured'] ?? false),
                'is_breaking'   => (bool) ($validated['is_breaking'] ?? false),
                'published_at'  => $validated['status'] === 'published' ? now() : null,
            ]);

            if (!empty($validated['tags'])) {
                $article->tags()->sync($validated['tags']);
            }

            return response()->json([
                'success' => true,
                'message' => $article->status === 'published' ? 'Berita berhasil diterbitkan.' : 'Draf berita berhasil disimpan.',
                'data'    => $article->load(['category', 'tags']),
            ], 201);
        });
    }

    public function update(Request $request, $id): JsonResponse
    {
        $article = Article::findOrFail($id);
        Gate::authorize('update', $article);

        $validated = $request->validate([
            'title'         => 'sometimes|string|max:255',
            'category_id'   => 'sometimes|exists:categories,id',
            'excerpt'       => 'nullable|string|max:500',
            'content'       => 'sometimes|string',
            'status'        => 'sometimes|in:draft,published,review,archived',
            'author_source' => 'nullable|string|max:255',
            'source'        => 'nullable|string|max:255',
            'thumbnail'     => 'nullable',
            'thumbnail_url' => 'nullable|string',
            'thumbnail_file' => 'nullable|file|image|mimes:jpeg,png,jpg,webp,gif,avif,svg|max:5120',
            'image'         => 'nullable|file|image|mimes:jpeg,png,jpg,webp,gif,avif,svg|max:5120',
            'video_url'     => 'nullable|string',
            'is_featured'   => 'nullable|boolean',
            'is_breaking'   => 'nullable|boolean',
            'tags'          => 'nullable|array',
            'tags.*'        => 'exists:tags,id',
        ]);

        return DB::transaction(function () use ($request, $article, $validated) {
            if (isset($validated['status']) && $validated['status'] === 'published' && !$article->published_at) {
                $validated['published_at'] = now();
            }

            // Handle image upload if provided as file
            if ($request->hasFile('thumbnail_file')) {
                $validated['thumbnail'] = $request->file('thumbnail_file')->store('articles', 'public');
            } elseif ($request->hasFile('image')) {
                $validated['thumbnail'] = $request->file('image')->store('articles', 'public');
            } elseif ($request->hasFile('thumbnail')) {
                $validated['thumbnail'] = $request->file('thumbnail')->store('articles', 'public');
            } elseif (isset($validated['thumbnail_url']) && empty($validated['thumbnail'])) {
                $validated['thumbnail'] = $validated['thumbnail_url'];
            }

            unset($validated['thumbnail_file'], $validated['image'], $validated['thumbnail_url']);

            $tags = $validated['tags'] ?? null;
            unset($validated['tags']);

            $article->update($validated);

            if ($tags !== null) {
                $article->tags()->sync($tags);
            }

            return response()->json([
                'success' => true,
                'message' => 'Artikel berhasil diperbarui.',
                'data'    => $article->fresh(['category', 'tags']),
            ]);
        });
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        $article = Article::findOrFail($id);
        Gate::authorize('delete', $article);

        $article->delete();

        return response()->json([
            'success' => true,
            'message' => 'Artikel berhasil dihapus.',
        ]);
    }

    public function publish(Request $request, $id): JsonResponse
    {
        $article = Article::findOrFail($id);
        Gate::authorize('publish', $article);

        $article->update([
            'status'       => 'published',
            'published_at' => $article->published_at ?? now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Artikel berhasil diterbitkan.',
            'data'    => $article->fresh(['category', 'tags']),
        ]);
    }

    public function archive(Request $request, $id): JsonResponse
    {
        $article = Article::findOrFail($id);
        Gate::authorize('update', $article);

        $article->update([
            'status' => 'archived',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Artikel berhasil diarsipkan / disembunyikan.',
            'data'    => $article->fresh(['category', 'tags']),
        ]);
    }

    public function hide(Request $request, $id): JsonResponse
    {
        return $this->archive($request, $id);
    }

    public function draft(Request $request, $id): JsonResponse
    {
        $article = Article::findOrFail($id);
        Gate::authorize('update', $article);

        $article->update([
            'status' => 'draft',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status artikel dikembalikan menjadi draf.',
            'data'    => $article->fresh(['category', 'tags']),
        ]);
    }

    public function stats(Request $request): JsonResponse
    {
        $user  = $request->user();
        $query = Article::query();

        if (!$user->hasAnyRole(['Admin', 'Super Administrator', 'super-admin'])) {
            $query->where('author_id', $user->id);
        }

        $totalArticles     = (clone $query)->count();
        $publishedArticles = (clone $query)->where('status', 'published')->count();
        $draftArticles     = (clone $query)->where('status', 'draft')->count();
        $reviewArticles    = (clone $query)->where('status', 'review')->count();
        $archivedArticles  = (clone $query)->where('status', 'archived')->count();
        $totalViews        = (int) (clone $query)->sum('views_count');

        return response()->json([
            'success' => true,
            'data'    => [
                'total_articles'     => $totalArticles,
                'published_articles' => $publishedArticles,
                'draft_articles'     => $draftArticles,
                'review_articles'    => $reviewArticles,
                'archived_articles'  => $archivedArticles,
                'total_views'        => $totalViews,
                'articles' => [
                    'total'     => $totalArticles,
                    'published' => $publishedArticles,
                    'draft'     => $draftArticles,
                    'review'    => $reviewArticles,
                    'archived'  => $archivedArticles,
                    'views'     => $totalViews,
                ],
            ],
        ]);
    }
}
