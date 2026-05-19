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
            [672, 47.0],
            [673, 47.0],
            [720, 54.0],
            [721, 54.0],
            [864, 77.0],
            [865, 77.0],
            [907, 90.0],
            [908, 90.0],
            [931, 97.0],
            [932, 97.0],
            [1134, 158.0],
            [1135, 159.0],
            [1281, 203.0],
            [1282, 203.0],
            [1844, 372.0],
            [1845, 372.0],
            [2119, 454.0],
            [2120, 455.0],
            [2245, 492.0],
            [2246, 492.0],
            [2490, 566.0],
            [2491, 566.0],
            [2595, 597.0],
            [2596, 597.0],
            [2652, 618.0],
            [2653, 619.0],
            [2736, 649.0],
            [2737, 650.0],
            [2898, 709.0],
            [2899, 710.0],
            [3302, 859.0],
            [3303, 859.0],
            [3652, 988.0],
            [3653, 989.0],
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
            [1344, 94.0],
            [1346, 94.0],
            [1440, 108.0],
            [1442, 108.0],
            [1728, 154.0],
            [1730, 154.0],
            [1814, 180.0],
            [1816, 180.0],
            [1862, 194.0],
            [1864, 194.0],
            [2268, 316.0],
            [2270, 318.0],
            [2562, 406.0],
            [2564, 406.0],
            [3688, 744.0],
            [3690, 744.0],
            [4238, 908.0],
            [4240, 910.0],
            [4490, 984.0],
            [4492, 984.0],
            [4980, 1132.0],
            [4982, 1132.0],
            [5190, 1194.0],
            [5192, 1194.0],
            [5304, 1236.0],
            [5306, 1238.0],
            [5472, 1298.0],
            [5474, 1300.0],
            [5796, 1418.0],
            [5798, 1420.0],
            [6604, 1718.0],
            [6606, 1718.0],
            [7304, 1976.0],
            [7306, 1978.0],
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
            [2912.0, 204.0],
            [2916.33, 204.0],
            [3120.0, 234.0],
            [3124.33, 234.0],
            [3744.0, 334.0],
            [3748.33, 334.0],
            [3930.33, 390.0],
            [3934.67, 390.0],
            [4034.33, 420.0],
            [4038.67, 420.0],
            [4914.0, 685.0],
            [4918.33, 689.0],
            [5551.0, 880.0],
            [5555.33, 880.0],
            [7990.67, 1612.0],
            [7995.0, 1612.0],
            [9182.33, 1967.0],
            [9186.67, 1972.0],
            [9728.33, 2132.0],
            [9732.67, 2132.0],
            [10790.0, 2453.0],
            [10794.33, 2453.0],
            [11245.0, 2587.0],
            [11249.33, 2587.0],
            [11492.0, 2678.0],
            [11496.33, 2682.0],
            [11856.0, 2812.0],
            [11860.33, 2817.0],
            [12558.0, 3072.0],
            [12562.33, 3077.0],
            [14308.67, 3722.0],
            [14313.0, 3722.0],
            [15825.33, 4281.0],
            [15829.67, 4286.0],
        ];
    }
}
