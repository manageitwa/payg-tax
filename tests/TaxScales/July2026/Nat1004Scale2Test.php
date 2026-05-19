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
final class Nat1004Scale2Test extends TestCase
{
    #[\PHPUnit\Framework\Attributes\DataProvider('weeklyData')]
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
     * @return \Iterator<int, array<int, (float | int)>>
     */
    public static function weeklyData(): \Iterator
    {
        yield [116, 0.0];
        yield [117, 0.0];
        yield [187, 0.0];
        yield [188, 0.0];
        yield [249, 0.0];
        yield [250, 0.0];
        yield [361, 0.0];
        yield [362, 0.0];
        yield [370, 1.0];
        yield [371, 1.0];
        yield [514, 23.0];
        yield [515, 23.0];
        yield [537, 26.0];
        yield [538, 27.0];
        yield [672, 60.0];
        yield [673, 60.0];
        yield [720, 68.0];
        yield [721, 68.0];
        yield [864, 94.0];
        yield [865, 94.0];
        yield [907, 108.0];
        yield [908, 108.0];
        yield [931, 116.0];
        yield [932, 116.0];
        yield [1134, 181.0];
        yield [1135, 181.0];
        yield [1281, 229.0];
        yield [1282, 229.0];
        yield [1844, 409.0];
        yield [1845, 409.0];
        yield [2119, 497.0];
        yield [2120, 497.0];
        yield [2245, 537.0];
        yield [2246, 537.0];
        yield [2490, 615.0];
        yield [2491, 616.0];
        yield [2595, 649.0];
        yield [2596, 649.0];
        yield [2652, 671.0];
        yield [2653, 672.0];
        yield [2736, 704.0];
        yield [2737, 704.0];
        yield [2898, 767.0];
        yield [2899, 768.0];
        yield [3302, 925.0];
        yield [3303, 925.0];
        yield [3652, 1061.0];
        yield [3653, 1062.0];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('fortnightlyData')]
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
     * @return \Iterator<int, array<int, (float | int)>>
     */
    public static function fortnightlyData(): \Iterator
    {
        yield [232, 0.0];
        yield [234, 0.0];
        yield [374, 0.0];
        yield [376, 0.0];
        yield [498, 0.0];
        yield [500, 0.0];
        yield [722, 0.0];
        yield [724, 0.0];
        yield [740, 2.0];
        yield [742, 2.0];
        yield [1028, 46.0];
        yield [1030, 46.0];
        yield [1074, 52.0];
        yield [1076, 54.0];
        yield [1344, 120.0];
        yield [1346, 120.0];
        yield [1440, 136.0];
        yield [1442, 136.0];
        yield [1728, 188.0];
        yield [1730, 188.0];
        yield [1814, 216.0];
        yield [1816, 216.0];
        yield [1862, 232.0];
        yield [1864, 232.0];
        yield [2268, 362.0];
        yield [2270, 362.0];
        yield [2562, 458.0];
        yield [2564, 458.0];
        yield [3688, 818.0];
        yield [3690, 818.0];
        yield [4238, 994.0];
        yield [4240, 994.0];
        yield [4490, 1074.0];
        yield [4492, 1074.0];
        yield [4980, 1230.0];
        yield [4982, 1232.0];
        yield [5190, 1298.0];
        yield [5192, 1298.0];
        yield [5304, 1342.0];
        yield [5306, 1344.0];
        yield [5472, 1408.0];
        yield [5474, 1408.0];
        yield [5796, 1534.0];
        yield [5798, 1536.0];
        yield [6604, 1850.0];
        yield [6606, 1850.0];
        yield [7304, 2122.0];
        yield [7306, 2124.0];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('monthlyData')]
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
     * @return \Iterator<int, array<int, (float | int)>>
     */
    public static function monthlyData(): \Iterator
    {
        yield [502.67, 0.0];
        yield [507.0, 0.0];
        yield [810.33, 0.0];
        yield [814.67, 0.0];
        yield [1079.0, 0.0];
        yield [1083.33, 0.0];
        yield [1564.33, 0.0];
        yield [1568.67, 0.0];
        yield [1603.33, 4.0];
        yield [1607.67, 4.0];
        yield [2227.33, 100.0];
        yield [2231.67, 100.0];
        yield [2327.0, 113.0];
        yield [2331.33, 117.0];
        yield [2912.0, 260.0];
        yield [2916.33, 260.0];
        yield [3120.0, 295.0];
        yield [3124.33, 295.0];
        yield [3744.0, 407.0];
        yield [3748.33, 407.0];
        yield [3930.33, 468.0];
        yield [3934.67, 468.0];
        yield [4034.33, 503.0];
        yield [4038.67, 503.0];
        yield [4914.0, 784.0];
        yield [4918.33, 784.0];
        yield [5551.0, 992.0];
        yield [5555.33, 992.0];
        yield [7990.67, 1772.0];
        yield [7995.0, 1772.0];
        yield [9182.33, 2154.0];
        yield [9186.67, 2154.0];
        yield [9728.33, 2327.0];
        yield [9732.67, 2327.0];
        yield [10790.0, 2665.0];
        yield [10794.33, 2669.0];
        yield [11245.0, 2812.0];
        yield [11249.33, 2812.0];
        yield [11492.0, 2908.0];
        yield [11496.33, 2912.0];
        yield [11856.0, 3051.0];
        yield [11860.33, 3051.0];
        yield [12558.0, 3324.0];
        yield [12562.33, 3328.0];
        yield [14308.67, 4008.0];
        yield [14313.0, 4008.0];
        yield [15825.33, 4598.0];
        yield [15829.67, 4602.0];
    }
}
