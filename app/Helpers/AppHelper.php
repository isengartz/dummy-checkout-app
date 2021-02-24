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
    public static function formatCurrency(float $price,string $currency) : string {
        $fmt = numfmt_create('de_DE',\NumberFormatter::CURRENCY);
        return numfmt_format_currency($fmt,$price,$currency);
    }
}
