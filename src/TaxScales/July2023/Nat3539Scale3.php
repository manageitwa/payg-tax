<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\TaxScales\July2023;

use ManageIt\PaygTax\Entities\Earning;
use ManageIt\PaygTax\Entities\Payee;
use ManageIt\PaygTax\Entities\Payer;
use ManageIt\PaygTax\TaxScales\BaseCoefficientScale;
use ManageIt\PaygTax\Utilities\Date;

/**
 * Tax scale for foreign residents (not working holiday makers) with an STSL debt.
 *
 * This tax scale has come into effect from 1 July 2023.
 */
class Nat3539Scale3 extends BaseCoefficientScale
{
    /**
     * {@inheritDoc}
     */
    protected array $coefficients = [
        991 => [0.3250, 0.3250],
        1144 => [0.3350, 0.3250],
        1213 => [0.3450, 0.3250],
        1286 => [0.3500, 0.3250],
        1363 => [0.3550, 0.3250],
        1445 => [0.3600, 0.3250],
        1531 => [0.3650, 0.3250],
        1623 => [0.3700, 0.3250],
        1721 => [0.3750, 0.3250],
        1824 => [0.3800, 0.3250],
        1933 => [0.3850, 0.3250],
        2049 => [0.3900, 0.3250],
        2172 => [0.3950, 0.3250],
        2303 => [0.4000, 0.3250],
        2307 => [0.4050, 0.3250],
        2441 => [0.4500, 103.8462],
        2587 => [0.4550, 103.8462],
        2743 => [0.4600, 103.8462],
        2907 => [0.4650, 103.8462],
        3461 => [0.4700, 103.8462],
        999999999 => [0.55, 380.7692],
    ];

    /**
     * {@inheritDoc}
     */
    public function isEligible(Payer $payer, Payee $payee, Earning $earning): bool
    {
        // Only applies after 1 July 2023
        if (!Date::from($earning->getPayDate(), '2023-07-01')) {
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
