<?php

namespace ManageIt\PaygTax\TaxScales\October2020;

use ManageIt\PaygTax\Entities\Earning;
use ManageIt\PaygTax\Entities\Payee;
use ManageIt\PaygTax\Entities\Payer;
use ManageIt\PaygTax\TaxScales\BaseCoefficientScale;
use ManageIt\PaygTax\Utilities\Date;

/**
 * Tax scale for Australian residents who are claiming the Seniors and Pensioners Offset and are currently single.
 *
 * This tax scale has come into effect from 13 October 2020.
 */
class Nat4466Single extends BaseCoefficientScale
{
    /**
     * {@inheritDoc}
     */
    protected array $coefficients = [
        585 => [0, 0],
        646 => [0.19, 111.2308],
        693 => [0.315, 192.0529],
        721 => [0.415, 261.3913],
        865 => [0.424, 267.8817],
        989 => [0.4727, 309.9183],
        1282 => [0.3477, 186.2115],
        2307 => [0.345, 182.7504],
        3461 => [0.39, 286.5965],
        999999999 => [0.47, 563.5196],
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

        // Only applies to payees with a tax file number.
        if (!$payee->hasTfnNumber()) {
            return false;
        }

        // Cannot be a working holiday maker
        if ($payee->getResidencyStatus() === Payee::WORKING_HOLIDAY_MAKER) {
            return false;
        }

        // Only applies to payees claiming the Seniors Offset as a single.
        if ($payee->getSeniorsOffset() !== \ManageIt\PaygTax\Entities\Payee::SENIORS_OFFSET_SINGLE) {
            return false;
        }

        return true;
    }
}
