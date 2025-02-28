<?php

namespace App\Console\Commands;

use App\Support\Enums\IndicatorsEnum;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use App\Models\Holiday;
use App\Models\Indicator;
use App\Models\IndicatorValue;
use PhpOffice\PhpSpreadsheet\Reader\Xls;

class IndicatorsImport extends Command
{
    protected $signature = 'indicators-import {first?}';

    protected $description = 'Import indicators from CEPEA/Esalq data';

    protected array $data = [];

    public function handle(): void
    {
        $firstIntegrating = $this->argument('first') == 'true';

        $startTime = now();
        try {
            $this->data[] = now()->format('Y-m-d H:i:s') . '- Starting indicators import...';
            $indicators = Indicator::all();

            foreach ($indicators as $indicator) {
                $this->data[] = 'Importing indicator: ' . $indicator->description;

                $isUpdated = IndicatorValue::query()
                    ->where('indicator_id', $indicator->id)
                    ->where('date', now()->format('Y-m-d'))
                    ->exists();

                if ($isUpdated) {
                    $this->data[] = 'Indicator Updated: ' . $indicator->description;
                    continue;
                }

                if (!$firstIntegrating && (now()->isWeekend() || $this->isHoliday(now()))) {
                    $this->data[] = 'Weekend or holiday, updating indicators...';
                    $this->updateNonWorkingDays($indicator);
                }

                if (!$firstIntegrating && now()->isMonday()) {
                    $this->updateWeekend($indicator);
                }

                $this->data[] = 'Requesting API for indicator: ' . $indicator->description;
                $xlsPath = $this->callApi($indicator);

                if ($xlsPath) {
                    $this->data[] = 'Importing values for indicator: ' . $indicator->description;

                    $this->storeValues($indicator, $xlsPath);
                } else {
                    $this->data[] = 'Indicator Not Updated: ' . $indicator->description;
                }
            }

            $this->data[] = now()->format('Y-m-d H:i:s') . '- Finishing indicators import';

        } catch (Exception $e) {
            $this->data[] = 'Error: ' . $e->getMessage();
        } finally {
            $endTime = now();
            $this->data[] = 'Execution time: ' . $endTime->diffForHumans($startTime);
            $this->info(implode(PHP_EOL, $this->data));
        }
    }

    private function isValidDate(?string $date, string $format = 'Y-m-d'): bool
    {
        if (!$date) {
            return false;
        }

        try {
            Carbon::createFromFormat($format, $date);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    private function callApi(Indicator $indicator): string
    {
        $apiUrl = config('services.cepea.api');

        $lastIndicatorDate = IndicatorValue::query()
            ->where('indicator_id', $indicator->id)
            ->orderBy('date', 'desc')
            ->first();

        $lastIndicatorDate = $lastIndicatorDate
            ? Carbon::parse($lastIndicatorDate->date)->addDay()
            : now()->subYear()->startOfYear();

        if ($lastIndicatorDate->isToday()) {
            return '';
        }

        $response = Http::get($apiUrl, [
            'tabela_id' => $indicator->external_id,
            'data_inicial' => $lastIndicatorDate->format('d/m/Y'),
            'periodicidade' => 1,
            'data_final' => now()->subDay()->format('d/m/Y')
        ])->json();

        if (isset($response['arquivo'])) {
            $dataUri = $response['arquivo'];

            $xlsResponse = Http::get($dataUri);

            $filePath = storage_path("app/public/{$indicator->description}.xls");
            file_put_contents($filePath, $xlsResponse->body());

            return $filePath;
        } else {
            $this->warn("Field \"arquivo\" not found in JSON response - {$indicator->description}");
        }

        return '';
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    private function storeValues(Indicator $indicator, string $xlsPath): void
    {
        $filePath = $xlsPath;

        $reader = new Xls();
        $spreadsheet = $reader->load($filePath);
        $worksheetInfo = $reader->listWorksheetInfo($filePath);

        $sheetName = $worksheetInfo[0]['worksheetName'];

        $spreadsheet->setActiveSheetIndexByName($sheetName);

        $sheet = $spreadsheet->getActiveSheet();

        $sheetData = [];

        foreach ($sheet->getRowIterator() as $row) {
            $rowData = [];
            foreach ($row->getCellIterator() as $cell) {
                $cellValue = $cell->getValue();
                $rowData[] = $cellValue;
            }

            if ($this->isValidDate($rowData[0], 'd/m/Y')) {
                $registerDate = Carbon::createFromFormat('d/m/Y', $rowData[0]);

                $columnPosition = IndicatorsEnum::isSecondColumn($indicator) ? 2 : 1;
                $value = str_replace(',', '.', $rowData[$columnPosition]);

                if (count($sheetData) > 1) {
                    $lastRow = $sheetData[count($sheetData) - 1];
                    $lastDate = Carbon::parse($lastRow['date']);
                    $diffInDays = (int) $lastDate->diffInDays($registerDate);

                    if ($diffInDays > 1) {
                        for ($i = 0; $i < $diffInDays - 1; $i++) {
                            $date = $lastDate->addDay();

                            $sheetData[] = [
                                'indicator_id' => $indicator->id,
                                'date' => $date->format('Y-m-d'),
                                'value' => $lastRow['value'],
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                        }
                    }
                }

                $sheetData[] = [
                    'indicator_id' => $indicator->id,
                    'date' => $registerDate->format('Y-m-d'),
                    'value' => $value,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        IndicatorValue::query()
            ->insert($sheetData);

        $this->data[] = 'Values imported successfully. Indicator: ' . $indicator->description;
    }

    private function isHoliday(Carbon $date): bool
    {
        return Holiday::query()
            ->where('date', $date->format('Y-m-d'))
            ->exists();
    }

    private function updateNonWorkingDays(Indicator $indicator): void
    {
        /** @var IndicatorValue $lastIndicator */
        $lastIndicator = IndicatorValue::query()
            ->where('indicator_id', $indicator->id)
            ->orderBy('date', 'desc')
            ->first();

        $lastIndicatorDate = Carbon::parse($lastIndicator->date);

        $diffInDays = (int) $lastIndicatorDate->diffInDays(now());

        for ($i = 0; $i < $diffInDays; $i++) {
            $lastIndicatorDate->addDay();

            if ($lastIndicatorDate->isWeekend() || $this->isHoliday($lastIndicatorDate)) {
                IndicatorValue::query()
                    ->create([
                        'indicator_id' => $indicator->id,
                        'date' => ($lastIndicatorDate->format('Y-m-d')),
                        'value' => $lastIndicator->value,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
            }
        }

        $this->data[] = 'Values updated for non working days! Indicator: ' . $indicator->description;
    }


    private function updateWeekend(Indicator $indicator): void
    {
        $lastIndicator = IndicatorValue::query()
            ->where('indicator_id', $indicator->id)
            ->orderBy('date', 'desc')
            ->first();

        $lastIndicatorDate = Carbon::parse($lastIndicator->date);

        $diffInDays = (int) $lastIndicatorDate->diffInDays(now());

        for ($i = 0; $i < $diffInDays; $i++) {
            $lastIndicatorDate->addDay();

            if ($lastIndicatorDate->isWeekend()) {
                IndicatorValue::query()
                    ->create([
                        'indicator_id' => $indicator->id,
                        'date' => ($lastIndicatorDate->format('Y-m-d')),
                        'value' => $lastIndicator->value,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
            }
        }

        $this->data[] = 'Values updated for weekend! Indicator: ' . $indicator->description;
    }
}
