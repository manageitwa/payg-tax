<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\TaxScales\July2024;

use ManageIt\PaygTax\Entities\Earning;
use ManageIt\PaygTax\Entities\Payee;
use ManageIt\PaygTax\Entities\Payer;
use ManageIt\PaygTax\TaxScales\BaseCoefficientScale;
use ManageIt\PaygTax\Utilities\Date;

/**
 * Standard tax scale for Australian residents not claiming the tax free threshold.
 *
 * This tax scale has come into effect from 1 July 2024.
 */
class Nat1004 extends BaseCoefficientScale
{
    /**
     * Coefficients for each scale in NAT1004 (except Scale 4).
     *
     * @var array<string, array<int, array<int, int|float>>>
     */
    protected array $scaledCoefficients = [
        'scale1' => [
            150 => [0.1600, 0.1600],
            371 => [0.2117, 7.7550],
            515 => [0.1890, -0.6702],
            932 => [0.3227, 68.2367],
            2246 => [0.3200, 65.7202],
            3303 => [0.3900, 222.9510],
            999999999 => [0.4700, 487.2587],
        ],
        'scale2' => [
            361 => [0.0000, 0.0000],
            500 => [0.1600, 57.8462],
            625 => [0.2600, 107.8462],
            721 => [0.1800, 57.8462],
            865 => [0.1890, 64.3365],
            1282 => [0.3227, 180.0385],
            2596 => [0.3200, 176.5769],
            3653 => [0.3900, 358.3077],
            999999999 => [0.4700, 650.6154],
        ],
        'scale3' => [
            2596 => [0.3000, 0.3000],
            3653 => [0.3700, 181.7308],
            999999999 => [0.4500, 474.0385],
        ],
        'scale5' => [
            361 => [0.0000, 0.0000],
            721 => [0.1600, 57.8462],
            865 => [0.1690, 64.3365],
            1282 => [0.3027, 180.0385],
            2596 => [0.3000, 176.5769],
            3653 => [0.3700, 358.3077],
            999999999 => [0.4500, 650.6154],
        ],
        'scale6' => [
            361 => [0.0000, 0.0000],
            721 => [0.1600, 57.8462],
            843 => [0.1690, 64.3365],
            865 => [0.2190, 106.4962],
            1053 => [0.3527, 222.1981],
            1282 => [0.3127, 180.0385],
            2596 => [0.3100, 176.5769],
            3653 => [0.3800, 358.3077],
            999999999 => [0.4600, 650.6154],
        ],
    ];

    /**
     * {@inheritDoc}
     */
    public function isEligible(Payer $payer, Payee $payee, Earning $earning): bool
    {
        // Only applies after 1 July 2024.
        if (!Date::from($earning->getPayDate(), '2024-07-01')) {
            return false;
        }

        // Only applies to Australian residents or foreign residents (not working holiday makers).
        if ($payee->getResidencyStatus() === \ManageIt\PaygTax\Entities\Payee::WORKING_HOLIDAY_MAKER) {
            return false;
        }

        // Only applies to payees without an STSL debt.
        if ($payee->hasSTSLDebt()) {
            return false;
        }

        // Only applies to payees not claiming the Seniors Offset.
        if ($payee->getSeniorsOffset() !== \ManageIt\PaygTax\Entities\Payee::SENIORS_OFFSET_NONE) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function getCoefficients(Payer $payer, Payee $payee, Earning $earning): array
    {
        if (!$payee->hasTfnNumber()) {
            return [];
        }

        // Foreign residents always use scale 3
        if ($payee->getResidencyStatus() === \ManageIt\PaygTax\Entities\Payee::FOREIGN_RESIDENT) {
            return $this->scaledCoefficients['scale3'];
        }

        // People not claiming tax free threshold must use scale 1
        if (!$payee->claimsTaxFreeThreshold()) {
            return $this->scaledCoefficients['scale1'];
        }

        // People claiming full Medicare levy exemption
        if ($payee->getMedicareLevyExemption() === \ManageIt\PaygTax\Entities\Payee::MEDICARE_LEVY_EXEMPTION_FULL) {
            return $this->scaledCoefficients['scale5'];
        }

        // People claiming half Medicare levy exemption
        if ($payee->getMedicareLevyExemption() === \ManageIt\PaygTax\Entities\Payee::MEDICARE_LEVY_EXEMPTION_HALF) {
            return $this->scaledCoefficients['scale6'];
        }

        return $this->scaledCoefficients['scale2'];
    }

    /**
     * {@inheritDoc}
     */
    public function getTaxWithheldAmount(Payer $payer, Payee $payee, Earning $earning): float
    {
        // Calculations for Scale 4 - No TFN Number
        if (!$payee->hasTfnNumber()) {
            // Residents have a flat 47% withholding if they do have a TFN, and foreign residents have a flat 45%.
            // Cents are discarded entirely.
            if ($payee->getResidencyStatus() === Payee::RESIDENT) {
                return floor(floor($earning->getGrossAmount()) * 0.47);
            }

            return floor(floor($earning->getGrossAmount()) * 0.45);
        }

        // Everything else
        return parent::getTaxWithheldAmount($payer, $payee, $earning);
    }
}
