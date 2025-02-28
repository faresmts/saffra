<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use App\Models\MachineryMaintenance;
use App\Models\PropertyOutflow;
use App\Models\Sale;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class IncomeChart extends ChartWidget
{
    protected static ?string $heading = 'Evolução de Vendas Mensal';

    protected function getData(): array
    {
        $now = now();
        $monthRange = now()->subMonths(6)->startOfMonth();
        $months = [];

        while ($monthRange->isBefore($now)) {
            $months[] = $monthRange->copy()->format('Y-m-d');
            $monthRange->addMonth();
        }

        $data = [
            'labels' => [],
            'datasets' => [
                'income' => [],
                'expenses' => [],
                'profit' => [],
            ],
        ];

        foreach ($months as $month) {
            $date = Carbon::parse($month);
            $startDate = $date->startOfMonth()->startOfDay()->toDateTimeString();
            $endDate = $date->endOfMonth()->endOfDay()->toDateTimeString();

            $salesIncome = Sale::query()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get()
                ->sum('total_raw');

            $expensesFromEmployees = Employee::query()
                ->where('created_at', '>', $startDate)
                ->get()
                ->filter(fn ($employee) => $employee->created_at->isBefore($endDate))
                ->sum('raw_salary');

            $expensesFromMachinery = MachineryMaintenance::query()
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('cost');

            $expensesFromProperties = PropertyOutflow::query()
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('value');

            $expenses = $expensesFromEmployees + $expensesFromMachinery + $expensesFromProperties;
            $profit = $salesIncome - $expenses;


            $data['labels'][] = $date->format('m/Y');
            $data['datasets']['income'][] = $salesIncome;
            $data['datasets']['expenses'][] = $expenses;
            $data['datasets']['profit'][] = $profit;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Faturamento',
                    'data' => $data['datasets']['income'],
                ],
            ],
            'labels' => $data['labels'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
