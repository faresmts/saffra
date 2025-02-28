<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int indicator_id
 * @property float value
 * @property string date
 */
class IndicatorValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'indicator_id',
        'value',
        'date',
    ];

    public function indicator(): BelongsTo
    {
        return $this->belongsTo(Indicator::class);
    }
}
