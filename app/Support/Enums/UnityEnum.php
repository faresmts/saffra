<?php

namespace App\Support\Enums;

enum UnityEnum: string
{
    case KG = 'kg';
    case L = 'l';
    case UN = 'un';
    case M = 'm';
    case M2 = 'm²';
    case M3 = 'm³';
    case CM = 'cm';
    case CM2 = 'cm²';
    case CM3 = 'cm³';
    case MM = 'mm';
    case MM2 = 'mm²';
    case MM3 = 'mm³';
    case MG = 'mg';
    case G = 'g';
    case ML = 'ml';

    public static function values(): array
    {
        return [
            self::KG->value,
            self::L->value,
            self::UN->value,
            self::M->value,
            self::M2->value,
            self::M3->value,
            self::CM->value,
            self::CM2->value,
            self::CM3->value,
            self::MM->value,
            self::MM2->value,
            self::MM3->value,
            self::MG->value,
            self::G->value,
            self::ML->value,
        ];
    }

    public static function options(): array
    {
        return [
            self::KG->value => 'Kilograma (kg)',
            self::L->value => 'Litro (l)',
            self::UN->value => 'Unidade (un)',
            self::M->value => 'Metro (m)',
            self::M2->value => 'Metro Quadrado (m²)',
            self::M3->value => 'Metro Cúbico (m³)',
            self::CM->value => 'Centímetro (cm)',
            self::CM2->value => 'Centímetro Quadrado (cm²)',
            self::CM3->value => 'Centímetro Cúbico (cm³)',
            self::MM->value => 'Milímetro (mm)',
            self::MM2->value => 'Milímetro Quadrado (mm²)',
            self::MM3->value => 'Milímetro Cúbico (mm³)',
            self::MG->value => 'Miligrama (mg)',
            self::G->value => 'Grama (g)',
            self::ML->value => 'Mililitro (ml)',
        ];
    }

    public static function translate(UnityEnum $value): string
    {
        return match ($value) {
            self::KG => 'Kilograma (kg)',
            self::L => 'Litro (l)',
            self::UN => 'Unidade (un)',
            self::M => 'Metro (m)',
            self::M2 => 'Metro Quadrado (m²)',
            self::M3 => 'Metro Cúbico (m³)',
            self::CM => 'Centímetro (cm)',
            self::CM2 => 'Centímetro Quadrado (cm²)',
            self::CM3 => 'Centímetro Cúbico (cm³)',
            self::MM => 'Milímetro (mm)',
            self::MM2 => 'Milímetro Quadrado (mm²)',
            self::MM3 => 'Milímetro Cúbico (mm³)',
            self::MG => 'Miligrama (mg)',
            self::G => 'Grama (g)',
            self::ML => 'Mililitro (ml)',
        };
    }
}
