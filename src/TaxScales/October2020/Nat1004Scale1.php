<?php

namespace ManageIt\PaygTax\TaxScales\October2020;

use ManageIt\PaygTax\Entities\Earning;
use ManageIt\PaygTax\Entities\Payee;
use ManageIt\PaygTax\Entities\Payer;
use ManageIt\PaygTax\TaxScales\BaseCoefficientScale;
use ManageIt\PaygTax\Utilities\Date;

/**
 * Standard tax scale for Australian residents not claiming the tax free threshold.
 *
 * This tax scale has come into effect from 13 October 2020.
 */
class Nat1004Scale1 extends BaseCoefficientScale
{
    /**
     * {@inheritDoc}
     */
    protected array $coefficients = [
        88 => [0.19, 0.19],
        371 => [0.2348, 3.9639],
        515 => [0.219, -1.9003],
        932 => [0.3477, 64.4297],
        1957 => [0.345, 61.9132],
        3111 => [0.39, 150.0093],
        999999999 => [0.47, 398.9324],
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

        // Only applies to payees without an STSL debt.
        if ($payee->hasSTSLDebt()) {
            return false;
        }

        // Only applies to payees not claiming the Seniors Offset.
        if ($payee->getSeniorsOffset() !== \ManageIt\PaygTax\Entities\Payee::SENIORS_OFFSET_NONE) {
            return false;
        }

        return true;
    }
}
