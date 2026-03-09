<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $date = $this->published_at ?? $this->created_at ?? now();

        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'cover' => $this->cover ? asset('storage/' . $this->cover) : null,
            'published_at' => $date->format('d/m/Y H:i'),
            'is_featured' => $this->is_featured,
            'tags' => $this->tags->map->only(['id', 'name', 'slug']),
            'category' => $this->category
                ? $this->category->only(['id', 'name', 'slug'])
                : null,

            'author' => $this->user
                ? [
                    'name' => $this->author?->name ?? 'Autor Desconhecido',
                    'email' => $this->user->email,
                ]
                : null,

        ];
    }
}
