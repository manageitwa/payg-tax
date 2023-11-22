<?php

namespace ManageIt\PaygTax\Tests\TaxScales\October2020;

use ManageIt\PaygTax\TaxScales\October2020\Nat1004Scale1;
use ManageIt\PaygTax\Tests\Fixtures\Earning;
use ManageIt\PaygTax\Tests\Fixtures\Payee;
use ManageIt\PaygTax\Tests\Fixtures\Payer;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;

/**
 * @covers \ManageIt\PaygTax\TaxScales\October2020\Nat1004Scale1
 */
class Nat1004Scale1Test extends TestCase
{
    protected Nat1004Scale1 $scale;

    public function setUp(): void
    {
        $this->scale = new Nat1004Scale1();
    }

    public function testEligibility(): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;
        $payee->stsl = false;

        $earning = new Earning();
        $earning->date = new \DateTime('2022-10-10');

        Assert::assertTrue($this->scale->isEligible($payer, $payee, $earning));

        $payee->residencyStatus = Payee::FOREIGN_RESIDENT;
        Assert::assertFalse($this->scale->isEligible($payer, $payee, $earning));

        $payee->residencyStatus = Payee::WORKING_HOLIDAY_MAKER;
        Assert::assertFalse($this->scale->isEligible($payer, $payee, $earning));

        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = false;
        Assert::assertFalse($this->scale->isEligible($payer, $payee, $earning));

        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = true;
        Assert::assertFalse($this->scale->isEligible($payer, $payee, $earning));

        $payee->claimsTaxFreeThreshold = false;
        $payee->stsl = true;
        Assert::assertFalse($this->scale->isEligible($payer, $payee, $earning));

        $payee->stsl = false;
        Assert::assertTrue($this->scale->isEligible($payer, $payee, $earning));

        $earning->date = new \DateTime('2019-08-01');
        Assert::assertFalse($this->scale->isEligible($payer, $payee, $earning));
    }

    /**
     * @dataProvider weeklyData
     */
    public function testWeeklyWithholding(int $gross, int $withheld): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->payCycle = Payee::PAY_CYCLE_WEEKLY;
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;
        $payee->stsl = false;

        $earning = new Earning();
        $earning->date = new \DateTime('2022-10-10');
        $earning->gross = $gross;

        Assert::assertEquals($withheld, $this->scale->getTaxWithheldAmount($payer, $payee, $earning));
    }

    /**
     * @return array<int, array<int|float, int|float>>
     */
    public function weeklyData(): array
    {
        return [
            [87, 17],
            [88, 17],
            [116, 24],
            [117, 24],
            [249, 55],
            [250, 55],
            [358, 80],
            [359, 81],
            [370, 83],
            [371, 83],
            [437, 98],
            [438, 98],
            [514, 115],
            [515, 115],
            [547, 126],
            [548, 126],
            [720, 186],
            [721, 187],
            [738, 193],
            [739, 193],
            [864, 236],
            [865, 237],
            [923, 257],
            [924, 257],
            [931, 260],
            [932, 260],
            [1281, 380],
            [1282, 381],
            [1844, 575],
            [1845, 575],
            [1956, 613],
            [1957, 614],
            [2119, 677],
            [2120, 677],
            [2306, 750],
            [2307, 750],
            [2490, 821],
            [2491, 822],
            [2652, 885],
            [2653, 885],
            [2736, 917],
            [2737, 918],
            [2898, 981],
            [2899, 981],
            [2913, 986],
            [2914, 987],
            [3111, 1064],
            [3461, 1228],
        ];
    }

    /**
     * @dataProvider fortnightlyData
     */
    public function testFortnightlyWithholding(int $gross, int $withheld): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->payCycle = Payee::PAY_CYCLE_FORTNIGHTLY;
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;
        $payee->stsl = false;

        $earning = new Earning();
        $earning->date = new \DateTime('2022-10-10');
        $earning->gross = $gross;

        Assert::assertEquals($withheld, $this->scale->getTaxWithheldAmount($payer, $payee, $earning));
    }

    /**
     * @return array<int, array<int|float, int|float>>
     */
    public function fortnightlyData(): array
    {
        return [
            [174, 34],
            [176, 34],
            [232, 48],
            [234, 48],
            [498, 110],
            [500, 110],
            [716, 160],
            [718, 162],
            [740, 166],
            [742, 166],
            [874, 196],
            [876, 196],
            [1028, 230],
            [1030, 230],
            [1094, 252],
            [1096, 252],
            [1440, 372],
            [1442, 374],
            [1476, 386],
            [1478, 386],
            [1728, 472],
            [1730, 474],
            [1846, 514],
            [1848, 514],
            [1862, 520],
            [1864, 520],
            [2562, 760],
            [2564, 762],
            [3688, 1150],
            [3690, 1150],
            [3912, 1226],
            [3914, 1228],
            [4238, 1354],
            [4240, 1354],
            [4612, 1500],
            [4614, 1500],
            [4980, 1642],
            [4982, 1644],
            [5304, 1770],
            [5306, 1770],
            [5472, 1834],
            [5474, 1836],
            [5796, 1962],
            [5798, 1962],
            [5826, 1972],
            [5828, 1974],
            [6222, 2128],
            [6922, 2456],
        ];
    }

    /**
     * @dataProvider monthlyData
     */
    public function testMonthlyWithholding(float $gross, int $withheld): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->payCycle = Payee::PAY_CYCLE_MONTHLY;
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;
        $payee->stsl = false;

        $earning = new Earning();
        $earning->date = new \DateTime('2022-10-10');
        $earning->gross = $gross;

        Assert::assertEquals($withheld, $this->scale->getTaxWithheldAmount($payer, $payee, $earning));
    }

    /**
     * @return array<int, array<int|float, int|float>>
     */
    public function monthlyData(): array
    {
        return [
            [377.00, 74],
            [381.33, 74],
            [502.67, 104],
            [507.00, 104],
            [1079.00, 238],
            [1083.33, 238],
            [1551.33, 347],
            [1555.67, 351],
            [1603.33, 360],
            [1607.67, 360],
            [1893.67, 425],
            [1898.00, 425],
            [2227.33, 498],
            [2231.67, 498],
            [2370.33, 546],
            [2374.67, 546],
            [3120.00, 806],
            [3124.33, 810],
            [3198.00, 836],
            [3202.33, 836],
            [3744.00, 1023],
            [3748.33, 1027],
            [3999.67, 1114],
            [4004.00, 1114],
            [4034.33, 1127],
            [4038.67, 1127],
            [5551.00, 1647],
            [5555.33, 1651],
            [7990.67, 2492],
            [7995.00, 2492],
            [8476.00, 2656],
            [8480.33, 2661],
            [9182.33, 2934],
            [9186.67, 2934],
            [9992.67, 3250],
            [9997.00, 3250],
            [10790.00, 3558],
            [10794.33, 3562],
            [11492.00, 3835],
            [11496.33, 3835],
            [11856.00, 3974],
            [11860.33, 3978],
            [12558.00, 4251],
            [12562.33, 4251],
            [12623.00, 4273],
            [12627.33, 4277],
            [13481.00, 4611],
            [14997.67, 5321],
        ];
    }
}
