<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\TaxScales\July2023;

use ManageIt\PaygTax\Entities\Earning;
use ManageIt\PaygTax\Entities\Payee;
use ManageIt\PaygTax\Entities\Payer;
use ManageIt\PaygTax\TaxScales\BaseCoefficientScale;
use ManageIt\PaygTax\Utilities\Date;

/**
 * Tax scale for Australian residents not claiming the tax free threshold and have an STSL debt.
 *
 * This tax scale has come into effect from 1 July 2023.
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
        641 => [0.3477, 64.4297],
        794 => [0.3577, 64.4297],
        863 => [0.3677, 64.4297],
        932 => [0.3727, 64.4297],
        936 => [0.3700, 61.9132],
        1013 => [0.3750, 61.9132],
        1095 => [0.3800, 61.9132],
        1181 => [0.3850, 61.9132],
        1273 => [0.3900, 61.9132],
        1371 => [0.3950, 61.9132],
        1474 => [0.4000, 61.9132],
        1583 => [0.4050, 61.9132],
        1699 => [0.4100, 61.9132],
        1822 => [0.4150, 61.9132],
        1953 => [0.4200, 61.9132],
        1957 => [0.4250, 61.9132],
        2091 => [0.4700, 150.0093],
        2237 => [0.4750, 150.0093],
        2393 => [0.4800, 150.0093],
        2557 => [0.4850, 150.0093],
        3111 => [0.4900, 150.0093],
        999999999 => [0.5700, 398.9324],
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
