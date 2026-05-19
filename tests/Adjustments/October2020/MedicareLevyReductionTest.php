<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\Tests\Adjustments\October2020;

use ManageIt\PaygTax\Adjustments\MedicareLevyReduction;
use ManageIt\PaygTax\TaxScales\Nat1004;
use ManageIt\PaygTax\Tests\Fixtures\Earning;
use ManageIt\PaygTax\Tests\Fixtures\Payee;
use ManageIt\PaygTax\Tests\Fixtures\Payer;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;

#[\PHPUnit\Framework\Attributes\CoversClass(\ManageIt\PaygTax\Adjustments\MedicareLevyReduction::class)]
final class MedicareLevyReductionTest extends TestCase
{
    protected MedicareLevyReduction $adjustment;

    public function setUp(): void
    {
        $this->adjustment = new MedicareLevyReduction();
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('weeklyData')]
    public function testWeeklyAdjustmentScale2(
        int $gross,
        float $spouseOnly,
        float $children1,
        float $children2,
        float $children3,
        float $children4,
        float $children5,
    ): void {
        $payer = new Payer();

        $payee = new Payee();
        $payee->payCycle = Payee::PAY_CYCLE_WEEKLY;
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;

        $earning = new Earning();
        $earning->date = new \DateTime('2022-10-10');
        $earning->gross = $gross;

        $scale = new Nat1004();

        $this->adjustment->spouse = true;
        $this->adjustment->children = 0;

        Assert::assertSame(
            $spouseOnly,
            $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1,
        );

        $this->adjustment->children = 1;
        Assert::assertSame($children1, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 2;
        Assert::assertSame($children2, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 3;
        Assert::assertSame($children3, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 4;
        Assert::assertSame($children4, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 5;
        Assert::assertSame($children5, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);
    }

    /**
     * @return \Iterator<int, array<int, (float | int)>>
     */
    public static function weeklyData(): \Iterator
    {
        yield [437, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0];
        yield [438, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0];
        yield [492, 5.0, 5.0, 5.0, 5.0, 5.0, 5.0];
        yield [493, 6.0, 6.0, 6.0, 6.0, 6.0, 6.0];
        yield [547, 11.0, 11.0, 11.0, 11.0, 11.0, 11.0];
        yield [548, 11.0, 11.0, 11.0, 11.0, 11.0, 11.0];
        yield [575, 12.0, 12.0, 12.0, 12.0, 12.0, 12.0];
        yield [576, 12.0, 12.0, 12.0, 12.0, 12.0, 12.0];
        yield [603, 12.0, 12.0, 12.0, 12.0, 12.0, 12.0];
        yield [604, 12.0, 12.0, 12.0, 12.0, 12.0, 12.0];
        yield [631, 13.0, 13.0, 13.0, 13.0, 13.0, 13.0];
        yield [632, 13.0, 13.0, 13.0, 13.0, 13.0, 13.0];
        yield [659, 13.0, 13.0, 13.0, 13.0, 13.0, 13.0];
        yield [660, 13.0, 13.0, 13.0, 13.0, 13.0, 13.0];
        yield [687, 14.0, 14.0, 14.0, 14.0, 14.0, 14.0];
        yield [688, 14.0, 14.0, 14.0, 14.0, 14.0, 14.0];
        yield [715, 14.0, 14.0, 14.0, 14.0, 14.0, 14.0];
        yield [716, 14.0, 14.0, 14.0, 14.0, 14.0, 14.0];
        yield [743, 14.0, 15.0, 15.0, 15.0, 15.0, 15.0];
        yield [744, 14.0, 15.0, 15.0, 15.0, 15.0, 15.0];
        yield [771, 12.0, 15.0, 15.0, 15.0, 15.0, 15.0];
        yield [772, 12.0, 15.0, 15.0, 15.0, 15.0, 15.0];
        yield [799, 10.0, 16.0, 16.0, 16.0, 16.0, 16.0];
        yield [800, 10.0, 16.0, 16.0, 16.0, 16.0, 16.0];
        yield [827, 8.0, 15.0, 17.0, 17.0, 17.0, 17.0];
        yield [828, 8.0, 14.0, 17.0, 17.0, 17.0, 17.0];
        yield [855, 6.0, 12.0, 17.0, 17.0, 17.0, 17.0];
        yield [856, 5.0, 12.0, 17.0, 17.0, 17.0, 17.0];
        yield [883, 3.0, 10.0, 17.0, 18.0, 18.0, 18.0];
        yield [884, 3.0, 10.0, 17.0, 18.0, 18.0, 18.0];
        yield [911, 1.0, 8.0, 15.0, 18.0, 18.0, 18.0];
        yield [912, 1.0, 8.0, 15.0, 18.0, 18.0, 18.0];
        yield [939, 0.0, 6.0, 12.0, 19.0, 19.0, 19.0];
        yield [940, 0.0, 6.0, 12.0, 19.0, 19.0, 19.0];
        yield [967, 0.0, 3.0, 10.0, 17.0, 19.0, 19.0];
        yield [968, 0.0, 3.0, 10.0, 17.0, 19.0, 19.0];
        yield [995, 0.0, 1.0, 8.0, 15.0, 20.0, 20.0];
        yield [996, 0.0, 1.0, 8.0, 15.0, 20.0, 20.0];
        yield [1023, 0.0, 0.0, 6.0, 12.0, 19.0, 20.0];
        yield [1024, 0.0, 0.0, 6.0, 12.0, 19.0, 20.0];
        yield [1051, 0.0, 0.0, 3.0, 10.0, 17.0, 21.0];
        yield [1052, 0.0, 0.0, 3.0, 10.0, 17.0, 21.0];
        yield [1079, 0.0, 0.0, 1.0, 8.0, 15.0, 22.0];
        yield [1080, 0.0, 0.0, 1.0, 8.0, 15.0, 21.0];
        yield [1263, 0.0, 0.0, 0.0, 0.0, 0.0, 7.0];
        yield [1264, 0.0, 0.0, 0.0, 0.0, 0.0, 7.0];
        yield [1348, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0];
        yield [1349, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('weeklyDataScale6')]
    public function testWeeklyAdjustmentScale6(
        int $gross,
        float $children1,
        float $children2,
        float $children3,
        float $children4,
        float $children5,
    ): void {
        $payer = new Payer();

        $payee = new Payee();
        $payee->payCycle = Payee::PAY_CYCLE_WEEKLY;
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_HALF;

        $earning = new Earning();
        $earning->date = new \DateTime('2022-10-10');
        $earning->gross = $gross;

        $scale = new Nat1004();

        $this->adjustment->spouse = true;
        $this->adjustment->children = 1;
        Assert::assertSame($children1, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 2;
        Assert::assertSame($children2, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 3;
        Assert::assertSame($children3, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 4;
        Assert::assertSame($children4, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 5;
        Assert::assertSame($children5, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);
    }

    /**
     * @return \Iterator<int, array<int, (float | int)>>
     */
    public static function weeklyDataScale6(): \Iterator
    {
        yield [738, 0.0, 0.0, 0.0, 0.0, 0.0];
        yield [739, 0.0, 0.0, 0.0, 0.0, 0.0];
        yield [830, 5.0, 5.0, 5.0, 5.0, 5.0];
        yield [831, 5.0, 5.0, 5.0, 5.0, 5.0];
        yield [923, 9.0, 9.0, 9.0, 9.0, 9.0];
        yield [924, 3.0, 7.0, 9.0, 9.0, 9.0];
        yield [940, 3.0, 6.0, 9.0, 9.0, 9.0];
        yield [941, 3.0, 6.0, 9.0, 9.0, 9.0];
        yield [957, 2.0, 5.0, 9.0, 10.0, 10.0];
        yield [958, 2.0, 5.0, 9.0, 10.0, 10.0];
        yield [974, 1.0, 5.0, 8.0, 10.0, 10.0];
        yield [975, 1.0, 5.0, 8.0, 10.0, 10.0];
        yield [991, 1.0, 4.0, 8.0, 10.0, 10.0];
        yield [992, 1.0, 4.0, 7.0, 10.0, 10.0];
        yield [1008, 0.0, 3.0, 7.0, 10.0, 10.0];
        yield [1009, 0.0, 3.0, 7.0, 10.0, 10.0];
        yield [1025, 0.0, 3.0, 6.0, 10.0, 10.0];
        yield [1026, 0.0, 3.0, 6.0, 10.0, 10.0];
        yield [1042, 0.0, 2.0, 5.0, 9.0, 10.0];
        yield [1043, 0.0, 2.0, 5.0, 9.0, 10.0];
        yield [1059, 0.0, 1.0, 5.0, 8.0, 11.0];
        yield [1060, 0.0, 1.0, 5.0, 8.0, 11.0];
        yield [1076, 0.0, 1.0, 4.0, 8.0, 11.0];
        yield [1077, 0.0, 1.0, 4.0, 7.0, 11.0];
        yield [1093, 0.0, 0.0, 3.0, 7.0, 10.0];
        yield [1094, 0.0, 0.0, 3.0, 7.0, 10.0];
        yield [1110, 0.0, 0.0, 3.0, 6.0, 10.0];
        yield [1111, 0.0, 0.0, 3.0, 6.0, 10.0];
        yield [1127, 0.0, 0.0, 2.0, 5.0, 9.0];
        yield [1128, 0.0, 0.0, 2.0, 5.0, 9.0];
        yield [1144, 0.0, 0.0, 1.0, 5.0, 8.0];
        yield [1145, 0.0, 0.0, 1.0, 5.0, 8.0];
        yield [1161, 0.0, 0.0, 1.0, 4.0, 8.0];
        yield [1162, 0.0, 0.0, 1.0, 4.0, 7.0];
        yield [1178, 0.0, 0.0, 0.0, 3.0, 7.0];
        yield [1179, 0.0, 0.0, 0.0, 3.0, 7.0];
        yield [1195, 0.0, 0.0, 0.0, 3.0, 6.0];
        yield [1196, 0.0, 0.0, 0.0, 3.0, 6.0];
        yield [1212, 0.0, 0.0, 0.0, 2.0, 5.0];
        yield [1213, 0.0, 0.0, 0.0, 2.0, 5.0];
        yield [1229, 0.0, 0.0, 0.0, 1.0, 5.0];
        yield [1230, 0.0, 0.0, 0.0, 1.0, 5.0];
        yield [1246, 0.0, 0.0, 0.0, 1.0, 4.0];
        yield [1247, 0.0, 0.0, 0.0, 1.0, 4.0];
        yield [1263, 0.0, 0.0, 0.0, 0.0, 3.0];
        yield [1264, 0.0, 0.0, 0.0, 0.0, 3.0];
        yield [1348, 0.0, 0.0, 0.0, 0.0, 0.0];
        yield [1349, 0.0, 0.0, 0.0, 0.0, 0.0];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('fortnightlyData')]
    public function testFortnightlyAdjustmentScale2(
        int $gross,
        float $spouseOnly,
        float $children1,
        float $children2,
        float $children3,
        float $children4,
        float $children5,
    ): void {
        $payer = new Payer();

        $payee = new Payee();
        $payee->payCycle = Payee::PAY_CYCLE_FORTNIGHTLY;
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;

        $earning = new Earning();
        $earning->date = new \DateTime('2022-10-10');
        $earning->gross = $gross;

        $scale = new Nat1004();

        $this->adjustment->spouse = true;
        $this->adjustment->children = 0;

        Assert::assertSame(
            $spouseOnly,
            $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1,
        );

        $this->adjustment->children = 1;
        Assert::assertSame($children1, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 2;
        Assert::assertSame($children2, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 3;
        Assert::assertSame($children3, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 4;
        Assert::assertSame($children4, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 5;
        Assert::assertSame($children5, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);
    }

    /**
     * @return \Iterator<int, array<int, (float | int)>>
     */
    public static function fortnightlyData(): \Iterator
    {
        yield [874, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0];
        yield [876, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0];
        yield [984, 10.0, 10.0, 10.0, 10.0, 10.0, 10.0];
        yield [986, 12.0, 12.0, 12.0, 12.0, 12.0, 12.0];
        yield [1094, 22.0, 22.0, 22.0, 22.0, 22.0, 22.0];
        yield [1096, 22.0, 22.0, 22.0, 22.0, 22.0, 22.0];
        yield [1150, 24.0, 24.0, 24.0, 24.0, 24.0, 24.0];
        yield [1152, 24.0, 24.0, 24.0, 24.0, 24.0, 24.0];
        yield [1206, 24.0, 24.0, 24.0, 24.0, 24.0, 24.0];
        yield [1208, 24.0, 24.0, 24.0, 24.0, 24.0, 24.0];
        yield [1262, 26.0, 26.0, 26.0, 26.0, 26.0, 26.0];
        yield [1264, 26.0, 26.0, 26.0, 26.0, 26.0, 26.0];
        yield [1318, 26.0, 26.0, 26.0, 26.0, 26.0, 26.0];
        yield [1320, 26.0, 26.0, 26.0, 26.0, 26.0, 26.0];
        yield [1374, 28.0, 28.0, 28.0, 28.0, 28.0, 28.0];
        yield [1376, 28.0, 28.0, 28.0, 28.0, 28.0, 28.0];
        yield [1430, 28.0, 28.0, 28.0, 28.0, 28.0, 28.0];
        yield [1432, 28.0, 28.0, 28.0, 28.0, 28.0, 28.0];
        yield [1486, 28.0, 30.0, 30.0, 30.0, 30.0, 30.0];
        yield [1488, 28.0, 30.0, 30.0, 30.0, 30.0, 30.0];
        yield [1542, 24.0, 30.0, 30.0, 30.0, 30.0, 30.0];
        yield [1544, 24.0, 30.0, 30.0, 30.0, 30.0, 30.0];
        yield [1598, 20.0, 32.0, 32.0, 32.0, 32.0, 32.0];
        yield [1600, 20.0, 32.0, 32.0, 32.0, 32.0, 32.0];
        yield [1654, 16.0, 30.0, 34.0, 34.0, 34.0, 34.0];
        yield [1656, 16.0, 28.0, 34.0, 34.0, 34.0, 34.0];
        yield [1710, 12.0, 24.0, 34.0, 34.0, 34.0, 34.0];
        yield [1712, 10.0, 24.0, 34.0, 34.0, 34.0, 34.0];
        yield [1766, 6.0, 20.0, 34.0, 36.0, 36.0, 36.0];
        yield [1768, 6.0, 20.0, 34.0, 36.0, 36.0, 36.0];
        yield [1822, 2.0, 16.0, 30.0, 36.0, 36.0, 36.0];
        yield [1824, 2.0, 16.0, 30.0, 36.0, 36.0, 36.0];
        yield [1878, 0.0, 12.0, 24.0, 38.0, 38.0, 38.0];
        yield [1880, 0.0, 12.0, 24.0, 38.0, 38.0, 38.0];
        yield [1934, 0.0, 6.0, 20.0, 34.0, 38.0, 38.0];
        yield [1936, 0.0, 6.0, 20.0, 34.0, 38.0, 38.0];
        yield [1990, 0.0, 2.0, 16.0, 30.0, 40.0, 40.0];
        yield [1992, 0.0, 2.0, 16.0, 30.0, 40.0, 40.0];
        yield [2046, 0.0, 0.0, 12.0, 24.0, 38.0, 40.0];
        yield [2048, 0.0, 0.0, 12.0, 24.0, 38.0, 40.0];
        yield [2102, 0.0, 0.0, 6.0, 20.0, 34.0, 42.0];
        yield [2104, 0.0, 0.0, 6.0, 20.0, 34.0, 42.0];
        yield [2158, 0.0, 0.0, 2.0, 16.0, 30.0, 44.0];
        yield [2160, 0.0, 0.0, 2.0, 16.0, 30.0, 42.0];
        yield [2526, 0.0, 0.0, 0.0, 0.0, 0.0, 14.0];
        yield [2528, 0.0, 0.0, 0.0, 0.0, 0.0, 14.0];
        yield [2696, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0];
        yield [2698, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('fortnightlyDataScale6')]
    public function testFortnightlyAdjustmentScale6(
        int $gross,
        float $children1,
        float $children2,
        float $children3,
        float $children4,
        float $children5,
    ): void {
        $payer = new Payer();

        $payee = new Payee();
        $payee->payCycle = Payee::PAY_CYCLE_FORTNIGHTLY;
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_HALF;

        $earning = new Earning();
        $earning->date = new \DateTime('2022-10-10');
        $earning->gross = $gross;

        $scale = new Nat1004();

        $this->adjustment->spouse = true;
        $this->adjustment->children = 1;
        Assert::assertSame($children1, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 2;
        Assert::assertSame($children2, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 3;
        Assert::assertSame($children3, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 4;
        Assert::assertSame($children4, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 5;
        Assert::assertSame($children5, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);
    }

    /**
     * @return \Iterator<int, array<int, (float | int)>>
     */
    public static function fortnightlyDataScale6(): \Iterator
    {
        yield [1476, 0.0, 0.0, 0.0, 0.0, 0.0];
        yield [1478, 0.0, 0.0, 0.0, 0.0, 0.0];
        yield [1660, 10.0, 10.0, 10.0, 10.0, 10.0];
        yield [1662, 10.0, 10.0, 10.0, 10.0, 10.0];
        yield [1846, 18.0, 18.0, 18.0, 18.0, 18.0];
        yield [1848, 6.0, 14.0, 18.0, 18.0, 18.0];
        yield [1880, 6.0, 12.0, 18.0, 18.0, 18.0];
        yield [1882, 6.0, 12.0, 18.0, 18.0, 18.0];
        yield [1914, 4.0, 10.0, 18.0, 20.0, 20.0];
        yield [1916, 4.0, 10.0, 18.0, 20.0, 20.0];
        yield [1948, 2.0, 10.0, 16.0, 20.0, 20.0];
        yield [1950, 2.0, 10.0, 16.0, 20.0, 20.0];
        yield [1982, 2.0, 8.0, 16.0, 20.0, 20.0];
        yield [1984, 2.0, 8.0, 14.0, 20.0, 20.0];
        yield [2016, 0.0, 6.0, 14.0, 20.0, 20.0];
        yield [2018, 0.0, 6.0, 14.0, 20.0, 20.0];
        yield [2050, 0.0, 6.0, 12.0, 20.0, 20.0];
        yield [2052, 0.0, 6.0, 12.0, 20.0, 20.0];
        yield [2084, 0.0, 4.0, 10.0, 18.0, 20.0];
        yield [2086, 0.0, 4.0, 10.0, 18.0, 20.0];
        yield [2118, 0.0, 2.0, 10.0, 16.0, 22.0];
        yield [2120, 0.0, 2.0, 10.0, 16.0, 22.0];
        yield [2152, 0.0, 2.0, 8.0, 16.0, 22.0];
        yield [2154, 0.0, 2.0, 8.0, 14.0, 22.0];
        yield [2186, 0.0, 0.0, 6.0, 14.0, 20.0];
        yield [2188, 0.0, 0.0, 6.0, 14.0, 20.0];
        yield [2220, 0.0, 0.0, 6.0, 12.0, 20.0];
        yield [2222, 0.0, 0.0, 6.0, 12.0, 20.0];
        yield [2254, 0.0, 0.0, 4.0, 10.0, 18.0];
        yield [2256, 0.0, 0.0, 4.0, 10.0, 18.0];
        yield [2288, 0.0, 0.0, 2.0, 10.0, 16.0];
        yield [2290, 0.0, 0.0, 2.0, 10.0, 16.0];
        yield [2322, 0.0, 0.0, 2.0, 8.0, 16.0];
        yield [2324, 0.0, 0.0, 2.0, 8.0, 14.0];
        yield [2356, 0.0, 0.0, 0.0, 6.0, 14.0];
        yield [2358, 0.0, 0.0, 0.0, 6.0, 14.0];
        yield [2390, 0.0, 0.0, 0.0, 6.0, 12.0];
        yield [2392, 0.0, 0.0, 0.0, 6.0, 12.0];
        yield [2424, 0.0, 0.0, 0.0, 4.0, 10.0];
        yield [2426, 0.0, 0.0, 0.0, 4.0, 10.0];
        yield [2458, 0.0, 0.0, 0.0, 2.0, 10.0];
        yield [2460, 0.0, 0.0, 0.0, 2.0, 10.0];
        yield [2492, 0.0, 0.0, 0.0, 2.0, 8.0];
        yield [2494, 0.0, 0.0, 0.0, 2.0, 8.0];
        yield [2526, 0.0, 0.0, 0.0, 0.0, 6.0];
        yield [2528, 0.0, 0.0, 0.0, 0.0, 6.0];
        yield [2696, 0.0, 0.0, 0.0, 0.0, 0.0];
        yield [2698, 0.0, 0.0, 0.0, 0.0, 0.0];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('monthlyData')]
    public function testMonthlyAdjustmentScale2(
        float $gross,
        float $spouseOnly,
        float $children1,
        float $children2,
        float $children3,
        float $children4,
        float $children5,
    ): void {
        $payer = new Payer();

        $payee = new Payee();
        $payee->payCycle = Payee::PAY_CYCLE_MONTHLY;
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;

        $earning = new Earning();
        $earning->date = new \DateTime('2022-10-10');
        $earning->gross = $gross;

        $scale = new Nat1004();

        $this->adjustment->spouse = true;
        $this->adjustment->children = 0;

        Assert::assertSame(
            $spouseOnly,
            $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1,
        );

        $this->adjustment->children = 1;
        Assert::assertSame($children1, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 2;
        Assert::assertSame($children2, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 3;
        Assert::assertSame($children3, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 4;
        Assert::assertSame($children4, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 5;
        Assert::assertSame($children5, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);
    }

    /**
     * @return \Iterator<int, array<int, (float | int)>>
     */
    public static function monthlyData(): \Iterator
    {
        yield [1893.67, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0];
        yield [1898.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0];
        yield [2132.0, 22.0, 22.0, 22.0, 22.0, 22.0, 22.0];
        yield [2136.33, 26.0, 26.0, 26.0, 26.0, 26.0, 26.0];
        yield [2370.33, 48.0, 48.0, 48.0, 48.0, 48.0, 48.0];
        yield [2374.67, 48.0, 48.0, 48.0, 48.0, 48.0, 48.0];
        yield [2491.67, 52.0, 52.0, 52.0, 52.0, 52.0, 52.0];
        yield [2496.0, 52.0, 52.0, 52.0, 52.0, 52.0, 52.0];
        yield [2613.0, 52.0, 52.0, 52.0, 52.0, 52.0, 52.0];
        yield [2617.33, 52.0, 52.0, 52.0, 52.0, 52.0, 52.0];
        yield [2734.33, 56.0, 56.0, 56.0, 56.0, 56.0, 56.0];
        yield [2738.67, 56.0, 56.0, 56.0, 56.0, 56.0, 56.0];
        yield [2855.67, 56.0, 56.0, 56.0, 56.0, 56.0, 56.0];
        yield [2860.0, 56.0, 56.0, 56.0, 56.0, 56.0, 56.0];
        yield [2977.0, 61.0, 61.0, 61.0, 61.0, 61.0, 61.0];
        yield [2981.33, 61.0, 61.0, 61.0, 61.0, 61.0, 61.0];
        yield [3098.33, 61.0, 61.0, 61.0, 61.0, 61.0, 61.0];
        yield [3102.67, 61.0, 61.0, 61.0, 61.0, 61.0, 61.0];
        yield [3219.67, 61.0, 65.0, 65.0, 65.0, 65.0, 65.0];
        yield [3224.0, 61.0, 65.0, 65.0, 65.0, 65.0, 65.0];
        yield [3341.0, 52.0, 65.0, 65.0, 65.0, 65.0, 65.0];
        yield [3345.33, 52.0, 65.0, 65.0, 65.0, 65.0, 65.0];
        yield [3462.33, 43.0, 69.0, 69.0, 69.0, 69.0, 69.0];
        yield [3466.67, 43.0, 69.0, 69.0, 69.0, 69.0, 69.0];
        yield [3583.67, 35.0, 65.0, 74.0, 74.0, 74.0, 74.0];
        yield [3588.0, 35.0, 61.0, 74.0, 74.0, 74.0, 74.0];
        yield [3705.0, 26.0, 52.0, 74.0, 74.0, 74.0, 74.0];
        yield [3709.33, 22.0, 52.0, 74.0, 74.0, 74.0, 74.0];
        yield [3826.33, 13.0, 43.0, 74.0, 78.0, 78.0, 78.0];
        yield [3830.67, 13.0, 43.0, 74.0, 78.0, 78.0, 78.0];
        yield [3947.67, 4.0, 35.0, 65.0, 78.0, 78.0, 78.0];
        yield [3952.0, 4.0, 35.0, 65.0, 78.0, 78.0, 78.0];
        yield [4069.0, 0.0, 26.0, 52.0, 82.0, 82.0, 82.0];
        yield [4073.33, 0.0, 26.0, 52.0, 82.0, 82.0, 82.0];
        yield [4190.33, 0.0, 13.0, 43.0, 74.0, 82.0, 82.0];
        yield [4194.67, 0.0, 13.0, 43.0, 74.0, 82.0, 82.0];
        yield [4311.67, 0.0, 4.0, 35.0, 65.0, 87.0, 87.0];
        yield [4316.0, 0.0, 4.0, 35.0, 65.0, 87.0, 87.0];
        yield [4433.0, 0.0, 0.0, 26.0, 52.0, 82.0, 87.0];
        yield [4437.33, 0.0, 0.0, 26.0, 52.0, 82.0, 87.0];
        yield [4554.33, 0.0, 0.0, 13.0, 43.0, 74.0, 91.0];
        yield [4558.67, 0.0, 0.0, 13.0, 43.0, 74.0, 91.0];
        yield [4675.67, 0.0, 0.0, 4.0, 35.0, 65.0, 95.0];
        yield [4680.0, 0.0, 0.0, 4.0, 35.0, 65.0, 91.0];
        yield [5473.0, 0.0, 0.0, 0.0, 0.0, 0.0, 30.0];
        yield [5477.33, 0.0, 0.0, 0.0, 0.0, 0.0, 30.0];
        yield [5841.33, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0];
        yield [5845.67, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0];
    }
}
