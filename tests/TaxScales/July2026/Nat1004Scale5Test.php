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
final class Nat1004Scale5Test extends TestCase
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
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_FULL;

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
        yield [672, 47.0];
        yield [673, 47.0];
        yield [720, 54.0];
        yield [721, 54.0];
        yield [864, 77.0];
        yield [865, 77.0];
        yield [907, 90.0];
        yield [908, 90.0];
        yield [931, 97.0];
        yield [932, 97.0];
        yield [1134, 158.0];
        yield [1135, 159.0];
        yield [1281, 203.0];
        yield [1282, 203.0];
        yield [1844, 372.0];
        yield [1845, 372.0];
        yield [2119, 454.0];
        yield [2120, 455.0];
        yield [2245, 492.0];
        yield [2246, 492.0];
        yield [2490, 566.0];
        yield [2491, 566.0];
        yield [2595, 597.0];
        yield [2596, 597.0];
        yield [2652, 618.0];
        yield [2653, 619.0];
        yield [2736, 649.0];
        yield [2737, 650.0];
        yield [2898, 709.0];
        yield [2899, 710.0];
        yield [3302, 859.0];
        yield [3303, 859.0];
        yield [3652, 988.0];
        yield [3653, 989.0];
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
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_FULL;

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
        yield [1344, 94.0];
        yield [1346, 94.0];
        yield [1440, 108.0];
        yield [1442, 108.0];
        yield [1728, 154.0];
        yield [1730, 154.0];
        yield [1814, 180.0];
        yield [1816, 180.0];
        yield [1862, 194.0];
        yield [1864, 194.0];
        yield [2268, 316.0];
        yield [2270, 318.0];
        yield [2562, 406.0];
        yield [2564, 406.0];
        yield [3688, 744.0];
        yield [3690, 744.0];
        yield [4238, 908.0];
        yield [4240, 910.0];
        yield [4490, 984.0];
        yield [4492, 984.0];
        yield [4980, 1132.0];
        yield [4982, 1132.0];
        yield [5190, 1194.0];
        yield [5192, 1194.0];
        yield [5304, 1236.0];
        yield [5306, 1238.0];
        yield [5472, 1298.0];
        yield [5474, 1300.0];
        yield [5796, 1418.0];
        yield [5798, 1420.0];
        yield [6604, 1718.0];
        yield [6606, 1718.0];
        yield [7304, 1976.0];
        yield [7306, 1978.0];
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
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_FULL;

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
        yield [2912.0, 204.0];
        yield [2916.33, 204.0];
        yield [3120.0, 234.0];
        yield [3124.33, 234.0];
        yield [3744.0, 334.0];
        yield [3748.33, 334.0];
        yield [3930.33, 390.0];
        yield [3934.67, 390.0];
        yield [4034.33, 420.0];
        yield [4038.67, 420.0];
        yield [4914.0, 685.0];
        yield [4918.33, 689.0];
        yield [5551.0, 880.0];
        yield [5555.33, 880.0];
        yield [7990.67, 1612.0];
        yield [7995.0, 1612.0];
        yield [9182.33, 1967.0];
        yield [9186.67, 1972.0];
        yield [9728.33, 2132.0];
        yield [9732.67, 2132.0];
        yield [10790.0, 2453.0];
        yield [10794.33, 2453.0];
        yield [11245.0, 2587.0];
        yield [11249.33, 2587.0];
        yield [11492.0, 2678.0];
        yield [11496.33, 2682.0];
        yield [11856.0, 2812.0];
        yield [11860.33, 2817.0];
        yield [12558.0, 3072.0];
        yield [12562.33, 3077.0];
        yield [14308.67, 3722.0];
        yield [14313.0, 3722.0];
        yield [15825.33, 4281.0];
        yield [15829.67, 4286.0];
    }
}
