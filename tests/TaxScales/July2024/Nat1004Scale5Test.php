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
final class Nat1004Scale5Test extends TestCase
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
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_FULL;

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
        yield [864, 82];
        yield [865, 82];
        yield [931, 102];
        yield [932, 102];
        yield [1052, 139];
        yield [1053, 139];
        yield [1281, 208];
        yield [1282, 208];
        yield [1844, 377];
        yield [1845, 377];
        yield [2119, 459];
        yield [2120, 460];
        yield [2245, 497];
        yield [2246, 498];
        yield [2490, 571];
        yield [2491, 571];
        yield [2595, 602];
        yield [2596, 603];
        yield [2652, 623];
        yield [2653, 624];
        yield [2736, 654];
        yield [2737, 655];
        yield [2898, 714];
        yield [2899, 715];
        yield [3302, 864];
        yield [3303, 864];
        yield [3652, 993];
        yield [3653, 994];
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
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_FULL;

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
        yield [1728, 164];
        yield [1730, 164];
        yield [1862, 204];
        yield [1864, 204];
        yield [2104, 278];
        yield [2106, 278];
        yield [2562, 416];
        yield [2564, 416];
        yield [3688, 754];
        yield [3690, 754];
        yield [4238, 918];
        yield [4240, 920];
        yield [4490, 994];
        yield [4492, 996];
        yield [4980, 1142];
        yield [4982, 1142];
        yield [5190, 1204];
        yield [5192, 1206];
        yield [5304, 1246];
        yield [5306, 1248];
        yield [5472, 1308];
        yield [5474, 1310];
        yield [5796, 1428];
        yield [5798, 1430];
        yield [6604, 1728];
        yield [6606, 1728];
        yield [7304, 1986];
        yield [7306, 1988];
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
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_FULL;

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
        yield [3744.0, 355];
        yield [3748.33, 355];
        yield [4034.33, 442];
        yield [4038.67, 442];
        yield [4558.67, 602];
        yield [4563.0, 602];
        yield [5551.0, 901];
        yield [5555.33, 901];
        yield [7990.67, 1634];
        yield [7995.0, 1634];
        yield [9182.33, 1989];
        yield [9186.67, 1993];
        yield [9728.33, 2154];
        yield [9732.67, 2158];
        yield [10790.0, 2474];
        yield [10794.33, 2474];
        yield [11245.0, 2609];
        yield [11249.33, 2613];
        yield [11492.0, 2700];
        yield [11496.33, 2704];
        yield [11856.0, 2834];
        yield [11860.33, 2838];
        yield [12558.0, 3094];
        yield [12562.33, 3098];
        yield [14308.67, 3744];
        yield [14313.0, 3744];
        yield [15825.33, 4303];
        yield [15829.67, 4307];
    }
}
