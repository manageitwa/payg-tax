<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\TaxScales\July2024;

use ManageIt\PaygTax\Entities\Earning;
use ManageIt\PaygTax\Entities\Payee;
use ManageIt\PaygTax\Entities\Payer;
use ManageIt\PaygTax\TaxScales\BaseCoefficientScale;
use ManageIt\PaygTax\Utilities\Date;

/**
 * Tax scale for foreign residents (not working holiday makers) with an STSL debt.
 *
 * This tax scale has come into effect from 1st July 2024.
 */
class Nat3539Scale3 extends BaseCoefficientScale
{
    /**
     * {@inheritDoc}
     */
    protected array $coefficients = [
        1046 => [0.3000, 0.3000],
        1208 => [0.3100, 0.3000],
        1281 => [0.3200, 0.3000],
        1358 => [0.3250, 0.3000],
        1439 => [0.3300, 0.3000],
        1525 => [0.3350, 0.3000],
        1617 => [0.3400, 0.3000],
        1714 => [0.3450, 0.3000],
        1817 => [0.3500, 0.3000],
        1926 => [0.3550, 0.3000],
        2042 => [0.3600, 0.3000],
        2164 => [0.3650, 0.3000],
        2294 => [0.3700, 0.3000],
        2432 => [0.3750, 0.3000],
        2578 => [0.3800, 0.3000],
        2596 => [0.3850, 0.3000],
        2732 => [0.4550, 181.7308],
        2896 => [0.4600, 181.7308],
        3070 => [0.4650, 181.7308],
        3653 => [0.4700, 181.7308],
        999999999 => [0.5500, 474.0385],
    ];

    /**
     * {@inheritDoc}
     */
    public function isEligible(Payer $payer, Payee $payee, Earning $earning): bool
    {
        // Only applies after 1 July 2024.
        if (!Date::from($earning->getPayDate(), '2024-07-01')) {
            return false;
        }

        // Only applies to foreign residents.
        if ($payee->getResidencyStatus() !== \ManageIt\PaygTax\Entities\Payee::FOREIGN_RESIDENT) {
            return false;
        }

        // Only applies to payees with a tax file number.
        if (!$payee->hasTfnNumber()) {
            return false;
        }

        // Only applies to payees with an STSL debt.
        if (!$payee->hasSTSLDebt()) {
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
