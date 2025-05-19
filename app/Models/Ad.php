<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'html_code',
        'type',
        'link',
        'is_active',
        'start_at',
        'end_at',
    ];


    /**
     * Relação many-to-many com posições
     */
    public function positions()
    {
        return $this->belongsToMany(Position::class)
                    ->withPivot('post_id')
                    ->withTimestamps();
    }

    /**
     * Anúncio pode ser exclusivo para um post
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
