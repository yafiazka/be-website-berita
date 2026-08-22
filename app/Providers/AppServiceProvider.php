<?php

/**
 * Tujuan: Service provider utama aplikasi untuk bootstrapping layanan dan observer
 * Caller: Laravel Framework Bootstrap Lifecycle
 * Dependensi: App\Models\Article, App\Observers\ArticleObserver
 * Main Functions: register(), boot()
 * Side Effects: Registrasi Model Observers
 */

namespace App\Providers;

use App\Models\Article;
use App\Observers\ArticleObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Article::observe(ArticleObserver::class);
    }
}

