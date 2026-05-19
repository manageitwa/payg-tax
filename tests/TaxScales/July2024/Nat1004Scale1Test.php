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
final class Nat1004Scale1Test extends TestCase
{
    #[\PHPUnit\Framework\Attributes\DataProvider('weeklyData')]
    public function testWeeklyWithholding(int $gross, int $withheld): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->payCycle = Payee::PAY_CYCLE_WEEKLY;
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;
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
        yield [116, 19];
        yield [117, 19];
        yield [149, 24];
        yield [150, 24];
        yield [249, 45];
        yield [250, 45];
        yield [360, 69];
        yield [361, 69];
        yield [370, 71];
        yield [371, 71];
        yield [499, 95];
        yield [500, 95];
        yield [514, 98];
        yield [515, 98];
        yield [624, 133];
        yield [625, 134];
        yield [720, 164];
        yield [721, 165];
        yield [842, 204];
        yield [843, 204];
        yield [864, 211];
        yield [865, 211];
        yield [931, 233];
        yield [932, 233];
        yield [1052, 271];
        yield [1053, 272];
        yield [1281, 345];
        yield [1282, 345];
        yield [1844, 525];
        yield [1845, 525];
        yield [2119, 613];
        yield [2120, 613];
        yield [2245, 653];
        yield [2246, 653];
        yield [2490, 749];
        yield [2491, 749];
        yield [2595, 789];
        yield [2596, 790];
        yield [2652, 812];
        yield [2653, 812];
        yield [2736, 844];
        yield [2737, 845];
        yield [2898, 908];
        yield [2899, 908];
        yield [3302, 1065];
        yield [3303, 1066];
        yield [3652, 1230];
        yield [3653, 1230];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('fortnightlyData')]
    public function testFortnightlyWithholding(int $gross, int $withheld): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->payCycle = Payee::PAY_CYCLE_FORTNIGHTLY;
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;
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
        yield [232, 38];
        yield [234, 38];
        yield [298, 48];
        yield [300, 48];
        yield [498, 90];
        yield [500, 90];
        yield [720, 138];
        yield [722, 138];
        yield [740, 142];
        yield [742, 142];
        yield [998, 190];
        yield [1000, 190];
        yield [1028, 196];
        yield [1030, 196];
        yield [1248, 266];
        yield [1250, 268];
        yield [1440, 328];
        yield [1442, 330];
        yield [1684, 408];
        yield [1686, 408];
        yield [1728, 422];
        yield [1730, 422];
        yield [1862, 466];
        yield [1864, 466];
        yield [2104, 542];
        yield [2106, 544];
        yield [2562, 690];
        yield [2564, 690];
        yield [3688, 1050];
        yield [3690, 1050];
        yield [4238, 1226];
        yield [4240, 1226];
        yield [4490, 1306];
        yield [4492, 1306];
        yield [4980, 1498];
        yield [4982, 1498];
        yield [5190, 1578];
        yield [5192, 1580];
        yield [5304, 1624];
        yield [5306, 1624];
        yield [5472, 1688];
        yield [5474, 1690];
        yield [5796, 1816];
        yield [5798, 1816];
        yield [6604, 2130];
        yield [6606, 2132];
        yield [7304, 2460];
        yield [7306, 2460];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('monthlyData')]
    public function testMonthlyWithholding(float $gross, int $withheld): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->payCycle = Payee::PAY_CYCLE_MONTHLY;
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;
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
        yield [502.67, 82];
        yield [507.0, 82];
        yield [645.67, 104];
        yield [650.0, 104];
        yield [1079.0, 195];
        yield [1083.33, 195];
        yield [1560.0, 299];
        yield [1564.33, 299];
        yield [1603.33, 308];
        yield [1607.67, 308];
        yield [2162.33, 412];
        yield [2166.67, 412];
        yield [2227.33, 425];
        yield [2231.67, 425];
        yield [2704.0, 576];
        yield [2708.33, 581];
        yield [3120.0, 711];
        yield [3124.33, 715];
        yield [3648.67, 884];
        yield [3653.0, 884];
        yield [3744.0, 914];
        yield [3748.33, 914];
        yield [4034.33, 1010];
        yield [4038.67, 1010];
        yield [4558.67, 1174];
        yield [4563.0, 1179];
        yield [5551.0, 1495];
        yield [5555.33, 1495];
        yield [7990.67, 2275];
        yield [7995.0, 2275];
        yield [9182.33, 2656];
        yield [9186.67, 2656];
        yield [9728.33, 2830];
        yield [9732.67, 2830];
        yield [10790.0, 3246];
        yield [10794.33, 3246];
        yield [11245.0, 3419];
        yield [11249.33, 3423];
        yield [11492.0, 3519];
        yield [11496.33, 3519];
        yield [11856.0, 3657];
        yield [11860.33, 3662];
        yield [12558.0, 3935];
        yield [12562.33, 3935];
        yield [14308.67, 4615];
        yield [14313.0, 4619];
        yield [15825.33, 5330];
        yield [15829.67, 5330];
    }
}
