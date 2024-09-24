<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\TaxScales\October2020;

use ManageIt\PaygTax\Entities\Earning;
use ManageIt\PaygTax\Entities\Payee;
use ManageIt\PaygTax\Entities\Payer;
use ManageIt\PaygTax\TaxScales\BaseCoefficientScale;
use ManageIt\PaygTax\Utilities\Date;

/**
 * Tax scale for Australian residents who are claiming the Seniors and Pensioners Offset and are a member of a couple.
 *
 * This tax scale has come into effect from 13 October 2020.
 */
class Nat4466Couple extends BaseCoefficientScale
{
    /**
     * {@inheritDoc}
     */
    protected array $coefficients = [
        521 => [0, 0],
        583 => [0.19, 99.1538],
        693 => [0.315, 172.0288],
        721 => [0.415, 241.3673],
        829 => [0.424, 247.8577],
        865 => [0.299, 144.175],
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
        // Only applies after 13 October 2020 and before 1 July 2024.
        if (!Date::between($earning->getPayDate(), '2020-10-13', '2024-06-30')) {
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

        // Only applies to payees claiming the Seniors Offset as a member of a couple
        if ($payee->getSeniorsOffset() !== \ManageIt\PaygTax\Entities\Payee::SENIORS_OFFSET_COUPLE) {
            return false;
        }

        return true;
    }
}
