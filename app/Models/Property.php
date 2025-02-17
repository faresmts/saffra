<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function propertyOutflows(): HasMany
    {
        return $this->hasMany(PropertyOutflow::class);
    }
}
