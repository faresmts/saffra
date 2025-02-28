<?php

namespace App\Console\Commands;

use App\Jobs\GenerateForecastJob;
use App\Models\Supply;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ScheduleForecasts extends Command
{
    protected $signature = '
        schedule-forecasts
        {--supply=}
        {--frequency=}
        {date?}
    ';

    protected $description = 'Schedule forecasts for one or all supplys';

    public function handle(): void
    {
        $supply = $this->option('supply');
        $date = $this->argument('date');


        $date = $date ? Carbon::parse($date) : Carbon::now()->addDay();

        Log::info(
            'Scheduling forecasts - Supply: ' . $supply
            . ' - Date: ' . $date
        );

        $supplies = $supply
            ? Supply::query()->where('id', $supply)->get()
            : Supply::all();

        foreach ($supplies as $supply) {
            GenerateForecastJob::dispatchSync($date, $supply);
        }

        Log::info('Forecasts scheduled - Supplys:' . $supplies->count());
    }
}
