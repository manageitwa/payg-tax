<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\Entities;

/**
 * A tax scale is a particular tax bracket that is applied to the earnings of a payee.
 *
 * Tax scales generally include coefficients that determine the percentage of tax to be withheld for a particular
 * earning, minus an adjustment amount.
 *
 * The tax scale is used to calculate the initial amount of tax to be withheld for an earning. This amount may then be
 * increased or decreased depending on the tax adjustments claimed.
 */
interface TaxScale
{
    /**
     * Determines the eligibility of this scale.
     *
     * This method will be passed the payer, payee and earning. It should return `true` if the scale is eligible to be
     * applied for this earning, and `false` otherwise.
     */
    public function isEligible(
        \ManageIt\PaygTax\Entities\Payer $payer,
        \ManageIt\PaygTax\Entities\Payee $payee,
        \ManageIt\PaygTax\Entities\Earning $earning
    ): bool;

    /**
     * Gets the amount of tax that should be withheld from the gross earning.
     *
     * Note that this is pre-adjustment. Tax adjustments will be applied after this method is called.
     */
    public function getTaxWithheldAmount(
        \ManageIt\PaygTax\Entities\Payer $payer,
        \ManageIt\PaygTax\Entities\Payee $payee,
        \ManageIt\PaygTax\Entities\Earning $earning
    ): float;
}
