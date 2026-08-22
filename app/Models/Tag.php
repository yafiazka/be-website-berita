<?php

/**
 * Tujuan: Model Eloquent entitas Tag berita
 * Caller: TagController, TagCrudController, ArticleController
 * Dependensi: Spatie\Activitylog, Backpack\CRUD, Illuminate\Support\Str
 * Main Functions: articles(), boot()
 * Side Effects: DB Read/Write tabel tags, activity_log
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Tag extends Model
{
    use HasFactory, LogsActivity, CrudTrait;

    protected $fillable = [
        'name',
        'slug',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            if (blank($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            } else {
                $tag->slug = Str::slug($tag->slug);
            }
        });

        static::updating(function ($tag) {
            if (blank($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            } elseif ($tag->isDirty('slug')) {
                $tag->slug = Str::slug($tag->slug);
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'slug'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_tag');
    }
}
