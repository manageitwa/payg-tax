<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\TaxScales\July2023;

use ManageIt\PaygTax\Entities\Earning;
use ManageIt\PaygTax\Entities\Payee;
use ManageIt\PaygTax\Entities\Payer;
use ManageIt\PaygTax\TaxScales\BaseCoefficientScale;
use ManageIt\PaygTax\Utilities\Date;

/**
 * Tax scale for Australian residents who are claiming the tax free threshold and are claiming a half
 * Medicare levy exemption, and have an STSL debt.
 *
 * This tax scale has come into effect from 1 July 2023.
 */
class Nat3539Scale6 extends BaseCoefficientScale
{
    /**
     * {@inheritDoc}
     */
    protected array $coefficients = [
        359 => [0.0000, 0.0000],
        721 => [0.1900, 68.3462],
        739 => [0.1990, 74.8365],
        865 => [0.2490, 111.8308],
        924 => [0.3777, 223.2058],
        991 => [0.3377, 186.2119],
        1144 => [0.3477, 186.2119],
        1213 => [0.3577, 186.2119],
        1282 => [0.3627, 186.2119],
        1286 => [0.3600, 182.7504],
        1363 => [0.3650, 182.7504],
        1445 => [0.3700, 182.7504],
        1531 => [0.3750, 182.7504],
        1623 => [0.3800, 182.7504],
        1721 => [0.3850, 182.7504],
        1824 => [0.3900, 182.7504],
        1933 => [0.3950, 182.7504],
        2049 => [0.4000, 182.7504],
        2172 => [0.4050, 182.7504],
        2303 => [0.4100, 182.7504],
        2307 => [0.4150, 182.7504],
        2441 => [0.4600, 286.5965],
        2587 => [0.4650, 286.5965],
        2743 => [0.4700, 286.5965],
        2907 => [0.4750, 286.5965],
        3461 => [0.4800, 286.5965],
        999999999 => [0.56, 563.5196],
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

        // Only applies to payees claiming a half Medicare Levy exemption.
        if ($payee->getMedicareLevyExemption() !== \ManageIt\PaygTax\Entities\Payee::MEDICARE_LEVY_EXEMPTION_HALF) {
            return false;
        }

        // Only applies to payees not claiming the Seniors Offset.
        if ($payee->getSeniorsOffset() !== \ManageIt\PaygTax\Entities\Payee::SENIORS_OFFSET_NONE) {
            return false;
        }

        return true;
    }
}
