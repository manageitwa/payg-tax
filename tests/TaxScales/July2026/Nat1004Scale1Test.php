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
final class Nat1004Scale1Test extends TestCase
{
    #[\PHPUnit\Framework\Attributes\DataProvider('weeklyData')]
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
     * @return \Iterator<int, array<int, (float | int)>>
     */
    public static function weeklyData(): \Iterator
    {
        yield [116, 17.0];
        yield [117, 18.0];
        yield [187, 28.0];
        yield [188, 28.0];
        yield [249, 41.0];
        yield [250, 41.0];
        yield [361, 64.0];
        yield [362, 65.0];
        yield [370, 66.0];
        yield [371, 66.0];
        yield [514, 92.0];
        yield [515, 92.0];
        yield [537, 99.0];
        yield [538, 100.0];
        yield [672, 143.0];
        yield [673, 143.0];
        yield [720, 158.0];
        yield [721, 159.0];
        yield [864, 205.0];
        yield [865, 205.0];
        yield [907, 219.0];
        yield [908, 219.0];
        yield [931, 227.0];
        yield [932, 227.0];
        yield [1134, 292.0];
        yield [1135, 292.0];
        yield [1281, 339.0];
        yield [1282, 339.0];
        yield [1844, 519.0];
        yield [1845, 519.0];
        yield [2119, 607.0];
        yield [2120, 607.0];
        yield [2245, 647.0];
        yield [2246, 647.0];
        yield [2490, 743.0];
        yield [2491, 743.0];
        yield [2595, 784.0];
        yield [2596, 784.0];
        yield [2652, 806.0];
        yield [2653, 806.0];
        yield [2736, 839.0];
        yield [2737, 839.0];
        yield [2898, 902.0];
        yield [2899, 902.0];
        yield [3302, 1059.0];
        yield [3303, 1060.0];
        yield [3652, 1224.0];
        yield [3653, 1224.0];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('fortnightlyData')]
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
     * @return \Iterator<int, array<int, (float | int)>>
     */
    public static function fortnightlyData(): \Iterator
    {
        yield [232, 34.0];
        yield [234, 36.0];
        yield [374, 56.0];
        yield [376, 56.0];
        yield [498, 82.0];
        yield [500, 82.0];
        yield [722, 128.0];
        yield [724, 130.0];
        yield [740, 132.0];
        yield [742, 132.0];
        yield [1028, 184.0];
        yield [1030, 184.0];
        yield [1074, 198.0];
        yield [1076, 200.0];
        yield [1344, 286.0];
        yield [1346, 286.0];
        yield [1440, 316.0];
        yield [1442, 318.0];
        yield [1728, 410.0];
        yield [1730, 410.0];
        yield [1814, 438.0];
        yield [1816, 438.0];
        yield [1862, 454.0];
        yield [1864, 454.0];
        yield [2268, 584.0];
        yield [2270, 584.0];
        yield [2562, 678.0];
        yield [2564, 678.0];
        yield [3688, 1038.0];
        yield [3690, 1038.0];
        yield [4238, 1214.0];
        yield [4240, 1214.0];
        yield [4490, 1294.0];
        yield [4492, 1294.0];
        yield [4980, 1486.0];
        yield [4982, 1486.0];
        yield [5190, 1568.0];
        yield [5192, 1568.0];
        yield [5304, 1612.0];
        yield [5306, 1612.0];
        yield [5472, 1678.0];
        yield [5474, 1678.0];
        yield [5796, 1804.0];
        yield [5798, 1804.0];
        yield [6604, 2118.0];
        yield [6606, 2120.0];
        yield [7304, 2448.0];
        yield [7306, 2448.0];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('monthlyData')]
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
     * @return \Iterator<int, array<int, (float | int)>>
     */
    public static function monthlyData(): \Iterator
    {
        yield [502.67, 74.0];
        yield [507.0, 78.0];
        yield [810.33, 121.0];
        yield [814.67, 121.0];
        yield [1079.0, 178.0];
        yield [1083.33, 178.0];
        yield [1564.33, 277.0];
        yield [1568.67, 282.0];
        yield [1603.33, 286.0];
        yield [1607.67, 286.0];
        yield [2227.33, 399.0];
        yield [2231.67, 399.0];
        yield [2327.0, 429.0];
        yield [2331.33, 433.0];
        yield [2912.0, 620.0];
        yield [2916.33, 620.0];
        yield [3120.0, 685.0];
        yield [3124.33, 689.0];
        yield [3744.0, 888.0];
        yield [3748.33, 888.0];
        yield [3930.33, 949.0];
        yield [3934.67, 949.0];
        yield [4034.33, 984.0];
        yield [4038.67, 984.0];
        yield [4914.0, 1265.0];
        yield [4918.33, 1265.0];
        yield [5551.0, 1469.0];
        yield [5555.33, 1469.0];
        yield [7990.67, 2249.0];
        yield [7995.0, 2249.0];
        yield [9182.33, 2630.0];
        yield [9186.67, 2630.0];
        yield [9728.33, 2804.0];
        yield [9732.67, 2804.0];
        yield [10790.0, 3220.0];
        yield [10794.33, 3220.0];
        yield [11245.0, 3397.0];
        yield [11249.33, 3397.0];
        yield [11492.0, 3493.0];
        yield [11496.33, 3493.0];
        yield [11856.0, 3636.0];
        yield [11860.33, 3636.0];
        yield [12558.0, 3909.0];
        yield [12562.33, 3909.0];
        yield [14308.67, 4589.0];
        yield [14313.0, 4593.0];
        yield [15825.33, 5304.0];
        yield [15829.67, 5304.0];
    }
}
