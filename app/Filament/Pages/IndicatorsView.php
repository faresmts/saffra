<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class IndicatorsView extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static string $view = 'filament.pages.indicators-view';

    protected static ?string $title = 'Indicadores Econômicos';

    protected static ?string $navigationGroup = 'Financeiro';
}
