<?php

namespace ManageIt\PaygTax\Tests\Fixtures;

use ManageIt\PaygTax\Entities\Payee as PayeeInterface;

class Payee implements PayeeInterface
{
    public int $residencyStatus = self::RESIDENT;
    public bool $tfn = true;
    public bool $claimsTaxFreeThreshold = true;
    public int $payCycle = self::PAY_CYCLE_WEEKLY;
    public bool $stsl = false;
    public int $medicareLevyExemption = self::MEDICARE_LEVY_EXEMPTION_NONE;
    public int $seniorsOffset = self::SENIORS_OFFSET_NONE;
    /** @var \ManageIt\PaygTax\Entities\TaxAdjustment[] */
    public array $adjustments = [];
    public float $ytdGross = 0;

    public function getResidencyStatus(): int
    {
        return $this->residencyStatus;
    }

    public function hasTfnNumber(): bool
    {
        return $this->tfn;
    }

    public function getPayCycle(): int
    {
        return $this->payCycle;
    }

    public function claimsTaxFreeThreshold(): bool
    {
        return $this->claimsTaxFreeThreshold;
    }

    public function hasSTSLDebt(): bool
    {
        return $this->stsl;
    }

    public function getAdjustments(): array
    {
        return $this->adjustments;
    }

    public function getMedicareLevyExemption(): int
    {
        return $this->medicareLevyExemption;
    }

    public function getSeniorsOffset(): int
    {
        return $this->seniorsOffset;
    }

    public function getYtdGross(): float
    {
        return $this->ytdGross;
    }
}
