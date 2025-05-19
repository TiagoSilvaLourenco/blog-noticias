<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
     use HasFactory;

    protected $fillable = [
        'code',
        'label',
        'width',
        'height',
    ];

    public function getLabelWithDimensionsAttribute(): string
    {
        return "{$this->label} ({$this->width}×{$this->height})";
    }

    /**
     * Anúncios relacionados a esta posição
     */
    public function ads()
    {
        return $this->belongsToMany(Ad::class)
                    ->withPivot('post_id')
                    ->withTimestamps();
    }
}
