<?php

namespace App\Filament\Widgets;

use App\Models\Forecast;
use App\Models\Indicator;
use App\Models\IndicatorValue;
use App\Models\Sale;
use App\Support\Enums\IndicatorsEnum;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class ForecastsChart extends ChartWidget
{
    protected static ?string $heading = 'Previsões';

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        $endDate = match ($activeFilter) {
            'today' => now()->addDay(),
            'week' => now()->subWeek(),
            'quarter' => now()->subQuarter(),
            'semester' => now()->subMonths(6),
            'year' => now()->subYear(),
            default => now()->subMonth(),
        };

        $startDate = now();

        $forecasts = Forecast::query()
            ->leftJoin('sales', 'sales.sold_at', '=', 'forecasts.date')
            ->whereBetween('forecasts.date', [$endDate, $startDate])
            ->orderBy('forecasts.date')
            ->get();

        $datasets = [];
        $labels = [];

        foreach ($forecasts as $forecast) {
            $labels[] = Carbon::parse($forecast->date)->format('d/m/Y');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Previsão',
                    'data' => $forecasts->pluck('forecast')->toArray(),
                    'borderColor' => '#4c51bf',
                ],
                [
                    'label' => 'Vendido',
                    'data' => $forecasts->pluck('quantity')->toArray(),
                    'borderColor' => '#f56565',
                ]
            ],
            'labels' => $labels,
        ];
    }

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Hoje',
            'week' => 'Última semana',
            'month' => 'Último mês',
            'quarter' => 'Último trimestre',
            'semester' => 'Último semestre',
            'year' => 'Este ano',
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
