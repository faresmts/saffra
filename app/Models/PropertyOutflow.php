<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyOutflow extends Model
{
    protected $fillable = [
        'property_id',
        'description',
        'date',
        'value',
    ];

    /**
     * Get the associated property
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}
