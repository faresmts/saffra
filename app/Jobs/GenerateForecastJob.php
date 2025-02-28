<?php

namespace App\Jobs;

use App\Models\Indicator;
use App\Models\Supply;
use Carbon\Carbon;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GenerateForecastJob implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    public function __construct(
        public Carbon $date,
        public Supply $supply
    ) {}

    public function handle(): void
    {
        try {
            $startTime = now();

            Log::info("GenerateForecast: " . $this->date->format('d/m/Y') . " - " . $this->supply->id);

            $startDate = $this->date->copy();
            $endDate = $this->date->copy()->subDays(7);
            $daysAmount = 7;

            $payload = $this->buildPayload($startDate, $endDate, $daysAmount);

            $indicators = Indicator::query()
                ->get();

            $indicatorsData = $this->getVerifiedIndicators($startDate, $endDate, $indicators, $daysAmount);

            $salesData = $this->getVerifiedSales($startDate, $endDate, $daysAmount, $this->product->id);

            $payload = $this->buildPayload($startDate, $daysAmount, $salesData, $indicatorsData);

            [$forecast, $date] = $this->requestForecast($payload);

            $forecastModel = Forecast::query()
                ->where('product_id', $this->product->id)
                ->where('frequency', $this->frequency)
                ->where('sold_at', $date->format('Y-m-d'))
                ->first();

            if ($forecastModel) {
                $forecastModel->update([
                    'forecast' => $forecast['corrected'],
                    'previous_forecast' => $correctedForecast['current'],
                ]);
            } else {
                Forecast::query()
                    ->create([
                        'product_id' => $this->supply->id,
                        'sold_at' => $date->format('Y-m-d'),
                        'forecast' => $forecast['corrected'],
                    ]);
            }

            Notification::make()
                ->title('Previsão gerada com sucesso!')
                ->success()
                ->send();

            Log::info('Previsão gerada com sucesso!');


        } catch (Exception $e) {
            Notification::make()
                ->title('Algo deu errado ao gerar a previsão. Tente novamente mais tarde')
                ->warning()
                ->send();

            Log::error("GenerateForecast: " . $this->frequency . " - " . $this->date->format('d/m/Y') . " - " . $this->product->imported_id . " - " . $e->getMessage());
        }
    }

    private function buildPayload($startDate, $daysAmount, Collection $salesData, Collection $indicatorsData): array
    {
        $dailyData = [];

        for ($i = 0; $i < $daysAmount; $i++) {
            $date = $startDate->copy()->subDays($i);

            $sale = $salesData->where('operation_date', $date->format('Y-m-d 00:00:00'))->first();

            $dailyData[] = [
                'date' => $date->format('d/m/Y'),
                'sold' => $sale->n_events_day ?? 0,
                'value' => $sale->product_value ?? 0,
                'weekday' => $date->dayOfWeek(),
                'indicators' => $indicatorsData->mapWithKeys(fn(Indicator $indicator) => [
                    $indicator->description => $indicator->values->where('date', $date->format('Y-m-d'))->first()->value
                ])->toArray()
            ];
        }

        $targetDate = match ($this->frequency) {
            FrequencyEnum::DAILY->value => $this->date->copy()->format('d/m/Y'),
            FrequencyEnum::WEEKLY->value => $this->date->copy()->startOfWeek()->format('d/m/Y'),
            FrequencyEnum::MONTHLY->value => $this->date->copy()->startOfMonth()->format('d/m/Y'),
        };

        $payload = [
            'date' => $targetDate,
            'pkl_name' => $this->frequency . '_' . $this->product->imported_id,
            'frequency' => $this->frequency,
            'product_id' => $this->product->id,
            'imported_id' => $this->product->imported_id,
            'data' => $dailyData
        ];

        Log::info("payload: " . json_encode($payload, JSON_PRETTY_PRINT));

        return $payload;
    }

    /**
     * @throws Exception
     */
    private function requestForecast(array $payload): array
    {
        $parserUrl = config('services.parser.api');

        $response = Http::post($parserUrl . '/forecast', $payload)->json();

        if (isset($response['erro'])) {
            throw new Exception($response['erro']);
        }

        $forecast = $response['forecast'];
        $date = Carbon::parse($response['forecast_date']);

        return [$forecast, $date];
    }
}
