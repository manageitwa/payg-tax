<?php

namespace ManageIt\PaygTax\Tests\Fixtures;

use ManageIt\PaygTax\Entities\Earning as EarningInterface;

class Earning implements EarningInterface
{
    public ?\DateTimeInterface $date = null;
    public float $gross = 0;

    public function getPayDate(): \DateTimeInterface
    {
        return $this->date ?? new \DateTime();
    }

    public function getGrossAmount(): float
    {
        return $this->gross;
    }
}
