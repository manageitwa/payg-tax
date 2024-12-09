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
class Nat1004Scale3Test extends TestCase
{
    /**
     * @dataProvider weeklyData
     */
    public function testWeeklyWithholding(int $gross, int $withheld): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->payCycle = Payee::PAY_CYCLE_WEEKLY;
        $payee->residencyStatus = Payee::FOREIGN_RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;
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
            [116, 35],
            [117, 35],
            [149, 45],
            [150, 45],
            [249, 75],
            [250, 75],
            [360, 108],
            [361, 108],
            [370, 111],
            [371, 111],
            [499, 150],
            [500, 150],
            [514, 154],
            [515, 154],
            [624, 187],
            [625, 187],
            [720, 216],
            [721, 216],
            [842, 253],
            [843, 253],
            [864, 259],
            [865, 259],
            [931, 279],
            [932, 280],
            [1052, 316],
            [1053, 316],
            [1281, 384],
            [1282, 385],
            [1844, 553],
            [1845, 553],
            [2119, 636],
            [2120, 636],
            [2245, 673],
            [2246, 674],
            [2490, 747],
            [2491, 747],
            [2595, 778],
            [2596, 779],
            [2652, 800],
            [2653, 800],
            [2736, 831],
            [2737, 831],
            [2898, 891],
            [2899, 891],
            [3302, 1040],
            [3303, 1041],
            [3652, 1170],
            [3653, 1170],
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
        $payee->residencyStatus = Payee::FOREIGN_RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;
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
            [232, 70],
            [234, 70],
            [298, 90],
            [300, 90],
            [498, 150],
            [500, 150],
            [720, 216],
            [722, 216],
            [740, 222],
            [742, 222],
            [998, 300],
            [1000, 300],
            [1028, 308],
            [1030, 308],
            [1248, 374],
            [1250, 374],
            [1440, 432],
            [1442, 432],
            [1684, 506],
            [1686, 506],
            [1728, 518],
            [1730, 518],
            [1862, 558],
            [1864, 560],
            [2104, 632],
            [2106, 632],
            [2562, 768],
            [2564, 770],
            [3688, 1106],
            [3690, 1106],
            [4238, 1272],
            [4240, 1272],
            [4490, 1346],
            [4492, 1348],
            [4980, 1494],
            [4982, 1494],
            [5190, 1556],
            [5192, 1558],
            [5304, 1600],
            [5306, 1600],
            [5472, 1662],
            [5474, 1662],
            [5796, 1782],
            [5798, 1782],
            [6604, 2080],
            [6606, 2082],
            [7304, 2340],
            [7306, 2340],
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
        $payee->residencyStatus = Payee::FOREIGN_RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;
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
            [502.67, 152],
            [507.00, 152],
            [645.67, 195],
            [650.00, 195],
            [1079.00, 325],
            [1083.33, 325],
            [1560.00, 468],
            [1564.33, 468],
            [1603.33, 481],
            [1607.67, 481],
            [2162.33, 650],
            [2166.67, 650],
            [2227.33, 667],
            [2231.67, 667],
            [2704.00, 810],
            [2708.33, 810],
            [3120.00, 936],
            [3124.33, 936],
            [3648.67, 1096],
            [3653.00, 1096],
            [3744.00, 1122],
            [3748.33, 1122],
            [4034.33, 1209],
            [4038.67, 1213],
            [4558.67, 1369],
            [4563.00, 1369],
            [5551.00, 1664],
            [5555.33, 1668],
            [7990.67, 2396],
            [7995.00, 2396],
            [9182.33, 2756],
            [9186.67, 2756],
            [9728.33, 2916],
            [9732.67, 2921],
            [10790.00, 3237],
            [10794.33, 3237],
            [11245.00, 3371],
            [11249.33, 3376],
            [11492.00, 3467],
            [11496.33, 3467],
            [11856.00, 3601],
            [11860.33, 3601],
            [12558.00, 3861],
            [12562.33, 3861],
            [14308.67, 4507],
            [14313.00, 4511],
            [15825.33, 5070],
            [15829.67, 5070],
        ];
    }
}
