<?php

namespace ManageIt\PaygTax\TaxScales;

use ManageIt\PaygTax\Entities\Payer;
use ManageIt\PaygTax\Entities\Payee;
use ManageIt\PaygTax\Entities\Earning;
use ManageIt\PaygTax\Entities\TaxScale;
use ManageIt\PaygTax\Utilities\Math;

abstract class BaseCoefficientScale implements TaxScale
{
    /**
     * The coefficients to be applied to the earnings amount.
     *
     * This must be an array of coefficients, with the key being the maximum amount of gross earning to which the
     * coefficients will apply to, and the value being an array of two values: the percentage of tax to be withheld,
     * and a flat adjustment made after the percentage is applied.
     *
     * This should be ordered from lowest gross amount to highest, as per the ATO's specification.
     *
     * ```
     * ['max gross amount' => ['percentage', 'adjustment']]
     * ```
     *
     * @var array<int, array<int, int|float>>
     */
    protected array $coefficients = [];

    /**
     * {@inheritDoc}
     */
    abstract public function isEligible(Payer $payer, Payee $payee, Earning $earning): bool;

    /**
     * {@inheritDoc}
     */
    public function getTaxWithheldAmount(Payer $payer, Payee $payee, Earning $earning): float
    {
        // If no earnings, no tax
        if ($earning->getGrossAmount() <= 0) {
            return 0;
        }

        // Get weekly gross
        $weeklyGross = $this->getWeeklyGross(
            $payee->getPayCycle(),
            $earning->getGrossAmount(),
        );

        // Determine applicable coefficient
        $coefficient = null;
        ksort($this->coefficients, SORT_NUMERIC);

        for ($i = 0; $i < count($this->coefficients); $i++) {
            $maxGross = array_keys($this->coefficients)[$i];

            if ($weeklyGross < $maxGross) {
                $coefficient = $this->coefficients[$maxGross];
                break;
            }
        }

        if (is_null($coefficient)) {
            throw new \Exception(sprintf(
                'Unable to determine applicable coefficient for gross amount of $%s in scale %s',
                $earning->getGrossAmount(),
                static::class
            ));
        }

        // Short circuit - if both coefficients are zero, no tax
        if ($coefficient[0] === 0 && $coefficient[1] === 0) {
            return 0;
        }

        // Calculate tax withheld
        $withheld = Math::round(($weeklyGross * $coefficient[0]) - $coefficient[1]);

        // Convert back to the pay cycle's amount
        return $this->convertWeeklyTax($payee->getPayCycle(), $withheld);
    }

    /**
     * Determines the weekly gross amount for the given pay cycle.
     *
     * Since coefficients are given by the ATO in weekly amounts, we need to convert the gross amount to a weekly
     * gross.
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
     *
     * Per the ATO, all coefficients are given in weekly amounts. This method will convert the weekly tax amount back to
     * the corresponding pay cycle.
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
