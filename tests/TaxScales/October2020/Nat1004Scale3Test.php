<?php

namespace ManageIt\PaygTax\Tests\TaxScales\October2020;

use ManageIt\PaygTax\TaxScales\October2020\Nat1004Scale3;
use ManageIt\PaygTax\Tests\Fixtures\Earning;
use ManageIt\PaygTax\Tests\Fixtures\Payee;
use ManageIt\PaygTax\Tests\Fixtures\Payer;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;

/**
 * @covers \ManageIt\PaygTax\TaxScales\October2020\Nat1004Scale3
 */
class Nat1004Scale3Test extends TestCase
{
    protected Nat1004Scale3 $scale;

    public function setUp(): void
    {
        $this->scale = new Nat1004Scale3();
    }

    public function testEligibility(): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->residencyStatus = Payee::FOREIGN_RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = true;
        $payee->stsl = false;

        $earning = new Earning();
        $earning->date = new \DateTime('2022-10-10');

        Assert::assertTrue($this->scale->isEligible($payer, $payee, $earning));

        $payee->residencyStatus = Payee::RESIDENT;
        Assert::assertFalse($this->scale->isEligible($payer, $payee, $earning));

        $payee->residencyStatus = Payee::WORKING_HOLIDAY_MAKER;
        Assert::assertFalse($this->scale->isEligible($payer, $payee, $earning));

        $payee->residencyStatus = Payee::FOREIGN_RESIDENT;
        $payee->tfn = false;
        Assert::assertFalse($this->scale->isEligible($payer, $payee, $earning));

        $payee->tfn = true;
        $payee->stsl = true;
        Assert::assertFalse($this->scale->isEligible($payer, $payee, $earning));

        $payee->stsl = false;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_FULL;
        Assert::assertFalse($this->scale->isEligible($payer, $payee, $earning));

        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_NONE;
        $payee->seniorsOffset = Payee::SENIORS_OFFSET_COUPLE;
        Assert::assertFalse($this->scale->isEligible($payer, $payee, $earning));

        $payee->seniorsOffset = Payee::SENIORS_OFFSET_NONE;
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
            [87, 28],
            [88, 29],
            [116, 38],
            [117, 38],
            [249, 81],
            [250, 81],
            [358, 116],
            [359, 117],
            [370, 120],
            [371, 121],
            [437, 142],
            [438, 142],
            [514, 167],
            [515, 167],
            [547, 178],
            [548, 178],
            [720, 234],
            [721, 234],
            [738, 240],
            [739, 240],
            [864, 281],
            [865, 281],
            [923, 300],
            [924, 300],
            [931, 303],
            [932, 303],
            [1281, 416],
            [1282, 417],
            [1844, 599],
            [1845, 600],
            [1956, 636],
            [1957, 636],
            [2119, 689],
            [2120, 689],
            [2306, 749],
            [2307, 750],
            [2490, 818],
            [2491, 818],
            [2652, 878],
            [2653, 878],
            [2736, 909],
            [2737, 909],
            [2898, 969],
            [2899, 969],
            [2913, 974],
            [2914, 975],
            [3111, 1048],
            [3461, 1177],
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
            [174, 56],
            [176, 58],
            [232, 76],
            [234, 76],
            [498, 162],
            [500, 162],
            [716, 232],
            [718, 234],
            [740, 240],
            [742, 242],
            [874, 284],
            [876, 284],
            [1028, 334],
            [1030, 334],
            [1094, 356],
            [1096, 356],
            [1440, 468],
            [1442, 468],
            [1476, 480],
            [1478, 480],
            [1728, 562],
            [1730, 562],
            [1846, 600],
            [1848, 600],
            [1862, 606],
            [1864, 606],
            [2562, 832],
            [2564, 834],
            [3688, 1198],
            [3690, 1200],
            [3912, 1272],
            [3914, 1272],
            [4238, 1378],
            [4240, 1378],
            [4612, 1498],
            [4614, 1500],
            [4980, 1636],
            [4982, 1636],
            [5304, 1756],
            [5306, 1756],
            [5472, 1818],
            [5474, 1818],
            [5796, 1938],
            [5798, 1938],
            [5826, 1948],
            [5828, 1950],
            [6222, 2096],
            [6922, 2354],
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
            [377.00, 121],
            [381.33, 126],
            [502.67, 165],
            [507.00, 165],
            [1079.00, 351],
            [1083.33, 351],
            [1551.33, 503],
            [1555.67, 507],
            [1603.33, 520],
            [1607.67, 524],
            [1893.67, 615],
            [1898.00, 615],
            [2227.33, 724],
            [2231.67, 724],
            [2370.33, 771],
            [2374.67, 771],
            [3120.00, 1014],
            [3124.33, 1014],
            [3198.00, 1040],
            [3202.33, 1040],
            [3744.00, 1218],
            [3748.33, 1218],
            [3999.67, 1300],
            [4004.00, 1300],
            [4034.33, 1313],
            [4038.67, 1313],
            [5551.00, 1803],
            [5555.33, 1807],
            [7990.67, 2596],
            [7995.00, 2600],
            [8476.00, 2756],
            [8480.33, 2756],
            [9182.33, 2986],
            [9186.67, 2986],
            [9992.67, 3246],
            [9997.00, 3250],
            [10790.00, 3545],
            [10794.33, 3545],
            [11492.00, 3805],
            [11496.33, 3805],
            [11856.00, 3939],
            [11860.33, 3939],
            [12558.00, 4199],
            [12562.33, 4199],
            [12623.00, 4221],
            [12627.33, 4225],
            [13481.00, 4541],
            [14997.67, 5100],
        ];
    }
}
