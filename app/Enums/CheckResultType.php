<?php

namespace App\Enums;

enum CheckResultType: int
{
    case PASS = 0;
    case PASS_WITH_REMARKS = 1;
    case FAIL_WITH_ISSUES = 2;

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
            CheckResultType::PASS => 'pass',
            CheckResultType::PASS_WITH_REMARKS => 'pass with remarks',
            CheckResultType::FAIL_WITH_ISSUES => 'fail with issues',
        };
    }

    public static function getLabels(): array
    {
        return [
            '0' => 'pass',
            '1' => 'pass with remarks',
            '2' => 'fail with issues',
        ];
    }
}
