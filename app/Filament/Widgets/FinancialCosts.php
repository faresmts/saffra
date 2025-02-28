<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use App\Models\MachineryMaintenance;
use App\Models\PropertyOutflow;
use App\Models\Sale;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Random\RandomException;

class FinancialCosts extends BaseWidget
{
    protected ?string $heading = 'Visão Geral';

    protected ?string $description = 'Visualização de entradas e saídas de todos os setores';
    protected static ?string $pollingInterval = '20s';

    /**
     * @throws RandomException
     */
    protected function getStats(): array
    {
        $salesIncome = Sale::query()
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->get()
            ->sum('total_raw');

        $expensesFromEmployees = Employee::query()
            ->sum('raw_salary');

        $expensesFromMachinery = MachineryMaintenance::query()
            ->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('cost');

        $expensesFromProperties = PropertyOutflow::query()
            ->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('value');

        $expenses = $expensesFromEmployees + $expensesFromMachinery + $expensesFromProperties;
        $profit = $salesIncome - $expenses;

        $expensesFormatted = 'R$ ' . number_format($expenses, 2, ',', '.');
        $profitFormatted = 'R$ ' . number_format($profit, 2, ',', '.');
        $salesIncomeFormatted = 'R$ ' . number_format($salesIncome, 2, ',', '.');

        $chartFakeData = [
            'income' => [],
            'expenses' => [],
            'profit' => [],
        ];

        for ($i = 0; $i < random_int(10, 50); $i++) {
            $chartFakeData['income'][] = random_int(1, 100);
            $chartFakeData['expenses'][] = random_int(1, 100);
            $chartFakeData['profit'][] = random_int(1, 100);
        }

        return [
            Stat::make('Entradas', $salesIncomeFormatted)
                ->color('primary')
                ->chart($chartFakeData['income'])
                ->icon('heroicon-o-currency-dollar'),


            Stat::make('Saídas', $expensesFormatted)
                ->color('danger')
                ->chart($chartFakeData['expenses'])
                ->icon('heroicon-o-currency-dollar'),


            Stat::make('Saldo', $profitFormatted)
                ->color($profit >= 0 ? 'success' : 'danger')
                ->chart($chartFakeData['profit'])
                ->icon($profit >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down'),
        ];
    }
}
