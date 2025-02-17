<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $fillable = [
        'name',
        'size',
        'is_active',
        'address',
    ];
}
