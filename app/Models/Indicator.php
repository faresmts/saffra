<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string description
 * @property string external_id
 */
class Indicator extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'external_id',
    ];

    public function values(): HasMany
    {
        return $this->hasMany(IndicatorValue::class);
    }
}
