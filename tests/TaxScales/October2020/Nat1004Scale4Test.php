<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\Tests\TaxScales\October2020;

use ManageIt\PaygTax\PaygTax;
use ManageIt\PaygTax\Tests\Fixtures\Earning;
use ManageIt\PaygTax\Tests\Fixtures\Payee;
use ManageIt\PaygTax\Tests\Fixtures\Payer;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;

#[\PHPUnit\Framework\Attributes\CoversClass(\ManageIt\PaygTax\TaxScales\Nat1004::class)]
final class Nat1004Scale4Test extends TestCase
{
    #[\PHPUnit\Framework\Attributes\DataProvider('weeklyData')]
    public function testWeeklyWithholding(int $gross, int $withheld, bool $resident): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->payCycle = Payee::PAY_CYCLE_WEEKLY;
        $payee->residencyStatus = $resident ? Payee::RESIDENT : Payee::FOREIGN_RESIDENT;
        $payee->tfn = false;
        $payee->claimsTaxFreeThreshold = false;
        $payee->stsl = false;

        $earning = new Earning();
        $earning->date = new \DateTime('2022-10-15');
        $earning->gross = $gross;

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($withheld, $payg->getTaxWithheldAmount());
    }

    /**
     * @return \Iterator<int, array<int, (bool | float | int)>>
     */
    public static function weeklyData(): \Iterator
    {
        // Australian Residents
        yield [87, 40, true];
        yield [88, 41, true];
        yield [116, 54, true];
        yield [370, 173, true];
        yield [547, 257, true];
        yield [739, 347, true];
        yield [931, 437, true];
        yield [1845, 867, true];
        yield [2307, 1084, true];
        yield [2737, 1286, true];
        yield [3461, 1626, true];
        // Foreign residents
        yield [87, 39, false];
        yield [88, 39, false];
        yield [116, 52, false];
        yield [370, 166, false];
        yield [547, 246, false];
        yield [739, 332, false];
        yield [931, 418, false];
        yield [1845, 830, false];
        yield [2307, 1038, false];
        yield [2737, 1231, false];
        yield [3461, 1557, false];
    }
}
