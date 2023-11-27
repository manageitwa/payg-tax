<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\TaxScales\July2022;

use ManageIt\PaygTax\Entities\Earning;
use ManageIt\PaygTax\Entities\Payee;
use ManageIt\PaygTax\Entities\Payer;
use ManageIt\PaygTax\TaxScales\BaseCoefficientScale;
use ManageIt\PaygTax\Utilities\Date;

/**
 * Tax scale for foreign residents (not working holiday makers) with an STSL debt.
 *
 * This tax scale has come into effect from 1 July 2022.
 */
class Nat3539Scale3 extends BaseCoefficientScale
{
    /**
     * {@inheritDoc}
     */
    protected array $coefficients = [
        930 => [0.3250, 0.3250],
        1073 => [0.3350, 0.3250],
        1138 => [0.3450, 0.3250],
        1206 => [0.3500, 0.3250],
        1278 => [0.3550, 0.3250],
        1355 => [0.3600, 0.3250],
        1436 => [0.3650, 0.3250],
        1523 => [0.3700, 0.3250],
        1614 => [0.3750, 0.3250],
        1711 => [0.3800, 0.3250],
        1814 => [0.3850, 0.3250],
        1923 => [0.3900, 0.3250],
        2038 => [0.3950, 0.3250],
        2160 => [0.4000, 0.3250],
        2290 => [0.4050, 0.3250],
        2307 => [0.4100, 0.3250],
        2427 => [0.4550, 103.8462],
        2573 => [0.4600, 103.8462],
        2727 => [0.4650, 103.8462],
        3461 => [0.4700, 103.8462],
        999999999 => [0.55, 380.7692],
    ];

    /**
     * {@inheritDoc}
     */
    public function isEligible(Payer $payer, Payee $payee, Earning $earning): bool
    {
        // Only applies between 1 July 2022 and 30 June 2023.
        if (!Date::between($earning->getPayDate(), '2022-07-01', '2023-06-30')) {
            return false;
        }

        // Only applies to foreign residents.
        if ($payee->getResidencyStatus() !== \ManageIt\PaygTax\Entities\Payee::FOREIGN_RESIDENT) {
            return false;
        }

        // Only applies to payees with a tax file number.
        if (!$payee->hasTfnNumber()) {
            return false;
        }

        // Only applies to payees with an STSL debt.
        if (!$payee->hasSTSLDebt()) {
            return false;
        }

        // Only applies to payees with no Medicare Levy exemption.
        if ($payee->getMedicareLevyExemption() !== \ManageIt\PaygTax\Entities\Payee::MEDICARE_LEVY_EXEMPTION_NONE) {
            return false;
        }

        // Only applies to payees not claiming the Seniors Offset.
        if ($payee->getSeniorsOffset() !== \ManageIt\PaygTax\Entities\Payee::SENIORS_OFFSET_NONE) {
            return false;
        }

        return true;
    }
}
