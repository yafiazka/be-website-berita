<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\FeedController;

Route::get('/', function () {
    return response()->json([
        'name' => config('app.name', 'Website Berita API'),
        'version' => '1.0.0',
        'api_doc' => url('/api/v1/health'),
    ]);
});

Route::get('/sitemap.xml', [FeedController::class, 'sitemap']);
Route::get('/rss', [FeedController::class, 'rss']);
