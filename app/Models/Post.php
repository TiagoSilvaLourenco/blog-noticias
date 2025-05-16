<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

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
