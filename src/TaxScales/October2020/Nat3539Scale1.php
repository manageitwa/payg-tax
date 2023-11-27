<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\TaxScales\October2020;

use ManageIt\PaygTax\Entities\Earning;
use ManageIt\PaygTax\Entities\Payee;
use ManageIt\PaygTax\Entities\Payer;
use ManageIt\PaygTax\TaxScales\BaseCoefficientScale;
use ManageIt\PaygTax\Utilities\Date;

/**
 * Tax scale for Australian residents not claiming the tax free threshold and have an STSL debt.
 *
 * This tax scale has come into effect from 13 October 2020.
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
        546 => [0.3477, 64.4297],
        685 => [0.3577, 64.4297],
        747 => [0.3677, 64.4297],
        813 => [0.3727, 64.4297],
        882 => [0.3777, 64.4297],
        932 => [0.3827, 64.4297],
        956 => [0.3800, 61.9132],
        1035 => [0.3850, 61.9132],
        1118 => [0.3900, 61.9132],
        1206 => [0.3950, 61.9132],
        1299 => [0.4000, 61.9132],
        1398 => [0.4050, 61.9132],
        1503 => [0.4100, 61.9132],
        1615 => [0.4150, 61.9132],
        1732 => [0.4200, 61.9132],
        1855 => [0.4250, 61.9132],
        1957 => [0.4300, 61.9132],
        1990 => [0.4750, 150.0093],
        2130 => [0.4800, 150.0093],
        2279 => [0.4850, 150.0093],
        3111 => [0.4900, 150.0093],
        999999999 => [0.57, 398.9324],
    ];

    /**
     * {@inheritDoc}
     */
    public function isEligible(Payer $payer, Payee $payee, Earning $earning): bool
    {
        // Only applies after 13 October 2020.
        if (!Date::from($earning->getPayDate(), '2020-10-13')) {
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
