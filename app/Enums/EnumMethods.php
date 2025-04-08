<?php

declare(strict_types=1);

namespace App\Enums;

use BackedEnum;

trait EnumMethods
{
    /**
     * Get all the enum cases.
     *
     * @return array<int, int|string>
     */
    public static function values(): array
    {
        return array_map(fn (BackedEnum $case) => $case->value, self::cases());
    }

    /**
     * Get all the enum cases as an associative array.
     *
     * @return array<int|string, string>
     */
    public static function options(): array
    {
        return array_column(array_map(
            fn (BackedEnum $case) => ['value' => $case->value, 'label' => __("enums.{$case->name}")],
            self::cases()
        ), 'label', 'value');
    }
}
