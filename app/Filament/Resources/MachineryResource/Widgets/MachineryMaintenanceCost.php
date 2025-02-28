<?php

namespace App\Filament\Resources\MachineryResource\Widgets;

use App\Models\MachineryMaintenance;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MachineryMaintenanceCost extends BaseWidget
{
    protected function getStats(): array
    {
        $monthlyMaintenance = MachineryMaintenance::query()
            ->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()]);

        $monthlyMaintenanceCost = $monthlyMaintenance->sum('cost');
        $monthlyMaintenanceCostFormatted = 'R$ ' . number_format($monthlyMaintenanceCost, 2, ',', '.');

        $nextMonthlyMaintenance = MachineryMaintenance::query()
            ->whereBetween('date', [now()->addMonth()->startOfMonth(), now()->addMonth()->endOfMonth()]);

        return [
            Stat::make('Manutenções esse mês', $monthlyMaintenance->count()),
            Stat::make('Total em manutenções do mês', $monthlyMaintenanceCostFormatted),
            Stat::make('Manutenções no próximo mês', $nextMonthlyMaintenance->count())
        ];
    }
}
