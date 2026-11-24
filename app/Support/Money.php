<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Exact money math on numeric strings (bcmath). Never use floats for money.
 * Results are rounded half away from zero to 2 decimals (centavos).
 */
final class Money
{
    private const SCALE = 4;

    public static function add(string $a, string $b): string
    {
        return self::round(bcadd($a, $b, self::SCALE));
    }

    public static function sub(string $a, string $b): string
    {
        return self::round(bcsub($a, $b, self::SCALE));
    }

    public static function mul(string $a, string $b): string
    {
        return self::round(bcmul($a, $b, self::SCALE));
    }

    /** $rate is a percentage: "15.00" means 15%. */
    public static function percent(string $amount, string $rate): string
    {
        return self::round(bcdiv(bcmul($amount, $rate, self::SCALE), '100', self::SCALE));
    }

    public static function compare(string $a, string $b): int
    {
        return bccomp($a, $b, 2);
    }

    public static function round(string $value): string
    {
        $negative = str_starts_with($value, '-');
        $rounded = bcadd(bcadd(ltrim($value, '-'), '0.005', 3), '0', 2);

        return $negative && bccomp($rounded, '0', 2) !== 0 ? '-'.$rounded : $rounded;
    }
}
