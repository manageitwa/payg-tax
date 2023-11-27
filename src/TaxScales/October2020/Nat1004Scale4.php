<?php

namespace ManageIt\PaygTax\TaxScales\October2020;

use ManageIt\PaygTax\Entities\Earning;
use ManageIt\PaygTax\Entities\Payee;
use ManageIt\PaygTax\Entities\Payer;
use ManageIt\PaygTax\Entities\TaxScale;
use ManageIt\PaygTax\Utilities\Date;

/**
 * Standard tax scale for any person who has not provided a Tax File Number.
 *
 * A person with a no tax file number cannot claim any offsets or adjustments.
 *
 * This tax scale has come into effect from 13 October 2020.
 */
class Nat1004Scale4 implements TaxScale
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

        // Only applies to payees without a tax file number.
        if ($payee->hasTfnNumber()) {
            return false;
        }

        // Working Holiday Makers still use their own tax scale.
        if ($payee->getResidencyStatus() === Payee::WORKING_HOLIDAY_MAKER) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function getTaxWithheldAmount(Payer $payer, Payee $payee, Earning $earning): float
    {
        // Residents have a flat 47% withholding if they do have a TFN, and foreign residents have a flat 45%.
        // Cents are discarded entirely.
        if ($payee->getResidencyStatus() === Payee::RESIDENT) {
            return floor(floor($earning->getGrossAmount()) * 0.47);
        }

        return floor(floor($earning->getGrossAmount()) * 0.45);
    }
}
