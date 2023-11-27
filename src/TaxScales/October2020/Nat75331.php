<?php

namespace ManageIt\PaygTax\TaxScales\October2020;

use ManageIt\PaygTax\Entities\Earning;
use ManageIt\PaygTax\Entities\Payee;
use ManageIt\PaygTax\Entities\Payer;
use ManageIt\PaygTax\Entities\TaxScale;
use ManageIt\PaygTax\Utilities\Date;
use ManageIt\PaygTax\Utilities\Math;

/**
 * Tax scale for Working Holiday Makers.
 *
 * This tax scale has come into effect from 13 October 2020.
 *
 * Note that this scale requires a YTD gross to be provided to correctly calculate tax, as tax percentages are based on
 * the running value.
 */
class Nat75331 implements TaxScale
{
    /**
     * {@inheritDoc}
     */
    public function isEligible(Payer $payer, Payee $payee, Earning $earning): bool
    {
        // Only applies after 13 October 2020.
        if (!Date::from($earning->getPayDate(), '2020-10-13')) {
            return false;
        }

        // Only applies to payees whose residency is as a Working Holiday Maker
        if ($payee->getResidencyStatus() !== \ManageIt\PaygTax\Entities\Payee::WORKING_HOLIDAY_MAKER) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function getTaxWithheldAmount(Payer $payer, Payee $payee, Earning $earning): float
    {
        if ($payee->getYtdGross() <= 45000 && $payee->hasTfnNumber()) {
            return Math::round($earning->getGrossAmount() * 0.15);
        }
        if ($payee->getYtdGross() <= 120000 && $payee->hasTfnNumber()) {
            return Math::round($earning->getGrossAmount() * 0.325);
        }
        if ($payee->getYtdGross() <= 180000 && $payee->hasTfnNumber()) {
            return Math::round($earning->getGrossAmount() * 0.37);
        }

        // Where withholding is calculated on the top marginal rate of tax or when no TFN is provided, ignore cents in
        // the withholding result.
        return floor(floor($earning->getGrossAmount()) * 0.45);
    }
}
