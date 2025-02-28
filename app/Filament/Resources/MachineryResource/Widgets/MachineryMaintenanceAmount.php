<?php

namespace App\Filament\Resources\MachineryResource\Widgets;

use App\Filament\Resources\MachineryResource\Pages\ListMachineries;
use App\Models\Machinery;
use App\Models\MachineryMaintenance;
use Carbon\Carbon;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;

class MachineryMaintenanceAmount extends ChartWidget
{
    use ExposesTableToWidgets;
    use InteractsWithPageTable;

    protected static ?string $heading = 'Manutenções por mês';

    protected function getData(): array
    {
        $maintenancePerMonth = MachineryMaintenance::query()
            ->selectRaw('strftime("%m/%Y", date) as month, COUNT(*) as count')
            ->whereBetween('date', [Carbon::now()->subMonths(6), Carbon::now()->addMonths(6)])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Manutenções',
                    'data' => $maintenancePerMonth->pluck('count')->toArray(),
                ],
            ],
            'labels' => $maintenancePerMonth->pluck('month')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getTablePage(): string
    {
        return ListMachineries::class;
    }
}
