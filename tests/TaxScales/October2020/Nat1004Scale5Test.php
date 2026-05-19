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
final class Nat1004Scale5Test extends TestCase
{
    #[\PHPUnit\Framework\Attributes\DataProvider('weeklyData')]
    public function testWeeklyWithholding(int $gross, float $withheld): void
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
        yield [87, 0.0];
        yield [88, 0.0];
        yield [116, 0.0];
        yield [117, 0.0];
        yield [249, 0.0];
        yield [250, 0.0];
        yield [358, 0.0];
        yield [359, 0.0];
        yield [370, 2.0];
        yield [371, 2.0];
        yield [437, 15.0];
        yield [438, 15.0];
        yield [514, 30.0];
        yield [515, 30.0];
        yield [547, 36.0];
        yield [548, 36.0];
        yield [720, 69.0];
        yield [721, 69.0];
        yield [738, 72.0];
        yield [739, 72.0];
        yield [864, 97.0];
        yield [865, 98.0];
        yield [923, 117.0];
        yield [924, 117.0];
        yield [931, 119.0];
        yield [932, 120.0];
        yield [1281, 234.0];
        yield [1282, 234.0];
        yield [1844, 417.0];
        yield [1845, 417.0];
        yield [1956, 453.0];
        yield [1957, 454.0];
        yield [2119, 506.0];
        yield [2120, 507.0];
        yield [2306, 567.0];
        yield [2307, 567.0];
        yield [2490, 635.0];
        yield [2491, 635.0];
        yield [2652, 695.0];
        yield [2653, 695.0];
        yield [2736, 726.0];
        yield [2737, 726.0];
        yield [2898, 786.0];
        yield [2899, 786.0];
        yield [2913, 792.0];
        yield [2914, 792.0];
        yield [3111, 865.0];
        yield [3461, 994.0];
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
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_FULL;

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
        yield [174, 0.0];
        yield [176, 0.0];
        yield [232, 0.0];
        yield [234, 0.0];
        yield [498, 0.0];
        yield [500, 0.0];
        yield [716, 0.0];
        yield [718, 0.0];
        yield [740, 4.0];
        yield [742, 4.0];
        yield [874, 30.0];
        yield [876, 30.0];
        yield [1028, 60.0];
        yield [1030, 60.0];
        yield [1094, 72.0];
        yield [1096, 72.0];
        yield [1440, 138.0];
        yield [1442, 138.0];
        yield [1476, 144.0];
        yield [1478, 144.0];
        yield [1728, 194.0];
        yield [1730, 196.0];
        yield [1846, 234.0];
        yield [1848, 234.0];
        yield [1862, 238.0];
        yield [1864, 240.0];
        yield [2562, 468.0];
        yield [2564, 468.0];
        yield [3688, 834.0];
        yield [3690, 834.0];
        yield [3912, 906.0];
        yield [3914, 908.0];
        yield [4238, 1012.0];
        yield [4240, 1014.0];
        yield [4612, 1134.0];
        yield [4614, 1134.0];
        yield [4980, 1270.0];
        yield [4982, 1270.0];
        yield [5304, 1390.0];
        yield [5306, 1390.0];
        yield [5472, 1452.0];
        yield [5474, 1452.0];
        yield [5796, 1572.0];
        yield [5798, 1572.0];
        yield [5826, 1584.0];
        yield [5828, 1584.0];
        yield [6222, 1730.0];
        yield [6922, 1988.0];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('monthlyData')]
    public function testMonthlyWithholding(float $gross, float $withheld): void
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
        yield [377.0, 0.0];
        yield [381.33, 0.0];
        yield [502.67, 0.0];
        yield [507.0, 0.0];
        yield [1079.0, 0.0];
        yield [1083.33, 0.0];
        yield [1551.33, 0.0];
        yield [1555.67, 0.0];
        yield [1603.33, 9.0];
        yield [1607.67, 9.0];
        yield [1893.67, 65.0];
        yield [1898.0, 65.0];
        yield [2227.33, 130.0];
        yield [2231.67, 130.0];
        yield [2370.33, 156.0];
        yield [2374.67, 156.0];
        yield [3120.0, 299.0];
        yield [3124.33, 299.0];
        yield [3198.0, 312.0];
        yield [3202.33, 312.0];
        yield [3744.0, 420.0];
        yield [3748.33, 425.0];
        yield [3999.67, 507.0];
        yield [4004.0, 507.0];
        yield [4034.33, 516.0];
        yield [4038.67, 520.0];
        yield [5551.0, 1014.0];
        yield [5555.33, 1014.0];
        yield [7990.67, 1807.0];
        yield [7995.0, 1807.0];
        yield [8476.0, 1963.0];
        yield [8480.33, 1967.0];
        yield [9182.33, 2193.0];
        yield [9186.67, 2197.0];
        yield [9992.67, 2457.0];
        yield [9997.0, 2457.0];
        yield [10790.0, 2752.0];
        yield [10794.33, 2752.0];
        yield [11492.0, 3012.0];
        yield [11496.33, 3012.0];
        yield [11856.0, 3146.0];
        yield [11860.33, 3146.0];
        yield [12558.0, 3406.0];
        yield [12562.33, 3406.0];
        yield [12623.0, 3432.0];
        yield [12627.33, 3432.0];
        yield [13481.0, 3748.0];
        yield [14997.67, 4307.0];
    }
}
