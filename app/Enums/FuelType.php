<?php

namespace App\Enums;

enum FuelType: int
{
    case PETROL = 0;
    case DIESEL = 1;
    case ELECTRIC = 2;
    case HYBRID = 3;
    case OTHER = 4;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function keys(): array
    {
        $cases = self::cases();
        return array_combine(
            array_column($cases, 'name'),
            array_column($cases, 'value')
        );
    }

    public function label(): string
    {
        return static::getLabel($this);
    }

    public static function getLabel(self $value): string
    {
        return match ($value) {
            FuelType::PETROL => 'petrol',
            FuelType::DIESEL => 'diesel',
            FuelType::ELECTRIC => 'electric',
            FuelType::HYBRID => 'hybrid',
            FuelType::OTHER => 'other',
        };
    }

    public static function getLabels(): array
    {
        return [
            '0' => 'petrol',
            '1' => 'diesel',
            '2' => 'electric',
            '3' => 'hybrid',
            '4' => 'other',
        ];
    }
}
