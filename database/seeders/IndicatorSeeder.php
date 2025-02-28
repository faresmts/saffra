<?php

namespace Database\Seeders;

use App\Models\Indicator;
use App\Support\Enums\IndicatorsEnum;
use Illuminate\Database\Seeder;

class IndicatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (IndicatorsEnum::indicators() as $indicator) {
            Indicator::query()
                ->create($indicator);
        }
    }
}
