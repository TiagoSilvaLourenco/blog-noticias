<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'cover' => $this->cover,
            'published_at' => $this->published_at,
            'is_featured' => $this->is_featured,
            'tags' => $this->tags->map->only(['id', 'name', 'slug']),
            'category' => $this->category
                ? $this->category->only(['id', 'name', 'slug'])
                : null,

            'author' => $this->user
                ? [
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                ]
                : null,

        ];
    }
}
