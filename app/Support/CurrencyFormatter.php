<?php

namespace App\Support;

use App\Models\Tour;

class CurrencyFormatter
{
    public static function formatAmount(int $amount, string $currency = Tour::CURRENCY_USD): string
    {
        $amount = max(0, $amount);

        return number_format($amount, 0, '.', ',');
    }

    public static function format(int $amount, string $currency = Tour::CURRENCY_USD): string
    {
        $formatted = self::formatAmount($amount, $currency);

        return '$'.$formatted;
    }

    public static function symbol(string $currency = Tour::CURRENCY_USD): string
    {
        return '$';
    }

    public static function symbolBefore(string $currency = Tour::CURRENCY_USD): bool
    {
        return true;
    }

    public static function parse(?string $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        $digits = preg_replace('/\D/', '', $value);

        if ($digits === null || $digits === '') {
            return null;
        }

        return max(0, (int) $digits);
    }
}
