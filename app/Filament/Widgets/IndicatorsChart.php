<?php

namespace App\Filament\Widgets;

use App\Models\Indicator;
use App\Models\IndicatorValue;
use App\Support\Enums\IndicatorsEnum;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Livewire\Attributes\On;

class IndicatorsChart extends ChartWidget
{
    protected static ?string $heading = 'Selecione os indicadores na legenda';

    public ?string $filter = 'month';
    protected static ?string $pollingInterval = '1000s';

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        $endDate = match ($activeFilter) {
            'today' => now(),
            'week' => now()->subWeek(),
            'quarter' => now()->subQuarter(),
            'semester' => now()->subMonths(6),
            'year' => now()->subYear(),
            default => now()->subMonth(),
        };

        $startDate = now();

        $indicators = Indicator::all();

        $datasets = [];
        $labels = [];

        foreach ($indicators as $indicator) {
            $indicatorsValues = IndicatorValue::query()
                ->where('indicator_id', $indicator->id)
                ->whereBetween('date', [$endDate, $startDate]);

            $indicator->indicatorValuesFiltered = $indicatorsValues->pluck( 'value', 'date');

            $labels = $indicatorsValues->pluck('date')->toArray();

            $datasets[] = [
                'label' => $indicator->description,
                'data' => $indicatorsValues->pluck('value')->toArray(),
                'borderColor' => IndicatorsEnum::getChartColors($indicator->description),
                'backgroundColor' => IndicatorsEnum::getChartColors($indicator->description),
            ];
        }

        foreach ($labels as $key => $label) {
            $labels[$key] = Carbon::parse($label)->format('d/m/Y');
        }

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
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
}
