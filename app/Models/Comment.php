<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    // Comentário pertence a um post
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
