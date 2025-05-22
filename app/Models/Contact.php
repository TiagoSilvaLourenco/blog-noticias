<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'type',
        'message',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function getTypeAttribute($value): string
    {
        return match ($value) {
            'advertise' => 'Anunciar',
            'contact'   => 'Contato',
            default     => ucfirst($value),
        };
    }

}
