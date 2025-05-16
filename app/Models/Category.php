<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
     use HasFactory;

    // Categoria tem muitos posts
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
