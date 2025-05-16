<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use HasFactory;

    // Anúncio pertence a um post
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
