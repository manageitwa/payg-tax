<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\TaxScales;

use ManageIt\PaygTax\Entities\Earning;
use ManageIt\PaygTax\Entities\Payee;
use ManageIt\PaygTax\Entities\Payer;
use ManageIt\PaygTax\Exceptions\NoTaxScalesException;
use ManageIt\PaygTax\Utilities\Date;

/**
 * Tax scale for Seniors and Pensioners.
 *
 * The formulas below apply the Seniors and Pensioners Tax Offset.
 */
class Nat4466 extends BaseCoefficientScale
{
    /**
     * Coefficients for each scale in NAT1004 (except Scale 4), grouped by switchover date.
     *
     * @var array<string, array<string, array<int, array<int, int|float>>>>
     */
    protected array $scaledCoefficients = [
        '2020-10-01' => [
            'single' => [
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
            ],
            'illness-separated' => [
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
            ],
            'couple' => [
                521 => [0, 0],
                583 => [0.19, 99.1538],
                693 => [0.315, 172.0288],
                721 => [0.415, 241.3673],
                829 => [0.424, 247.8577],
                865 => [0.299, 144.175],
                1282 => [0.3477, 186.2115],
                2307 => [0.345, 182.7504],
                3461 => [0.39, 286.5965],
                999999 => [0.47, 563.5196],
            ],
        ],
        '2024-07-01' => [
            'single' => [
                629 => [0, 0],
                671 => [0.16, 100.7308],
                721 => [0.285, 184.6707],
                790 => [0.294, 191.1611],
                865 => [0.394, 270.1784],
                987 => [0.5277, 385.8803],
                1014 => [0.4477, 306.863],
                1282 => [0.3227, 180.0385],
                2596 => [0.32, 176.5769],
                3653 => [0.39, 358.3077],
                999999999 => [0.47, 650.6154],
            ],
            'illness-separated' => [
                606 => [0, 0],
                648 => [0.16, 97.0769],
                721 => [0.285, 178.1635],
                790 => [0.294, 184.6538],
                865 => [0.394, 263.6712],
                962 => [0.5277, 379.3731],
                987 => [0.4027, 259.0558],
                1282 => [0.3227, 180.0385],
                2596 => [0.32, 176.5769],
                3653 => [0.39, 358.3077],
                999999999 => [0.47, 650.6154],
            ],
            'couple' => [
                554 => [0, 0],
                596 => [0.16, 88.6538],
                721 => [0.285, 163.1587],
                790 => [0.294, 169.649],
                842 => [0.394, 248.6663],
                865 => [0.269, 143.3538],
                987 => [0.4027, 259.0558],
                1282 => [0.3227, 180.0385],
                2596 => [0.32, 176.5769],
                3653 => [0.39, 358.3077],
                999999999 => [0.47, 650.6154],
            ],
            // FULL Medicare Levy Exemption
            'single-fmle' => [
                629 => [0, 0],
                671 => [0.16, 100.7308],
                721 => [0.285, 184.6707],
                865 => [0.294, 191.1611],
                1014 => [0.4277, 306.863],
                1282 => [0.3027, 180.0385],
                2596 => [0.3, 176.5769],
                3653 => [0.37, 358.3077],
                999999999 => [0.45, 650.6154],
            ],
            'illness-separated-fmle' => [
                606 => [0, 0],
                648 => [0.16, 97.0769],
                721 => [0.285, 178.1635],
                865 => [0.294, 184.6538],
                962 => [0.4277, 300.3558],
                1282 => [0.3027, 180.0385],
                2596 => [0.3, 176.5769],
                3653 => [0.37, 358.3077],
                999999999 => [0.45, 650.6154],
            ],
            'couple-fmle' => [
                554 => [0, 0],
                596 => [0.16, 88.6538],
                721 => [0.285, 163.1587],
                842 => [0.294, 169.649],
                865 => [0.169, 64.3365],
                1282 => [0.3027, 180.0385],
                2596 => [0.3, 176.5769],
                3653 => [0.37, 358.3077],
                999999999 => [0.45, 650.6154],
            ],
            // HALF Medicare Levy Exemption
            'single-hmle' => [
                629 => [0, 0],
                671 => [0.16, 100.7308],
                721 => [0.285, 184.6707],
                865 => [0.294, 191.1611],
                1014 => [0.4277, 306.863],
                1099 => [0.3027, 180.0385],
                1282 => [0.3527, 235.0365],
                1374 => [0.35, 231.575],
                2596 => [0.31, 176.5769],
                3653 => [0.38, 358.3077],
                999999999 => [0.46, 650.6154],
            ],
            'illness-separated-hmle' => [
                606 => [0, 0],
                648 => [0.16, 97.0769],
                721 => [0.285, 178.1635],
                865 => [0.294, 184.6538],
                962 => [0.4277, 300.3558],
                1099 => [0.3027, 180.0385],
                1282 => [0.3527, 235.0365],
                1374 => [0.35, 231.575],
                2596 => [0.31, 176.5769],
                3653 => [0.38, 358.3077],
                999999999 => [0.46, 650.6154],
            ],
            'couple-hmle' => [
                554 => [0, 0],
                596 => [0.16, 88.6538],
                721 => [0.285, 163.1587],
                842 => [0.294, 169.649],
                865 => [0.169, 64.3365],
                1099 => [0.3027, 180.0385],
                1282 => [0.3527, 235.0365],
                1374 => [0.35, 231.575],
                2596 => [0.31, 176.5769],
                3653 => [0.38, 358.3077],
                999999999 => [0.46, 650.6154],
            ],
        ],
    ];

    /**
     * {@inheritDoc}
     */
    public function getCoefficients(Payer $payer, Payee $payee, Earning $earning): array
    {
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

        if (Date::from($earning->getPayDate(), '2024-07-01') && $payee->getMedicareLevyExemption() === Payee::MEDICARE_LEVY_EXEMPTION_FULL) {
            if ($payee->getSeniorsOffset() === Payee::SENIORS_OFFSET_SINGLE) {
                return $this->scaledCoefficients[$coefficientDate]['single-fmle'];
            }
            if ($payee->getSeniorsOffset() === Payee::SENIORS_OFFSET_ILLNESS_SEPARATED) {
                return $this->scaledCoefficients[$coefficientDate]['illness-separated-fmle'];
            }
            if ($payee->getSeniorsOffset() === Payee::SENIORS_OFFSET_COUPLE) {
                return $this->scaledCoefficients[$coefficientDate]['couple-fmle'];
            }
        } elseif (Date::from($earning->getPayDate(), '2024-07-01') && $payee->getMedicareLevyExemption() === Payee::MEDICARE_LEVY_EXEMPTION_HALF) {
            if ($payee->getSeniorsOffset() === Payee::SENIORS_OFFSET_SINGLE) {
                return $this->scaledCoefficients[$coefficientDate]['single-hmle'];
            }
            if ($payee->getSeniorsOffset() === Payee::SENIORS_OFFSET_ILLNESS_SEPARATED) {
                return $this->scaledCoefficients[$coefficientDate]['illness-separated-hmle'];
            }
            if ($payee->getSeniorsOffset() === Payee::SENIORS_OFFSET_COUPLE) {
                return $this->scaledCoefficients[$coefficientDate]['couple-hmle'];
            }
        } else {
            if ($payee->getSeniorsOffset() === Payee::SENIORS_OFFSET_SINGLE) {
                return $this->scaledCoefficients[$coefficientDate]['single'];
            }
            if ($payee->getSeniorsOffset() === Payee::SENIORS_OFFSET_ILLNESS_SEPARATED) {
                return $this->scaledCoefficients[$coefficientDate]['illness-separated'];
            }
            if ($payee->getSeniorsOffset() === Payee::SENIORS_OFFSET_COUPLE) {
                return $this->scaledCoefficients[$coefficientDate]['couple'];
            }
        }

        throw new NoTaxScalesException('Invalid seniors offset found');
    }
}
