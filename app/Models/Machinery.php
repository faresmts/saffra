<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Machinery extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'manufacturer',
        'fabricated_at',
        'purchased_at',
        'cost',
    ];

    public function maintenance(): HasMany
    {
        return $this->hasMany(MachineryMaintenance::class);
    }

    public function getMaintenanceAmountAttribute($value)
    {
        return $this->maintenance->count();
    }

    public function getMaintenanceCostAttribute($value)
    {
        return 'R$ ' . number_format($this->maintenance->sum('cost'), 2, ',', '.');
    }
}
