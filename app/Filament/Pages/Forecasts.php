<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Forecasts extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $title = 'Previsões';
    protected static string $view = 'filament.pages.forecasts';
}
