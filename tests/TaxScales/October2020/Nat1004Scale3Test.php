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
        yield [87, 28];
        yield [88, 29];
        yield [116, 38];
        yield [117, 38];
        yield [249, 81];
        yield [250, 81];
        yield [358, 116];
        yield [359, 117];
        yield [370, 120];
        yield [371, 121];
        yield [437, 142];
        yield [438, 142];
        yield [514, 167];
        yield [515, 167];
        yield [547, 178];
        yield [548, 178];
        yield [720, 234];
        yield [721, 234];
        yield [738, 240];
        yield [739, 240];
        yield [864, 281];
        yield [865, 281];
        yield [923, 300];
        yield [924, 300];
        yield [931, 303];
        yield [932, 303];
        yield [1281, 416];
        yield [1282, 417];
        yield [1844, 599];
        yield [1845, 600];
        yield [1956, 636];
        yield [1957, 636];
        yield [2119, 689];
        yield [2120, 689];
        yield [2306, 749];
        yield [2307, 750];
        yield [2490, 818];
        yield [2491, 818];
        yield [2652, 878];
        yield [2653, 878];
        yield [2736, 909];
        yield [2737, 909];
        yield [2898, 969];
        yield [2899, 969];
        yield [2913, 974];
        yield [2914, 975];
        yield [3111, 1048];
        yield [3461, 1177];
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
        yield [174, 56];
        yield [176, 58];
        yield [232, 76];
        yield [234, 76];
        yield [498, 162];
        yield [500, 162];
        yield [716, 232];
        yield [718, 234];
        yield [740, 240];
        yield [742, 242];
        yield [874, 284];
        yield [876, 284];
        yield [1028, 334];
        yield [1030, 334];
        yield [1094, 356];
        yield [1096, 356];
        yield [1440, 468];
        yield [1442, 468];
        yield [1476, 480];
        yield [1478, 480];
        yield [1728, 562];
        yield [1730, 562];
        yield [1846, 600];
        yield [1848, 600];
        yield [1862, 606];
        yield [1864, 606];
        yield [2562, 832];
        yield [2564, 834];
        yield [3688, 1198];
        yield [3690, 1200];
        yield [3912, 1272];
        yield [3914, 1272];
        yield [4238, 1378];
        yield [4240, 1378];
        yield [4612, 1498];
        yield [4614, 1500];
        yield [4980, 1636];
        yield [4982, 1636];
        yield [5304, 1756];
        yield [5306, 1756];
        yield [5472, 1818];
        yield [5474, 1818];
        yield [5796, 1938];
        yield [5798, 1938];
        yield [5826, 1948];
        yield [5828, 1950];
        yield [6222, 2096];
        yield [6922, 2354];
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
        yield [377.0, 121];
        yield [381.33, 126];
        yield [502.67, 165];
        yield [507.0, 165];
        yield [1079.0, 351];
        yield [1083.33, 351];
        yield [1551.33, 503];
        yield [1555.67, 507];
        yield [1603.33, 520];
        yield [1607.67, 524];
        yield [1893.67, 615];
        yield [1898.0, 615];
        yield [2227.33, 724];
        yield [2231.67, 724];
        yield [2370.33, 771];
        yield [2374.67, 771];
        yield [3120.0, 1014];
        yield [3124.33, 1014];
        yield [3198.0, 1040];
        yield [3202.33, 1040];
        yield [3744.0, 1218];
        yield [3748.33, 1218];
        yield [3999.67, 1300];
        yield [4004.0, 1300];
        yield [4034.33, 1313];
        yield [4038.67, 1313];
        yield [5551.0, 1803];
        yield [5555.33, 1807];
        yield [7990.67, 2596];
        yield [7995.0, 2600];
        yield [8476.0, 2756];
        yield [8480.33, 2756];
        yield [9182.33, 2986];
        yield [9186.67, 2986];
        yield [9992.67, 3246];
        yield [9997.0, 3250];
        yield [10790.0, 3545];
        yield [10794.33, 3545];
        yield [11492.0, 3805];
        yield [11496.33, 3805];
        yield [11856.0, 3939];
        yield [11860.33, 3939];
        yield [12558.0, 4199];
        yield [12562.33, 4199];
        yield [12623.0, 4221];
        yield [12627.33, 4225];
        yield [13481.0, 4541];
        yield [14997.67, 5100];
    }
}
