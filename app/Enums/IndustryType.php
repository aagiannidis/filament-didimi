<?php

namespace App\Enums;

enum IndustryType: int
{
    case GENERAL_SUPPLIES = 0;
    case FUEL_AND_ENERGY = 1;
    case PARTS = 2;
    case SERVICING = 3;


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
            IndustryType::GENERAL_SUPPLIES => 'general supplies',
            IndustryType::FUEL_AND_ENERGY => 'fuel and energy',
            IndustryType::PARTS => 'part',
            IndustryType::SERVICING => 'servicing',
        };
    }

    public static function getLabels(): array
    {
        return [
            '0'=> 'general supplies',
            '1'=> 'fuel and energy',
            '2'=> 'part',
            '3'=> 'servicing',
        ];
    }
}
