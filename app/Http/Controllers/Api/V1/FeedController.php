<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class FeedController extends Controller
{
    public function health(): JsonResponse
    {
        $dbStatus = 'OK';
        try {
            DB::connection()->getPdo();
        } catch (\Throwable $e) {
            $dbStatus = 'Error: ' . $e->getMessage();
        }

        $redisStatus = 'OK';
        try {
            Redis::ping();
        } catch (\Throwable $e) {
            $redisStatus = 'Error: ' . $e->getMessage();
        }

        $status = ($dbStatus === 'OK' && $redisStatus === 'OK') ? 'healthy' : 'degraded';

        return ApiResponse::success([
            'status' => $status,
            'timestamp' => now()->toIso8601String(),
            'database' => $dbStatus,
            'redis' => $redisStatus,
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
        ], 'System health check.');
    }

    public function sitemap(): Response
    {
        $articles = Article::published()->latest('published_at')->take(100)->get();
        $categories = Category::all();

        $baseUrl = config('app.url', 'http://localhost:8086');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        $xml .= '<url><loc>' . $baseUrl . '</loc><changefreq>always</changefreq><priority>1.0</priority></url>';

        foreach ($categories as $category) {
            $xml .= '<url>';
            $xml .= '<loc>' . $baseUrl . '/kategori/' . $category->slug . '</loc>';
            $xml .= '<changefreq>hourly</changefreq>';
            $xml .= '<priority>0.8</priority>';
            $xml .= '</url>';
        }

        foreach ($articles as $article) {
            $xml .= '<url>';
            $xml .= '<loc>' . $baseUrl . '/berita/' . $article->slug . '</loc>';
            $xml .= '<lastmod>' . ($article->updated_at ?? $article->published_at)->toIso8601String() . '</lastmod>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.9</priority>';
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    public function rss(): Response
    {
        $articles = Article::published()->with(['author', 'category'])->latest('published_at')->take(30)->get();
        $appName = config('app.name', 'Website Berita');
        $baseUrl = config('app.url', 'http://localhost:8086');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">';
        $xml .= '<channel>';
        $xml .= '<title>' . htmlspecialchars($appName) . '</title>';
        $xml .= '<link>' . $baseUrl . '</link>';
        $xml .= '<description>Kabar Berita Terkini dan Terpercaya</description>';
        $xml .= '<language>id</language>';
        $xml .= '<lastBuildDate>' . now()->toRfc2822String() . '</lastBuildDate>';

        foreach ($articles as $article) {
            $xml .= '<item>';
            $xml .= '<title>' . htmlspecialchars($article->title) . '</title>';
            $xml .= '<link>' . $baseUrl . '/berita/' . $article->slug . '</link>';
            $xml .= '<description>' . htmlspecialchars($article->excerpt ?? '') . '</description>';
            $xml .= '<author>' . htmlspecialchars($article->author?->email ?? 'redaksi@berita.local') . ' (' . htmlspecialchars($article->author?->name ?? 'Redaksi') . ')</author>';
            $xml .= '<category>' . htmlspecialchars($article->category?->name ?? 'Umum') . '</category>';
            $xml .= '<pubDate>' . $article->published_at->toRfc2822String() . '</pubDate>';
            $xml .= '<guid>' . $baseUrl . '/berita/' . $article->slug . '</guid>';
            $xml .= '</item>';
        }

        $xml .= '</channel>';
        $xml .= '</rss>';

        return response($xml, 200, ['Content-Type' => 'application/rss+xml; charset=utf-8']);
    }
}
