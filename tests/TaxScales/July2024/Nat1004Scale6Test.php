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
final class Nat1004Scale6Test extends TestCase
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
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_HALF;

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
        yield [514, 25];
        yield [515, 25];
        yield [624, 42];
        yield [625, 42];
        yield [720, 58];
        yield [721, 58];
        yield [842, 78];
        yield [843, 78];
        yield [864, 83];
        yield [865, 83];
        yield [931, 107];
        yield [932, 107];
        yield [1052, 149];
        yield [1053, 150];
        yield [1281, 221];
        yield [1282, 221];
        yield [1844, 395];
        yield [1845, 396];
        yield [2119, 481];
        yield [2120, 481];
        yield [2245, 520];
        yield [2246, 520];
        yield [2490, 596];
        yield [2491, 596];
        yield [2595, 628];
        yield [2596, 629];
        yield [2652, 650];
        yield [2653, 650];
        yield [2736, 682];
        yield [2737, 682];
        yield [2898, 743];
        yield [2899, 744];
        yield [3302, 897];
        yield [3303, 897];
        yield [3652, 1030];
        yield [3653, 1030];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('fortnightlyData')]
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
        yield [1028, 50];
        yield [1030, 50];
        yield [1248, 84];
        yield [1250, 84];
        yield [1440, 116];
        yield [1442, 116];
        yield [1684, 156];
        yield [1686, 156];
        yield [1728, 166];
        yield [1730, 166];
        yield [1862, 214];
        yield [1864, 214];
        yield [2104, 298];
        yield [2106, 300];
        yield [2562, 442];
        yield [2564, 442];
        yield [3688, 790];
        yield [3690, 792];
        yield [4238, 962];
        yield [4240, 962];
        yield [4490, 1040];
        yield [4492, 1040];
        yield [4980, 1192];
        yield [4982, 1192];
        yield [5190, 1256];
        yield [5192, 1258];
        yield [5304, 1300];
        yield [5306, 1300];
        yield [5472, 1364];
        yield [5474, 1364];
        yield [5796, 1486];
        yield [5798, 1488];
        yield [6604, 1794];
        yield [6606, 1794];
        yield [7304, 2060];
        yield [7306, 2060];
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
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_HALF;

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
        yield [2227.33, 108];
        yield [2231.67, 108];
        yield [2704.0, 182];
        yield [2708.33, 182];
        yield [3120.0, 251];
        yield [3124.33, 251];
        yield [3648.67, 338];
        yield [3653.0, 338];
        yield [3744.0, 360];
        yield [3748.33, 360];
        yield [4034.33, 464];
        yield [4038.67, 464];
        yield [4558.67, 646];
        yield [4563.0, 650];
        yield [5551.0, 958];
        yield [5555.33, 958];
        yield [7990.67, 1712];
        yield [7995.0, 1716];
        yield [9182.33, 2084];
        yield [9186.67, 2084];
        yield [9728.33, 2253];
        yield [9732.67, 2253];
        yield [10790.0, 2583];
        yield [10794.33, 2583];
        yield [11245.0, 2721];
        yield [11249.33, 2726];
        yield [11492.0, 2817];
        yield [11496.33, 2817];
        yield [11856.0, 2955];
        yield [11860.33, 2955];
        yield [12558.0, 3220];
        yield [12562.33, 3224];
        yield [14308.67, 3887];
        yield [14313.0, 3887];
        yield [15825.33, 4463];
        yield [15829.67, 4463];
    }
}
