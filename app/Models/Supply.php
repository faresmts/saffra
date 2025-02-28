<?php

namespace App\Models;

use App\Support\Enums\UnityEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supply extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'stock',
        'price',
        'unit'
    ];

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'R$ ' . number_format($this->price, 2, ',', '.');
    }

    public function getFormattedUnitAttribute(): string
    {
        return UnityEnum::translate(UnityEnum::from($this->unit));
    }

    public function getFormattedFullNameAttribute(): string
    {
        return "{$this->name} ({$this->formattedPrice} / {$this->formattedUnit})";
    }
}
