<?php

namespace ManageIt\PaygTax\Tests\Adjustments\October2020;

use ManageIt\PaygTax\Adjustments\MedicareLevyReduction;
use ManageIt\PaygTax\TaxScales\Nat1004;
use ManageIt\PaygTax\Tests\Fixtures\Earning;
use ManageIt\PaygTax\Tests\Fixtures\Payee;
use ManageIt\PaygTax\Tests\Fixtures\Payer;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;

/**
 * @covers \ManageIt\PaygTax\Adjustments\MedicareLevyReduction
 */
class MedicareLevyReductionTest extends TestCase
{
    protected MedicareLevyReduction $adjustment;

    public function setUp(): void
    {
        $this->adjustment = new MedicareLevyReduction();
    }

    /**
     * @dataProvider weeklyData
     */
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

        Assert::assertEquals(
            $spouseOnly,
            $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1,
        );

        $this->adjustment->children = 1;
        Assert::assertEquals($children1, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 2;
        Assert::assertEquals($children2, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 3;
        Assert::assertEquals($children3, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 4;
        Assert::assertEquals($children4, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 5;
        Assert::assertEquals($children5, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);
    }

    /**
     * @return array<int, array<int|float, int|float>>
     */
    public static function weeklyData(): array
    {
        return [
            [437, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0],
            [438, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0],
            [492, 5.0, 5.0, 5.0, 5.0, 5.0, 5.0],
            [493, 6.0, 6.0, 6.0, 6.0, 6.0, 6.0],
            [547, 11.0, 11.0, 11.0, 11.0, 11.0, 11.0],
            [548, 11.0, 11.0, 11.0, 11.0, 11.0, 11.0],
            [575, 12.0, 12.0, 12.0, 12.0, 12.0, 12.0],
            [576, 12.0, 12.0, 12.0, 12.0, 12.0, 12.0],
            [603, 12.0, 12.0, 12.0, 12.0, 12.0, 12.0],
            [604, 12.0, 12.0, 12.0, 12.0, 12.0, 12.0],
            [631, 13.0, 13.0, 13.0, 13.0, 13.0, 13.0],
            [632, 13.0, 13.0, 13.0, 13.0, 13.0, 13.0],
            [659, 13.0, 13.0, 13.0, 13.0, 13.0, 13.0],
            [660, 13.0, 13.0, 13.0, 13.0, 13.0, 13.0],
            [687, 14.0, 14.0, 14.0, 14.0, 14.0, 14.0],
            [688, 14.0, 14.0, 14.0, 14.0, 14.0, 14.0],
            [715, 14.0, 14.0, 14.0, 14.0, 14.0, 14.0],
            [716, 14.0, 14.0, 14.0, 14.0, 14.0, 14.0],
            [743, 14.0, 15.0, 15.0, 15.0, 15.0, 15.0],
            [744, 14.0, 15.0, 15.0, 15.0, 15.0, 15.0],
            [771, 12.0, 15.0, 15.0, 15.0, 15.0, 15.0],
            [772, 12.0, 15.0, 15.0, 15.0, 15.0, 15.0],
            [799, 10.0, 16.0, 16.0, 16.0, 16.0, 16.0],
            [800, 10.0, 16.0, 16.0, 16.0, 16.0, 16.0],
            [827, 8.0, 15.0, 17.0, 17.0, 17.0, 17.0],
            [828, 8.0, 14.0, 17.0, 17.0, 17.0, 17.0],
            [855, 6.0, 12.0, 17.0, 17.0, 17.0, 17.0],
            [856, 5.0, 12.0, 17.0, 17.0, 17.0, 17.0],
            [883, 3.0, 10.0, 17.0, 18.0, 18.0, 18.0],
            [884, 3.0, 10.0, 17.0, 18.0, 18.0, 18.0],
            [911, 1.0, 8.0, 15.0, 18.0, 18.0, 18.0],
            [912, 1.0, 8.0, 15.0, 18.0, 18.0, 18.0],
            [939, 0.0, 6.0, 12.0, 19.0, 19.0, 19.0],
            [940, 0.0, 6.0, 12.0, 19.0, 19.0, 19.0],
            [967, 0.0, 3.0, 10.0, 17.0, 19.0, 19.0],
            [968, 0.0, 3.0, 10.0, 17.0, 19.0, 19.0],
            [995, 0.0, 1.0, 8.0, 15.0, 20.0, 20.0],
            [996, 0.0, 1.0, 8.0, 15.0, 20.0, 20.0],
            [1023, 0.0, 0.0, 6.0, 12.0, 19.0, 20.0],
            [1024, 0.0, 0.0, 6.0, 12.0, 19.0, 20.0],
            [1051, 0.0, 0.0, 3.0, 10.0, 17.0, 21.0],
            [1052, 0.0, 0.0, 3.0, 10.0, 17.0, 21.0],
            [1079, 0.0, 0.0, 1.0, 8.0, 15.0, 22.0],
            [1080, 0.0, 0.0, 1.0, 8.0, 15.0, 21.0],
            [1263, 0.0, 0.0, 0.0, 0.0, 0.0, 7.0],
            [1264, 0.0, 0.0, 0.0, 0.0, 0.0, 7.0],
            [1348, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0],
            [1349, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0],
        ];
    }

    /**
     * @dataProvider weeklyDataScale6
     */
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
        Assert::assertEquals($children1, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 2;
        Assert::assertEquals($children2, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 3;
        Assert::assertEquals($children3, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 4;
        Assert::assertEquals($children4, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 5;
        Assert::assertEquals($children5, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);
    }

    /**
     * @return array<int, array<int|float, int|float>>
     */
    public static function weeklyDataScale6(): array
    {
        return [
            [738, 0.0, 0.0, 0.0, 0.0, 0.0],
            [739, 0.0, 0.0, 0.0, 0.0, 0.0],
            [830, 5.0, 5.0, 5.0, 5.0, 5.0],
            [831, 5.0, 5.0, 5.0, 5.0, 5.0],
            [923, 9.0, 9.0, 9.0, 9.0, 9.0],
            [924, 3.0, 7.0, 9.0, 9.0, 9.0],
            [940, 3.0, 6.0, 9.0, 9.0, 9.0],
            [941, 3.0, 6.0, 9.0, 9.0, 9.0],
            [957, 2.0, 5.0, 9.0, 10.0, 10.0],
            [958, 2.0, 5.0, 9.0, 10.0, 10.0],
            [974, 1.0, 5.0, 8.0, 10.0, 10.0],
            [975, 1.0, 5.0, 8.0, 10.0, 10.0],
            [991, 1.0, 4.0, 8.0, 10.0, 10.0],
            [992, 1.0, 4.0, 7.0, 10.0, 10.0],
            [1008, 0.0, 3.0, 7.0, 10.0, 10.0],
            [1009, 0.0, 3.0, 7.0, 10.0, 10.0],
            [1025, 0.0, 3.0, 6.0, 10.0, 10.0],
            [1026, 0.0, 3.0, 6.0, 10.0, 10.0],
            [1042, 0.0, 2.0, 5.0, 9.0, 10.0],
            [1043, 0.0, 2.0, 5.0, 9.0, 10.0],
            [1059, 0.0, 1.0, 5.0, 8.0, 11.0],
            [1060, 0.0, 1.0, 5.0, 8.0, 11.0],
            [1076, 0.0, 1.0, 4.0, 8.0, 11.0],
            [1077, 0.0, 1.0, 4.0, 7.0, 11.0],
            [1093, 0.0, 0.0, 3.0, 7.0, 10.0],
            [1094, 0.0, 0.0, 3.0, 7.0, 10.0],
            [1110, 0.0, 0.0, 3.0, 6.0, 10.0],
            [1111, 0.0, 0.0, 3.0, 6.0, 10.0],
            [1127, 0.0, 0.0, 2.0, 5.0, 9.0],
            [1128, 0.0, 0.0, 2.0, 5.0, 9.0],
            [1144, 0.0, 0.0, 1.0, 5.0, 8.0],
            [1145, 0.0, 0.0, 1.0, 5.0, 8.0],
            [1161, 0.0, 0.0, 1.0, 4.0, 8.0],
            [1162, 0.0, 0.0, 1.0, 4.0, 7.0],
            [1178, 0.0, 0.0, 0.0, 3.0, 7.0],
            [1179, 0.0, 0.0, 0.0, 3.0, 7.0],
            [1195, 0.0, 0.0, 0.0, 3.0, 6.0],
            [1196, 0.0, 0.0, 0.0, 3.0, 6.0],
            [1212, 0.0, 0.0, 0.0, 2.0, 5.0],
            [1213, 0.0, 0.0, 0.0, 2.0, 5.0],
            [1229, 0.0, 0.0, 0.0, 1.0, 5.0],
            [1230, 0.0, 0.0, 0.0, 1.0, 5.0],
            [1246, 0.0, 0.0, 0.0, 1.0, 4.0],
            [1247, 0.0, 0.0, 0.0, 1.0, 4.0],
            [1263, 0.0, 0.0, 0.0, 0.0, 3.0],
            [1264, 0.0, 0.0, 0.0, 0.0, 3.0],
            [1348, 0.0, 0.0, 0.0, 0.0, 0.0],
            [1349, 0.0, 0.0, 0.0, 0.0, 0.0],
        ];
    }

    /**
     * @dataProvider fortnightlyData
     */
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

        Assert::assertEquals(
            $spouseOnly,
            $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1,
        );

        $this->adjustment->children = 1;
        Assert::assertEquals($children1, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 2;
        Assert::assertEquals($children2, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 3;
        Assert::assertEquals($children3, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 4;
        Assert::assertEquals($children4, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 5;
        Assert::assertEquals($children5, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);
    }

    /**
     * @return array<int, array<int|float, int|float>>
     */
    public static function fortnightlyData(): array
    {
        return [
            [874, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0],
            [876, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0],
            [984, 10.0, 10.0, 10.0, 10.0, 10.0, 10.0],
            [986, 12.0, 12.0, 12.0, 12.0, 12.0, 12.0],
            [1094, 22.0, 22.0, 22.0, 22.0, 22.0, 22.0],
            [1096, 22.0, 22.0, 22.0, 22.0, 22.0, 22.0],
            [1150, 24.0, 24.0, 24.0, 24.0, 24.0, 24.0],
            [1152, 24.0, 24.0, 24.0, 24.0, 24.0, 24.0],
            [1206, 24.0, 24.0, 24.0, 24.0, 24.0, 24.0],
            [1208, 24.0, 24.0, 24.0, 24.0, 24.0, 24.0],
            [1262, 26.0, 26.0, 26.0, 26.0, 26.0, 26.0],
            [1264, 26.0, 26.0, 26.0, 26.0, 26.0, 26.0],
            [1318, 26.0, 26.0, 26.0, 26.0, 26.0, 26.0],
            [1320, 26.0, 26.0, 26.0, 26.0, 26.0, 26.0],
            [1374, 28.0, 28.0, 28.0, 28.0, 28.0, 28.0],
            [1376, 28.0, 28.0, 28.0, 28.0, 28.0, 28.0],
            [1430, 28.0, 28.0, 28.0, 28.0, 28.0, 28.0],
            [1432, 28.0, 28.0, 28.0, 28.0, 28.0, 28.0],
            [1486, 28.0, 30.0, 30.0, 30.0, 30.0, 30.0],
            [1488, 28.0, 30.0, 30.0, 30.0, 30.0, 30.0],
            [1542, 24.0, 30.0, 30.0, 30.0, 30.0, 30.0],
            [1544, 24.0, 30.0, 30.0, 30.0, 30.0, 30.0],
            [1598, 20.0, 32.0, 32.0, 32.0, 32.0, 32.0],
            [1600, 20.0, 32.0, 32.0, 32.0, 32.0, 32.0],
            [1654, 16.0, 30.0, 34.0, 34.0, 34.0, 34.0],
            [1656, 16.0, 28.0, 34.0, 34.0, 34.0, 34.0],
            [1710, 12.0, 24.0, 34.0, 34.0, 34.0, 34.0],
            [1712, 10.0, 24.0, 34.0, 34.0, 34.0, 34.0],
            [1766, 6.0, 20.0, 34.0, 36.0, 36.0, 36.0],
            [1768, 6.0, 20.0, 34.0, 36.0, 36.0, 36.0],
            [1822, 2.0, 16.0, 30.0, 36.0, 36.0, 36.0],
            [1824, 2.0, 16.0, 30.0, 36.0, 36.0, 36.0],
            [1878, 0.0, 12.0, 24.0, 38.0, 38.0, 38.0],
            [1880, 0.0, 12.0, 24.0, 38.0, 38.0, 38.0],
            [1934, 0.0, 6.0, 20.0, 34.0, 38.0, 38.0],
            [1936, 0.0, 6.0, 20.0, 34.0, 38.0, 38.0],
            [1990, 0.0, 2.0, 16.0, 30.0, 40.0, 40.0],
            [1992, 0.0, 2.0, 16.0, 30.0, 40.0, 40.0],
            [2046, 0.0, 0.0, 12.0, 24.0, 38.0, 40.0],
            [2048, 0.0, 0.0, 12.0, 24.0, 38.0, 40.0],
            [2102, 0.0, 0.0, 6.0, 20.0, 34.0, 42.0],
            [2104, 0.0, 0.0, 6.0, 20.0, 34.0, 42.0],
            [2158, 0.0, 0.0, 2.0, 16.0, 30.0, 44.0],
            [2160, 0.0, 0.0, 2.0, 16.0, 30.0, 42.0],
            [2526, 0.0, 0.0, 0.0, 0.0, 0.0, 14.0],
            [2528, 0.0, 0.0, 0.0, 0.0, 0.0, 14.0],
            [2696, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0],
            [2698, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0],
        ];
    }

    /**
     * @dataProvider fortnightlyDataScale6
     */
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
        Assert::assertEquals($children1, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 2;
        Assert::assertEquals($children2, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 3;
        Assert::assertEquals($children3, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 4;
        Assert::assertEquals($children4, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 5;
        Assert::assertEquals($children5, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);
    }

    /**
     * @return array<int, array<int|float, int|float>>
     */
    public static function fortnightlyDataScale6(): array
    {
        return [
            [1476, 0.0, 0.0, 0.0, 0.0, 0.0],
            [1478, 0.0, 0.0, 0.0, 0.0, 0.0],
            [1660, 10.0, 10.0, 10.0, 10.0, 10.0],
            [1662, 10.0, 10.0, 10.0, 10.0, 10.0],
            [1846, 18.0, 18.0, 18.0, 18.0, 18.0],
            [1848, 6.0, 14.0, 18.0, 18.0, 18.0],
            [1880, 6.0, 12.0, 18.0, 18.0, 18.0],
            [1882, 6.0, 12.0, 18.0, 18.0, 18.0],
            [1914, 4.0, 10.0, 18.0, 20.0, 20.0],
            [1916, 4.0, 10.0, 18.0, 20.0, 20.0],
            [1948, 2.0, 10.0, 16.0, 20.0, 20.0],
            [1950, 2.0, 10.0, 16.0, 20.0, 20.0],
            [1982, 2.0, 8.0, 16.0, 20.0, 20.0],
            [1984, 2.0, 8.0, 14.0, 20.0, 20.0],
            [2016, 0.0, 6.0, 14.0, 20.0, 20.0],
            [2018, 0.0, 6.0, 14.0, 20.0, 20.0],
            [2050, 0.0, 6.0, 12.0, 20.0, 20.0],
            [2052, 0.0, 6.0, 12.0, 20.0, 20.0],
            [2084, 0.0, 4.0, 10.0, 18.0, 20.0],
            [2086, 0.0, 4.0, 10.0, 18.0, 20.0],
            [2118, 0.0, 2.0, 10.0, 16.0, 22.0],
            [2120, 0.0, 2.0, 10.0, 16.0, 22.0],
            [2152, 0.0, 2.0, 8.0, 16.0, 22.0],
            [2154, 0.0, 2.0, 8.0, 14.0, 22.0],
            [2186, 0.0, 0.0, 6.0, 14.0, 20.0],
            [2188, 0.0, 0.0, 6.0, 14.0, 20.0],
            [2220, 0.0, 0.0, 6.0, 12.0, 20.0],
            [2222, 0.0, 0.0, 6.0, 12.0, 20.0],
            [2254, 0.0, 0.0, 4.0, 10.0, 18.0],
            [2256, 0.0, 0.0, 4.0, 10.0, 18.0],
            [2288, 0.0, 0.0, 2.0, 10.0, 16.0],
            [2290, 0.0, 0.0, 2.0, 10.0, 16.0],
            [2322, 0.0, 0.0, 2.0, 8.0, 16.0],
            [2324, 0.0, 0.0, 2.0, 8.0, 14.0],
            [2356, 0.0, 0.0, 0.0, 6.0, 14.0],
            [2358, 0.0, 0.0, 0.0, 6.0, 14.0],
            [2390, 0.0, 0.0, 0.0, 6.0, 12.0],
            [2392, 0.0, 0.0, 0.0, 6.0, 12.0],
            [2424, 0.0, 0.0, 0.0, 4.0, 10.0],
            [2426, 0.0, 0.0, 0.0, 4.0, 10.0],
            [2458, 0.0, 0.0, 0.0, 2.0, 10.0],
            [2460, 0.0, 0.0, 0.0, 2.0, 10.0],
            [2492, 0.0, 0.0, 0.0, 2.0, 8.0],
            [2494, 0.0, 0.0, 0.0, 2.0, 8.0],
            [2526, 0.0, 0.0, 0.0, 0.0, 6.0],
            [2528, 0.0, 0.0, 0.0, 0.0, 6.0],
            [2696, 0.0, 0.0, 0.0, 0.0, 0.0],
            [2698, 0.0, 0.0, 0.0, 0.0, 0.0],
        ];
    }

    /**
     * @dataProvider monthlyData
     */
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

        Assert::assertEquals(
            $spouseOnly,
            $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1,
        );

        $this->adjustment->children = 1;
        Assert::assertEquals($children1, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 2;
        Assert::assertEquals($children2, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 3;
        Assert::assertEquals($children3, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 4;
        Assert::assertEquals($children4, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

        $this->adjustment->children = 5;
        Assert::assertEquals($children5, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);
    }

    /**
     * @return array<int, array<int|float, int|float>>
     */
    public static function monthlyData(): array
    {
        return [
            [1893.67, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0],
            [1898.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0],
            [2132.0, 22.0, 22.0, 22.0, 22.0, 22.0, 22.0],
            [2136.33, 26.0, 26.0, 26.0, 26.0, 26.0, 26.0],
            [2370.33, 48.0, 48.0, 48.0, 48.0, 48.0, 48.0],
            [2374.67, 48.0, 48.0, 48.0, 48.0, 48.0, 48.0],
            [2491.67, 52.0, 52.0, 52.0, 52.0, 52.0, 52.0],
            [2496.0, 52.0, 52.0, 52.0, 52.0, 52.0, 52.0],
            [2613.0, 52.0, 52.0, 52.0, 52.0, 52.0, 52.0],
            [2617.33, 52.0, 52.0, 52.0, 52.0, 52.0, 52.0],
            [2734.33, 56.0, 56.0, 56.0, 56.0, 56.0, 56.0],
            [2738.67, 56.0, 56.0, 56.0, 56.0, 56.0, 56.0],
            [2855.67, 56.0, 56.0, 56.0, 56.0, 56.0, 56.0],
            [2860.0, 56.0, 56.0, 56.0, 56.0, 56.0, 56.0],
            [2977.0, 61.0, 61.0, 61.0, 61.0, 61.0, 61.0],
            [2981.33, 61.0, 61.0, 61.0, 61.0, 61.0, 61.0],
            [3098.33, 61.0, 61.0, 61.0, 61.0, 61.0, 61.0],
            [3102.67, 61.0, 61.0, 61.0, 61.0, 61.0, 61.0],
            [3219.67, 61.0, 65.0, 65.0, 65.0, 65.0, 65.0],
            [3224.0, 61.0, 65.0, 65.0, 65.0, 65.0, 65.0],
            [3341.0, 52.0, 65.0, 65.0, 65.0, 65.0, 65.0],
            [3345.33, 52.0, 65.0, 65.0, 65.0, 65.0, 65.0],
            [3462.33, 43.0, 69.0, 69.0, 69.0, 69.0, 69.0],
            [3466.67, 43.0, 69.0, 69.0, 69.0, 69.0, 69.0],
            [3583.67, 35.0, 65.0, 74.0, 74.0, 74.0, 74.0],
            [3588.0, 35.0, 61.0, 74.0, 74.0, 74.0, 74.0],
            [3705.0, 26.0, 52.0, 74.0, 74.0, 74.0, 74.0],
            [3709.33, 22.0, 52.0, 74.0, 74.0, 74.0, 74.0],
            [3826.33, 13.0, 43.0, 74.0, 78.0, 78.0, 78.0],
            [3830.67, 13.0, 43.0, 74.0, 78.0, 78.0, 78.0],
            [3947.67, 4.0, 35.0, 65.0, 78.0, 78.0, 78.0],
            [3952.0, 4.0, 35.0, 65.0, 78.0, 78.0, 78.0],
            [4069.0, 0.0, 26.0, 52.0, 82.0, 82.0, 82.0],
            [4073.33, 0.0, 26.0, 52.0, 82.0, 82.0, 82.0],
            [4190.33, 0.0, 13.0, 43.0, 74.0, 82.0, 82.0],
            [4194.67, 0.0, 13.0, 43.0, 74.0, 82.0, 82.0],
            [4311.67, 0.0, 4.0, 35.0, 65.0, 87.0, 87.0],
            [4316.0, 0.0, 4.0, 35.0, 65.0, 87.0, 87.0],
            [4433.0, 0.0, 0.0, 26.0, 52.0, 82.0, 87.0],
            [4437.33, 0.0, 0.0, 26.0, 52.0, 82.0, 87.0],
            [4554.33, 0.0, 0.0, 13.0, 43.0, 74.0, 91.0],
            [4558.67, 0.0, 0.0, 13.0, 43.0, 74.0, 91.0],
            [4675.67, 0.0, 0.0, 4.0, 35.0, 65.0, 95.0],
            [4680.0, 0.0, 0.0, 4.0, 35.0, 65.0, 91.0],
            [5473.0, 0.0, 0.0, 0.0, 0.0, 0.0, 30.0],
            [5477.33, 0.0, 0.0, 0.0, 0.0, 0.0, 30.0],
            [5841.33, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0],
            [5845.67, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0],
        ];
    }
}
