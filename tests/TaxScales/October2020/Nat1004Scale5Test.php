<?php

namespace ManageIt\PaygTax\Tests\TaxScales\October2020;

use ManageIt\PaygTax\PaygTax;
use ManageIt\PaygTax\TaxScales\October2020\Nat1004Scale5;
use ManageIt\PaygTax\Tests\Fixtures\Earning;
use ManageIt\PaygTax\Tests\Fixtures\Payee;
use ManageIt\PaygTax\Tests\Fixtures\Payer;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;

/**
 * @covers \ManageIt\PaygTax\TaxScales\October2020\Nat1004Scale5
 */
class Nat1004Scale5Test extends TestCase
{
    protected Nat1004Scale5 $scale;

    public function setUp(): void
    {
        $this->scale = new Nat1004Scale5();
    }

    public function testEligibility(): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = true;
        $payee->stsl = false;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_FULL;

        $earning = new Earning();
        $earning->date = new \DateTime('2022-10-15');

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
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_HALF;
        Assert::assertFalse($this->scale->isEligible($payer, $payee, $earning));

        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_FULL;
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
    public function testWeeklyWithholding(int $gross, float $withheld): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->payCycle = Payee::PAY_CYCLE_WEEKLY;
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = true;
        $payee->stsl = false;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_FULL;

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
     * @return array<int, array<int|float, int|float>>
     */
    public function weeklyData(): array
    {
        return [
            [87, 0.00],
            [88, 0.00],
            [116, 0.00],
            [117, 0.00],
            [249, 0.00],
            [250, 0.00],
            [358, 0.00],
            [359, 0.00],
            [370, 2.00],
            [371, 2.00],
            [437, 15.00],
            [438, 15.00],
            [514, 30.00],
            [515, 30.00],
            [547, 36.00],
            [548, 36.00],
            [720, 69.00],
            [721, 69.00],
            [738, 72.00],
            [739, 72.00],
            [864, 97.00],
            [865, 98.00],
            [923, 117.00],
            [924, 117.00],
            [931, 119.00],
            [932, 120.00],
            [1281, 234.00],
            [1282, 234.00],
            [1844, 417.00],
            [1845, 417.00],
            [1956, 453.00],
            [1957, 454.00],
            [2119, 506.00],
            [2120, 507.00],
            [2306, 567.00],
            [2307, 567.00],
            [2490, 635.00],
            [2491, 635.00],
            [2652, 695.00],
            [2653, 695.00],
            [2736, 726.00],
            [2737, 726.00],
            [2898, 786.00],
            [2899, 786.00],
            [2913, 792.00],
            [2914, 792.00],
            [3111, 865.00],
            [3461, 994.00],
        ];
    }

    /**
     * @dataProvider fortnightlyData
     */
    public function testFortnightlyWithholding(int $gross, float $withheld): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->payCycle = Payee::PAY_CYCLE_FORTNIGHTLY;
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = true;
        $payee->stsl = false;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_FULL;

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
     * @return array<int, array<int|float, int|float>>
     */
    public function fortnightlyData(): array
    {
        return [
            [174, 0.00],
            [176, 0.00],
            [232, 0.00],
            [234, 0.00],
            [498, 0.00],
            [500, 0.00],
            [716, 0.00],
            [718, 0.00],
            [740, 4.00],
            [742, 4.00],
            [874, 30.00],
            [876, 30.00],
            [1028, 60.00],
            [1030, 60.00],
            [1094, 72.00],
            [1096, 72.00],
            [1440, 138.00],
            [1442, 138.00],
            [1476, 144.00],
            [1478, 144.00],
            [1728, 194.00],
            [1730, 196.00],
            [1846, 234.00],
            [1848, 234.00],
            [1862, 238.00],
            [1864, 240.00],
            [2562, 468.00],
            [2564, 468.00],
            [3688, 834.00],
            [3690, 834.00],
            [3912, 906.00],
            [3914, 908.00],
            [4238, 1012.00],
            [4240, 1014.00],
            [4612, 1134.00],
            [4614, 1134.00],
            [4980, 1270.00],
            [4982, 1270.00],
            [5304, 1390.00],
            [5306, 1390.00],
            [5472, 1452.00],
            [5474, 1452.00],
            [5796, 1572.00],
            [5798, 1572.00],
            [5826, 1584.00],
            [5828, 1584.00],
            [6222, 1730.00],
            [6922, 1988.00],
        ];
    }

    /**
     * @dataProvider monthlyData
     */
    public function testMonthlyWithholding(float $gross, float $withheld): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->payCycle = Payee::PAY_CYCLE_MONTHLY;
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = true;
        $payee->stsl = false;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_FULL;

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
     * @return array<int, array<int|float, int|float>>
     */
    public function monthlyData(): array
    {
        return [
            [377.00, 0.00],
            [381.33, 0.00],
            [502.67, 0.00],
            [507.00, 0.00],
            [1079.00, 0.00],
            [1083.33, 0.00],
            [1551.33, 0.00],
            [1555.67, 0.00],
            [1603.33, 9.00],
            [1607.67, 9.00],
            [1893.67, 65.00],
            [1898.00, 65.00],
            [2227.33, 130.00],
            [2231.67, 130.00],
            [2370.33, 156.00],
            [2374.67, 156.00],
            [3120.00, 299.00],
            [3124.33, 299.00],
            [3198.00, 312.00],
            [3202.33, 312.00],
            [3744.00, 420.00],
            [3748.33, 425.00],
            [3999.67, 507.00],
            [4004.00, 507.00],
            [4034.33, 516.00],
            [4038.67, 520.00],
            [5551.00, 1014.00],
            [5555.33, 1014.00],
            [7990.67, 1807.00],
            [7995.00, 1807.00],
            [8476.00, 1963.00],
            [8480.33, 1967.00],
            [9182.33, 2193.00],
            [9186.67, 2197.00],
            [9992.67, 2457.00],
            [9997.00, 2457.00],
            [10790.00, 2752.00],
            [10794.33, 2752.00],
            [11492.00, 3012.00],
            [11496.33, 3012.00],
            [11856.00, 3146.00],
            [11860.33, 3146.00],
            [12558.00, 3406.00],
            [12562.33, 3406.00],
            [12623.00, 3432.00],
            [12627.33, 3432.00],
            [13481.00, 3748.00],
            [14997.67, 4307.00],
        ];
    }
}
