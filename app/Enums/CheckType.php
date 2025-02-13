<?php

namespace App\Enums;

enum CheckType: int
{
    case PERIODIC_ON_TIME = 0;
    case PERIODIC_LATE = 1;
    case RECHECK = 2;

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
            CheckType::PERIODIC_ON_TIME => 'periodic',
            CheckType::PERIODIC_LATE => 'periodic (late)',
            CheckType::RECHECK => 'recheck',
        };
    }

    public static function getLabels(): array
    {
        return [
            '0' => 'periodic',
            '1' => 'periodic (late)',
            '2' => 'recheck',
        ];
    }
}
