<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\TaxScales\July2024;

use ManageIt\PaygTax\Entities\Earning;
use ManageIt\PaygTax\Entities\Payee;
use ManageIt\PaygTax\Entities\Payer;
use ManageIt\PaygTax\TaxScales\BaseCoefficientScale;
use ManageIt\PaygTax\Utilities\Date;

/**
 * Tax scale for Australian residents claiming the tax free threshold with an STSL debt.
 *
 * This tax scale has come into effect from 1st July 2024.
 */
class Nat3539Scale2 extends BaseCoefficientScale
{
    /**
     * {@inheritDoc}
     */
    protected array $coefficients = [
        361 => [0.0000, 0.0000],
        500 => [0.1600, 57.8462],
        625 => [0.2600, 107.8462],
        721 => [0.1800, 57.8462],
        865 => [0.1890, 64.3365],
        1046 => [0.3227, 180.0385],
        1208 => [0.3327, 180.0385],
        1281 => [0.3427, 180.0385],
        1358 => [0.3450, 176.5769],
        1439 => [0.3500, 176.5769],
        1525 => [0.3550, 176.5769],
        1617 => [0.3600, 176.5769],
        1714 => [0.3650, 176.5769],
        1817 => [0.3700, 176.5769],
        1926 => [0.3750, 176.5769],
        2042 => [0.3800, 176.5769],
        2164 => [0.3850, 176.5769],
        2294 => [0.3900, 176.5769],
        2432 => [0.3950, 176.5769],
        2578 => [0.4000, 176.5769],
        2596 => [0.4050, 176.5769],
        2732 => [0.4750, 358.3077],
        2896 => [0.4800, 358.3077],
        3070 => [0.4850, 358.3077],
        3653 => [0.4900, 358.3077],
        999999999 => [0.5700, 650.6154],
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

        // Only applies to payees claiming the tax free threshold.
        if (!$payee->claimsTaxFreeThreshold()) {
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
