<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\TaxScales\October2020;

use ManageIt\PaygTax\Entities\Earning;
use ManageIt\PaygTax\Entities\Payee;
use ManageIt\PaygTax\Entities\Payer;
use ManageIt\PaygTax\TaxScales\BaseCoefficientScale;
use ManageIt\PaygTax\Utilities\Date;

/**
 * Tax scale for Australian residents who are claiming the tax free threshold and are claiming a full
 * Medicare levy exemption and have an STSL debt.
 *
 * This tax scale has come into effect from 13 October 2020.
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
        896 => [0.3277, 186.2115],
        1035 => [0.3377, 186.2115],
        1097 => [0.3477, 186.2115],
        1163 => [0.3527, 186.2115],
        1232 => [0.3577, 186.2115],
        1282 => [0.3627, 186.2115],
        1306 => [0.3600, 182.7500],
        1385 => [0.3650, 182.7500],
        1468 => [0.3700, 182.7500],
        1556 => [0.3750, 182.7500],
        1649 => [0.3800, 182.7500],
        1748 => [0.3850, 182.7500],
        1853 => [0.3900, 182.7500],
        1965 => [0.3950, 182.7500],
        2082 => [0.4000, 182.7500],
        2205 => [0.4050, 182.7500],
        2307 => [0.4100, 182.7500],
        2340 => [0.4550, 286.5962],
        2480 => [0.4600, 286.5962],
        2629 => [0.4650, 286.5962],
        3461 => [0.4700, 286.5962],
        999999999 => [0.55, 563.5192],
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
