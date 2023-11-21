<?php

namespace ManageIt\PaygTax\Utilities;

use Carbon\Carbon;

/**
 * Date utility for comparing dates.
 */
class Date
{
    /**
     * Determines if the date is either on, or after the from date.
     *
     * @param \DateTimeInterface|string|int $date
     * @param \DateTimeInterface|string|int $to
     */
    public static function from(\DateTimeInterface $date, $from): bool
    {
        $date = static::dateToCarbon($date)->setTime(0, 0);
        $from = static::dateToCarbon($from)->setTime(0, 0);

        return $date->greaterThanOrEqualTo($from);
    }

    /**
     * Determines if the date is either on, or before the to date.
     *
     * @param \DateTimeInterface|string|int $date
     * @param \DateTimeInterface|string|int $to
     */
    public static function to($date, $to): bool
    {
        $date = static::dateToCarbon($date)->setTime(0, 0);
        $to = static::dateToCarbon($to)->setTime(0, 0);

        return $date->lessThanOrEqualTo($to);
    }

    /**
     * Converts a date to a Carbon instance.
     *
     * This method accepts a DateTimeInterface instance, a date string or an integer timestamp.
     *
     * @param \DateTimeInterface|string|int $date
     */
    public static function dateToCarbon($date): Carbon
    {
        if ($date instanceof \DateTimeInterface) {
            return Carbon::instance($date);
        } else if (is_string($date)) {
            return Carbon::parse($date);
        } else if (is_int($date)) {
            return Carbon::createFromTimestamp($date, 'UTC');
        }

        throw new \InvalidArgumentException(
            'Date must be a DateTimeInterface instance, integer timestamp or date string.'
        );
    }
}
