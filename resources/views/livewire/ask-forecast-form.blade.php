<div>
    <form wire:submit="dispatchForecast" class="flex flex-col gap-4">
        <div>
            {{ $this->form }}
        </div>

        <div>
            <x-filament::button
                size="lg"
                type="submit"
                icon="heroicon-o-chart-bar"
            >
                Pedir previsão
            </x-filament::button>
        </div>
    </form>
</div>
