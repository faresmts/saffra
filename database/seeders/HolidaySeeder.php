<?php

namespace Database\Seeders;

use App\Models\Holiday;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HolidaySeeder extends Seeder
{
    public function run(): void
    {
        $holidays = [
            [
                'date' => '2024-01-01',
                'description' => 'Confraternização Universal (feriado nacional)'
            ],
            [
                'date' => '2024-02-12',
                'description' => 'Carnaval (ponto facultativo)'
            ],
            [
                'date' => '2024-02-13',
                'description' => 'Carnaval (ponto facultativo)'
            ],
            [
                'date' => '2024-02-14',
                'description' => 'Quarta-Feira de Cinzas (ponto facultativo até as 14h)'
            ],
            [
                'date' => '2024-03-29',
                'description' => 'Paixão de Cristo (feriado nacional)'
            ],
            [
                'date' => '2024-04-21',
                'description' => 'Tiradentes (feriado nacional)'
            ],
            [
                'date' => '2024-05-01',
                'description' => 'Dia Mundial do Trabalho (feriado nacional)'
            ],
            [
                'date' => '2024-05-30',
                'description' => 'Corpus Christi (ponto facultativo)'
            ],
            [
                'date' => '2024-05-31',
                'description' => 'Ponto facultativo'
            ],
            [
                'date' => '2024-09-07',
                'description' => 'Independência do Brasil (feriado nacional)'
            ],
            [
                'date' => '2024-10-12',
                'description' => 'Nossa Senhora Aparecida (feriado nacional)'
            ],
            [
                'date' => '2024-10-28',
                'description' => 'Dia do Servidor Público Federal (ponto facultativo)'
            ],
            [
                'date' => '2024-11-02',
                'description' => 'Finados (feriado nacional)'
            ],
            [
                'date' => '2024-11-15',
                'description' => 'Proclamação da República (feriado nacional)'
            ],
            [
                'date' => '2024-11-20',
                'description' => 'Dia Nacional de Zumbi e da Consciência Negra (feriado nacional)'
            ],
            [
                'date' => '2024-12-24',
                'description' => 'Véspera do Natal (ponto facultativo após as 14h)'
            ],
            [
                'date' => '2024-12-25',
                'description' => 'Natal (feriado nacional)'
            ],
            [
                'date' => '2024-12-31',
                'description' => 'Véspera do Ano Novo (ponto facultativo após as 14h)'
            ],
            [
                'date' => '2025-01-01',
                'description' => 'Confraternização Universal (feriado nacional)'
            ],
            [
                'date' => '2025-03-03',
                'description' => 'Carnaval (ponto facultativo)'
            ],
            [
                'date' => '2025-03-04',
                'description' => 'Carnaval (ponto facultativo)'
            ],
            [
                'date' => '2025-04-18',
                'description' => 'Paixão de Cristo (feriado nacional)'
            ],
            [
                'date' => '2025-04-21',
                'description' => 'Tiradentes (feriado nacional)'
            ],
            [
                'date' => '2025-05-01',
                'description' => 'Dia do Trabalho (feriado nacional)'
            ],
            [
                'date' => '2025-06-19',
                'description' => 'Corpus Christi (ponto facultativo)'
            ],
            [
                'date' => '2025-09-07',
                'description' => 'Independência do Brasil (feriado nacional)'
            ],
            [
                'date' => '2025-10-12',
                'description' => 'Nossa Senhora Aparecida - Padroeira do Brasil (feriado nacional)'
            ],
            [
                'date' => '2025-11-02',
                'description' => 'Finados (feriado nacional)'
            ],
            [
                'date' => '2025-11-15',
                'description' => 'Proclamação da República (feriado nacional)'
            ],
            [
                'date' => '2025-11-20',
                'description' => 'Dia Nacional de Zumbi e da Consciência Negra (feriado nacional)'
            ],
            [
                'date' => '2025-12-25',
                'description' => 'Natal (feriado nacional)'
            ]
        ];


        foreach ($holidays as $holiday) {
            Holiday::query()
                ->create($holiday);
        }
    }
}
