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
class Nat1004Scale6Test extends TestCase
{
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
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_HALF;

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
            [864, 104.00],
            [865, 104.00],
            [923, 126.00],
            [924, 126.00],
            [931, 129.00],
            [932, 129.00],
            [1281, 247.00],
            [1282, 247.00],
            [1844, 435.00],
            [1845, 436.00],
            [1956, 473.00],
            [1957, 473.00],
            [2119, 527.00],
            [2120, 528.00],
            [2306, 590.00],
            [2307, 590.00],
            [2490, 660.00],
            [2491, 660.00],
            [2652, 722.00],
            [2653, 722.00],
            [2736, 753.00],
            [2737, 754.00],
            [2898, 815.00],
            [2899, 815.00],
            [2913, 821.00],
            [2914, 821.00],
            [3111, 896.00],
            [3461, 1029.00],
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
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_HALF;

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
            [1728, 208.00],
            [1730, 208.00],
            [1846, 252.00],
            [1848, 252.00],
            [1862, 258.00],
            [1864, 258.00],
            [2562, 494.00],
            [2564, 494.00],
            [3688, 870.00],
            [3690, 872.00],
            [3912, 946.00],
            [3914, 946.00],
            [4238, 1054.00],
            [4240, 1056.00],
            [4612, 1180.00],
            [4614, 1180.00],
            [4980, 1320.00],
            [4982, 1320.00],
            [5304, 1444.00],
            [5306, 1444.00],
            [5472, 1506.00],
            [5474, 1508.00],
            [5796, 1630.00],
            [5798, 1630.00],
            [5826, 1642.00],
            [5828, 1642.00],
            [6222, 1792.00],
            [6922, 2058.00],
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
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_HALF;

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
            [3744.00, 451.00],
            [3748.33, 451.00],
            [3999.67, 546.00],
            [4004.00, 546.00],
            [4034.33, 559.00],
            [4038.67, 559.00],
            [5551.00, 1070.00],
            [5555.33, 1070.00],
            [7990.67, 1885.00],
            [7995.00, 1889.00],
            [8476.00, 2050.00],
            [8480.33, 2050.00],
            [9182.33, 2284.00],
            [9186.67, 2288.00],
            [9992.67, 2557.00],
            [9997.00, 2557.00],
            [10790.00, 2860.00],
            [10794.33, 2860.00],
            [11492.00, 3129.00],
            [11496.33, 3129.00],
            [11856.00, 3263.00],
            [11860.33, 3267.00],
            [12558.00, 3532.00],
            [12562.33, 3532.00],
            [12623.00, 3558.00],
            [12627.33, 3558.00],
            [13481.00, 3883.00],
            [14997.67, 4459.00],
        ];
    }
}
