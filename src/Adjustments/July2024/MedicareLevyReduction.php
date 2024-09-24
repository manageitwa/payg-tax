<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\Adjustments\July2024;

use ManageIt\PaygTax\Entities\Payer;
use ManageIt\PaygTax\Entities\Payee;
use ManageIt\PaygTax\Entities\Earning;
use ManageIt\PaygTax\Entities\TaxAdjustment;
use ManageIt\PaygTax\Entities\TaxScale;
use ManageIt\PaygTax\TaxScales\July2024\Nat1004Scale2;
use ManageIt\PaygTax\TaxScales\July2024\Nat1004Scale6;
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
        // The payee must be claiming the tax free threshold
        if (!$payee->claimsTaxFreeThreshold()) {
            return false;
        }

        // The payee must be claiming to have a spouse or children
        if ($this->spouse === false && $this->children === 0) {
            return false;
        }

        // If claiming a half Medicare levy exemption, the payee must be claiming children - the
        // reduction is not applicable to payees who only have a spouse
        if (
            $payee->getMedicareLevyExemption() === \ManageIt\PaygTax\Entities\Payee::MEDICARE_LEVY_EXEMPTION_HALF
            && $this->children === 0
        ) {
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
            // Weekly gross must be $500 or higher, but less than the shading out point
            if ($weeklyGross < 500 || $weeklyGross >= $this->getShadingOutPoint($taxScale)) {
                return 0;
            }

            if ($weeklyGross < 625) {
                // Final value must be negative
                return $this->convertWeeklyTax(
                    $payee->getPayCycle(),
                    Math::round(
                        ($weeklyGross - 500)
                        * 0.1
                    )
                ) * -1;
            } elseif ($weeklyGross >= 625 && $weeklyGross <= $this->getWeeklyFamilyThreshold()) {
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
            // Weekly gross must be $843 or higher, but less than the shading out point
            if ($weeklyGross < 843 || $weeklyGross >= $this->getShadingOutPoint($taxScale)) {
                return 0;
            }

            if ($weeklyGross < 1053) {
                // Final value must be negative
                return $this->convertWeeklyTax(
                    $payee->getPayCycle(),
                    Math::round(
                        ($weeklyGross - 843.19)
                        * 0.05
                    )
                ) * -1;
            } elseif ($weeklyGross >= 1053 && $weeklyGross <= $this->getWeeklyFamilyThreshold()) {
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
            // $43,846 / 52 weeks
            return 843.192307692;
        }

        // Up to a maximum of 10 children can be claimed
        $children = min($this->children, 10);
        $annualThreshold = 43846 + ($children * 4027);
        return round($annualThreshold / 52, 2);
    }

    /**
     * Calculate the shading out point where the reduction is no longer applicable.
     */
    protected function getShadingOutPoint(TaxScale $taxScale): float
    {
        if ($taxScale instanceof Nat1004Scale2) {
            return floor(($this->getWeeklyFamilyThreshold() * 0.1) / 0.08);
        }

        return floor(($this->getWeeklyFamilyThreshold() * 0.05) / 0.04);
    }
}
