<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\Tests\TaxScales\July2026;

use ManageIt\PaygTax\PaygTax;
use ManageIt\PaygTax\Tests\Fixtures\Earning;
use ManageIt\PaygTax\Tests\Fixtures\Payee;
use ManageIt\PaygTax\Tests\Fixtures\Payer;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;

#[\PHPUnit\Framework\Attributes\CoversClass(\ManageIt\PaygTax\TaxScales\Nat1004::class)]
final class Nat1004Scale3Test extends TestCase
{
    #[\PHPUnit\Framework\Attributes\DataProvider('weeklyData')]
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
     * @return \Iterator<int, array<int, (float | int)>>
     */
    public static function weeklyData(): \Iterator
    {
        yield [116, 35.0];
        yield [117, 35.0];
        yield [187, 56.0];
        yield [188, 56.0];
        yield [249, 75.0];
        yield [250, 75.0];
        yield [361, 108.0];
        yield [362, 109.0];
        yield [370, 111.0];
        yield [371, 111.0];
        yield [514, 154.0];
        yield [515, 154.0];
        yield [537, 161.0];
        yield [538, 161.0];
        yield [672, 202.0];
        yield [673, 202.0];
        yield [720, 216.0];
        yield [721, 216.0];
        yield [864, 259.0];
        yield [865, 259.0];
        yield [907, 272.0];
        yield [908, 272.0];
        yield [931, 279.0];
        yield [932, 280.0];
        yield [1134, 340.0];
        yield [1135, 340.0];
        yield [1281, 384.0];
        yield [1282, 385.0];
        yield [1844, 553.0];
        yield [1845, 553.0];
        yield [2119, 636.0];
        yield [2120, 636.0];
        yield [2245, 673.0];
        yield [2246, 674.0];
        yield [2490, 747.0];
        yield [2491, 747.0];
        yield [2595, 778.0];
        yield [2596, 779.0];
        yield [2652, 800.0];
        yield [2653, 800.0];
        yield [2736, 831.0];
        yield [2737, 831.0];
        yield [2898, 891.0];
        yield [2899, 891.0];
        yield [3302, 1040.0];
        yield [3303, 1041.0];
        yield [3652, 1170.0];
        yield [3653, 1170.0];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('fortnightlyData')]
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
     * @return \Iterator<int, array<int, (float | int)>>
     */
    public static function fortnightlyData(): \Iterator
    {
        yield [232, 70.0];
        yield [234, 70.0];
        yield [374, 112.0];
        yield [376, 112.0];
        yield [498, 150.0];
        yield [500, 150.0];
        yield [722, 216.0];
        yield [724, 218.0];
        yield [740, 222.0];
        yield [742, 222.0];
        yield [1028, 308.0];
        yield [1030, 308.0];
        yield [1074, 322.0];
        yield [1076, 322.0];
        yield [1344, 404.0];
        yield [1346, 404.0];
        yield [1440, 432.0];
        yield [1442, 432.0];
        yield [1728, 518.0];
        yield [1730, 518.0];
        yield [1814, 544.0];
        yield [1816, 544.0];
        yield [1862, 558.0];
        yield [1864, 560.0];
        yield [2268, 680.0];
        yield [2270, 680.0];
        yield [2562, 768.0];
        yield [2564, 770.0];
        yield [3688, 1106.0];
        yield [3690, 1106.0];
        yield [4238, 1272.0];
        yield [4240, 1272.0];
        yield [4490, 1346.0];
        yield [4492, 1348.0];
        yield [4980, 1494.0];
        yield [4982, 1494.0];
        yield [5190, 1556.0];
        yield [5192, 1558.0];
        yield [5304, 1600.0];
        yield [5306, 1600.0];
        yield [5472, 1662.0];
        yield [5474, 1662.0];
        yield [5796, 1782.0];
        yield [5798, 1782.0];
        yield [6604, 2080.0];
        yield [6606, 2082.0];
        yield [7304, 2340.0];
        yield [7306, 2340.0];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('monthlyData')]
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
     * @return \Iterator<int, array<int, (float | int)>>
     */
    public static function monthlyData(): \Iterator
    {
        yield [502.67, 152.0];
        yield [507.0, 152.0];
        yield [810.33, 243.0];
        yield [814.67, 243.0];
        yield [1079.0, 325.0];
        yield [1083.33, 325.0];
        yield [1564.33, 468.0];
        yield [1568.67, 472.0];
        yield [1603.33, 481.0];
        yield [1607.67, 481.0];
        yield [2227.33, 667.0];
        yield [2231.67, 667.0];
        yield [2327.0, 698.0];
        yield [2331.33, 698.0];
        yield [2912.0, 875.0];
        yield [2916.33, 875.0];
        yield [3120.0, 936.0];
        yield [3124.33, 936.0];
        yield [3744.0, 1122.0];
        yield [3748.33, 1122.0];
        yield [3930.33, 1179.0];
        yield [3934.67, 1179.0];
        yield [4034.33, 1209.0];
        yield [4038.67, 1213.0];
        yield [4914.0, 1473.0];
        yield [4918.33, 1473.0];
        yield [5551.0, 1664.0];
        yield [5555.33, 1668.0];
        yield [7990.67, 2396.0];
        yield [7995.0, 2396.0];
        yield [9182.33, 2756.0];
        yield [9186.67, 2756.0];
        yield [9728.33, 2916.0];
        yield [9732.67, 2921.0];
        yield [10790.0, 3237.0];
        yield [10794.33, 3237.0];
        yield [11245.0, 3371.0];
        yield [11249.33, 3376.0];
        yield [11492.0, 3467.0];
        yield [11496.33, 3467.0];
        yield [11856.0, 3601.0];
        yield [11860.33, 3601.0];
        yield [12558.0, 3861.0];
        yield [12562.33, 3861.0];
        yield [14308.67, 4507.0];
        yield [14313.0, 4511.0];
        yield [15825.33, 5070.0];
        yield [15829.67, 5070.0];
    }
}
