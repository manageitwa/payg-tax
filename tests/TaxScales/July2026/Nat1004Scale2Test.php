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
class Nat1004Scale2Test extends TestCase
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
            [116, 0.0],
            [117, 0.0],
            [187, 0.0],
            [188, 0.0],
            [249, 0.0],
            [250, 0.0],
            [361, 0.0],
            [362, 0.0],
            [370, 1.0],
            [371, 1.0],
            [514, 23.0],
            [515, 23.0],
            [537, 26.0],
            [538, 27.0],
            [672, 60.0],
            [673, 60.0],
            [720, 68.0],
            [721, 68.0],
            [864, 94.0],
            [865, 94.0],
            [907, 108.0],
            [908, 108.0],
            [931, 116.0],
            [932, 116.0],
            [1134, 181.0],
            [1135, 181.0],
            [1281, 229.0],
            [1282, 229.0],
            [1844, 409.0],
            [1845, 409.0],
            [2119, 497.0],
            [2120, 497.0],
            [2245, 537.0],
            [2246, 537.0],
            [2490, 615.0],
            [2491, 616.0],
            [2595, 649.0],
            [2596, 649.0],
            [2652, 671.0],
            [2653, 672.0],
            [2736, 704.0],
            [2737, 704.0],
            [2898, 767.0],
            [2899, 768.0],
            [3302, 925.0],
            [3303, 925.0],
            [3652, 1061.0],
            [3653, 1062.0],
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
            [232, 0.0],
            [234, 0.0],
            [374, 0.0],
            [376, 0.0],
            [498, 0.0],
            [500, 0.0],
            [722, 0.0],
            [724, 0.0],
            [740, 2.0],
            [742, 2.0],
            [1028, 46.0],
            [1030, 46.0],
            [1074, 52.0],
            [1076, 54.0],
            [1344, 120.0],
            [1346, 120.0],
            [1440, 136.0],
            [1442, 136.0],
            [1728, 188.0],
            [1730, 188.0],
            [1814, 216.0],
            [1816, 216.0],
            [1862, 232.0],
            [1864, 232.0],
            [2268, 362.0],
            [2270, 362.0],
            [2562, 458.0],
            [2564, 458.0],
            [3688, 818.0],
            [3690, 818.0],
            [4238, 994.0],
            [4240, 994.0],
            [4490, 1074.0],
            [4492, 1074.0],
            [4980, 1230.0],
            [4982, 1232.0],
            [5190, 1298.0],
            [5192, 1298.0],
            [5304, 1342.0],
            [5306, 1344.0],
            [5472, 1408.0],
            [5474, 1408.0],
            [5796, 1534.0],
            [5798, 1536.0],
            [6604, 1850.0],
            [6606, 1850.0],
            [7304, 2122.0],
            [7306, 2124.0],
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
            [502.67, 0.0],
            [507.0, 0.0],
            [810.33, 0.0],
            [814.67, 0.0],
            [1079.0, 0.0],
            [1083.33, 0.0],
            [1564.33, 0.0],
            [1568.67, 0.0],
            [1603.33, 4.0],
            [1607.67, 4.0],
            [2227.33, 100.0],
            [2231.67, 100.0],
            [2327.0, 113.0],
            [2331.33, 117.0],
            [2912.0, 260.0],
            [2916.33, 260.0],
            [3120.0, 295.0],
            [3124.33, 295.0],
            [3744.0, 407.0],
            [3748.33, 407.0],
            [3930.33, 468.0],
            [3934.67, 468.0],
            [4034.33, 503.0],
            [4038.67, 503.0],
            [4914.0, 784.0],
            [4918.33, 784.0],
            [5551.0, 992.0],
            [5555.33, 992.0],
            [7990.67, 1772.0],
            [7995.0, 1772.0],
            [9182.33, 2154.0],
            [9186.67, 2154.0],
            [9728.33, 2327.0],
            [9732.67, 2327.0],
            [10790.0, 2665.0],
            [10794.33, 2669.0],
            [11245.0, 2812.0],
            [11249.33, 2812.0],
            [11492.0, 2908.0],
            [11496.33, 2912.0],
            [11856.0, 3051.0],
            [11860.33, 3051.0],
            [12558.0, 3324.0],
            [12562.33, 3328.0],
            [14308.67, 4008.0],
            [14313.0, 4008.0],
            [15825.33, 4598.0],
            [15829.67, 4602.0],
        ];
    }
}
