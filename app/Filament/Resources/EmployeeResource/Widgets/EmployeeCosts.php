<?php

namespace App\Filament\Resources\EmployeeResource\Widgets;

use App\Models\Employee;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EmployeeCosts extends BaseWidget
{
    protected function getStats(): array
    {
        $rawSalaries = Employee::sum('raw_salary');
        $rawSalariesFormatted = number_format($rawSalaries, 2, ',', '.');

        $salaries = Employee::sum('salary');

        $taxesAmount = $rawSalaries - $salaries;
        $taxesAmountFormatted = number_format($taxesAmount, 2, ',', '.');

        return [
            Stat::make('Total em Salários Brutos', 'R$' . $rawSalariesFormatted),
            Stat::make('Total de Encargos', 'R$' . $taxesAmountFormatted),
            Stat::make('Quadro de Funcionários', Employee::count()),
        ];
    }
}
