<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'thumbnail' => $this->thumbnail_url ?? $this->thumbnail,
            'status' => $this->status,
            'is_breaking' => (bool)$this->is_breaking,
            'published_at' => $this->published_at?->toIso8601String(),
            'views_count' => (int)$this->views_count,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'author' => [
                'id' => $this->author?->id,
                'name' => $this->author?->name,
                'avatar' => $this->author?->avatar,
            ],
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
