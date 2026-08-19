<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'article_id' => $this->article_id,
            'parent_id' => $this->parent_id,
            'name' => $this->user ? $this->user->name : $this->name,
            'avatar' => $this->user ? $this->user->avatar : null,
            'content' => $this->content,
            'status' => $this->status,
            'replies' => CommentResource::collection($this->whenLoaded('replies')),
            'article' => new ArticleListResource($this->whenLoaded('article')),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
