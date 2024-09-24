<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\TaxScales\July2024;

use ManageIt\PaygTax\Entities\Earning;
use ManageIt\PaygTax\Entities\Payee;
use ManageIt\PaygTax\Entities\Payer;
use ManageIt\PaygTax\TaxScales\BaseCoefficientScale;
use ManageIt\PaygTax\Utilities\Date;

/**
 * Tax scale for Australian residents not claiming the tax free threshold and have an STSL debt.
 *
 * This tax scale has come into effect from 1st July 2024.
 */
class Nat3539Scale1 extends BaseCoefficientScale
{
    /**
     * {@inheritDoc}
     */
    protected array $coefficients = [
        150 => [0.1600, 0.1600],
        371 => [0.2117, 7.7550],
        515 => [0.1890, -0.6702],
        696 => [0.3227, 68.2367],
        858 => [0.3327, 68.2367],
        931 => [0.3427, 68.2367],
        1008 => [0.3450, 65.7202],
        1089 => [0.3500, 65.7202],
        1175 => [0.3550, 65.7202],
        1267 => [0.3600, 65.7202],
        1364 => [0.3650, 65.7202],
        1467 => [0.3700, 65.7202],
        1576 => [0.3750, 65.7202],
        1692 => [0.3800, 65.7202],
        1814 => [0.3850, 65.7202],
        1944 => [0.3900, 65.7202],
        2082 => [0.3950, 65.7202],
        2228 => [0.4000, 65.7202],
        2246 => [0.4050, 65.7202],
        2382 => [0.4750, 222.9510],
        2546 => [0.4800, 222.9510],
        2720 => [0.4850, 222.9510],
        3303 => [0.4900, 222.9510],
        999999999 => [0.5700, 487.2587],
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
