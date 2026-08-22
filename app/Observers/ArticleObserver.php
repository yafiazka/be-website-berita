<?php

/**
 * Tujuan: Mengamati siklus hidup model Article untuk otomatisasi slug unik dan timestamp published_at
 * Caller: Eloquent Model Article (Lifecycle hooks: creating, updating)
 * Dependensi: App\Models\Article, Illuminate\Support\Str
 * Main Functions: creating(), updating(), generateUniqueSlug()
 * Side Effects: Mutasi atribut slug dan published_at sebelum disimpan ke database
 */

namespace App\Observers;

use App\Models\Article;
use Illuminate\Support\Str;

class ArticleObserver
{
    public function creating(Article $article): void
    {
        if (blank($article->slug)) {
            $article->slug = $this->generateUniqueSlug($article->title ?: 'artikel');
        } else {
            $article->slug = Str::slug($article->slug);
        }

        if ($article->status === 'published' && empty($article->published_at)) {
            $article->published_at = now();
        }
    }

    public function updating(Article $article): void
    {
        if (blank($article->slug)) {
            $article->slug = $this->generateUniqueSlug($article->title ?: 'artikel', $article->id);
        } elseif ($article->isDirty('slug')) {
            $article->slug = Str::slug($article->slug);
        }

        if ($article->isDirty('status') && $article->status === 'published' && empty($article->published_at)) {
            $article->published_at = now();
        }
    }

    protected function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title);
        if (blank($baseSlug)) {
            $baseSlug = 'berita-' . Str::random(6);
        }

        $slug = $baseSlug;
        $count = 1;

        while (Article::where('slug', $slug)->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$baseSlug}-{$count}";
            $count++;
        }

        return $slug;
    }
}

