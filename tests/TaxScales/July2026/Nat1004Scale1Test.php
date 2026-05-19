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
class Nat1004Scale1Test extends TestCase
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
    public function weeklyData(): array
    {
        return [
            [116, 17.0],
            [117, 18.0],
            [187, 28.0],
            [188, 28.0],
            [249, 41.0],
            [250, 41.0],
            [361, 64.0],
            [362, 65.0],
            [370, 66.0],
            [371, 66.0],
            [514, 92.0],
            [515, 92.0],
            [537, 99.0],
            [538, 100.0],
            [672, 143.0],
            [673, 143.0],
            [720, 158.0],
            [721, 159.0],
            [864, 205.0],
            [865, 205.0],
            [907, 219.0],
            [908, 219.0],
            [931, 227.0],
            [932, 227.0],
            [1134, 292.0],
            [1135, 292.0],
            [1281, 339.0],
            [1282, 339.0],
            [1844, 519.0],
            [1845, 519.0],
            [2119, 607.0],
            [2120, 607.0],
            [2245, 647.0],
            [2246, 647.0],
            [2490, 743.0],
            [2491, 743.0],
            [2595, 784.0],
            [2596, 784.0],
            [2652, 806.0],
            [2653, 806.0],
            [2736, 839.0],
            [2737, 839.0],
            [2898, 902.0],
            [2899, 902.0],
            [3302, 1059.0],
            [3303, 1060.0],
            [3652, 1224.0],
            [3653, 1224.0],
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
    public function fortnightlyData(): array
    {
        return [
            [232, 34.0],
            [234, 36.0],
            [374, 56.0],
            [376, 56.0],
            [498, 82.0],
            [500, 82.0],
            [722, 128.0],
            [724, 130.0],
            [740, 132.0],
            [742, 132.0],
            [1028, 184.0],
            [1030, 184.0],
            [1074, 198.0],
            [1076, 200.0],
            [1344, 286.0],
            [1346, 286.0],
            [1440, 316.0],
            [1442, 318.0],
            [1728, 410.0],
            [1730, 410.0],
            [1814, 438.0],
            [1816, 438.0],
            [1862, 454.0],
            [1864, 454.0],
            [2268, 584.0],
            [2270, 584.0],
            [2562, 678.0],
            [2564, 678.0],
            [3688, 1038.0],
            [3690, 1038.0],
            [4238, 1214.0],
            [4240, 1214.0],
            [4490, 1294.0],
            [4492, 1294.0],
            [4980, 1486.0],
            [4982, 1486.0],
            [5190, 1568.0],
            [5192, 1568.0],
            [5304, 1612.0],
            [5306, 1612.0],
            [5472, 1678.0],
            [5474, 1678.0],
            [5796, 1804.0],
            [5798, 1804.0],
            [6604, 2118.0],
            [6606, 2120.0],
            [7304, 2448.0],
            [7306, 2448.0],
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
    public function monthlyData(): array
    {
        return [
            [502.67, 74.0],
            [507.0, 78.0],
            [810.33, 121.0],
            [814.67, 121.0],
            [1079.0, 178.0],
            [1083.33, 178.0],
            [1564.33, 277.0],
            [1568.67, 282.0],
            [1603.33, 286.0],
            [1607.67, 286.0],
            [2227.33, 399.0],
            [2231.67, 399.0],
            [2327.0, 429.0],
            [2331.33, 433.0],
            [2912.0, 620.0],
            [2916.33, 620.0],
            [3120.0, 685.0],
            [3124.33, 689.0],
            [3744.0, 888.0],
            [3748.33, 888.0],
            [3930.33, 949.0],
            [3934.67, 949.0],
            [4034.33, 984.0],
            [4038.67, 984.0],
            [4914.0, 1265.0],
            [4918.33, 1265.0],
            [5551.0, 1469.0],
            [5555.33, 1469.0],
            [7990.67, 2249.0],
            [7995.0, 2249.0],
            [9182.33, 2630.0],
            [9186.67, 2630.0],
            [9728.33, 2804.0],
            [9732.67, 2804.0],
            [10790.0, 3220.0],
            [10794.33, 3220.0],
            [11245.0, 3397.0],
            [11249.33, 3397.0],
            [11492.0, 3493.0],
            [11496.33, 3493.0],
            [11856.0, 3636.0],
            [11860.33, 3636.0],
            [12558.0, 3909.0],
            [12562.33, 3909.0],
            [14308.67, 4589.0],
            [14313.0, 4593.0],
            [15825.33, 5304.0],
            [15829.67, 5304.0],
        ];
    }
}
