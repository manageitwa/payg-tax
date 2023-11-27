<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\Utilities;

class Math
{
    /**
     * Replacement for the PHP `round()` function when working with tax amounts.
     *
     * The rules of the ATO are that any value that has a fraction of 0.5 or above needs to be rounded up to the
     * nearest whole value, and anything below 0.5 needs to be rounded down. The php `round()` function can't seem to
     * handle this :(.
     */
    public static function round(float $value): float
    {
        $fraction = $value - floor($value);

        if ($fraction >= 0.5) {
            return floor($value) + 1;
        }

        return floor($value);
    }
}
