<?php

namespace App\Observers;

use App\Models\Article;
use Illuminate\Support\Str;

class ArticleObserver
{
    public function creating(Article $article): void
    {
        if (empty($article->slug)) {
            $article->slug = $this->generateUniqueSlug($article->title);
        }

        if ($article->status === 'published' && empty($article->published_at)) {
            $article->published_at = now();
        }
    }

    public function updating(Article $article): void
    {
        if ($article->isDirty('title') && empty($article->slug)) {
            $article->slug = $this->generateUniqueSlug($article->title, $article->id);
        }

        if ($article->isDirty('status') && $article->status === 'published' && empty($article->published_at)) {
            $article->published_at = now();
        }
    }

    protected function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $count = 1;

        while (Article::where('slug', $slug)->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$baseSlug}-{$count}";
            $count++;
        }

        return $slug;
    }
}
