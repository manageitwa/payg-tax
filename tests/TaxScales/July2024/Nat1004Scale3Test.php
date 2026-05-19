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
final class Nat1004Scale3Test extends TestCase
{
    #[\PHPUnit\Framework\Attributes\DataProvider('weeklyData')]
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

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($withheld, $payg->getTaxWithheldAmount());
    }

    /**
     * @return \Iterator<int, array<int, (float | int)>>
     */
    public static function weeklyData(): \Iterator
    {
        yield [116, 35];
        yield [117, 35];
        yield [149, 45];
        yield [150, 45];
        yield [249, 75];
        yield [250, 75];
        yield [360, 108];
        yield [361, 108];
        yield [370, 111];
        yield [371, 111];
        yield [499, 150];
        yield [500, 150];
        yield [514, 154];
        yield [515, 154];
        yield [624, 187];
        yield [625, 187];
        yield [720, 216];
        yield [721, 216];
        yield [842, 253];
        yield [843, 253];
        yield [864, 259];
        yield [865, 259];
        yield [931, 279];
        yield [932, 280];
        yield [1052, 316];
        yield [1053, 316];
        yield [1281, 384];
        yield [1282, 385];
        yield [1844, 553];
        yield [1845, 553];
        yield [2119, 636];
        yield [2120, 636];
        yield [2245, 673];
        yield [2246, 674];
        yield [2490, 747];
        yield [2491, 747];
        yield [2595, 778];
        yield [2596, 779];
        yield [2652, 800];
        yield [2653, 800];
        yield [2736, 831];
        yield [2737, 831];
        yield [2898, 891];
        yield [2899, 891];
        yield [3302, 1040];
        yield [3303, 1041];
        yield [3652, 1170];
        yield [3653, 1170];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('fortnightlyData')]
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

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($withheld, $payg->getTaxWithheldAmount());
    }

    /**
     * @return \Iterator<int, array<int, (float | int)>>
     */
    public static function fortnightlyData(): \Iterator
    {
        yield [232, 70];
        yield [234, 70];
        yield [298, 90];
        yield [300, 90];
        yield [498, 150];
        yield [500, 150];
        yield [720, 216];
        yield [722, 216];
        yield [740, 222];
        yield [742, 222];
        yield [998, 300];
        yield [1000, 300];
        yield [1028, 308];
        yield [1030, 308];
        yield [1248, 374];
        yield [1250, 374];
        yield [1440, 432];
        yield [1442, 432];
        yield [1684, 506];
        yield [1686, 506];
        yield [1728, 518];
        yield [1730, 518];
        yield [1862, 558];
        yield [1864, 560];
        yield [2104, 632];
        yield [2106, 632];
        yield [2562, 768];
        yield [2564, 770];
        yield [3688, 1106];
        yield [3690, 1106];
        yield [4238, 1272];
        yield [4240, 1272];
        yield [4490, 1346];
        yield [4492, 1348];
        yield [4980, 1494];
        yield [4982, 1494];
        yield [5190, 1556];
        yield [5192, 1558];
        yield [5304, 1600];
        yield [5306, 1600];
        yield [5472, 1662];
        yield [5474, 1662];
        yield [5796, 1782];
        yield [5798, 1782];
        yield [6604, 2080];
        yield [6606, 2082];
        yield [7304, 2340];
        yield [7306, 2340];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('monthlyData')]
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

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($withheld, $payg->getTaxWithheldAmount());
    }

    /**
     * @return \Iterator<int, array<int, (float | int)>>
     */
    public static function monthlyData(): \Iterator
    {
        yield [502.67, 152];
        yield [507.0, 152];
        yield [645.67, 195];
        yield [650.0, 195];
        yield [1079.0, 325];
        yield [1083.33, 325];
        yield [1560.0, 468];
        yield [1564.33, 468];
        yield [1603.33, 481];
        yield [1607.67, 481];
        yield [2162.33, 650];
        yield [2166.67, 650];
        yield [2227.33, 667];
        yield [2231.67, 667];
        yield [2704.0, 810];
        yield [2708.33, 810];
        yield [3120.0, 936];
        yield [3124.33, 936];
        yield [3648.67, 1096];
        yield [3653.0, 1096];
        yield [3744.0, 1122];
        yield [3748.33, 1122];
        yield [4034.33, 1209];
        yield [4038.67, 1213];
        yield [4558.67, 1369];
        yield [4563.0, 1369];
        yield [5551.0, 1664];
        yield [5555.33, 1668];
        yield [7990.67, 2396];
        yield [7995.0, 2396];
        yield [9182.33, 2756];
        yield [9186.67, 2756];
        yield [9728.33, 2916];
        yield [9732.67, 2921];
        yield [10790.0, 3237];
        yield [10794.33, 3237];
        yield [11245.0, 3371];
        yield [11249.33, 3376];
        yield [11492.0, 3467];
        yield [11496.33, 3467];
        yield [11856.0, 3601];
        yield [11860.33, 3601];
        yield [12558.0, 3861];
        yield [12562.33, 3861];
        yield [14308.67, 4507];
        yield [14313.0, 4511];
        yield [15825.33, 5070];
        yield [15829.67, 5070];
    }
}
