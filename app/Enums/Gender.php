<?php

namespace App\Enums;

enum Gender: string
{
    case MALE = 'male';
    case FEMALE = 'female';
    case OTHER = 'other';

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
}