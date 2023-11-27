<?php

namespace ManageIt\PaygTax\TaxScales\October2020;

use ManageIt\PaygTax\Entities\Earning;
use ManageIt\PaygTax\Entities\Payee;
use ManageIt\PaygTax\Entities\Payer;
use ManageIt\PaygTax\TaxScales\BaseCoefficientScale;
use ManageIt\PaygTax\Utilities\Date;

/**
 * Tax scale for Australian residents who are claiming the Seniors and Pensioners Offset and are a member of a couple
 * separated by illness or other circumstance.
 *
 * This tax scale has come into effect from 13 October 2020.
 */
class Nat4466IllnessSeparated extends BaseCoefficientScale
{
    /**
     * {@inheritDoc}
     */
    protected array $coefficients = [
        566 => [0, 0],
        627 => [0.19, 107.5769],
        693 => [0.315, 185.9952],
        721 => [0.415, 255.3337],
        865 => [0.424, 261.824],
        941 => [0.4727, 303.8606],
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

        // Only applies to payees claiming the Seniors Offset as illness-separated.
        if ($payee->getSeniorsOffset() !== \ManageIt\PaygTax\Entities\Payee::SENIORS_OFFSET_ILLNESS_SEPARATED) {
            return false;
        }

        return true;
    }
}
