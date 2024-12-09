<?php

namespace ManageIt\PaygTax\Tests\TaxScales\October2020;

use ManageIt\PaygTax\PaygTax;
use ManageIt\PaygTax\Tests\Fixtures\Earning;
use ManageIt\PaygTax\Tests\Fixtures\Payee;
use ManageIt\PaygTax\Tests\Fixtures\Payer;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;

/**
 * @covers \ManageIt\PaygTax\TaxScales\Nat1004
 */
class Nat1004Scale4Test extends TestCase
{
    /**
     * @dataProvider weeklyData
     */
    public function testWeeklyWithholding(int $gross, int $withheld, bool $resident): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->payCycle = Payee::PAY_CYCLE_WEEKLY;
        $payee->residencyStatus = ($resident) ? Payee::RESIDENT : Payee::FOREIGN_RESIDENT;
        $payee->tfn = false;
        $payee->claimsTaxFreeThreshold = false;
        $payee->stsl = false;

        $earning = new Earning();
        $earning->date = new \DateTime('2022-10-15');
        $earning->gross = $gross;

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($withheld, $payg->getTaxWithheldAmount());
    }

    /**
     * @return array<int, array<int|float, int|float|bool>>
     */
    public function weeklyData(): array
    {
        return [
            // Australian Residents
            [87, 40, true],
            [88, 41, true],
            [116, 54, true],
            [370, 173, true],
            [547, 257, true],
            [739, 347, true],
            [931, 437, true],
            [1845, 867, true],
            [2307, 1084, true],
            [2737, 1286, true],
            [3461, 1626, true],
            // Foreign residents
            [87, 39, false],
            [88, 39, false],
            [116, 52, false],
            [370, 166, false],
            [547, 246, false],
            [739, 332, false],
            [931, 418, false],
            [1845, 830, false],
            [2307, 1038, false],
            [2737, 1231, false],
            [3461, 1557, false],
        ];
    }
}
