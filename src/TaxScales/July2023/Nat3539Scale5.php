<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\TaxScales\July2023;

use ManageIt\PaygTax\Entities\Earning;
use ManageIt\PaygTax\Entities\Payee;
use ManageIt\PaygTax\Entities\Payer;
use ManageIt\PaygTax\TaxScales\BaseCoefficientScale;
use ManageIt\PaygTax\Utilities\Date;

/**
 * Tax scale for Australian residents who are claiming the tax free threshold and are claiming a full
 * Medicare levy exemption and have an STSL debt.
 *
 * This tax scale has come into effect from 1 July 2023.
 */
class Nat3539Scale5 extends BaseCoefficientScale
{
    /**
     * {@inheritDoc}
     */
    protected array $coefficients = [
        359 => [0.0000, 0.0000],
        721 => [0.1900, 68.3462],
        865 => [0.1990, 74.8365],
        991 => [0.3277, 186.2115],
        1144 => [0.3377, 186.2115],
        1213 => [0.3477, 186.2115],
        1282 => [0.3527, 186.2115],
        1286 => [0.3500, 182.7500],
        1363 => [0.3550, 182.7500],
        1445 => [0.3600, 182.7500],
        1531 => [0.3650, 182.7500],
        1623 => [0.3700, 182.7500],
        1721 => [0.3750, 182.7500],
        1824 => [0.3800, 182.7500],
        1933 => [0.3850, 182.7500],
        2049 => [0.3900, 182.7500],
        2172 => [0.3950, 182.7500],
        2303 => [0.4000, 182.7500],
        2307 => [0.4050, 182.7500],
        2441 => [0.4500, 286.5962],
        2587 => [0.4550, 286.5962],
        2743 => [0.4600, 286.5962],
        2907 => [0.4650, 286.5962],
        3461 => [0.4700, 286.5962],
        999999999 => [0.55, 563.5192],
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

        // Only applies to payees claiming the tax free threshold.
        if (!$payee->claimsTaxFreeThreshold()) {
            return false;
        }

        // Only applies to payees with an STSL debt.
        if (!$payee->hasSTSLDebt()) {
            return false;
        }

        // Only applies to payees claiming a full Medicare Levy exemption.
        if ($payee->getMedicareLevyExemption() !== \ManageIt\PaygTax\Entities\Payee::MEDICARE_LEVY_EXEMPTION_FULL) {
            return false;
        }

        // Only applies to payees not claiming the Seniors Offset.
        if ($payee->getSeniorsOffset() !== \ManageIt\PaygTax\Entities\Payee::SENIORS_OFFSET_NONE) {
            return false;
        }

        return true;
    }
}
