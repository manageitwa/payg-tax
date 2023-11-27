<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\Traits;

use ManageIt\PaygTax\Entities\Payee;
use ManageIt\PaygTax\Utilities\Math;

trait WeeklyConversion
{
    /**
     * Determines the weekly gross amount for the given pay cycle.
     */
    protected function getWeeklyGross(int $payCycle, float $gross): float
    {
        switch ($payCycle) {
            case Payee::PAY_CYCLE_CASUAL:
            case Payee::PAY_CYCLE_DAILY:
                return floor($gross * 5) + 0.99;
            case Payee::PAY_CYCLE_FORTNIGHTLY:
                return floor($gross / 2) + 0.99;
            case Payee::PAY_CYCLE_MONTHLY:
                $cents = round($gross - floor($gross), 2);
                $gross = ($cents === 0.33) ? ($gross + 0.01) : $gross;
                return floor(($gross * 3) / 13) + 0.99;
            case Payee::PAY_CYCLE_QUARTERLY:
                return floor($gross / 13) + 0.99;
            default:
                return floor($gross) + 0.99;
        }
    }

    /**
     * Converts a weekly tax amount back to the corresponding pay cycle.
     */
    protected function convertWeeklyTax(int $payCycle, float $withheld): float
    {
        switch ($payCycle) {
            case Payee::PAY_CYCLE_CASUAL:
            case Payee::PAY_CYCLE_DAILY:
                return Math::round($withheld / 5);
            case Payee::PAY_CYCLE_FORTNIGHTLY:
                return $withheld * 2;
            case Payee::PAY_CYCLE_MONTHLY:
                return Math::round(($withheld * 13) / 3);
            case Payee::PAY_CYCLE_QUARTERLY:
                return $withheld * 13;
            default:
                return $withheld;
        }
    }
}
