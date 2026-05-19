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
            [672, 47.0],
            [673, 47.0],
            [720, 54.0],
            [721, 54.0],
            [864, 77.0],
            [865, 77.0],
            [907, 90.0],
            [908, 90.0],
            [931, 98.0],
            [932, 98.0],
            [1134, 170.0],
            [1135, 170.0],
            [1281, 216.0],
            [1282, 216.0],
            [1844, 390.0],
            [1845, 391.0],
            [2119, 475.0],
            [2120, 476.0],
            [2245, 515.0],
            [2246, 515.0],
            [2490, 590.0],
            [2491, 591.0],
            [2595, 623.0],
            [2596, 623.0],
            [2652, 645.0],
            [2653, 645.0],
            [2736, 677.0],
            [2737, 677.0],
            [2898, 738.0],
            [2899, 739.0],
            [3302, 892.0],
            [3303, 892.0],
            [3652, 1025.0],
            [3653, 1025.0],
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
            [1344, 94.0],
            [1346, 94.0],
            [1440, 108.0],
            [1442, 108.0],
            [1728, 154.0],
            [1730, 154.0],
            [1814, 180.0],
            [1816, 180.0],
            [1862, 196.0],
            [1864, 196.0],
            [2268, 340.0],
            [2270, 340.0],
            [2562, 432.0],
            [2564, 432.0],
            [3688, 780.0],
            [3690, 782.0],
            [4238, 950.0],
            [4240, 952.0],
            [4490, 1030.0],
            [4492, 1030.0],
            [4980, 1180.0],
            [4982, 1182.0],
            [5190, 1246.0],
            [5192, 1246.0],
            [5304, 1290.0],
            [5306, 1290.0],
            [5472, 1354.0],
            [5474, 1354.0],
            [5796, 1476.0],
            [5798, 1478.0],
            [6604, 1784.0],
            [6606, 1784.0],
            [7304, 2050.0],
            [7306, 2050.0],
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
            [2912.0, 204.0],
            [2916.33, 204.0],
            [3120.0, 234.0],
            [3124.33, 234.0],
            [3744.0, 334.0],
            [3748.33, 334.0],
            [3930.33, 390.0],
            [3934.67, 390.0],
            [4034.33, 425.0],
            [4038.67, 425.0],
            [4914.0, 737.0],
            [4918.33, 737.0],
            [5551.0, 936.0],
            [5555.33, 936.0],
            [7990.67, 1690.0],
            [7995.0, 1694.0],
            [9182.33, 2058.0],
            [9186.67, 2063.0],
            [9728.33, 2232.0],
            [9732.67, 2232.0],
            [10790.0, 2557.0],
            [10794.33, 2561.0],
            [11245.0, 2700.0],
            [11249.33, 2700.0],
            [11492.0, 2795.0],
            [11496.33, 2795.0],
            [11856.0, 2934.0],
            [11860.33, 2934.0],
            [12558.0, 3198.0],
            [12562.33, 3202.0],
            [14308.67, 3865.0],
            [14313.0, 3865.0],
            [15825.33, 4442.0],
            [15829.67, 4442.0],
        ];
    }
}
