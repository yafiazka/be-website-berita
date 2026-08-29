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
        $user = $request->user() ?? \App\Models\User::where('username', 'admin')->first() ?? \App\Models\User::first();

        $query = Article::with(['category', 'tags', 'author'])->latest('created_at');

        // Jika bukan Super Administrator / Admin, hanya ambil artikel miliknya sendiri
        if ($user && !$user->hasAnyRole(['Admin', 'Super Administrator', 'super-admin'])) {
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

        return response()->json([
            'success' => true,
            'data'    => $article,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $user = $request->user() ?? \App\Models\User::where('username', 'admin')->first() ?? \App\Models\User::first();

        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'category_id'    => 'required|exists:categories,id',
            'excerpt'        => 'nullable|string|max:500',
            'content'        => 'required|string',
            'status'         => 'required|in:draft,published,review,archived',
            'author_source'  => 'nullable|string|max:255',
            'source'         => 'nullable|string|max:255',
            'thumbnail'      => 'nullable',
            'thumbnail_url'  => 'nullable|string',
            'thumbnail_file' => 'nullable',
            'image'          => 'nullable',
            'file'           => 'nullable',
            'video_url'      => 'nullable|string',
            'is_featured'    => 'nullable',
            'is_breaking'    => 'nullable',
            'tags'           => 'nullable',
        ]);

        return DB::transaction(function () use ($request, $validated, $user) {
            $slug  = Str::slug($validated['title']);
            $count = Article::where('slug', 'like', "{$slug}%")->count();
            if ($count > 0) {
                $slug .= '-' . ($count + 1);
            }

            $excerpt = $validated['excerpt'] ?? Str::limit(strip_tags($validated['content']), 160);

            // Handle direct File upload from ANY field key, Base64, or URL string
            $uploadedFile = null;
            if ($request->hasFile('thumbnail')) {
                $uploadedFile = $request->file('thumbnail');
            } elseif ($request->hasFile('thumbnail_file')) {
                $uploadedFile = $request->file('thumbnail_file');
            } elseif ($request->hasFile('thumbnailFile')) {
                $uploadedFile = $request->file('thumbnailFile');
            } elseif ($request->hasFile('image')) {
                $uploadedFile = $request->file('image');
            } elseif ($request->hasFile('file')) {
                $uploadedFile = $request->file('file');
            } elseif ($request->hasFile('cover')) {
                $uploadedFile = $request->file('cover');
            } elseif ($request->hasFile('featured_image')) {
                $uploadedFile = $request->file('featured_image');
            } elseif (!empty($request->allFiles())) {
                $uploadedFile = array_values($request->allFiles())[0];
            }

            $rawThumbnail = $uploadedFile
                ?? $request->input('thumbnail')
                ?? $request->input('thumbnail_url')
                ?? $request->input('thumbnail_file')
                ?? $request->input('thumbnailFile')
                ?? $request->input('image')
                ?? $request->input('file')
                ?? $request->input('cover')
                ?? $request->input('featured_image')
                ?? ($validated['thumbnail'] ?? null)
                ?? ($validated['thumbnail_url'] ?? null);

            $thumbnail = \App\Services\ImageService::processAndStore($rawThumbnail, 'articles');

            $authorUser = $user ?? $request->user() ?? \App\Models\User::where('username', 'admin')->first() ?? \App\Models\User::first();
            $isSuperAdmin = $authorUser ? $authorUser->hasAnyRole(['Super Administrator', 'Admin', 'super-admin']) : true;
            $authorSource = $isSuperAdmin
                ? ($validated['author_source'] ?? 'Redaksi JERITAN')
                : ($validated['author_source'] ?? ($authorUser ? $authorUser->name : 'Redaksi JERITAN'));

            $isFeatured = filter_var($validated['is_featured'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $isBreaking = filter_var($validated['is_breaking'] ?? false, FILTER_VALIDATE_BOOLEAN);

            $article = Article::create([
                'title'         => $validated['title'],
                'slug'          => $slug,
                'category_id'   => $validated['category_id'],
                'author_id'     => $authorUser ? $authorUser->id : 1,
                'author_source' => $authorSource,
                'source'        => $validated['source'] ?? null,
                'excerpt'       => $excerpt,
                'content'       => $validated['content'],
                'status'        => $validated['status'],
                'thumbnail'     => $thumbnail,
                'video_url'     => $validated['video_url'] ?? null,
                'is_featured'   => $isFeatured,
                'is_breaking'   => $isBreaking,
                'published_at'  => $validated['status'] === 'published' ? now() : null,
            ]);

            // Normalisasi tags
            $tags = $validated['tags'] ?? [];
            if (is_string($tags)) {
                $decoded = json_decode($tags, true);
                $tags = is_array($decoded) ? $decoded : array_filter(explode(',', $tags));
            }

            if (!empty($tags)) {
                $article->tags()->sync($tags);
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

        $validated = $request->validate([
            'title'          => 'sometimes|string|max:255',
            'category_id'    => 'sometimes|exists:categories,id',
            'excerpt'        => 'nullable|string|max:500',
            'content'        => 'sometimes|string',
            'status'         => 'sometimes|in:draft,published,review,archived',
            'author_source'  => 'nullable|string|max:255',
            'source'         => 'nullable|string|max:255',
            'thumbnail'      => 'nullable',
            'thumbnail_url'  => 'nullable|string',
            'thumbnail_file' => 'nullable',
            'image'          => 'nullable',
            'file'           => 'nullable',
            'video_url'      => 'nullable|string',
            'is_featured'    => 'nullable',
            'is_breaking'    => 'nullable',
            'tags'           => 'nullable',
        ]);

        return DB::transaction(function () use ($request, $article, $validated) {
            if (isset($validated['status']) && $validated['status'] === 'published' && !$article->published_at) {
                $validated['published_at'] = now();
            }

            if (isset($validated['is_featured'])) {
                $validated['is_featured'] = filter_var($validated['is_featured'], FILTER_VALIDATE_BOOLEAN);
            }

            if (isset($validated['is_breaking'])) {
                $validated['is_breaking'] = filter_var($validated['is_breaking'], FILTER_VALIDATE_BOOLEAN);
            }

            // Handle direct File upload from ANY field key, Base64, or URL string
            $uploadedFile = null;
            if ($request->hasFile('thumbnail')) {
                $uploadedFile = $request->file('thumbnail');
            } elseif ($request->hasFile('thumbnail_file')) {
                $uploadedFile = $request->file('thumbnail_file');
            } elseif ($request->hasFile('thumbnailFile')) {
                $uploadedFile = $request->file('thumbnailFile');
            } elseif ($request->hasFile('image')) {
                $uploadedFile = $request->file('image');
            } elseif ($request->hasFile('file')) {
                $uploadedFile = $request->file('file');
            } elseif ($request->hasFile('cover')) {
                $uploadedFile = $request->file('cover');
            } elseif ($request->hasFile('featured_image')) {
                $uploadedFile = $request->file('featured_image');
            } elseif (!empty($request->allFiles())) {
                $uploadedFile = array_values($request->allFiles())[0];
            }

            $rawThumbnail = $uploadedFile
                ?? $request->input('thumbnail')
                ?? $request->input('thumbnail_url')
                ?? $request->input('thumbnail_file')
                ?? $request->input('thumbnailFile')
                ?? $request->input('image')
                ?? $request->input('file')
                ?? $request->input('cover')
                ?? $request->input('featured_image')
                ?? ($validated['thumbnail'] ?? null)
                ?? ($validated['thumbnail_url'] ?? null);

            if ($rawThumbnail !== null) {
                $validated['thumbnail'] = \App\Services\ImageService::processAndStore($rawThumbnail, 'articles');
            }

            unset($validated['thumbnail_file'], $validated['thumbnailFile'], $validated['image'], $validated['file'], $validated['thumbnail_url']);

            $tags = $validated['tags'] ?? null;
            if (is_string($tags)) {
                $decoded = json_decode($tags, true);
                $tags = is_array($decoded) ? $decoded : array_filter(explode(',', $tags));
            }
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
        $article->delete();

        return response()->json([
            'success' => true,
            'message' => 'Artikel berhasil dihapus.',
        ]);
    }

    public function publish(Request $request, $id): JsonResponse
    {
        $article = Article::findOrFail($id);

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
        $user  = $request->user() ?? \App\Models\User::where('username', 'admin')->first() ?? \App\Models\User::first();
        $query = Article::query();

        if ($user && !$user->hasAnyRole(['Admin', 'Super Administrator', 'super-admin'])) {
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
