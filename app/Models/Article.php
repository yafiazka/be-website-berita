<?php

/**
 * Tujuan: Model Eloquent entitas Artikel berita
 * Caller: ArticleController, ArticleCrudController, FeedController, BookmarkController
 * Dependensi: Spatie\Activitylog, Spatie\MediaLibrary, Backpack\CRUD, App\Observers\ArticleObserver
 * Main Functions: category(), author(), tags(), comments(), approvedComments(), bookmarks(), likes(), scopePublished(), scopeBreaking(), scopeTrending()
 * Side Effects: DB Read/Write tabel articles, article_tag, activity_log
 */

namespace App\Models;

use App\Observers\ArticleObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

#[ObservedBy([ArticleObserver::class])]
class Article extends Model implements HasMedia
{
    use HasFactory, LogsActivity, InteractsWithMedia, CrudTrait;

    protected $fillable = [
        'category_id',
        'author_id',
        'author_source',
        'source',
        'title',
        'slug',
        'excerpt',
        'content',
        'thumbnail',
        'thumbnail_file',
        'video_url',
        'status',
        'is_featured',
        'is_breaking',
        'published_at',
        'meta_title',
        'meta_description',
        'og_image',
        'views_count',
    ];

    protected $appends = [
        'thumbnail_url',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_breaking' => 'boolean',
        'published_at' => 'datetime',
        'views_count' => 'integer',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'status', 'is_featured', 'is_breaking', 'published_at', 'category_id', 'author_source', 'source'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if (empty($this->thumbnail)) {
            return null;
        }

        if (str_starts_with($this->thumbnail, 'http://') || str_starts_with($this->thumbnail, 'https://')) {
            return $this->thumbnail;
        }

        return asset('storage/' . ltrim($this->thumbnail, '/'));
    }

    public function setThumbnailAttribute($value): void
    {
        if ($value === null || $value === '') {
            $this->attributes['thumbnail'] = null;
            return;
        }

        if ($value instanceof \Illuminate\Http\UploadedFile) {
            $this->attributes['thumbnail'] = \App\Services\ImageService::processAndStore($value, 'articles');
            return;
        }

        if (is_string($value) && preg_match('/^data:image\/(\w+);base64,/', $value)) {
            $this->attributes['thumbnail'] = \App\Services\ImageService::processAndStore($value, 'articles');
            return;
        }

        // Direct string path or external URL
        $this->attributes['thumbnail'] = $value;
    }

    public function setThumbnailFileAttribute($value): void
    {
        if ($value instanceof \Illuminate\Http\UploadedFile || (is_string($value) && preg_match('/^data:image\/(\w+);base64,/', $value))) {
            $this->attributes['thumbnail'] = \App\Services\ImageService::processAndStore($value, 'articles');
        }
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'article_tag');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function approvedComments(): HasMany
    {
        return $this->hasMany(Comment::class)->where('status', 'approved')->whereNull('parent_id')->with('replies');
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeBreaking($query)
    {
        return $query->published()->where('is_breaking', true);
    }

    public function scopeFeatured($query)
    {
        return $query->published()->where('is_featured', true);
    }

    public function scopeTrending($query)
    {
        return $query->published()->orderBy('views_count', 'desc');
    }
}
