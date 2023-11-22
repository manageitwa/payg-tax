<?php

namespace ManageIt\PaygTax\Tests\TaxScales\October2020;

use ManageIt\PaygTax\TaxScales\October2020\Nat1004Scale2;
use ManageIt\PaygTax\Tests\Fixtures\Earning;
use ManageIt\PaygTax\Tests\Fixtures\Payee;
use ManageIt\PaygTax\Tests\Fixtures\Payer;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;

/**
 * @covers \ManageIt\PaygTax\TaxScales\October2020\Nat1004Scale2
 */
class Nat1004Scale2Test extends TestCase
{
    protected Nat1004Scale2 $scale;

    public function setUp(): void
    {
        $this->scale = new Nat1004Scale2();
    }

    public function testEligibility(): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = true;
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
        $payee->claimsTaxFreeThreshold = false;
        Assert::assertFalse($this->scale->isEligible($payer, $payee, $earning));

        $payee->claimsTaxFreeThreshold = true;
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
            [87, 0],
            [88, 0],
            [116, 0],
            [117, 0],
            [249, 0],
            [250, 0],
            [358, 0],
            [359, 0],
            [370, 2],
            [371, 2],
            [437, 15],
            [438, 15],
            [514, 37],
            [515, 37],
            [547, 47],
            [548, 47],
            [720, 83],
            [721, 83],
            [738, 87],
            [739, 87],
            [864, 115],
            [865, 115],
            [923, 135],
            [924, 135],
            [931, 138],
            [932, 138],
            [1281, 260],
            [1282, 260],
            [1844, 454],
            [1845, 454],
            [1956, 492],
            [1957, 493],
            [2119, 549],
            [2120, 549],
            [2306, 613],
            [2307, 614],
            [2490, 685],
            [2491, 685],
            [2652, 748],
            [2653, 748],
            [2736, 781],
            [2737, 781],
            [2898, 844],
            [2899, 844],
            [2913, 850],
            [2914, 850],
            [3111, 927],
            [3461, 1064],
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
            [174, 0],
            [176, 0],
            [232, 0],
            [234, 0],
            [498, 0],
            [500, 0],
            [716, 0],
            [718, 0],
            [740, 4],
            [742, 4],
            [874, 30],
            [876, 30],
            [1028, 74],
            [1030, 74],
            [1094, 94],
            [1096, 94],
            [1440, 166],
            [1442, 166],
            [1476, 174],
            [1478, 174],
            [1728, 230],
            [1730, 230],
            [1846, 270],
            [1848, 270],
            [1862, 276],
            [1864, 276],
            [2562, 520],
            [2564, 520],
            [3688, 908],
            [3690, 908],
            [3912, 984],
            [3914, 986],
            [4238, 1098],
            [4240, 1098],
            [4612, 1226],
            [4614, 1228],
            [4980, 1370],
            [4982, 1370],
            [5304, 1496],
            [5306, 1496],
            [5472, 1562],
            [5474, 1562],
            [5796, 1688],
            [5798, 1688],
            [5826, 1700],
            [5828, 1700],
            [6222, 1854],
            [6922, 2128],
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
            [377.00, 0],
            [381.33, 0],
            [502.67, 0],
            [507.00, 0],
            [1079.00, 0],
            [1083.33, 0],
            [1551.33, 0],
            [1555.67, 0],
            [1603.33, 9],
            [1607.67, 9],
            [1893.67, 65],
            [1898.00, 65],
            [2227.33, 160],
            [2231.67, 160],
            [2370.33, 204],
            [2374.67, 204],
            [3120.00, 360],
            [3124.33, 360],
            [3198.00, 377],
            [3202.33, 377],
            [3744.00, 498],
            [3748.33, 498],
            [3999.67, 585],
            [4004.00, 585],
            [4034.33, 598],
            [4038.67, 598],
            [5551.00, 1127],
            [5555.33, 1127],
            [7990.67, 1967],
            [7995.00, 1967],
            [8476.00, 2132],
            [8480.33, 2136],
            [9182.33, 2379],
            [9186.67, 2379],
            [9992.67, 2656],
            [9997.00, 2661],
            [10790.00, 2968],
            [10794.33, 2968],
            [11492.00, 3241],
            [11496.33, 3241],
            [11856.00, 3384],
            [11860.33, 3384],
            [12558.00, 3657],
            [12562.33, 3657],
            [12623.00, 3683],
            [12627.33, 3683],
            [13481.00, 4017],
            [14997.67, 4611],
        ];
    }
}
