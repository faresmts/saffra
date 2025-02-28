<?php

namespace App\Livewire;

use App\Support\Enums\IndicatorsEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Component;

class IndicatorsViewForm extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('indicator')
                    ->label('Indicadores')
                    ->reactive()
                    ->multiple()
                    ->native(false)
                    ->afterStateUpdated(function () {
                        $this->dispatch("form-updated.['teste', 'test'].2025-01-01.2025-02-25");
                    })
                    ->options(IndicatorsEnum::options()),

                DatePicker::make('start_date')
                    ->label('Data Inicial')
                    ->reactive()
                    ->required()
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->default(now()),

                DatePicker::make('end_date')
                    ->label('Data Final')
                    ->reactive()
                    ->required()
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->default(now()->subMonths(2)->startOfMonth()),
            ])
            ->columns(3)
            ->statePath('data');
    }

    public function render(): View|Application
    {
        return view('livewire.indicators-view-form');
    }
}
