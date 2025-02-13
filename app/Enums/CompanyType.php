<?php

namespace App\Enums;

enum CompanyType: int
{
    case PARTNER = 0;
    case SUPPLIER = 1;
    case MANUFACTURER = 2;
    case SERVICE_PROVIDER = 3;


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
            CompanyType::PARTNER => 'partner',
            CompanyType::SUPPLIER => 'supplier',
            CompanyType::MANUFACTURER => 'manufacturer',
            CompanyType::SERVICE_PROVIDER => 'service provider',
        };
    }

    public static function getLabels(): array
    {
        return [
            '0' => 'partner',
            '1' => 'supplier',
            '2' => 'manufacturer',
            '3' => 'service provider',
        ];
    }
}
