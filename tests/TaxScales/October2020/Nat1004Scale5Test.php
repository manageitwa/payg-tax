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
class Nat1004Scale5Test extends TestCase
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
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_FULL;

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
            [864, 97.0],
            [865, 98.0],
            [923, 117.0],
            [924, 117.0],
            [931, 119.0],
            [932, 120.0],
            [1281, 234.0],
            [1282, 234.0],
            [1844, 417.0],
            [1845, 417.0],
            [1956, 453.0],
            [1957, 454.0],
            [2119, 506.0],
            [2120, 507.0],
            [2306, 567.0],
            [2307, 567.0],
            [2490, 635.0],
            [2491, 635.0],
            [2652, 695.0],
            [2653, 695.0],
            [2736, 726.0],
            [2737, 726.0],
            [2898, 786.0],
            [2899, 786.0],
            [2913, 792.0],
            [2914, 792.0],
            [3111, 865.0],
            [3461, 994.0],
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
            [1728, 194.0],
            [1730, 196.0],
            [1846, 234.0],
            [1848, 234.0],
            [1862, 238.0],
            [1864, 240.0],
            [2562, 468.0],
            [2564, 468.0],
            [3688, 834.0],
            [3690, 834.0],
            [3912, 906.0],
            [3914, 908.0],
            [4238, 1012.0],
            [4240, 1014.0],
            [4612, 1134.0],
            [4614, 1134.0],
            [4980, 1270.0],
            [4982, 1270.0],
            [5304, 1390.0],
            [5306, 1390.0],
            [5472, 1452.0],
            [5474, 1452.0],
            [5796, 1572.0],
            [5798, 1572.0],
            [5826, 1584.0],
            [5828, 1584.0],
            [6222, 1730.0],
            [6922, 1988.0],
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
            [3744.0, 420.0],
            [3748.33, 425.0],
            [3999.67, 507.0],
            [4004.0, 507.0],
            [4034.33, 516.0],
            [4038.67, 520.0],
            [5551.0, 1014.0],
            [5555.33, 1014.0],
            [7990.67, 1807.0],
            [7995.0, 1807.0],
            [8476.0, 1963.0],
            [8480.33, 1967.0],
            [9182.33, 2193.0],
            [9186.67, 2197.0],
            [9992.67, 2457.0],
            [9997.0, 2457.0],
            [10790.0, 2752.0],
            [10794.33, 2752.0],
            [11492.0, 3012.0],
            [11496.33, 3012.0],
            [11856.0, 3146.0],
            [11860.33, 3146.0],
            [12558.0, 3406.0],
            [12562.33, 3406.0],
            [12623.0, 3432.0],
            [12627.33, 3432.0],
            [13481.0, 3748.0],
            [14997.67, 4307.0],
        ];
    }
}
