<?php

namespace ManageIt\PaygTax\Tests\TaxScales\July2024;

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
    public function testWeeklyWithholding(int $gross, int $withheld): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->payCycle = Payee::PAY_CYCLE_WEEKLY;
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = true;
        $payee->stsl = false;

        $earning = new Earning();
        $earning->date = new \DateTime('2024-10-15');
        $earning->gross = $gross;

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($withheld, $payg->getTaxWithheldAmount());
    }

    /**
     * @return array<int, array<int|float, int|float>>
     */
    public function weeklyData(): array
    {
        return [
            [116, 0],
            [117, 0],
            [149, 0],
            [150, 0],
            [249, 0],
            [250, 0],
            [360, 0],
            [361, 0],
            [370, 2],
            [371, 2],
            [499, 22],
            [500, 22],
            [514, 26],
            [515, 26],
            [624, 55],
            [625, 55],
            [720, 72],
            [721, 72],
            [842, 95],
            [843, 95],
            [864, 99],
            [865, 99],
            [931, 121],
            [932, 121],
            [1052, 160],
            [1053, 160],
            [1281, 234],
            [1282, 234],
            [1844, 414],
            [1845, 414],
            [2119, 502],
            [2120, 502],
            [2245, 542],
            [2246, 542],
            [2490, 621],
            [2491, 621],
            [2595, 654],
            [2596, 655],
            [2652, 676],
            [2653, 677],
            [2736, 709],
            [2737, 710],
            [2898, 772],
            [2899, 773],
            [3302, 930],
            [3303, 930],
            [3652, 1066],
            [3653, 1067],
        ];
    }

    /**
     * @dataProvider fortnightlyData
     */
    public function testFortnightlyWithholding(int $gross, int $withheld): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->payCycle = Payee::PAY_CYCLE_FORTNIGHTLY;
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = true;
        $payee->stsl = false;

        $earning = new Earning();
        $earning->date = new \DateTime('2024-10-15');
        $earning->gross = $gross;

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($withheld, $payg->getTaxWithheldAmount());
    }

    /**
     * @return array<int, array<int|float, int|float>>
     */
    public function fortnightlyData(): array
    {
        return [
            [232, 0],
            [234, 0],
            [298, 0],
            [300, 0],
            [498, 0],
            [500, 0],
            [720, 0],
            [722, 0],
            [740, 4],
            [742, 4],
            [998, 44],
            [1000, 44],
            [1028, 52],
            [1030, 52],
            [1248, 110],
            [1250, 110],
            [1440, 144],
            [1442, 144],
            [1684, 190],
            [1686, 190],
            [1728, 198],
            [1730, 198],
            [1862, 242],
            [1864, 242],
            [2104, 320],
            [2106, 320],
            [2562, 468],
            [2564, 468],
            [3688, 828],
            [3690, 828],
            [4238, 1004],
            [4240, 1004],
            [4490, 1084],
            [4492, 1084],
            [4980, 1242],
            [4982, 1242],
            [5190, 1308],
            [5192, 1310],
            [5304, 1352],
            [5306, 1354],
            [5472, 1418],
            [5474, 1420],
            [5796, 1544],
            [5798, 1546],
            [6604, 1860],
            [6606, 1860],
            [7304, 2132],
            [7306, 2134],
        ];
    }

    /**
     * @dataProvider monthlyData
     */
    public function testMonthlyWithholding(float $gross, int $withheld): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->payCycle = Payee::PAY_CYCLE_MONTHLY;
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = true;
        $payee->stsl = false;

        $earning = new Earning();
        $earning->date = new \DateTime('2024-10-15');
        $earning->gross = $gross;

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($withheld, $payg->getTaxWithheldAmount());
    }

    /**
     * @return array<int, array<int|float, int|float>>
     */
    public function monthlyData(): array
    {
        return [
            [502.67, 0],
            [507.00, 0],
            [645.67, 0],
            [650.00, 0],
            [1079.00, 0],
            [1083.33, 0],
            [1560.00, 0],
            [1564.33, 0],
            [1603.33, 9],
            [1607.67, 9],
            [2162.33, 95],
            [2166.67, 95],
            [2227.33, 113],
            [2231.67, 113],
            [2704.00, 238],
            [2708.33, 238],
            [3120.00, 312],
            [3124.33, 312],
            [3648.67, 412],
            [3653.00, 412],
            [3744.00, 429],
            [3748.33, 429],
            [4034.33, 524],
            [4038.67, 524],
            [4558.67, 693],
            [4563.00, 693],
            [5551.00, 1014],
            [5555.33, 1014],
            [7990.67, 1794],
            [7995.00, 1794],
            [9182.33, 2175],
            [9186.67, 2175],
            [9728.33, 2349],
            [9732.67, 2349],
            [10790.00, 2691],
            [10794.33, 2691],
            [11245.00, 2834],
            [11249.33, 2838],
            [11492.00, 2929],
            [11496.33, 2934],
            [11856.00, 3072],
            [11860.33, 3077],
            [12558.00, 3345],
            [12562.33, 3350],
            [14308.67, 4030],
            [14313.00, 4030],
            [15825.33, 4619],
            [15829.67, 4624],
        ];
    }
}
