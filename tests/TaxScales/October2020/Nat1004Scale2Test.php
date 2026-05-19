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
        yield [87, 0];
        yield [88, 0];
        yield [116, 0];
        yield [117, 0];
        yield [249, 0];
        yield [250, 0];
        yield [358, 0];
        yield [359, 0];
        yield [370, 2];
        yield [371, 2];
        yield [437, 15];
        yield [438, 15];
        yield [514, 37];
        yield [515, 37];
        yield [547, 47];
        yield [548, 47];
        yield [720, 83];
        yield [721, 83];
        yield [738, 87];
        yield [739, 87];
        yield [864, 115];
        yield [865, 115];
        yield [923, 135];
        yield [924, 135];
        yield [931, 138];
        yield [932, 138];
        yield [1281, 260];
        yield [1282, 260];
        yield [1844, 454];
        yield [1845, 454];
        yield [1956, 492];
        yield [1957, 493];
        yield [2119, 549];
        yield [2120, 549];
        yield [2306, 613];
        yield [2307, 614];
        yield [2490, 685];
        yield [2491, 685];
        yield [2652, 748];
        yield [2653, 748];
        yield [2736, 781];
        yield [2737, 781];
        yield [2898, 844];
        yield [2899, 844];
        yield [2913, 850];
        yield [2914, 850];
        yield [3111, 927];
        yield [3461, 1064];
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
        yield [174, 0];
        yield [176, 0];
        yield [232, 0];
        yield [234, 0];
        yield [498, 0];
        yield [500, 0];
        yield [716, 0];
        yield [718, 0];
        yield [740, 4];
        yield [742, 4];
        yield [874, 30];
        yield [876, 30];
        yield [1028, 74];
        yield [1030, 74];
        yield [1094, 94];
        yield [1096, 94];
        yield [1440, 166];
        yield [1442, 166];
        yield [1476, 174];
        yield [1478, 174];
        yield [1728, 230];
        yield [1730, 230];
        yield [1846, 270];
        yield [1848, 270];
        yield [1862, 276];
        yield [1864, 276];
        yield [2562, 520];
        yield [2564, 520];
        yield [3688, 908];
        yield [3690, 908];
        yield [3912, 984];
        yield [3914, 986];
        yield [4238, 1098];
        yield [4240, 1098];
        yield [4612, 1226];
        yield [4614, 1228];
        yield [4980, 1370];
        yield [4982, 1370];
        yield [5304, 1496];
        yield [5306, 1496];
        yield [5472, 1562];
        yield [5474, 1562];
        yield [5796, 1688];
        yield [5798, 1688];
        yield [5826, 1700];
        yield [5828, 1700];
        yield [6222, 1854];
        yield [6922, 2128];
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
        yield [377.0, 0];
        yield [381.33, 0];
        yield [502.67, 0];
        yield [507.0, 0];
        yield [1079.0, 0];
        yield [1083.33, 0];
        yield [1551.33, 0];
        yield [1555.67, 0];
        yield [1603.33, 9];
        yield [1607.67, 9];
        yield [1893.67, 65];
        yield [1898.0, 65];
        yield [2227.33, 160];
        yield [2231.67, 160];
        yield [2370.33, 204];
        yield [2374.67, 204];
        yield [3120.0, 360];
        yield [3124.33, 360];
        yield [3198.0, 377];
        yield [3202.33, 377];
        yield [3744.0, 498];
        yield [3748.33, 498];
        yield [3999.67, 585];
        yield [4004.0, 585];
        yield [4034.33, 598];
        yield [4038.67, 598];
        yield [5551.0, 1127];
        yield [5555.33, 1127];
        yield [7990.67, 1967];
        yield [7995.0, 1967];
        yield [8476.0, 2132];
        yield [8480.33, 2136];
        yield [9182.33, 2379];
        yield [9186.67, 2379];
        yield [9992.67, 2656];
        yield [9997.0, 2661];
        yield [10790.0, 2968];
        yield [10794.33, 2968];
        yield [11492.0, 3241];
        yield [11496.33, 3241];
        yield [11856.0, 3384];
        yield [11860.33, 3384];
        yield [12558.0, 3657];
        yield [12562.33, 3657];
        yield [12623.0, 3683];
        yield [12627.33, 3683];
        yield [13481.0, 4017];
        yield [14997.67, 4611];
    }
}
