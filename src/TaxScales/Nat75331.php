<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\TaxScales;

use ManageIt\PaygTax\Entities\Earning;
use ManageIt\PaygTax\Entities\Payee;
use ManageIt\PaygTax\Entities\Payer;
use ManageIt\PaygTax\Entities\TaxScale;
use ManageIt\PaygTax\Utilities\Date;
use ManageIt\PaygTax\Utilities\Math;

/**
 * Tax scale for Working Holiday Makers.
 *
 * Note that this scale requires a YTD gross to be provided to correctly calculate tax, as tax percentages are based on
 * the running value.
 */
class Nat75331 implements TaxScale
{
    /**
     * {@inheritDoc}
     */
    public function getTaxWithheldAmount(Payer $payer, Payee $payee, Earning $earning): float
    {
        // WHM taxation changes from 1st July 2024.
        if (Date::from($earning->getPayDate(), '2024-07-01')) {
            if ($payee->getYtdGross() <= 45000 && $payee->hasTfnNumber()) {
                return Math::round($earning->getGrossAmount() * 0.15);
            }
            if ($payee->getYtdGross() <= 135000 && $payee->hasTfnNumber()) {
                return Math::round($earning->getGrossAmount() * 0.3);
            }
            if ($payee->getYtdGross() <= 190000 && $payee->hasTfnNumber()) {
                return Math::round($earning->getGrossAmount() * 0.37);
            }
        }

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
