<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\Adjustments;

use ManageIt\PaygTax\Adjustments\October2020\MedicareLevyReduction as October2020Reduction;
use ManageIt\PaygTax\Adjustments\July2024\MedicareLevyReduction as July2024Reduction;
use ManageIt\PaygTax\Entities\Earning;
use ManageIt\PaygTax\Utilities\Date;

/**
 * Helper class that can be used to get the correct Medicare Levy Reduction instance for a given date.
 */
class MedicareLevyReduction
{
    /**
     * Returns the correct Medicare Levy Reduction instance for the given date.
     *
     * If the date is before October 2020, this method will return null.
     *
     * @param \DateTimeInterface|string|int $date
     * @return October2020Reduction|July2024Reduction|null
     */
    public static function forDate($date)
    {
        if (Date::from($date, '2024-07-01')) {
            return new July2024Reduction();
        }

        if (Date::from($date, '2020-10-13')) {
            return new October2020Reduction();
        }

        return null;
    }

    /**
     * Returns the correct Medicare Levy Reduction instance for the given earning payment date.
     *
     * If the date is before October 2020, this method will return null.
     *
     * @return October2020Reduction|July2024Reduction|null
     */
    public static function forEarning(Earning $earning)
    {
        return static::forDate($earning->getPayDate());
    }
}
