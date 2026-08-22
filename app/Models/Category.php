<?php

/**
 * Tujuan: Model Eloquent entitas Kategori berita
 * Caller: CategoryController, CategoryCrudController, ArticleController
 * Dependensi: Spatie\Activitylog, Backpack\CRUD, Illuminate\Support\Str
 * Main Functions: parent(), children(), articles(), boot()
 * Side Effects: DB Read/Write tabel categories, activity_log
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Category extends Model
{
    use HasFactory, LogsActivity, CrudTrait;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (blank($category->slug)) {
                $category->slug = Str::slug($category->name);
            } else {
                $category->slug = Str::slug($category->slug);
            }
        });

        static::updating(function ($category) {
            if (blank($category->slug)) {
                $category->slug = Str::slug($category->name);
            } elseif ($category->isDirty('slug')) {
                $category->slug = Str::slug($category->slug);
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'slug', 'description'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }
}
