<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'supply_id',
        'quantity',
        'payer',
        'sold_at'
    ];

    public function supply(): BelongsTo
    {
        return $this->belongsTo(Supply::class);
    }

    public function getTotalAttribute(): string
    {
        return 'R$ ' . number_format($this->supply->price * $this->quantity, 2, ',', '.');
    }

    public function getTotalRawAttribute(): float
    {
        return $this->supply->price * $this->quantity;
    }
}
