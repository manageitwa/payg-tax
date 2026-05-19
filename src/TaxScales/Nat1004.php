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
                359 => [0.0, 0.0],
                721 => [0.19, 68.3462],
                865 => [0.199, 74.8365],
                1282 => [0.3277, 186.2115],
                2307 => [0.325, 182.75],
                3461 => [0.37, 286.5962],
                999999999 => [0.45, 563.5192],
            ],
            'scale6' => [
                359 => [0.0, 0.0],
                721 => [0.19, 68.3462],
                739 => [0.199, 74.8365],
                865 => [0.249, 111.8308],
                924 => [0.3777, 223.2058],
                1282 => [0.3377, 186.2119],
                2307 => [0.335, 182.7504],
                3461 => [0.38, 286.5965],
                999999999 => [0.46, 563.5196],
            ],
        ],
        '2024-07-01' => [
            'scale1' => [
                150 => [0.16, 0.16],
                371 => [0.2117, 7.755],
                515 => [0.189, -0.6702],
                932 => [0.3227, 68.2367],
                2246 => [0.32, 65.7202],
                3303 => [0.39, 222.951],
                999999999 => [0.47, 487.2587],
            ],
            'scale2' => [
                361 => [0.0, 0.0],
                500 => [0.16, 57.8462],
                625 => [0.26, 107.8462],
                721 => [0.18, 57.8462],
                865 => [0.189, 64.3365],
                1282 => [0.3227, 180.0385],
                2596 => [0.32, 176.5769],
                3653 => [0.39, 358.3077],
                999999999 => [0.47, 650.6154],
            ],
            'scale3' => [
                2596 => [0.3, 0.3],
                3653 => [0.37, 181.7308],
                999999999 => [0.45, 474.0385],
            ],
            'scale5' => [
                361 => [0.0, 0.0],
                721 => [0.16, 57.8462],
                865 => [0.169, 64.3365],
                1282 => [0.3027, 180.0385],
                2596 => [0.3, 176.5769],
                3653 => [0.37, 358.3077],
                999999999 => [0.45, 650.6154],
            ],
            'scale6' => [
                361 => [0.0, 0.0],
                721 => [0.16, 57.8462],
                843 => [0.169, 64.3365],
                865 => [0.219, 106.4962],
                1053 => [0.3527, 222.1981],
                1282 => [0.3127, 180.0385],
                2596 => [0.31, 176.5769],
                3653 => [0.38, 358.3077],
                999999999 => [0.46, 650.6154],
            ],
        ],
        '2026-07-01' => [
            'scale1' => [
                188 => [0.15, 0.15],
                371 => [0.2084, 11.0185],
                515 => [0.179, 0.1066],
                932 => [0.3227, 74.1674],
                2246 => [0.32, 71.6508],
                3303 => [0.39, 228.8816],
                999999999 => [0.47, 493.1893],
            ],
            'scale2' => [
                362 => [0.0, 0.0],
                538 => [0.15, 54.3462],
                673 => [0.25, 108.2135],
                721 => [0.17, 54.3473],
                865 => [0.179, 60.8377],
                1282 => [0.3227, 185.1935],
                2596 => [0.32, 181.7319],
                3653 => [0.39, 363.4627],
                999999999 => [0.47, 655.7704],
            ],
            'scale3' => [
                2596 => [0.3, 0.3],
                3653 => [0.37, 181.7308],
                999999999 => [0.45, 474.0385],
            ],
            'scale5' => [
                362 => [0.0, 0.0],
                721 => [0.15, 54.3462],
                865 => [0.159, 60.8365],
                1282 => [0.3027, 185.1923],
                2596 => [0.3, 181.7308],
                3653 => [0.37, 363.4615],
                999999999 => [0.45, 655.7692],
            ],
            'scale6' => [
                362 => [0.0, 0.0],
                721 => [0.15, 54.3462],
                865 => [0.159, 60.8365],
                908 => [0.3027, 185.1923],
                1135 => [0.3527, 230.6135],
                1282 => [0.3127, 185.1923],
                2596 => [0.31, 181.7308],
                3653 => [0.38, 363.4615],
                999999999 => [0.46, 655.7692],
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

        // Foreign residents always use scale 3. If the payee is a Working Holiday Maker but the payer is not yet
        // registered as a WHM employer, they also use scale 3.
        if (
            $payee->getResidencyStatus() === \ManageIt\PaygTax\Entities\Payee::FOREIGN_RESIDENT ||
            ($payee->getResidencyStatus() === \ManageIt\PaygTax\Entities\Payee::WORKING_HOLIDAY_MAKER &&
                !$payer->isRegisteredWhmEmployer())
        ) {
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
