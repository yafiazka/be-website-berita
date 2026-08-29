<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'thumbnail' => $this->thumbnail_url ?? $this->thumbnail,
            'thumbnail_url' => $this->thumbnail_url ?? $this->thumbnail,
            'video_url' => $this->video_url,
            'author_source' => $this->author_source ?? $this->author?->name,
            'source' => $this->source,
            'status' => $this->status,
            'is_featured' => (bool)$this->is_featured,
            'is_breaking' => (bool)$this->is_breaking,
            'published_at' => $this->published_at?->toIso8601String(),
            'meta_title' => $this->meta_title ?? $this->title,
            'meta_description' => $this->meta_description ?? $this->excerpt,
            'og_image' => $this->og_image ? (str_starts_with($this->og_image, 'http') ? $this->og_image : asset('storage/' . ltrim($this->og_image, '/'))) : ($this->thumbnail_url ?? $this->thumbnail),
            'views_count' => (int)$this->views_count,
            'likes_count' => $this->likes()->count(),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'author' => [
                'id' => $this->author?->id,
                'name' => $this->author_source ?? $this->author?->name ?? 'Redaksi',
                'avatar' => $this->author?->avatar,
            ],
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'comments' => CommentResource::collection($this->whenLoaded('approvedComments')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
