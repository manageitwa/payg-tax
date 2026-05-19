<?php

namespace ManageIt\PaygTax\Tests\TaxScales\July2026;

use ManageIt\PaygTax\PaygTax;
use ManageIt\PaygTax\Tests\Fixtures\Earning;
use ManageIt\PaygTax\Tests\Fixtures\Payee;
use ManageIt\PaygTax\Tests\Fixtures\Payer;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;

/**
 * @covers \ManageIt\PaygTax\TaxScales\Nat1004
 */
class Nat1004Scale3Test extends TestCase
{
    /**
     * @dataProvider weeklyData
     */
    public function testWeeklyWithholding(int $gross, float $withheld): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->payCycle = Payee::PAY_CYCLE_WEEKLY;
        $payee->residencyStatus = Payee::FOREIGN_RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;
        $payee->stsl = false;

        $earning = new Earning();
        $earning->date = new \DateTime('2026-10-15');
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
            [116, 35.0],
            [117, 35.0],
            [187, 56.0],
            [188, 56.0],
            [249, 75.0],
            [250, 75.0],
            [361, 108.0],
            [362, 109.0],
            [370, 111.0],
            [371, 111.0],
            [514, 154.0],
            [515, 154.0],
            [537, 161.0],
            [538, 161.0],
            [672, 202.0],
            [673, 202.0],
            [720, 216.0],
            [721, 216.0],
            [864, 259.0],
            [865, 259.0],
            [907, 272.0],
            [908, 272.0],
            [931, 279.0],
            [932, 280.0],
            [1134, 340.0],
            [1135, 340.0],
            [1281, 384.0],
            [1282, 385.0],
            [1844, 553.0],
            [1845, 553.0],
            [2119, 636.0],
            [2120, 636.0],
            [2245, 673.0],
            [2246, 674.0],
            [2490, 747.0],
            [2491, 747.0],
            [2595, 778.0],
            [2596, 779.0],
            [2652, 800.0],
            [2653, 800.0],
            [2736, 831.0],
            [2737, 831.0],
            [2898, 891.0],
            [2899, 891.0],
            [3302, 1040.0],
            [3303, 1041.0],
            [3652, 1170.0],
            [3653, 1170.0],
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
        $payee->residencyStatus = Payee::FOREIGN_RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;
        $payee->stsl = false;

        $earning = new Earning();
        $earning->date = new \DateTime('2026-10-15');
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
            [232, 70.0],
            [234, 70.0],
            [374, 112.0],
            [376, 112.0],
            [498, 150.0],
            [500, 150.0],
            [722, 216.0],
            [724, 218.0],
            [740, 222.0],
            [742, 222.0],
            [1028, 308.0],
            [1030, 308.0],
            [1074, 322.0],
            [1076, 322.0],
            [1344, 404.0],
            [1346, 404.0],
            [1440, 432.0],
            [1442, 432.0],
            [1728, 518.0],
            [1730, 518.0],
            [1814, 544.0],
            [1816, 544.0],
            [1862, 558.0],
            [1864, 560.0],
            [2268, 680.0],
            [2270, 680.0],
            [2562, 768.0],
            [2564, 770.0],
            [3688, 1106.0],
            [3690, 1106.0],
            [4238, 1272.0],
            [4240, 1272.0],
            [4490, 1346.0],
            [4492, 1348.0],
            [4980, 1494.0],
            [4982, 1494.0],
            [5190, 1556.0],
            [5192, 1558.0],
            [5304, 1600.0],
            [5306, 1600.0],
            [5472, 1662.0],
            [5474, 1662.0],
            [5796, 1782.0],
            [5798, 1782.0],
            [6604, 2080.0],
            [6606, 2082.0],
            [7304, 2340.0],
            [7306, 2340.0],
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
        $payee->residencyStatus = Payee::FOREIGN_RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;
        $payee->stsl = false;

        $earning = new Earning();
        $earning->date = new \DateTime('2026-10-15');
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
            [502.67, 152.0],
            [507.0, 152.0],
            [810.33, 243.0],
            [814.67, 243.0],
            [1079.0, 325.0],
            [1083.33, 325.0],
            [1564.33, 468.0],
            [1568.67, 472.0],
            [1603.33, 481.0],
            [1607.67, 481.0],
            [2227.33, 667.0],
            [2231.67, 667.0],
            [2327.0, 698.0],
            [2331.33, 698.0],
            [2912.0, 875.0],
            [2916.33, 875.0],
            [3120.0, 936.0],
            [3124.33, 936.0],
            [3744.0, 1122.0],
            [3748.33, 1122.0],
            [3930.33, 1179.0],
            [3934.67, 1179.0],
            [4034.33, 1209.0],
            [4038.67, 1213.0],
            [4914.0, 1473.0],
            [4918.33, 1473.0],
            [5551.0, 1664.0],
            [5555.33, 1668.0],
            [7990.67, 2396.0],
            [7995.0, 2396.0],
            [9182.33, 2756.0],
            [9186.67, 2756.0],
            [9728.33, 2916.0],
            [9732.67, 2921.0],
            [10790.0, 3237.0],
            [10794.33, 3237.0],
            [11245.0, 3371.0],
            [11249.33, 3376.0],
            [11492.0, 3467.0],
            [11496.33, 3467.0],
            [11856.0, 3601.0],
            [11860.33, 3601.0],
            [12558.0, 3861.0],
            [12562.33, 3861.0],
            [14308.67, 4507.0],
            [14313.0, 4511.0],
            [15825.33, 5070.0],
            [15829.67, 5070.0],
        ];
    }
}
