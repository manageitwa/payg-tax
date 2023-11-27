<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\Entities;

/**
 * An earning is an individual wage payment made to a payee on a certain date.
 *
 * Each earning should contain a date and a gross amount. The gross will be used to calculate how much tax needs to be
 * withheld. Gross should include the following:
 *
 * - Normal hourly rate plus any overtime
 * - Leave payments
 * - Any taxable allowances
 * - Any taxable bonuses
 */
interface Earning
{
    /**
     * Gets the date in which this earning was paid.
     *
     * This, in general, should be the date in which the payee was paid a wage. It is mainly used to determine the
     * correct taxation data based on the financial year.
     */
    public function getPayDate(): \DateTimeInterface;

    /**
     * Gets the gross amount paid to the payee as an earning.
     */
    public function getGrossAmount(): float;
}
