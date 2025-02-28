<?php

namespace App\Support\Enums;

use App\Models\Indicator;

enum IndicatorsEnum: string
{
    case BOI_GORDO = 'boi-gordo-diario';
    case SOJA = 'soja';
    case MILHO_REAL = 'milho-diario';
    case MILHO_DOLAR = 'milho-diario-dolar';
    case DOLAR_DIARIO = 'dolar-diario';
    case FRANGO_CONGELADO_DIARIO = 'frango-congelado-diario';
    case FRANGO_RESFRIADO_DIARIO = 'frango-resfriado-diario';
    case SUINO_DIARIO = 'suino-diario';
    case SOJA_DIARIO_DOLAR = 'soja-diario-dolar';
    case SOJA_DIARIO_REAL = 'soja-diario-real';

    public static function isSecondColumn(Indicator $indicator): bool
    {
        return in_array($indicator->description, [
            self::MILHO_DOLAR->value,
            self::SOJA_DIARIO_DOLAR->value
        ]);
    }

    public static function indicators(): array
    {
        return [
            [
                'description' => self::BOI_GORDO->value,
                'external_id' => 2,
            ],
            [
                'description' => self::SOJA->value,
                'external_id' => 12,
            ],
            [
                'description' => self::MILHO_REAL->value,
                'external_id' => 77,
            ],
            [
                'description' => self::MILHO_DOLAR->value,
                'external_id' => 77,
            ],
            [
                'description' => self::DOLAR_DIARIO->value,
                'external_id' => 'dolar',
            ],
            [
                'description' => self::FRANGO_CONGELADO_DIARIO->value,
                'external_id' => 181,
            ],
            [
                'description' => self::FRANGO_RESFRIADO_DIARIO->value,
                'external_id' => 130,
            ],
            [
                'description' => self::SUINO_DIARIO->value,
                'external_id' => 124,
            ],
            [
                'description' => self::SOJA_DIARIO_DOLAR->value,
                'external_id' => 12,
            ],
            [
                'description' => self::SOJA_DIARIO_REAL->value,
                'external_id' => 12,
            ],
        ];
    }

    public static function options(): array
    {
        return [
            self::BOI_GORDO->value => 'Boi Gordo',
            self::SOJA->value => 'Soja',
            self::MILHO_REAL->value => 'Milho Real',
            self::MILHO_DOLAR->value => 'Milho Dólar',
            self::DOLAR_DIARIO->value => 'Dólar',
            self::FRANGO_CONGELADO_DIARIO->value => 'Frango Congelado',
            self::FRANGO_RESFRIADO_DIARIO->value => 'Frango Resfriado',
            self::SUINO_DIARIO->value => 'Suíno',
            self::SOJA_DIARIO_DOLAR->value => 'Soja Dólar',
            self::SOJA_DIARIO_REAL->value => 'Soja Real',
        ];
    }

    public static function getChartColors(string $indicator): string
    {
        return match ($indicator) {
            self::BOI_GORDO->value => '#8B4513',
            self::SOJA->value => '#C2B280',
            self::MILHO_REAL->value => '#FFD700',
            self::MILHO_DOLAR->value => '#FFD700',
            self::DOLAR_DIARIO->value => '#228B22',
            self::FRANGO_CONGELADO_DIARIO->value => '#ADD8E6',
            self::FRANGO_RESFRIADO_DIARIO->value => '#F08080',
            self::SUINO_DIARIO->value => '#FFC0CB',
            self::SOJA_DIARIO_DOLAR->value => '#C2B280',
            self::SOJA_DIARIO_REAL->value => '#C2B280',
        };
    }
}
