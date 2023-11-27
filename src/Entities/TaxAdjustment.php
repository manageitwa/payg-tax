<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\Entities;

/**
 * A tax adjustment is a deduction, offset or surcharge that is applied to the amount of tax to be withheld for an
 * earning.
 */
interface TaxAdjustment
{
    /**
     * Determines the eligibility of this adjustment.
     *
     * This method will be passed the payer, payee, the tax scale applied and earning. It should return `true` if the
     * adjustment is eligible to be applied for this earning, and `false` otherwise.
     */
    public function isEligible(
        \ManageIt\PaygTax\Entities\Payer $payer,
        \ManageIt\PaygTax\Entities\Payee $payee,
        \ManageIt\PaygTax\Entities\TaxScale $taxScale,
        \ManageIt\PaygTax\Entities\Earning $earning
    ): bool;

    /**
     * Gets the amount that should be subtracted from or added to the tax withheld for this earning.
     *
     * This method will only be called if the adjustment is eligible for this earning.
     */
    public function getAdjustmentAmount(
        \ManageIt\PaygTax\Entities\Payer $payer,
        \ManageIt\PaygTax\Entities\Payee $payee,
        \ManageIt\PaygTax\Entities\TaxScale $taxScale,
        \ManageIt\PaygTax\Entities\Earning $earning
    ): float;
}
