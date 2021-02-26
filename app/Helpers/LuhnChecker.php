<?php


namespace App\Helpers;


class LuhnChecker
{

    /**
     * Returns if the card passes Luhn's algorithm check
     * @param string $card
     * @return bool
     */
    public static function check(string $card) : bool
    {
        // remove any non digit
        $clean = preg_replace('/\D/', '', $card);

        return self::preValidate($clean) ? self::isValid($clean) : false;

    }

    /**
     * Calculates the Luhn sum
     * @param string $number
     * @return int
     */
    private static function calculate(string $number): int
    {
        $sum = 0;
        $parity = 1;
        for ($i = strlen($number) - 1; $i >= 0; $i--) {
            $factor = $parity ? 2 : 1;
            $parity = $parity ? 0 : 1;
            $sum += array_sum(str_split($number[$i] * $factor));
        }
        return $sum;
    }

    /**
     * Basic Validation to check if the card contain only zeros ( that could break Luhn's algorithm )
     * Or the length is less than 12 ( Maestro can have 12 digits )
     * @param string $card
     * @return bool
     */
    private static function preValidate(string $card) : bool
    {
        if (strlen($card) < 12 || intval($card) === 0) {
            return false;
        }
        return true;
    }

    /**
     * Returns the checksum ( last character )
     * @param string $card
     * @return string
     */
    private static function calculateChecksum(string $card) : string
    {
        return $card[-1];
    }

    /**
     * Check both checkSummed and non checkSummed payload against Luhn's algorithm
     * @param string $card
     * @return bool
     */
    private static function isValid(string $card) : bool {
        $formatted = intval($card.'0');
        $nonCheckSum = (self::calculate($formatted) % 10 ) === 0 ? true : false;
        $checkSummed = (self::calculate($formatted.self::calculateChecksum($formatted)) % 10 ) === 0 ? true : false;

        return $nonCheckSum || $checkSummed ? true : false;
    }
}
