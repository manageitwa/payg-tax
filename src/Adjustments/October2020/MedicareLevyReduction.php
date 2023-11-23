<?php

namespace ManageIt\PaygTax\Adjustments\October2020;

use ManageIt\PaygTax\Entities\Payer;
use ManageIt\PaygTax\Entities\Payee;
use ManageIt\PaygTax\Entities\Earning;
use ManageIt\PaygTax\Entities\TaxAdjustment;
use ManageIt\PaygTax\Entities\TaxScale;
use ManageIt\PaygTax\TaxScales\October2020\Nat1004Scale2;
use ManageIt\PaygTax\TaxScales\October2020\Nat1004Scale6;
use ManageIt\PaygTax\Traits\WeeklyConversion;
use ManageIt\PaygTax\Utilities\Math;

class MedicareLevyReduction implements TaxAdjustment
{
    use WeeklyConversion;

    /**
     * Whether the payee is claiming to have a spouse.
     */
    public bool $spouse = true;

    /**
     * The number of children the payee is claiming for the Medicare Levy Reduction.
     */
    public int $children = 0;

    public function __construct(bool $spouse = true, int $children = 0)
    {
        $this->spouse = $spouse;
        $this->children = $children;
    }

    public function isEligible(Payer $payer, Payee $payee, TaxScale $taxScale, Earning $earning): bool
    {
        // The tax scale must be either of the following tax scales
        if (
            $taxScale instanceof Nat1004Scale2 === false
            && $taxScale instanceof Nat1004Scale6 === false
        ) {
            return false;
        }

        // The payee must be claiming to have a spouse or children
        if ($this->spouse === false && $this->children === 0) {
            return false;
        }

        // If claiming a half Medicare levy exemption (NAT 1004 Scale 6), the payee must be claiming children - the
        // reduction is not applicable to payees who only have a spouse
        if ($taxScale instanceof Nat1004Scale6 && $this->children === 0) {
            return false;
        }

        return true;
    }

    public function getAdjustmentAmount(Payer $payer, Payee $payee, TaxScale $taxScale, Earning $earning): float
    {
        $weeklyGross = $this->getWeeklyGross(
            $payee->getPayCycle(),
            $earning->getGrossAmount(),
        );

        if ($taxScale instanceof Nat1004Scale2) {
            // Weekly gross must be $438 or higher, but less than the shading out point
            if ($weeklyGross < 438 || $weeklyGross >= $this->getShadingOutPoint()) {
                return 0;
            }

            if ($weeklyGross < 548) {
                // Final value must be negative
                return $this->convertWeeklyTax(
                    $payee->getPayCycle(),
                    Math::round(
                        ($weeklyGross - 438.48)
                        * 0.1
                    )
                ) * -1;
            } elseif ($weeklyGross >= 548 && $weeklyGross <= $this->getWeeklyFamilyThreshold()) {
                // Final value must be negative
                return $this->convertWeeklyTax(
                    $payee->getPayCycle(),
                    Math::round($weeklyGross * 0.02)
                ) * -1;
            } else {
                // Final value must be negative
                return $this->convertWeeklyTax(
                    $payee->getPayCycle(),
                    Math::round(
                        ($this->getWeeklyFamilyThreshold() * 0.02)
                        - (($weeklyGross - $this->getWeeklyFamilyThreshold()) * 0.08)
                    )
                ) * -1;
            }
        } elseif ($taxScale instanceof Nat1004Scale6) {
            // Weekly gross must be $739 or higher, but less than the shading out point
            if ($weeklyGross < 739 || $weeklyGross >= $this->getShadingOutPoint()) {
                return 0;
            }

            if ($weeklyGross < 924) {
                // Final value must be negative
                return $this->convertWeeklyTax(
                    $payee->getPayCycle(),
                    Math::round(
                        ($weeklyGross - 739.88)
                        * 0.05
                    )
                ) * -1;
            } elseif ($weeklyGross >= 924 && $weeklyGross <= $this->getWeeklyFamilyThreshold()) {
                // Final value must be negative
                return $this->convertWeeklyTax(
                    $payee->getPayCycle(),
                    Math::round($weeklyGross * 0.01)
                ) * -1;
            } else {
                // Final value must be negative
                return $this->convertWeeklyTax(
                    $payee->getPayCycle(),
                    Math::round(
                        ($this->getWeeklyFamilyThreshold() * 0.01)
                        - (($weeklyGross - $this->getWeeklyFamilyThreshold()) * 0.04)
                    )
                ) * -1;
            }
        }

        return 0;
    }

    /**
     * Calculate the weekly family threshold, where the reduction starts to apply.
     */
    protected function getWeeklyFamilyThreshold(): float
    {
        if ($this->spouse === true && $this->children === 0) {
            // $38,474 / 52 weeks
            return 739.88;
        }

        // Up to a maximum of 10 children can be claimed
        $children = min($this->children, 10);
        $annualThreshold = 38474 + ($children * 3533);
        return round($annualThreshold / 52, 2);
    }

    /**
     * Calculate the shading out point where the reduction is no longer applicable.
     */
    protected function getShadingOutPoint(): float
    {
        return floor(($this->getWeeklyFamilyThreshold() * 0.1) / 0.08);
    }
}
