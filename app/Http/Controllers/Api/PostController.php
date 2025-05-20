<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['author', 'category', 'tags'])
            ->where('status', 'published')
            ->orderByDesc('published_at');

        if ($search = $request->query('search')) {
            $query->where('title', 'like', "%$search%");
        }

        if ($category = $request->query('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $category));
        }

        if ($tag = $request->query('tag')) {
            $query->whereHas('tags', fn ($q) => $q->where('slug', $tag));
        }

        return PostResource::collection($query->paginate(10));
    }

    public function show(Post $post)
    {
        return PostResource::make($post);
    }
}
