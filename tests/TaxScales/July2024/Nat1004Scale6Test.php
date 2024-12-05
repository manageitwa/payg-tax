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
class Nat1004Scale6Test extends TestCase
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
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_HALF;

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
            [514, 25],
            [515, 25],
            [624, 42],
            [625, 42],
            [720, 58],
            [721, 58],
            [842, 78],
            [843, 78],
            [864, 83],
            [865, 83],
            [931, 107],
            [932, 107],
            [1052, 149],
            [1053, 150],
            [1281, 221],
            [1282, 221],
            [1844, 395],
            [1845, 396],
            [2119, 481],
            [2120, 481],
            [2245, 520],
            [2246, 520],
            [2490, 596],
            [2491, 596],
            [2595, 628],
            [2596, 629],
            [2652, 650],
            [2653, 650],
            [2736, 682],
            [2737, 682],
            [2898, 743],
            [2899, 744],
            [3302, 897],
            [3303, 897],
            [3652, 1030],
            [3653, 1030],
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
            [1028, 50],
            [1030, 50],
            [1248, 84],
            [1250, 84],
            [1440, 116],
            [1442, 116],
            [1684, 156],
            [1686, 156],
            [1728, 166],
            [1730, 166],
            [1862, 214],
            [1864, 214],
            [2104, 298],
            [2106, 300],
            [2562, 442],
            [2564, 442],
            [3688, 790],
            [3690, 792],
            [4238, 962],
            [4240, 962],
            [4490, 1040],
            [4492, 1040],
            [4980, 1192],
            [4982, 1192],
            [5190, 1256],
            [5192, 1258],
            [5304, 1300],
            [5306, 1300],
            [5472, 1364],
            [5474, 1364],
            [5796, 1486],
            [5798, 1488],
            [6604, 1794],
            [6606, 1794],
            [7304, 2060],
            [7306, 2060],
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
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_HALF;

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
            [2227.33, 108],
            [2231.67, 108],
            [2704.00, 182],
            [2708.33, 182],
            [3120.00, 251],
            [3124.33, 251],
            [3648.67, 338],
            [3653.00, 338],
            [3744.00, 360],
            [3748.33, 360],
            [4034.33, 464],
            [4038.67, 464],
            [4558.67, 646],
            [4563.00, 650],
            [5551.00, 958],
            [5555.33, 958],
            [7990.67, 1712],
            [7995.00, 1716],
            [9182.33, 2084],
            [9186.67, 2084],
            [9728.33, 2253],
            [9732.67, 2253],
            [10790.00, 2583],
            [10794.33, 2583],
            [11245.00, 2721],
            [11249.33, 2726],
            [11492.00, 2817],
            [11496.33, 2817],
            [11856.00, 2955],
            [11860.33, 2955],
            [12558.00, 3220],
            [12562.33, 3224],
            [14308.67, 3887],
            [14313.00, 3887],
            [15825.33, 4463],
            [15829.67, 4463],
        ];
    }
}
