<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\FeedController;

// Root URL langsung mengarah ke Dashboard CMS Admin
Route::get('/', function () {
    return redirect(backpack_url('dashboard'));
});

// Route /api mengembalikan informasi indeks REST API
Route::get('/api', function () {
    return response()->json([
        'status' => 'online',
        'api_version' => 'v1',
        'base_url' => url('/api/v1'),
        'settings' => url('/api/v1/settings'),
        'documentation' => url('/admin/api-docs'),
        'health' => url('/api/v1/health'),
    ]);
});

Route::get('/sitemap.xml', [FeedController::class, 'sitemap']);
Route::get('/rss', [FeedController::class, 'rss']);
