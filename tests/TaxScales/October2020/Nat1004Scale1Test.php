<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\Tests\TaxScales\October2020;

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
        $earning->date = new \DateTime('2022-10-15');
        $earning->gross = $gross;

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($withheld, $payg->getTaxWithheldAmount());
    }

    /**
     * @return \Iterator<int, array<int, (float | int)>>
     */
    public static function weeklyData(): \Iterator
    {
        yield [87, 17];
        yield [88, 17];
        yield [116, 24];
        yield [117, 24];
        yield [249, 55];
        yield [250, 55];
        yield [358, 80];
        yield [359, 81];
        yield [370, 83];
        yield [371, 83];
        yield [437, 98];
        yield [438, 98];
        yield [514, 115];
        yield [515, 115];
        yield [547, 126];
        yield [548, 126];
        yield [720, 186];
        yield [721, 187];
        yield [738, 193];
        yield [739, 193];
        yield [864, 236];
        yield [865, 237];
        yield [923, 257];
        yield [924, 257];
        yield [931, 260];
        yield [932, 260];
        yield [1281, 380];
        yield [1282, 381];
        yield [1844, 575];
        yield [1845, 575];
        yield [1956, 613];
        yield [1957, 614];
        yield [2119, 677];
        yield [2120, 677];
        yield [2306, 750];
        yield [2307, 750];
        yield [2490, 821];
        yield [2491, 822];
        yield [2652, 885];
        yield [2653, 885];
        yield [2736, 917];
        yield [2737, 918];
        yield [2898, 981];
        yield [2899, 981];
        yield [2913, 986];
        yield [2914, 987];
        yield [3111, 1064];
        yield [3461, 1228];
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
        $earning->date = new \DateTime('2022-10-15');
        $earning->gross = $gross;

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($withheld, $payg->getTaxWithheldAmount());
    }

    /**
     * @return \Iterator<int, array<int, (float | int)>>
     */
    public static function fortnightlyData(): \Iterator
    {
        yield [174, 34];
        yield [176, 34];
        yield [232, 48];
        yield [234, 48];
        yield [498, 110];
        yield [500, 110];
        yield [716, 160];
        yield [718, 162];
        yield [740, 166];
        yield [742, 166];
        yield [874, 196];
        yield [876, 196];
        yield [1028, 230];
        yield [1030, 230];
        yield [1094, 252];
        yield [1096, 252];
        yield [1440, 372];
        yield [1442, 374];
        yield [1476, 386];
        yield [1478, 386];
        yield [1728, 472];
        yield [1730, 474];
        yield [1846, 514];
        yield [1848, 514];
        yield [1862, 520];
        yield [1864, 520];
        yield [2562, 760];
        yield [2564, 762];
        yield [3688, 1150];
        yield [3690, 1150];
        yield [3912, 1226];
        yield [3914, 1228];
        yield [4238, 1354];
        yield [4240, 1354];
        yield [4612, 1500];
        yield [4614, 1500];
        yield [4980, 1642];
        yield [4982, 1644];
        yield [5304, 1770];
        yield [5306, 1770];
        yield [5472, 1834];
        yield [5474, 1836];
        yield [5796, 1962];
        yield [5798, 1962];
        yield [5826, 1972];
        yield [5828, 1974];
        yield [6222, 2128];
        yield [6922, 2456];
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
        $earning->date = new \DateTime('2022-10-15');
        $earning->gross = $gross;

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($withheld, $payg->getTaxWithheldAmount());
    }

    /**
     * @return \Iterator<int, array<int, (float | int)>>
     */
    public static function monthlyData(): \Iterator
    {
        yield [377.0, 74];
        yield [381.33, 74];
        yield [502.67, 104];
        yield [507.0, 104];
        yield [1079.0, 238];
        yield [1083.33, 238];
        yield [1551.33, 347];
        yield [1555.67, 351];
        yield [1603.33, 360];
        yield [1607.67, 360];
        yield [1893.67, 425];
        yield [1898.0, 425];
        yield [2227.33, 498];
        yield [2231.67, 498];
        yield [2370.33, 546];
        yield [2374.67, 546];
        yield [3120.0, 806];
        yield [3124.33, 810];
        yield [3198.0, 836];
        yield [3202.33, 836];
        yield [3744.0, 1023];
        yield [3748.33, 1027];
        yield [3999.67, 1114];
        yield [4004.0, 1114];
        yield [4034.33, 1127];
        yield [4038.67, 1127];
        yield [5551.0, 1647];
        yield [5555.33, 1651];
        yield [7990.67, 2492];
        yield [7995.0, 2492];
        yield [8476.0, 2656];
        yield [8480.33, 2661];
        yield [9182.33, 2934];
        yield [9186.67, 2934];
        yield [9992.67, 3250];
        yield [9997.0, 3250];
        yield [10790.0, 3558];
        yield [10794.33, 3562];
        yield [11492.0, 3835];
        yield [11496.33, 3835];
        yield [11856.0, 3974];
        yield [11860.33, 3978];
        yield [12558.0, 4251];
        yield [12562.33, 4251];
        yield [12623.0, 4273];
        yield [12627.33, 4277];
        yield [13481.0, 4611];
        yield [14997.67, 5321];
    }
}
