<?php

namespace Database\Seeders;

use App\Models\Forecast;
use App\Models\Sale;
use App\Models\Supply;
use Illuminate\Database\Seeder;

class ForecastSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $startDate = now();
        $endDate = now()->subMonths(6)->startOfMonth();

        $supply = Supply::query()->first();

        while ($endDate->lte($startDate)) {

            $sale = Sale::query()
            ->create([
                'supply_id' => $supply->id,
                'sold_at' => $endDate->format('Y-m-d'),
                'quantity' => random_int(1, 1000),
                'payer' => 'Fares'
            ]);

            Forecast::query()
            ->create([
                'supply_id' => $supply->id,
                'date' => $endDate->format('Y-m-d'),
                'forecast' => floor($sale->quantity * 1.1)
            ]);


            $endDate->addDay();
        }


    }
}
