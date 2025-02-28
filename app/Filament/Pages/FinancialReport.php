<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\FinancialCosts;
use App\Filament\Widgets\IncomeChart;
use App\Filament\Widgets\ProfitChart;
use Filament\Pages\Page;

class FinancialReport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static string $view = 'filament.pages.finalcial-report';
    protected static ?string $navigationLabel = 'Relatório Financeiro';
    protected static ?string $title = 'Relatório Financeiro';
    protected static ?string $navigationGroup = 'Financeiro';


    public function getHeaderWidgets(): array
    {
        return [
            FinancialCosts::class,
            IncomeChart::class,
            ProfitChart::class,
        ];
    }
}
