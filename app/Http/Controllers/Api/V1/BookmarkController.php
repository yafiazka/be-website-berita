<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use App\Http\Resources\ArticleListResource;
use App\Models\Article;
use App\Models\Bookmark;
use App\Models\Like;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function toggleLike(string $slug, Request $request): JsonResponse
    {
        $article = Article::where('slug', $slug)->first();
        if (!$article) {
            return ApiResponse::error('Artikel tidak ditemukan.', 404);
        }

        $userId = $request->user()?->id;
        $ip = $request->ip();

        $existingLike = Like::where('article_id', $article->id)
            ->where(function ($q) use ($userId, $ip) {
                if ($userId) {
                    $q->where('user_id', $userId);
                } else {
                    $q->where('ip_address', $ip);
                }
            })
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            return ApiResponse::success(['liked' => false, 'likes_count' => $article->likes()->count()], 'Menyukai artikel dibatalkan.');
        }

        Like::create([
            'article_id' => $article->id,
            'user_id' => $userId,
            'ip_address' => $userId ? null : $ip,
        ]);

        return ApiResponse::success(['liked' => true, 'likes_count' => $article->likes()->count()], 'Berhasil menyukai artikel.');
    }

    public function toggleBookmark(string $slug, Request $request): JsonResponse
    {
        $article = Article::where('slug', $slug)->first();
        if (!$article) {
            return ApiResponse::error('Artikel tidak ditemukan.', 404);
        }

        $user = $request->user();
        $bookmark = Bookmark::where('user_id', $user->id)->where('article_id', $article->id)->first();

        if ($bookmark) {
            $bookmark->delete();
            return ApiResponse::success(['bookmarked' => false], 'Artikel dihapus dari bookmark.');
        }

        Bookmark::create([
            'user_id' => $user->id,
            'article_id' => $article->id,
        ]);

        return ApiResponse::success(['bookmarked' => true], 'Artikel disimpan ke bookmark.');
    }

    public function myBookmarks(Request $request): JsonResponse
    {
        $user = $request->user();
        $articles = $user->bookmarkedArticles()
            ->published()
            ->with(['category', 'author', 'tags'])
            ->latest('bookmarks.created_at')
            ->paginate((int)$request->input('per_page', 12));

        return ApiResponse::paginated(ArticleListResource::collection($articles));
    }
}
