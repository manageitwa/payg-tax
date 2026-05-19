<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\Tests\Adjustments;

use ManageIt\PaygTax\Adjustments\ExtraPayPeriod;
use ManageIt\PaygTax\TaxScales\Nat1004;
use ManageIt\PaygTax\Tests\Fixtures\Earning;
use ManageIt\PaygTax\Tests\Fixtures\Payee;
use ManageIt\PaygTax\Tests\Fixtures\Payer;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;

#[\PHPUnit\Framework\Attributes\CoversClass(\ManageIt\PaygTax\Adjustments\ExtraPayPeriod::class)]
final class ExtraPayPeriodTest extends TestCase
{
    protected ExtraPayPeriod $adjustment;

    public function setUp(): void
    {
        $this->adjustment = new ExtraPayPeriod();
    }

    public function testEligibility(): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = true;

        $earning = new Earning();
        $earning->date = new \DateTime('2022-10-10');

        $scale = new Nat1004();

        Assert::assertTrue($this->adjustment->isEligible($payer, $payee, $scale, $earning));

        $payee->residencyStatus = Payee::FOREIGN_RESIDENT;
        Assert::assertTrue($this->adjustment->isEligible($payer, $payee, $scale, $earning));

        $payee->residencyStatus = Payee::RESIDENT;
        $payee->payCycle = Payee::PAY_CYCLE_FORTNIGHTLY;
        Assert::assertTrue($this->adjustment->isEligible($payer, $payee, $scale, $earning));

        $payee->payCycle = Payee::PAY_CYCLE_MONTHLY;
        Assert::assertFalse($this->adjustment->isEligible($payer, $payee, $scale, $earning));
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('weeklyData')]
    public function testWeeklyWithholding(int $gross, int $adjusted): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->payCycle = Payee::PAY_CYCLE_WEEKLY;
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;

        $earning = new Earning();
        $earning->date = new \DateTime('2022-10-10');
        $earning->gross = $gross;

        $scale = new Nat1004();

        Assert::assertEquals($adjusted, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning));
    }

    /**
     * @return \Iterator<int, array<int, (float | int)>>
     */
    public static function weeklyData(): \Iterator
    {
        yield [87, 0];
        yield [88, 0];
        yield [116, 0];
        yield [117, 0];
        yield [249, 0];
        yield [250, 0];
        yield [358, 0];
        yield [359, 0];
        yield [370, 0];
        yield [371, 0];
        yield [437, 0];
        yield [438, 0];
        yield [514, 0];
        yield [515, 0];
        yield [547, 0];
        yield [548, 0];
        yield [720, 0];
        yield [721, 0];
        yield [738, 0];
        yield [739, 0];
        yield [864, 0];
        yield [865, 0];
        yield [923, 3];
        yield [924, 3];
        yield [931, 3];
        yield [932, 3];
        yield [1281, 3];
        yield [1282, 3];
        yield [1844, 3];
        yield [1845, 3];
        yield [1956, 3];
        yield [1957, 3];
        yield [2119, 3];
        yield [2120, 3];
        yield [2306, 5];
        yield [2307, 5];
        yield [2490, 5];
        yield [2491, 5];
        yield [2652, 5];
        yield [2653, 5];
        yield [2736, 5];
        yield [2737, 5];
        yield [2898, 5];
        yield [2899, 5];
        yield [2913, 5];
        yield [2914, 5];
        yield [3111, 5];
        yield [3461, 10];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('fortnightlyData')]
    public function testFortnightlyWithholding(int $gross, int $adjusted): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->payCycle = Payee::PAY_CYCLE_FORTNIGHTLY;
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;

        $earning = new Earning();
        $earning->date = new \DateTime('2022-10-10');
        $earning->gross = $gross;

        $scale = new Nat1004();

        Assert::assertEquals($adjusted, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning));
    }

    /**
     * @return \Iterator<int, array<int, (float | int)>>
     */
    public static function fortnightlyData(): \Iterator
    {
        yield [174, 0];
        yield [176, 0];
        yield [232, 0];
        yield [234, 0];
        yield [498, 0];
        yield [500, 0];
        yield [716, 0];
        yield [718, 0];
        yield [740, 0];
        yield [742, 0];
        yield [874, 0];
        yield [876, 0];
        yield [1028, 0];
        yield [1030, 0];
        yield [1094, 0];
        yield [1096, 0];
        yield [1440, 0];
        yield [1442, 0];
        yield [1476, 0];
        yield [1478, 0];
        yield [1728, 0];
        yield [1730, 0];
        yield [1846, 13];
        yield [1848, 13];
        yield [1862, 13];
        yield [1864, 13];
        yield [2562, 13];
        yield [2564, 13];
        yield [3688, 13];
        yield [3690, 13];
        yield [3912, 13];
        yield [3914, 13];
        yield [4238, 13];
        yield [4240, 13];
        yield [4612, 21];
        yield [4614, 21];
        yield [4980, 21];
        yield [4982, 21];
        yield [5304, 21];
        yield [5306, 21];
        yield [5472, 21];
        yield [5474, 21];
        yield [5796, 21];
        yield [5798, 21];
        yield [5826, 21];
        yield [5828, 21];
        yield [6222, 21];
        yield [6922, 40];
    }
}
