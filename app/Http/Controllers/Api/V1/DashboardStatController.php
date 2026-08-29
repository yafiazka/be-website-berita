<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use App\Http\Resources\ArticleListResource;
use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\NewsletterSubscriber;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class DashboardStatController extends Controller
{
    public function overview(): JsonResponse
    {
        $totalArticles = Article::count();
        $publishedArticles = Article::where('status', 'published')->count();
        $draftArticles = Article::where('status', 'draft')->count();
        $reviewArticles = Article::where('status', 'review')->count();
        $archivedArticles = Article::where('status', 'archived')->count();
        $totalViews = (int) Article::sum('views_count');
        $totalComments = Comment::count();
        $pendingComments = Comment::where('status', 'pending')->count();
        $totalUsers = User::count();
        $totalCategories = Category::count();
        $totalSubscribers = NewsletterSubscriber::whereNull('unsubscribed_at')->count();

        return ApiResponse::success([
            'total_articles' => $totalArticles,
            'published_articles' => $publishedArticles,
            'draft_articles' => $draftArticles,
            'review_articles' => $reviewArticles,
            'archived_articles' => $archivedArticles,
            'total_views' => $totalViews,
            'total_comments' => $totalComments,
            'pending_comments' => $pendingComments,
            'total_users' => $totalUsers,
            'total_categories' => $totalCategories,
            'total_subscribers' => $totalSubscribers,
            'articles' => [
                'total' => $totalArticles,
                'published' => $publishedArticles,
                'draft' => $draftArticles,
                'review' => $reviewArticles,
                'archived' => $archivedArticles,
                'views' => $totalViews,
            ],
            'comments' => [
                'total' => $totalComments,
                'pending' => $pendingComments,
            ],
            'users' => [
                'total' => $totalUsers,
            ],
            'categories_count' => $totalCategories,
            'subscribers_count' => $totalSubscribers,
        ], 'Statistik dashboard redaksi.');
    }

    public function topArticles(): JsonResponse
    {
        $articles = Article::published()
            ->with(['category', 'author', 'tags'])
            ->orderBy('views_count', 'desc')
            ->take(10)
            ->get();

        return ApiResponse::success(ArticleListResource::collection($articles), '10 Artikel terpopuler.');
    }
}
