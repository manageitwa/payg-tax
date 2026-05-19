<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\Tests\TaxScales\July2024;

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

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($withheld, $payg->getTaxWithheldAmount());
    }

    /**
     * @return \Iterator<int, array<int, (float | int)>>
     */
    public static function weeklyData(): \Iterator
    {
        yield [116, 0];
        yield [117, 0];
        yield [149, 0];
        yield [150, 0];
        yield [249, 0];
        yield [250, 0];
        yield [360, 0];
        yield [361, 0];
        yield [370, 2];
        yield [371, 2];
        yield [499, 22];
        yield [500, 22];
        yield [514, 26];
        yield [515, 26];
        yield [624, 55];
        yield [625, 55];
        yield [720, 72];
        yield [721, 72];
        yield [842, 95];
        yield [843, 95];
        yield [864, 99];
        yield [865, 99];
        yield [931, 121];
        yield [932, 121];
        yield [1052, 160];
        yield [1053, 160];
        yield [1281, 234];
        yield [1282, 234];
        yield [1844, 414];
        yield [1845, 414];
        yield [2119, 502];
        yield [2120, 502];
        yield [2245, 542];
        yield [2246, 542];
        yield [2490, 621];
        yield [2491, 621];
        yield [2595, 654];
        yield [2596, 655];
        yield [2652, 676];
        yield [2653, 677];
        yield [2736, 709];
        yield [2737, 710];
        yield [2898, 772];
        yield [2899, 773];
        yield [3302, 930];
        yield [3303, 930];
        yield [3652, 1066];
        yield [3653, 1067];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('fortnightlyData')]
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

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($withheld, $payg->getTaxWithheldAmount());
    }

    /**
     * @return \Iterator<int, array<int, (float | int)>>
     */
    public static function fortnightlyData(): \Iterator
    {
        yield [232, 0];
        yield [234, 0];
        yield [298, 0];
        yield [300, 0];
        yield [498, 0];
        yield [500, 0];
        yield [720, 0];
        yield [722, 0];
        yield [740, 4];
        yield [742, 4];
        yield [998, 44];
        yield [1000, 44];
        yield [1028, 52];
        yield [1030, 52];
        yield [1248, 110];
        yield [1250, 110];
        yield [1440, 144];
        yield [1442, 144];
        yield [1684, 190];
        yield [1686, 190];
        yield [1728, 198];
        yield [1730, 198];
        yield [1862, 242];
        yield [1864, 242];
        yield [2104, 320];
        yield [2106, 320];
        yield [2562, 468];
        yield [2564, 468];
        yield [3688, 828];
        yield [3690, 828];
        yield [4238, 1004];
        yield [4240, 1004];
        yield [4490, 1084];
        yield [4492, 1084];
        yield [4980, 1242];
        yield [4982, 1242];
        yield [5190, 1308];
        yield [5192, 1310];
        yield [5304, 1352];
        yield [5306, 1354];
        yield [5472, 1418];
        yield [5474, 1420];
        yield [5796, 1544];
        yield [5798, 1546];
        yield [6604, 1860];
        yield [6606, 1860];
        yield [7304, 2132];
        yield [7306, 2134];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('monthlyData')]
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

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($withheld, $payg->getTaxWithheldAmount());
    }

    /**
     * @return \Iterator<int, array<int, (float | int)>>
     */
    public static function monthlyData(): \Iterator
    {
        yield [502.67, 0];
        yield [507.0, 0];
        yield [645.67, 0];
        yield [650.0, 0];
        yield [1079.0, 0];
        yield [1083.33, 0];
        yield [1560.0, 0];
        yield [1564.33, 0];
        yield [1603.33, 9];
        yield [1607.67, 9];
        yield [2162.33, 95];
        yield [2166.67, 95];
        yield [2227.33, 113];
        yield [2231.67, 113];
        yield [2704.0, 238];
        yield [2708.33, 238];
        yield [3120.0, 312];
        yield [3124.33, 312];
        yield [3648.67, 412];
        yield [3653.0, 412];
        yield [3744.0, 429];
        yield [3748.33, 429];
        yield [4034.33, 524];
        yield [4038.67, 524];
        yield [4558.67, 693];
        yield [4563.0, 693];
        yield [5551.0, 1014];
        yield [5555.33, 1014];
        yield [7990.67, 1794];
        yield [7995.0, 1794];
        yield [9182.33, 2175];
        yield [9186.67, 2175];
        yield [9728.33, 2349];
        yield [9732.67, 2349];
        yield [10790.0, 2691];
        yield [10794.33, 2691];
        yield [11245.0, 2834];
        yield [11249.33, 2838];
        yield [11492.0, 2929];
        yield [11496.33, 2934];
        yield [11856.0, 3072];
        yield [11860.33, 3077];
        yield [12558.0, 3345];
        yield [12562.33, 3350];
        yield [14308.67, 4030];
        yield [14313.0, 4030];
        yield [15825.33, 4619];
        yield [15829.67, 4624];
    }
}
