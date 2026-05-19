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

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($withheld, $payg->getTaxWithheldAmount());
    }

    /**
     * @return array<int, array<int|float, int|float>>
     */
    public static function weeklyData(): array
    {
        return [
            [87, 0.0],
            [88, 0.0],
            [116, 0.0],
            [117, 0.0],
            [249, 0.0],
            [250, 0.0],
            [358, 0.0],
            [359, 0.0],
            [370, 2.0],
            [371, 2.0],
            [437, 15.0],
            [438, 15.0],
            [514, 30.0],
            [515, 30.0],
            [547, 36.0],
            [548, 36.0],
            [720, 69.0],
            [721, 69.0],
            [738, 72.0],
            [739, 72.0],
            [864, 104.0],
            [865, 104.0],
            [923, 126.0],
            [924, 126.0],
            [931, 129.0],
            [932, 129.0],
            [1281, 247.0],
            [1282, 247.0],
            [1844, 435.0],
            [1845, 436.0],
            [1956, 473.0],
            [1957, 473.0],
            [2119, 527.0],
            [2120, 528.0],
            [2306, 590.0],
            [2307, 590.0],
            [2490, 660.0],
            [2491, 660.0],
            [2652, 722.0],
            [2653, 722.0],
            [2736, 753.0],
            [2737, 754.0],
            [2898, 815.0],
            [2899, 815.0],
            [2913, 821.0],
            [2914, 821.0],
            [3111, 896.0],
            [3461, 1029.0],
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

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($withheld, $payg->getTaxWithheldAmount());
    }

    /**
     * @return array<int, array<int|float, int|float>>
     */
    public static function fortnightlyData(): array
    {
        return [
            [174, 0.0],
            [176, 0.0],
            [232, 0.0],
            [234, 0.0],
            [498, 0.0],
            [500, 0.0],
            [716, 0.0],
            [718, 0.0],
            [740, 4.0],
            [742, 4.0],
            [874, 30.0],
            [876, 30.0],
            [1028, 60.0],
            [1030, 60.0],
            [1094, 72.0],
            [1096, 72.0],
            [1440, 138.0],
            [1442, 138.0],
            [1476, 144.0],
            [1478, 144.0],
            [1728, 208.0],
            [1730, 208.0],
            [1846, 252.0],
            [1848, 252.0],
            [1862, 258.0],
            [1864, 258.0],
            [2562, 494.0],
            [2564, 494.0],
            [3688, 870.0],
            [3690, 872.0],
            [3912, 946.0],
            [3914, 946.0],
            [4238, 1054.0],
            [4240, 1056.0],
            [4612, 1180.0],
            [4614, 1180.0],
            [4980, 1320.0],
            [4982, 1320.0],
            [5304, 1444.0],
            [5306, 1444.0],
            [5472, 1506.0],
            [5474, 1508.0],
            [5796, 1630.0],
            [5798, 1630.0],
            [5826, 1642.0],
            [5828, 1642.0],
            [6222, 1792.0],
            [6922, 2058.0],
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

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($withheld, $payg->getTaxWithheldAmount());
    }

    /**
     * @return array<int, array<int|float, int|float>>
     */
    public static function monthlyData(): array
    {
        return [
            [377.0, 0.0],
            [381.33, 0.0],
            [502.67, 0.0],
            [507.0, 0.0],
            [1079.0, 0.0],
            [1083.33, 0.0],
            [1551.33, 0.0],
            [1555.67, 0.0],
            [1603.33, 9.0],
            [1607.67, 9.0],
            [1893.67, 65.0],
            [1898.0, 65.0],
            [2227.33, 130.0],
            [2231.67, 130.0],
            [2370.33, 156.0],
            [2374.67, 156.0],
            [3120.0, 299.0],
            [3124.33, 299.0],
            [3198.0, 312.0],
            [3202.33, 312.0],
            [3744.0, 451.0],
            [3748.33, 451.0],
            [3999.67, 546.0],
            [4004.0, 546.0],
            [4034.33, 559.0],
            [4038.67, 559.0],
            [5551.0, 1070.0],
            [5555.33, 1070.0],
            [7990.67, 1885.0],
            [7995.0, 1889.0],
            [8476.0, 2050.0],
            [8480.33, 2050.0],
            [9182.33, 2284.0],
            [9186.67, 2288.0],
            [9992.67, 2557.0],
            [9997.0, 2557.0],
            [10790.0, 2860.0],
            [10794.33, 2860.0],
            [11492.0, 3129.0],
            [11496.33, 3129.0],
            [11856.0, 3263.0],
            [11860.33, 3267.0],
            [12558.0, 3532.0],
            [12562.33, 3532.0],
            [12623.0, 3558.0],
            [12627.33, 3558.0],
            [13481.0, 3883.0],
            [14997.67, 4459.0],
        ];
    }
}
