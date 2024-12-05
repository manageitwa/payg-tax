<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\Adjustments;

use ManageIt\PaygTax\Entities\Payer;
use ManageIt\PaygTax\Entities\Payee;
use ManageIt\PaygTax\Entities\Earning;
use ManageIt\PaygTax\Entities\TaxAdjustment;
use ManageIt\PaygTax\Entities\TaxScale;
use ManageIt\PaygTax\Traits\WeeklyConversion;
use ManageIt\PaygTax\Utilities\Date;
use ManageIt\PaygTax\Utilities\Math;
use ManageIt\PaygTax\Adjustments;

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
        // Not eligible if you do not have a TFN
        if ($payee->hasTfnNumber() === false) {
            return false;
        }

        // Not eligible if you're claiming a full Medicare levy exemption
        if ($payee->getMedicareLevyExemption() === Payee::MEDICARE_LEVY_EXEMPTION_FULL) {
            return false;
        }

        // Not eligible if you're not claiming the tax-free threshold
        if ($payee->claimsTaxFreeThreshold() === false) {
            return false;
        }

        // Not eligible if you're a foreign resident or Working Holiday Maker
        if ($payee->getResidencyStatus() !== Payee::RESIDENT) {
            return false;
        }

        // If claiming a half Medicare levy exemption (NAT 1004 Scale 6), the payee must be claiming children - the
        // reduction is not applicable to payees who only have a spouse
        if ($payee->getMedicareLevyExemption() === Payee::MEDICARE_LEVY_EXEMPTION_HALF && $this->children === 0) {
            return false;
        }

        // The payee must be claiming to have a spouse or children
        if ($this->spouse === false && $this->children === 0) {
            return false;
        }

        return true;
    }

    public function getAdjustmentAmount(Payer $payer, Payee $payee, TaxScale $taxScale, Earning $earning): float
    {
        if (Date::from($earning->getPayDate(), '2024-07-01')) {
            $adjustment = new Adjustments\July2024\MedicareLevyReduction($this->spouse, $this->children);
            return $adjustment->getAdjustmentAmount($payer, $payee, $taxScale, $earning);
        }

        $adjustment = new Adjustments\October2020\MedicareLevyReduction($this->spouse, $this->children);
        return $adjustment->getAdjustmentAmount($payer, $payee, $taxScale, $earning);
    }
}
