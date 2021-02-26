<?php


namespace App\Helpers;

class AppHelper
{
    /**
     * Returns a currency formatted price
     * @param float $price
     * @param string $currency
     * @return string
     */
    public static function formatCurrency(float $price, string $currency): string
    {
        $fmt = numfmt_create('de_DE', \NumberFormatter::CURRENCY);
        return numfmt_format_currency($fmt, $price, $currency);
    }

    /**
     * Multiply float * 100 to make it an integer
     * @param float $number
     * @return int
     */
    public static function denormalizePriceData(float $number): int
    {
        return intval($number * 100);
    }

    /**
     * Divide an integer with 100 to make it float
     * @param int $number
     * @return float
     */
    public static function normalizePriceData(int $number): float
    {
        return floatval($number / 100);
    }
}
