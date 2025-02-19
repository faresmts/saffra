<?php

namespace App\Filament\Resources\EmployeeResource\Widgets;

use App\Models\Employee;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class SalaryStats extends BaseWidget
{
    protected function getStats(): array
    {
        $totalBruto = Employee::sum('salario_bruto');
        $totalEncargos = Employee::sum(DB::raw('salario_bruto - salario_liquido'));

        return [
            Stat::make('Custo Total Salários', 'R$ ' . number_format($totalBruto, 2, ',', '.'))
                ->description('Total de salários brutos')
                ->color('success'),
                
            Stat::make('Custo Total Encargos', 'R$ ' . number_format($totalEncargos, 2, ',', '.'))
                ->description('Encargos trabalhistas')
                ->color('danger'),
        ];
    }
}