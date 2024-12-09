<?php

namespace ManageIt\PaygTax\Tests\Fixtures;

use ManageIt\PaygTax\Entities\Payer as PayerInterface;

class Payer implements PayerInterface
{
    public bool $whmEmployer = false;

    public function isRegisteredWhmEmployer(): bool
    {
        return $this->whmEmployer;
    }
}
