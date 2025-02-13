<?php

namespace App\Enums;

enum VehicleType: int
{
    case CAR = 0;
    case BUS = 1;
    case MOTORCYCLE = 2;
    case TRUCK = 3;
    case VAN = 4;
    case SUV = 5;
    case HEAVY_MACHINERY = 6;
    case OTHER = 7;

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
            VehicleType::CAR => 'car',
            VehicleType::BUS => 'bus',
            VehicleType::MOTORCYCLE => 'motorcycle',
            VehicleType::TRUCK => 'truck',
            VehicleType::VAN => 'van',
            VehicleType::SUV => 'suv',
            VehicleType::HEAVY_MACHINERY => 'heavy machinery',
            VehicleType::OTHER => 'other type',
        };
    }

    public static function getLabels(): array
    {
        return [
            '0' => 'car',
            '1' => 'bus',
            '2' => 'motorcycle',
            '3' => 'truck',
            '4' => 'van',
            '5' => 'suv',
            '6' => 'heavy machinery',
            '7' => 'other type',
        ];
    }
}
