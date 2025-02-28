<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MachineryMaintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'machinery_id',
        'date',
        'cost',
    ];

    public function machinery(): BelongsTo
    {
        return $this->belongsTo(Machinery::class);
    }
}
