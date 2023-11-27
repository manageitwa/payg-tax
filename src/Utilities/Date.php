<?php

declare(strict_types=1);

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
     * @param \DateTimeInterface|string|int $from
     */
    public static function from($date, $from): bool
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
     * Determines if the date is between two dates, inclusive.
     *
     * @param \DateTimeInterface|string|int $date
     * @param \DateTimeInterface|string|int $from
     * @param \DateTimeInterface|string|int $to
     */
    public static function between($date, $from, $to): bool
    {
        return static::from($date, $from) && static::to($date, $to);
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
        } elseif (is_string($date)) {
            return Carbon::parse($date);
        } else {
            return Carbon::createFromTimestamp($date, 'UTC');
        }
    }
}
