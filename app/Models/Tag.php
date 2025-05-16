<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    // Tag pertence a muitos posts (pivot post_tag)
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
}
