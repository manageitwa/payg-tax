<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\TaxScales;

use ManageIt\PaygTax\Entities\Earning;
use ManageIt\PaygTax\Entities\Payee;
use ManageIt\PaygTax\Entities\Payer;
use ManageIt\PaygTax\Exceptions\NoTaxScalesException;
use ManageIt\PaygTax\Utilities\Date;

/**
 * Standard tax scale for wages and salaries.
 *
 * Does not apply to Working Holiday Makers, a payee with an STSL debt, or a payee claiming the Seniors Offset.
 */
class Nat1004 extends BaseCoefficientScale
{
    /**
     * Coefficients for each scale in NAT1004 (except Scale 4), grouped by switchover date.
     *
     * @var array<string, array<string, array<int, array<int, int|float>>>>
     */
    protected array $scaledCoefficients = [
        '2020-10-01' => [
            'scale1' => [
                88 => [0.19, 0.19],
                371 => [0.2348, 3.9639],
                515 => [0.219, -1.9003],
                932 => [0.3477, 64.4297],
                1957 => [0.345, 61.9132],
                3111 => [0.39, 150.0093],
                999999999 => [0.47, 398.9324],
            ],
            'scale2' => [
                359 => [0, 0],
                438 => [0.19, 68.3462],
                548 => [0.29, 112.1942],
                721 => [0.21, 68.3465],
                865 => [0.219, 74.8369],
                1282 => [0.3477, 186.2119],
                2307 => [0.345, 182.7504],
                3461 => [0.39, 286.5965],
                999999999 => [0.47, 563.5196],
            ],
            'scale3' => [
                2307 => [0.325, 0.325],
                3461 => [0.37, 103.8462],
                999999999 => [0.45, 380.7692],
            ],
            'scale5' => [
                359 => [0.0000, 0.0000],
                721 => [0.1900, 68.3462],
                865 => [0.1990, 74.8365],
                1282 => [0.3277, 186.2115],
                2307 => [0.3250, 182.7500],
                3461 => [0.3700, 286.5962],
                999999999 => [0.45, 563.5192],
            ],
            'scale6' => [
                359 => [0.0000, 0.0000],
                721 => [0.1900, 68.3462],
                739 => [0.1990, 74.8365],
                865 => [0.2490, 111.8308],
                924 => [0.3777, 223.2058],
                1282 => [0.3377, 186.2119],
                2307 => [0.3350, 182.7504],
                3461 => [0.3800, 286.5965],
                999999999 => [0.46, 563.5196],
            ],
        ],
        '2024-07-01' => [
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
        ],
    ];

    /**
     * {@inheritDoc}
     */
    public function getCoefficients(Payer $payer, Payee $payee, Earning $earning): array
    {
        if (!$payee->hasTfnNumber()) {
            return [];
        }

        // Work out date to apply
        $coefficientDate = null;

        foreach (array_keys($this->scaledCoefficients) as $date) {
            if (!Date::from($earning->getPayDate(), $date)) {
                break;
            }
            $coefficientDate = $date;
        }

        if (is_null($coefficientDate)) {
            return [];
        }

        // Foreign residents always use scale 3
        if ($payee->getResidencyStatus() === \ManageIt\PaygTax\Entities\Payee::FOREIGN_RESIDENT) {
            return $this->scaledCoefficients[$coefficientDate]['scale3'];
        }

        // People not claiming tax free threshold must use scale 1
        if (!$payee->claimsTaxFreeThreshold()) {
            return $this->scaledCoefficients[$coefficientDate]['scale1'];
        }

        // People claiming full Medicare levy exemption
        if ($payee->getMedicareLevyExemption() === \ManageIt\PaygTax\Entities\Payee::MEDICARE_LEVY_EXEMPTION_FULL) {
            return $this->scaledCoefficients[$coefficientDate]['scale5'];
        }

        // People claiming half Medicare levy exemption
        if ($payee->getMedicareLevyExemption() === \ManageIt\PaygTax\Entities\Payee::MEDICARE_LEVY_EXEMPTION_HALF) {
            return $this->scaledCoefficients[$coefficientDate]['scale6'];
        }

        return $this->scaledCoefficients[$coefficientDate]['scale2'];
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
        } elseif ($this->getCoefficients($payer, $payee, $earning) === []) {
            throw new NoTaxScalesException('Tax scales not found for the given earning date');
        }

        // Everything else
        return parent::getTaxWithheldAmount($payer, $payee, $earning);
    }
}
