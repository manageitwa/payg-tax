<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\Adjustments;

use ManageIt\PaygTax\Entities\Payer;
use ManageIt\PaygTax\Entities\Payee;
use ManageIt\PaygTax\Entities\Earning;
use ManageIt\PaygTax\Entities\TaxAdjustment;
use ManageIt\PaygTax\Entities\TaxScale;

class ExtraPayPeriod implements TaxAdjustment
{
    public function isEligible(Payer $payer, Payee $payee, TaxScale $taxScale, Earning $earning): bool
    {
        // Only available to weekly and fortnightly pay cycles
        if ($payee->getPayCycle() !== Payee::PAY_CYCLE_WEEKLY && $payee->getPayCycle() !== Payee::PAY_CYCLE_FORTNIGHTLY) {
            return false;
        }

        return true;
    }

    public function getAdjustmentAmount(Payer $payer, Payee $payee, TaxScale $taxScale, Earning $earning): float
    {
        // Do not withhold extra tax if the tax withheld amount is zero
        if ($taxScale->getTaxWithheldAmount($payer, $payee, $earning) === (float) 0) {
            return 0;
        }

        // Weekly extra pay period
        if ($payee->getPayCycle() === Payee::PAY_CYCLE_WEEKLY) {
            if ($earning->getGrossAmount() >= 875 && $earning->getGrossAmount() <= 2299) {
                return 3;
            }
            if ($earning->getGrossAmount() >= 2300 && $earning->getGrossAmount() <= 3449) {
                return 5;
            }
            if ($earning->getGrossAmount() >= 3450) {
                return 10;
            }
        } elseif ($payee->getPayCycle() === Payee::PAY_CYCLE_FORTNIGHTLY) {
            if ($earning->getGrossAmount() > 1750 && $earning->getGrossAmount() <= 4549) {
                return 13;
            }
            if ($earning->getGrossAmount() > 4540 && $earning->getGrossAmount() <= 6749) {
                return 21;
            }
            if ($earning->getGrossAmount() >= 6750) {
                return 40;
            }
        }

        return 0;
    }
}
