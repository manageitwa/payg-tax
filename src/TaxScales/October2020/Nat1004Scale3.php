<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\TaxScales\October2020;

use ManageIt\PaygTax\Entities\Earning;
use ManageIt\PaygTax\Entities\Payee;
use ManageIt\PaygTax\Entities\Payer;
use ManageIt\PaygTax\TaxScales\BaseCoefficientScale;
use ManageIt\PaygTax\Utilities\Date;

/**
 * Standard tax scale for foreign residents (not working holiday makers).
 *
 * This tax scale has come into effect from 13 October 2020.
 */
class Nat1004Scale3 extends BaseCoefficientScale
{
    /**
     * {@inheritDoc}
     */
    protected array $coefficients = [
        2307 => [0.325, 0.325],
        3461 => [0.37, 103.8462],
        999999999 => [0.45, 380.7692],
    ];

    /**
     * {@inheritDoc}
     */
    public function isEligible(Payer $payer, Payee $payee, Earning $earning): bool
    {
        // Only applies after 13 October 2020 and before 1 July 2024.
        if (!Date::between($earning->getPayDate(), '2020-10-13', '2024-06-30')) {
            return false;
        }

        // Only applies to foreign residents (or Working Holiday Makers whose employers are not registered)
        if (
            $payee->getResidencyStatus() === \ManageIt\PaygTax\Entities\Payee::RESIDENT
            || ($payee->getResidencyStatus() === \ManageIt\PaygTax\Entities\Payee::WORKING_HOLIDAY_MAKER && $payer->isRegisteredWhmEmployer())
        ) {
            return false;
        }

        // Only applies to payees with a tax file number.
        if (!$payee->hasTfnNumber()) {
            return false;
        }

        // Only applies to payees without an STSL debt.
        if ($payee->hasSTSLDebt()) {
            return false;
        }

        // Only applies to payees with no Medicare Levy exemption.
        if ($payee->getMedicareLevyExemption() !== \ManageIt\PaygTax\Entities\Payee::MEDICARE_LEVY_EXEMPTION_NONE) {
            return false;
        }

        // Only applies to payees not claiming the Seniors Offset.
        if ($payee->getSeniorsOffset() !== \ManageIt\PaygTax\Entities\Payee::SENIORS_OFFSET_NONE) {
            return false;
        }

        return true;
    }
}
