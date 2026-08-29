<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\Auth\MeController;
use App\Http\Controllers\Api\V1\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\V1\ArticleController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\TagController;
use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\Api\V1\MediaController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\RoleController;
use App\Http\Controllers\Api\V1\NewsletterController;
use App\Http\Controllers\Api\V1\BookmarkController;
use App\Http\Controllers\Api\V1\SearchController;
use App\Http\Controllers\Api\V1\DashboardStatController;
use App\Http\Controllers\Api\V1\ActivityLogController;
use App\Http\Controllers\Api\V1\FeedController;
use App\Http\Controllers\Api\V1\SettingController;

Route::prefix('v1')->group(function () {

    // ── Health Check & Settings ────────────────────────────────
    Route::get('/health', [FeedController::class, 'health']);
    Route::get('/settings', [SettingController::class, 'index']);

    // ── Autentikasi ───────────────────────────────────────────
    Route::prefix('auth')->group(function () {
        Route::post('/register', RegisterController::class)->middleware('throttle:10,1');
        Route::post('/login', LoginController::class)->middleware('throttle:10,1');
        Route::post('/forgot-password', [ForgotPasswordController::class, 'forgotPassword'])->middleware('throttle:5,1');
        Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->middleware('throttle:5,1');

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', LogoutController::class);
            Route::get('/me', [MeController::class, 'show']);
            Route::put('/me', [MeController::class, 'updateProfile']);
            Route::put('/change-password', [MeController::class, 'changePassword']);
        });
    });

    // ── Artikel Publik ─────────────────────────────────────────
    Route::get('/articles/trending', [ArticleController::class, 'trending']);
    Route::get('/articles/breaking', [ArticleController::class, 'breaking']);
    Route::get('/articles', [ArticleController::class, 'index']);
    Route::get('/articles/{slug}', [ArticleController::class, 'show']);
    Route::get('/articles/{slug}/related', [ArticleController::class, 'related']);
    Route::get('/articles/{slug}/comments', [CommentController::class, 'getArticleComments']);
    Route::post('/articles/{slug}/comments', [CommentController::class, 'store'])->middleware('throttle:10,1');
    Route::post('/articles/{slug}/like', [BookmarkController::class, 'toggleLike'])->middleware('throttle:30,1');

    // ── Kategori & Tag Publik ──────────────────────────────────
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{slug}', [CategoryController::class, 'show']);
    Route::get('/categories/{slug}/articles', [ArticleController::class, 'byCategory']);
    Route::get('/tags', [TagController::class, 'index']);
    Route::get('/tags/{slug}/articles', [ArticleController::class, 'byTag']);

    // ── Pencarian ──────────────────────────────────────────────
    Route::get('/search', SearchController::class)->middleware('throttle:30,1');

    // ── Newsletter ─────────────────────────────────────────────
    Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->middleware('throttle:10,1');
    Route::post('/newsletter/unsubscribe', [NewsletterController::class, 'unsubscribe']);

    // ── Interaksi Pembaca (Auth) ──────────────────────────────
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/articles/{slug}/bookmark', [BookmarkController::class, 'toggleBookmark']);
        Route::get('/me/bookmarks', [BookmarkController::class, 'myBookmarks']);
    });

    // ── Rute Editorial (Redaksi CMS Frontend) ──────────────────
    Route::prefix('editorial')->middleware(['auth:sanctum'])->group(function () {
        // CRUD Artikel Redaksi
        Route::get('/articles', [ArticleController::class, 'dashboardIndex']);
        Route::post('/articles', [ArticleController::class, 'store']);
        Route::get('/articles/{id}', [ArticleController::class, 'dashboardShow']);
        Route::put('/articles/{id}', [ArticleController::class, 'update']);
        Route::delete('/articles/{id}', [ArticleController::class, 'destroy']);

        // Aksi Cepat
        Route::post('/articles/{id}/publish', [ArticleController::class, 'publish']);
        Route::post('/articles/{id}/submit-review', [ArticleController::class, 'submitReview']);
        Route::post('/articles/{id}/archive', [ArticleController::class, 'archive']);
        Route::post('/articles/{id}/schedule', [ArticleController::class, 'schedule']);

        // Upload Media / Gambar langsung dari Editor
        Route::post('/media/upload', [MediaController::class, 'upload']);

        // Statistik Redaksi
        Route::get('/stats', [DashboardStatController::class, 'overview']);
        Route::get('/stats/overview', [DashboardStatController::class, 'overview']);
        Route::get('/stats/top-articles', [DashboardStatController::class, 'topArticles']);
    });

    // ── Dashboard / Redaksi & Admin ───────────────────────────
    Route::prefix('dashboard')->middleware(['auth:sanctum'])->group(function () {
        
        // Articles Management
        Route::get('/articles', [ArticleController::class, 'dashboardIndex']);
        Route::post('/articles', [ArticleController::class, 'store']);
        Route::get('/articles/{id}', [ArticleController::class, 'dashboardShow']);
        Route::put('/articles/{id}', [ArticleController::class, 'update']);
        Route::delete('/articles/{id}', [ArticleController::class, 'destroy']);
        Route::post('/articles/{id}/submit-review', [ArticleController::class, 'submitReview']);
        Route::post('/articles/{id}/publish', [ArticleController::class, 'publish'])->middleware('role:Admin|Editor');
        Route::post('/articles/{id}/archive', [ArticleController::class, 'archive'])->middleware('role:Admin|Editor');
        Route::post('/articles/{id}/schedule', [ArticleController::class, 'schedule'])->middleware('role:Admin|Editor');

        // Categories Management
        Route::get('/categories', [CategoryController::class, 'dashboardIndex']);
        Route::post('/categories', [CategoryController::class, 'store'])->middleware('role:Admin|Editor');
        Route::put('/categories/{id}', [CategoryController::class, 'update'])->middleware('role:Admin|Editor');
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->middleware('role:Admin');

        // Tags Management
        Route::get('/tags', [TagController::class, 'dashboardIndex']);
        Route::post('/tags', [TagController::class, 'store']);
        Route::put('/tags/{id}', [TagController::class, 'update']);
        Route::delete('/tags/{id}', [TagController::class, 'destroy'])->middleware('role:Admin|Editor');

        // Comments Moderation
        Route::get('/comments', [CommentController::class, 'dashboardIndex'])->middleware('role:Admin|Editor');
        Route::post('/comments/{id}/approve', [CommentController::class, 'approve'])->middleware('role:Admin|Editor');
        Route::post('/comments/{id}/spam', [CommentController::class, 'markSpam'])->middleware('role:Admin|Editor');
        Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->middleware('role:Admin|Editor');

        // Media Management
        Route::post('/media/upload', [MediaController::class, 'upload']);
        Route::delete('/media', [MediaController::class, 'destroy'])->middleware('role:Admin|Editor');

        // Users & Roles & Settings Management (Admin only)
        Route::middleware('role:Admin')->group(function () {
            Route::get('/users', [UserController::class, 'index']);
            Route::post('/users', [UserController::class, 'store']);
            Route::get('/users/{id}', [UserController::class, 'show']);
            Route::put('/users/{id}', [UserController::class, 'update']);
            Route::delete('/users/{id}', [UserController::class, 'destroy']);
            Route::get('/roles', [RoleController::class, 'index']);
            Route::get('/activity-logs', [ActivityLogController::class, 'index']);
            Route::put('/settings', [SettingController::class, 'update']);
        });

        // Dashboard Stats
        Route::middleware('role:Admin|Editor')->group(function () {
            Route::get('/stats/overview', [DashboardStatController::class, 'overview']);
            Route::get('/stats/top-articles', [DashboardStatController::class, 'topArticles']);
        });
    });
});
