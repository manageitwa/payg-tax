<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\TaxScales\July2022;

use ManageIt\PaygTax\Entities\Earning;
use ManageIt\PaygTax\Entities\Payee;
use ManageIt\PaygTax\Entities\Payer;
use ManageIt\PaygTax\TaxScales\BaseCoefficientScale;
use ManageIt\PaygTax\Utilities\Date;

/**
 * Tax scale for Australian residents not claiming the tax free threshold and have an STSL debt.
 *
 * This tax scale has come into effect from 1 July 2022.
 */
class Nat3539Scale1 extends BaseCoefficientScale
{
    /**
     * {@inheritDoc}
     */
    protected array $coefficients = [
        88 => [0.1900, 0.1900],
        371 => [0.2348, 3.9639],
        515 => [0.2190, -1.9003],
        580 => [0.3477, 64.4297],
        723 => [0.3577, 64.4297],
        788 => [0.3677, 64.4297],
        856 => [0.3727, 64.4297],
        928 => [0.3777, 64.4297],
        932 => [0.3827, 64.4297],
        1005 => [0.3800, 61.9132],
        1086 => [0.3850, 61.9132],
        1173 => [0.3900, 61.9132],
        1264 => [0.3950, 61.9132],
        1361 => [0.4000, 61.9132],
        1464 => [0.4050, 61.9132],
        1573 => [0.4100, 61.9132],
        1688 => [0.4150, 61.9132],
        1810 => [0.4200, 61.9132],
        1940 => [0.4250, 61.9132],
        1957 => [0.4300, 61.9132],
        2077 => [0.4750, 150.0093],
        2223 => [0.4800, 150.0093],
        2377 => [0.4850, 150.0093],
        3111 => [0.4900, 150.0093],
        999999999 => [0.5700, 398.9324],
    ];

    /**
     * {@inheritDoc}
     */
    public function isEligible(Payer $payer, Payee $payee, Earning $earning): bool
    {
        // Only applies between 1 July 2022 and 30 June 2023.
        if (!Date::between($earning->getPayDate(), '2022-07-01', '2023-06-30')) {
            return false;
        }

        // Only applies to Australian residents.
        if ($payee->getResidencyStatus() !== \ManageIt\PaygTax\Entities\Payee::RESIDENT) {
            return false;
        }

        // Only applies to payees with a tax file number.
        if (!$payee->hasTfnNumber()) {
            return false;
        }

        // Only applies to payees not claiming the tax free threshold.
        if ($payee->claimsTaxFreeThreshold()) {
            return false;
        }

        // Only applies to payees with an STSL debt.
        if (!$payee->hasSTSLDebt()) {
            return false;
        }

        // Only applies to payees not claiming the Seniors Offset.
        if ($payee->getSeniorsOffset() !== \ManageIt\PaygTax\Entities\Payee::SENIORS_OFFSET_NONE) {
            return false;
        }

        return true;
    }
}
