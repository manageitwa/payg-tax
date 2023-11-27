<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\Adjustments;

use ManageIt\PaygTax\Entities\Payer;
use ManageIt\PaygTax\Entities\Payee;
use ManageIt\PaygTax\Entities\Earning;
use ManageIt\PaygTax\Entities\TaxAdjustment;
use ManageIt\PaygTax\Entities\TaxScale;

class TaxOffset implements TaxAdjustment
{
    public function isEligible(Payer $payer, Payee $payee, TaxScale $taxScale, Earning $earning): bool
    {
        return true;
    }

    public function getAdjustmentAmount(Payer $payer, Payee $payee, TaxScale $taxScale, Earning $earning): float
    {
        return 0;
    }
}
