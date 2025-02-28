<x-filament-panels::page>
    <x-filament::modal width="5xl">
        <x-slot name="trigger">
            <button
                class="button"
                type="submit">
                Nova previsão
            </button>
        </x-slot>

        <x-slot name="heading">
            Pedir previsão
        </x-slot>

        @livewire(\App\Livewire\AskForecastForm::class)

    </x-filament::modal>

    @livewire(App\Filament\Widgets\ForecastsChart::class)

    <style type="text/css">
        .chart {
            max-height: 300px;
            border-radius: 12px;
            padding: 16px 0;
        }

        .button {
            background-color: rgba(132, 204, 22, 1);
            padding: 12px 16px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.85rem;
            line-height: 1.5;
        }
    </style>
</x-filament-panels::page>
