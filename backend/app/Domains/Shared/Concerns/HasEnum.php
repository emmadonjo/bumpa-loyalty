<?php

declare(strict_types=1);

namespace App\Domains\Shared\Concerns;

trait HasEnum
{
    /**
     * Retrieve the values of the enum
     * @return string[]
     */
    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}
