<?php

namespace App\Livewire;

use App\Jobs\GenerateForecastJob;
use App\Models\Forecast;
use App\Models\IndicatorValue;
use App\Models\Sale;
use App\Models\Supply;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Component;
use Random\RandomException;

class AskForecastForm extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'supply' => Supply::query()->first()->id ?? 1,
            'target_date' => now(),
        ]);
    }

    public function form(Form $form): Form
    {
        $items = Supply::all()
            ->pluck('name', 'id');

        return $form
            ->schema([
                Grid::make()->schema([
                    Select::make('supply')
                        ->label('Insumo')
                        ->options($items)
                        ->default($items->keys()->first())
                        ->searchable()
                        ->columnSpan(2),

                    DatePicker::make('target_date')
                        ->label('Data')
                        ->default(now()->format('Y-m-d'))
                        ->columnSpan(1),

                ])->columns(3),

            ])
            ->statePath('data');
    }

    /**
     * @throws RandomException
     */
    public function dispatchForecast(): void
    {
        $data = $this->form->getState();

        $supply = Supply::query()->find($data['supply']);
        $targetDate = Carbon::parse($data['target_date']);

        $indicator = IndicatorValue::query()
            ->where('date', '=', now()->subDays(2)->format('Y-m-d'))
            ->first();

        if (! $indicator) {
            Notification::make()
                ->title('Dados de indicadores insuficientes para pedir essa previsão')
                ->warning()
                ->send();

            return;
        }

        $sale = Sale::query()
            ->where('supply_id', $supply->id)
            ->where('sold_at', '=', now()->subDays(2)->format('Y-m-d'))
            ->first();

        if (! $sale) {
            Notification::make()
                ->title('Dados de vendas insuficientes para pedir essa previsão')
                ->warning()
                ->send();

            return;
        }

// TODO: Dispatch Job when the system is ready with trained models
//        GenerateForecastJob::dispatch(
//            $targetDate,
//            $supply
//        );

        Forecast::query()
            ->updateOrInsert([
                'supply_id' => $supply->id,
                'date' => $targetDate->format('Y-m-d'),
            ], [
                'forecast' => $sale->quantity * (random_int(1, 5) / random_int(1, 5)),
            ]);

        $forecast = Forecast::query()
            ->where('supply_id', $supply->id)
            ->where('date', $targetDate)
            ->first();

        Notification::make()
            ->title('Previsão solicitada com sucesso!')
            ->success()
            ->send();
    }

    public function render(): Factory|Application|View
    {
        return view('livewire.ask-forecast-form');
    }
}
