<?php

namespace App\Helpers;

class AmountHelper
{
    /**
     * Round amount using custom rule: >= 0.45 rounds up, < 0.45 rounds down
     * 
     * @param float|string $amount
     * @return int
     */
    public static function roundAmount($amount)
    {
        $amount = (float) $amount;
        $integerPart = floor($amount);
        $decimalPart = $amount - $integerPart;
        
        // If decimal >= 0.45, round up, else round down
        if ($decimalPart >= 0.45) {
            return (int) ceil($amount);
        } else {
            return (int) floor($amount);
        }
    }

    /**
     * Format amount with custom rounding for display
     * 
     * @param float|string $amount
     * @return string
     */
    public static function formatAmount($amount)
    {
        $rounded = self::roundAmount($amount);
        return number_format($rounded, 0, '.', '');
    }

    /**
     * Format amount with custom rounding and currency symbol
     * 
     * @param float|string $amount
     * @param string $currency
     * @return string
     */
    public static function formatCurrency($amount, $currency = 'Tk.')
    {
        $rounded = self::roundAmount($amount);
        return $currency . ' ' . number_format($rounded, 0, '.', ',');
    }
}

