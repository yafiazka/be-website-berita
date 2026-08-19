<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Comment;
use App\Models\User;
use App\Models\Like;
use App\Models\Bookmark;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // 1. Metric Calculations
        $totalArticles = Article::count();
        $publishedArticles = Article::where('status', 'published')->count();
        $draftArticles = Article::where('status', 'draft')->count();
        $reviewArticles = Article::where('status', 'review')->count();
        $scheduledArticles = Article::where('status', 'scheduled')->count();
        $totalViews = Article::sum('views_count');

        $totalCategories = Category::count();
        $totalTags = Tag::count();
        $totalComments = Comment::count();
        $pendingComments = Comment::where('status', 'pending')->count();
        $totalUsers = User::count();
        $totalSubscribers = NewsletterSubscriber::whereNull('unsubscribed_at')->count();
        $totalLikes = Like::count();
        $totalBookmarks = Bookmark::count();

        // 2. 7-Day Trend of Article Views & Publications
        $last7Days = collect(range(6, 0))->map(function ($daysAgo) {
            $date = Carbon::now()->subDays($daysAgo);
            $dateString = $date->format('Y-m-d');
            $dayName = $date->translatedFormat('D, d M');

            $articlesCount = Article::whereDate('created_at', $dateString)->count();
            $viewsCount = Article::whereDate('created_at', $dateString)->sum('views_count');

            return [
                'date' => $dayName,
                'articles' => $articlesCount,
                'views' => $viewsCount,
            ];
        });

        // 3. Category Distribution
        $categoryDistribution = Category::withCount('articles')
            ->orderByDesc('articles_count')
            ->take(6)
            ->get();

        // 4. Top Trending Articles
        $topArticles = Article::with(['category', 'author'])
            ->where('status', 'published')
            ->orderByDesc('views_count')
            ->take(5)
            ->get();

        // 5. Recent Comments Needing Moderation
        $recentComments = Comment::with(['article', 'user'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalArticles',
            'publishedArticles',
            'draftArticles',
            'reviewArticles',
            'scheduledArticles',
            'totalViews',
            'totalCategories',
            'totalTags',
            'totalComments',
            'pendingComments',
            'totalUsers',
            'totalSubscribers',
            'totalLikes',
            'totalBookmarks',
            'last7Days',
            'categoryDistribution',
            'topArticles',
            'recentComments'
        ));
    }
}
