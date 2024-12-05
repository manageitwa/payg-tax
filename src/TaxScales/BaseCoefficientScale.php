<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\TaxScales;

use ManageIt\PaygTax\Entities\Payer;
use ManageIt\PaygTax\Entities\Payee;
use ManageIt\PaygTax\Entities\Earning;
use ManageIt\PaygTax\Entities\TaxScale;
use ManageIt\PaygTax\Traits\WeeklyConversion;
use ManageIt\PaygTax\Utilities\Math;

abstract class BaseCoefficientScale implements TaxScale
{
    use WeeklyConversion;

    /**
     * Gets the applicable coefficients for the given payer, payee, and earning.
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
     * @return array<int, array<int|float>>
     */
    abstract public function getCoefficients(Payer $payer, Payee $payee, Earning $earning): array;

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
        $selectedCoefficient = null;
        $coefficients = $this->getCoefficients($payer, $payee, $earning);
        ksort($coefficients, SORT_NUMERIC);

        for ($i = 0; $i < count($coefficients); $i++) {
            $maxGross = array_keys($coefficients)[$i];

            if ($weeklyGross < $maxGross) {
                $selectedCoefficient = $coefficients[$maxGross];
                break;
            }
        }

        if (is_null($selectedCoefficient)) {
            throw new \Exception(sprintf(
                'Unable to determine applicable coefficient for gross amount of $%s in scale %s',
                $earning->getGrossAmount(),
                static::class
            ));
        }

        // Short circuit - if both coefficients are zero, no tax
        if ($selectedCoefficient[0] === 0 && $selectedCoefficient[1] === 0) {
            return 0;
        }

        // Calculate tax withheld
        $withheld = Math::round(($weeklyGross * $selectedCoefficient[0]) - $selectedCoefficient[1]);

        // Convert back to the pay cycle's amount
        return $this->convertWeeklyTax($payee->getPayCycle(), $withheld);
    }
}
