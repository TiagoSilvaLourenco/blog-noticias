<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'cover',
        'status',
        'published_at',
        'is_featured',
        'comments_enabled',
        'comments_count',
        'user_id',
        'category_id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    // Autor do post
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Categoria principal do post
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Tags associadas ao post
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    // Comentários do post
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Anúncios vinculados ao post
    public function ads()
    {
        return $this->hasMany(Ad::class);
    }
}
