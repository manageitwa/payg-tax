<?php

namespace ManageIt\PaygTax\Tests\Adjustments;

use ManageIt\PaygTax\Adjustments\ExtraPayPeriod;
use ManageIt\PaygTax\TaxScales\October2020\Nat1004Scale2;
use ManageIt\PaygTax\Tests\Fixtures\Earning;
use ManageIt\PaygTax\Tests\Fixtures\Payee;
use ManageIt\PaygTax\Tests\Fixtures\Payer;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;

/**
 * @covers \ManageIt\PaygTax\Adjustments\ExtraPayPeriod
 */
class ExtraPayPeriodTest extends TestCase
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

        $scale = new Nat1004Scale2();

        Assert::assertTrue($this->adjustment->isEligible($payer, $payee, $scale, $earning));

        $payee->residencyStatus = Payee::FOREIGN_RESIDENT;
        Assert::assertTrue($this->adjustment->isEligible($payer, $payee, $scale, $earning));

        $payee->residencyStatus = Payee::RESIDENT;
        $payee->payCycle = Payee::PAY_CYCLE_FORTNIGHTLY;
        Assert::assertTrue($this->adjustment->isEligible($payer, $payee, $scale, $earning));

        $payee->payCycle = Payee::PAY_CYCLE_MONTHLY;
        Assert::assertFalse($this->adjustment->isEligible($payer, $payee, $scale, $earning));
    }

    /**
     * @dataProvider weeklyData
     */
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

        $scale = new Nat1004Scale2();

        Assert::assertEquals($adjusted, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning));
    }

    /**
     * @return array<int, array<int|float, int|float>>
     */
    public function weeklyData(): array
    {
        return [
            [87, 0],
            [88, 0],
            [116, 0],
            [117, 0],
            [249, 0],
            [250, 0],
            [358, 0],
            [359, 0],
            [370, 0],
            [371, 0],
            [437, 0],
            [438, 0],
            [514, 0],
            [515, 0],
            [547, 0],
            [548, 0],
            [720, 0],
            [721, 0],
            [738, 0],
            [739, 0],
            [864, 0],
            [865, 0],
            [923, 3],
            [924, 3],
            [931, 3],
            [932, 3],
            [1281, 3],
            [1282, 3],
            [1844, 3],
            [1845, 3],
            [1956, 3],
            [1957, 3],
            [2119, 3],
            [2120, 3],
            [2306, 5],
            [2307, 5],
            [2490, 5],
            [2491, 5],
            [2652, 5],
            [2653, 5],
            [2736, 5],
            [2737, 5],
            [2898, 5],
            [2899, 5],
            [2913, 5],
            [2914, 5],
            [3111, 5],
            [3461, 10],
        ];
    }

    /**
     * @dataProvider fortnightlyData
     */
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

        $scale = new Nat1004Scale2();

        Assert::assertEquals($adjusted, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning));
    }

    /**
     * @return array<int, array<int|float, int|float>>
     */
    public function fortnightlyData(): array
    {
        return [
            [174, 0],
            [176, 0],
            [232, 0],
            [234, 0],
            [498, 0],
            [500, 0],
            [716, 0],
            [718, 0],
            [740, 0],
            [742, 0],
            [874, 0],
            [876, 0],
            [1028, 0],
            [1030, 0],
            [1094, 0],
            [1096, 0],
            [1440, 0],
            [1442, 0],
            [1476, 0],
            [1478, 0],
            [1728, 0],
            [1730, 0],
            [1846, 13],
            [1848, 13],
            [1862, 13],
            [1864, 13],
            [2562, 13],
            [2564, 13],
            [3688, 13],
            [3690, 13],
            [3912, 13],
            [3914, 13],
            [4238, 13],
            [4240, 13],
            [4612, 21],
            [4614, 21],
            [4980, 21],
            [4982, 21],
            [5304, 21],
            [5306, 21],
            [5472, 21],
            [5474, 21],
            [5796, 21],
            [5798, 21],
            [5826, 21],
            [5828, 21],
            [6222, 21],
            [6922, 40],
        ];
    }
}
