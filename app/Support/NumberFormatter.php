<?php

namespace App\Support;

class NumberFormatter
{
    public static function format($number, $decimals = 1)
    {
        return number_format(
            $number,
            $decimals,
            ',',   // virgule pour les décimales
            ' '    // espace pour les milliers
        );
    }

    public static function parse($value)
    {
        if (is_numeric($value)) {
            return (float) $value;
        }

        // Remplace l'espace de milliers par rien et la virgule par un point
        $clean = str_replace([' ', ','], ['', '.'], $value);

        return (float) $clean;
    }
}
