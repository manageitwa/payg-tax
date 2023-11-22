<?php

namespace ManageIt\PaygTax\TaxScales\October2020;

use ManageIt\PaygTax\Entities\Earning;
use ManageIt\PaygTax\Entities\Payee;
use ManageIt\PaygTax\Entities\Payer;
use ManageIt\PaygTax\TaxScales\BaseCoefficientScale;
use ManageIt\PaygTax\Utilities\Date;

/**
 * Standard tax scale for Australian residents who are claiming the tax free threshold and are claiming a half
 * Medicare levy exemption.
 *
 * This tax scale has come into effect from 13 October 2020.
 */
class Nat1004Scale6 extends BaseCoefficientScale
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
        1282 => [0.3377, 186.2119],
        2307 => [0.3350, 182.7504],
        3461 => [0.3800, 286.5965],
        999999999 => [0.46, 563.5196],
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
