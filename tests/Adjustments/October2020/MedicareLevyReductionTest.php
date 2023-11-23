<?php

namespace ManageIt\PaygTax\Tests\Adjustments\October2020;

use ManageIt\PaygTax\Adjustments\October2020\MedicareLevyReduction;
use ManageIt\PaygTax\TaxScales\October2020\Nat1004Scale2;
use ManageIt\PaygTax\TaxScales\October2020\Nat1004Scale4;
use ManageIt\PaygTax\TaxScales\October2020\Nat1004Scale6;
use ManageIt\PaygTax\Tests\Fixtures\Earning;
use ManageIt\PaygTax\Tests\Fixtures\Payee;
use ManageIt\PaygTax\Tests\Fixtures\Payer;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;

/**
 * @covers \ManageIt\PaygTax\Adjustments\October2020\MedicareLevyReduction
 */
class MedicareLevyReductionTest extends TestCase
{
    protected MedicareLevyReduction $adjustment;

    public function setUp(): void
    {
        $this->adjustment = new MedicareLevyReduction();
    }

    public function testEligibility(): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = true;

        $scale = new Nat1004Scale2();

        $earning = new Earning();
        $earning->date = new \DateTime('2022-10-10');
        $earning->gross = 1000;

        Assert::assertTrue($this->adjustment->isEligible($payer, $payee, $scale, $earning));

        $scale = new Nat1004Scale6();
        Assert::assertFalse($this->adjustment->isEligible($payer, $payee, $scale, $earning));

        $this->adjustment->children = 1;
        Assert::assertTrue($this->adjustment->isEligible($payer, $payee, $scale, $earning));

        $scale = new Nat1004Scale4();
        Assert::assertFalse($this->adjustment->isEligible($payer, $payee, $scale, $earning));

        $scale = new Nat1004Scale2();
        $this->adjustment->spouse = false;
        $this->adjustment->children = 0;
        Assert::assertFalse($this->adjustment->isEligible($payer, $payee, $scale, $earning));

        $this->adjustment->children = 1;
        Assert::assertTrue($this->adjustment->isEligible($payer, $payee, $scale, $earning));

        $scale = new Nat1004Scale6();
        $this->adjustment->spouse = true;
        $this->adjustment->children = 0;
        Assert::assertFalse($this->adjustment->isEligible($payer, $payee, $scale, $earning));
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
        float $children5
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

        $scale = new Nat1004Scale2();

        $this->adjustment->spouse = true;
        $this->adjustment->children = 0;

        Assert::assertEquals($spouseOnly, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

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
    public function weeklyData(): array
    {
        return [
            [437, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00],
            [438, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00],
            [492, 5.00, 5.00, 5.00, 5.00, 5.00, 5.00],
            [493, 6.00, 6.00, 6.00, 6.00, 6.00, 6.00],
            [547, 11.00, 11.00, 11.00, 11.00, 11.00, 11.00],
            [548, 11.00, 11.00, 11.00, 11.00, 11.00, 11.00],
            [575, 12.00, 12.00, 12.00, 12.00, 12.00, 12.00],
            [576, 12.00, 12.00, 12.00, 12.00, 12.00, 12.00],
            [603, 12.00, 12.00, 12.00, 12.00, 12.00, 12.00],
            [604, 12.00, 12.00, 12.00, 12.00, 12.00, 12.00],
            [631, 13.00, 13.00, 13.00, 13.00, 13.00, 13.00],
            [632, 13.00, 13.00, 13.00, 13.00, 13.00, 13.00],
            [659, 13.00, 13.00, 13.00, 13.00, 13.00, 13.00],
            [660, 13.00, 13.00, 13.00, 13.00, 13.00, 13.00],
            [687, 14.00, 14.00, 14.00, 14.00, 14.00, 14.00],
            [688, 14.00, 14.00, 14.00, 14.00, 14.00, 14.00],
            [715, 14.00, 14.00, 14.00, 14.00, 14.00, 14.00],
            [716, 14.00, 14.00, 14.00, 14.00, 14.00, 14.00],
            [743, 14.00, 15.00, 15.00, 15.00, 15.00, 15.00],
            [744, 14.00, 15.00, 15.00, 15.00, 15.00, 15.00],
            [771, 12.00, 15.00, 15.00, 15.00, 15.00, 15.00],
            [772, 12.00, 15.00, 15.00, 15.00, 15.00, 15.00],
            [799, 10.00, 16.00, 16.00, 16.00, 16.00, 16.00],
            [800, 10.00, 16.00, 16.00, 16.00, 16.00, 16.00],
            [827, 8.00, 15.00, 17.00, 17.00, 17.00, 17.00],
            [828, 8.00, 14.00, 17.00, 17.00, 17.00, 17.00],
            [855, 6.00, 12.00, 17.00, 17.00, 17.00, 17.00],
            [856, 5.00, 12.00, 17.00, 17.00, 17.00, 17.00],
            [883, 3.00, 10.00, 17.00, 18.00, 18.00, 18.00],
            [884, 3.00, 10.00, 17.00, 18.00, 18.00, 18.00],
            [911, 1.00, 8.00, 15.00, 18.00, 18.00, 18.00],
            [912, 1.00, 8.00, 15.00, 18.00, 18.00, 18.00],
            [939, 0.00, 6.00, 12.00, 19.00, 19.00, 19.00],
            [940, 0.00, 6.00, 12.00, 19.00, 19.00, 19.00],
            [967, 0.00, 3.00, 10.00, 17.00, 19.00, 19.00],
            [968, 0.00, 3.00, 10.00, 17.00, 19.00, 19.00],
            [995, 0.00, 1.00, 8.00, 15.00, 20.00, 20.00],
            [996, 0.00, 1.00, 8.00, 15.00, 20.00, 20.00],
            [1023, 0.00, 0.00, 6.00, 12.00, 19.00, 20.00],
            [1024, 0.00, 0.00, 6.00, 12.00, 19.00, 20.00],
            [1051, 0.00, 0.00, 3.00, 10.00, 17.00, 21.00],
            [1052, 0.00, 0.00, 3.00, 10.00, 17.00, 21.00],
            [1079, 0.00, 0.00, 1.00, 8.00, 15.00, 22.00],
            [1080, 0.00, 0.00, 1.00, 8.00, 15.00, 21.00],
            [1263, 0.00, 0.00, 0.00, 0.00, 0.00, 7.00],
            [1264, 0.00, 0.00, 0.00, 0.00, 0.00, 7.00],
            [1348, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00],
            [1349, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00],
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
        float $children5
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

        $scale = new Nat1004Scale6();

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
    public function weeklyDataScale6(): array
    {
        return [
            [738, 0.00, 0.00, 0.00, 0.00, 0.00],
            [739, 0.00, 0.00, 0.00, 0.00, 0.00],
            [830, 5.00, 5.00, 5.00, 5.00, 5.00],
            [831, 5.00, 5.00, 5.00, 5.00, 5.00],
            [923, 9.00, 9.00, 9.00, 9.00, 9.00],
            [924, 3.00, 7.00, 9.00, 9.00, 9.00],
            [940, 3.00, 6.00, 9.00, 9.00, 9.00],
            [941, 3.00, 6.00, 9.00, 9.00, 9.00],
            [957, 2.00, 5.00, 9.00, 10.00, 10.00],
            [958, 2.00, 5.00, 9.00, 10.00, 10.00],
            [974, 1.00, 5.00, 8.00, 10.00, 10.00],
            [975, 1.00, 5.00, 8.00, 10.00, 10.00],
            [991, 1.00, 4.00, 8.00, 10.00, 10.00],
            [992, 1.00, 4.00, 7.00, 10.00, 10.00],
            [1008, 0.00, 3.00, 7.00, 10.00, 10.00],
            [1009, 0.00, 3.00, 7.00, 10.00, 10.00],
            [1025, 0.00, 3.00, 6.00, 10.00, 10.00],
            [1026, 0.00, 3.00, 6.00, 10.00, 10.00],
            [1042, 0.00, 2.00, 5.00, 9.00, 10.00],
            [1043, 0.00, 2.00, 5.00, 9.00, 10.00],
            [1059, 0.00, 1.00, 5.00, 8.00, 11.00],
            [1060, 0.00, 1.00, 5.00, 8.00, 11.00],
            [1076, 0.00, 1.00, 4.00, 8.00, 11.00],
            [1077, 0.00, 1.00, 4.00, 7.00, 11.00],
            [1093, 0.00, 0.00, 3.00, 7.00, 10.00],
            [1094, 0.00, 0.00, 3.00, 7.00, 10.00],
            [1110, 0.00, 0.00, 3.00, 6.00, 10.00],
            [1111, 0.00, 0.00, 3.00, 6.00, 10.00],
            [1127, 0.00, 0.00, 2.00, 5.00, 9.00],
            [1128, 0.00, 0.00, 2.00, 5.00, 9.00],
            [1144, 0.00, 0.00, 1.00, 5.00, 8.00],
            [1145, 0.00, 0.00, 1.00, 5.00, 8.00],
            [1161, 0.00, 0.00, 1.00, 4.00, 8.00],
            [1162, 0.00, 0.00, 1.00, 4.00, 7.00],
            [1178, 0.00, 0.00, 0.00, 3.00, 7.00],
            [1179, 0.00, 0.00, 0.00, 3.00, 7.00],
            [1195, 0.00, 0.00, 0.00, 3.00, 6.00],
            [1196, 0.00, 0.00, 0.00, 3.00, 6.00],
            [1212, 0.00, 0.00, 0.00, 2.00, 5.00],
            [1213, 0.00, 0.00, 0.00, 2.00, 5.00],
            [1229, 0.00, 0.00, 0.00, 1.00, 5.00],
            [1230, 0.00, 0.00, 0.00, 1.00, 5.00],
            [1246, 0.00, 0.00, 0.00, 1.00, 4.00],
            [1247, 0.00, 0.00, 0.00, 1.00, 4.00],
            [1263, 0.00, 0.00, 0.00, 0.00, 3.00],
            [1264, 0.00, 0.00, 0.00, 0.00, 3.00],
            [1348, 0.00, 0.00, 0.00, 0.00, 0.00],
            [1349, 0.00, 0.00, 0.00, 0.00, 0.00],
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
        float $children5
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

        $scale = new Nat1004Scale2();

        $this->adjustment->spouse = true;
        $this->adjustment->children = 0;

        Assert::assertEquals($spouseOnly, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

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
    public function fortnightlyData(): array
    {
        return [
            [874, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00],
            [876, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00],
            [984, 10.00, 10.00, 10.00, 10.00, 10.00, 10.00],
            [986, 12.00, 12.00, 12.00, 12.00, 12.00, 12.00],
            [1094, 22.00, 22.00, 22.00, 22.00, 22.00, 22.00],
            [1096, 22.00, 22.00, 22.00, 22.00, 22.00, 22.00],
            [1150, 24.00, 24.00, 24.00, 24.00, 24.00, 24.00],
            [1152, 24.00, 24.00, 24.00, 24.00, 24.00, 24.00],
            [1206, 24.00, 24.00, 24.00, 24.00, 24.00, 24.00],
            [1208, 24.00, 24.00, 24.00, 24.00, 24.00, 24.00],
            [1262, 26.00, 26.00, 26.00, 26.00, 26.00, 26.00],
            [1264, 26.00, 26.00, 26.00, 26.00, 26.00, 26.00],
            [1318, 26.00, 26.00, 26.00, 26.00, 26.00, 26.00],
            [1320, 26.00, 26.00, 26.00, 26.00, 26.00, 26.00],
            [1374, 28.00, 28.00, 28.00, 28.00, 28.00, 28.00],
            [1376, 28.00, 28.00, 28.00, 28.00, 28.00, 28.00],
            [1430, 28.00, 28.00, 28.00, 28.00, 28.00, 28.00],
            [1432, 28.00, 28.00, 28.00, 28.00, 28.00, 28.00],
            [1486, 28.00, 30.00, 30.00, 30.00, 30.00, 30.00],
            [1488, 28.00, 30.00, 30.00, 30.00, 30.00, 30.00],
            [1542, 24.00, 30.00, 30.00, 30.00, 30.00, 30.00],
            [1544, 24.00, 30.00, 30.00, 30.00, 30.00, 30.00],
            [1598, 20.00, 32.00, 32.00, 32.00, 32.00, 32.00],
            [1600, 20.00, 32.00, 32.00, 32.00, 32.00, 32.00],
            [1654, 16.00, 30.00, 34.00, 34.00, 34.00, 34.00],
            [1656, 16.00, 28.00, 34.00, 34.00, 34.00, 34.00],
            [1710, 12.00, 24.00, 34.00, 34.00, 34.00, 34.00],
            [1712, 10.00, 24.00, 34.00, 34.00, 34.00, 34.00],
            [1766, 6.00, 20.00, 34.00, 36.00, 36.00, 36.00],
            [1768, 6.00, 20.00, 34.00, 36.00, 36.00, 36.00],
            [1822, 2.00, 16.00, 30.00, 36.00, 36.00, 36.00],
            [1824, 2.00, 16.00, 30.00, 36.00, 36.00, 36.00],
            [1878, 0.00, 12.00, 24.00, 38.00, 38.00, 38.00],
            [1880, 0.00, 12.00, 24.00, 38.00, 38.00, 38.00],
            [1934, 0.00, 6.00, 20.00, 34.00, 38.00, 38.00],
            [1936, 0.00, 6.00, 20.00, 34.00, 38.00, 38.00],
            [1990, 0.00, 2.00, 16.00, 30.00, 40.00, 40.00],
            [1992, 0.00, 2.00, 16.00, 30.00, 40.00, 40.00],
            [2046, 0.00, 0.00, 12.00, 24.00, 38.00, 40.00],
            [2048, 0.00, 0.00, 12.00, 24.00, 38.00, 40.00],
            [2102, 0.00, 0.00, 6.00, 20.00, 34.00, 42.00],
            [2104, 0.00, 0.00, 6.00, 20.00, 34.00, 42.00],
            [2158, 0.00, 0.00, 2.00, 16.00, 30.00, 44.00],
            [2160, 0.00, 0.00, 2.00, 16.00, 30.00, 42.00],
            [2526, 0.00, 0.00, 0.00, 0.00, 0.00, 14.00],
            [2528, 0.00, 0.00, 0.00, 0.00, 0.00, 14.00],
            [2696, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00],
            [2698, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00],
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
        float $children5
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

        $scale = new Nat1004Scale6();

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
    public function fortnightlyDataScale6(): array
    {
        return [
            [1476, 0.00, 0.00, 0.00, 0.00, 0.00],
            [1478, 0.00, 0.00, 0.00, 0.00, 0.00],
            [1660, 10.00, 10.00, 10.00, 10.00, 10.00],
            [1662, 10.00, 10.00, 10.00, 10.00, 10.00],
            [1846, 18.00, 18.00, 18.00, 18.00, 18.00],
            [1848, 6.00, 14.00, 18.00, 18.00, 18.00],
            [1880, 6.00, 12.00, 18.00, 18.00, 18.00],
            [1882, 6.00, 12.00, 18.00, 18.00, 18.00],
            [1914, 4.00, 10.00, 18.00, 20.00, 20.00],
            [1916, 4.00, 10.00, 18.00, 20.00, 20.00],
            [1948, 2.00, 10.00, 16.00, 20.00, 20.00],
            [1950, 2.00, 10.00, 16.00, 20.00, 20.00],
            [1982, 2.00, 8.00, 16.00, 20.00, 20.00],
            [1984, 2.00, 8.00, 14.00, 20.00, 20.00],
            [2016, 0.00, 6.00, 14.00, 20.00, 20.00],
            [2018, 0.00, 6.00, 14.00, 20.00, 20.00],
            [2050, 0.00, 6.00, 12.00, 20.00, 20.00],
            [2052, 0.00, 6.00, 12.00, 20.00, 20.00],
            [2084, 0.00, 4.00, 10.00, 18.00, 20.00],
            [2086, 0.00, 4.00, 10.00, 18.00, 20.00],
            [2118, 0.00, 2.00, 10.00, 16.00, 22.00],
            [2120, 0.00, 2.00, 10.00, 16.00, 22.00],
            [2152, 0.00, 2.00, 8.00, 16.00, 22.00],
            [2154, 0.00, 2.00, 8.00, 14.00, 22.00],
            [2186, 0.00, 0.00, 6.00, 14.00, 20.00],
            [2188, 0.00, 0.00, 6.00, 14.00, 20.00],
            [2220, 0.00, 0.00, 6.00, 12.00, 20.00],
            [2222, 0.00, 0.00, 6.00, 12.00, 20.00],
            [2254, 0.00, 0.00, 4.00, 10.00, 18.00],
            [2256, 0.00, 0.00, 4.00, 10.00, 18.00],
            [2288, 0.00, 0.00, 2.00, 10.00, 16.00],
            [2290, 0.00, 0.00, 2.00, 10.00, 16.00],
            [2322, 0.00, 0.00, 2.00, 8.00, 16.00],
            [2324, 0.00, 0.00, 2.00, 8.00, 14.00],
            [2356, 0.00, 0.00, 0.00, 6.00, 14.00],
            [2358, 0.00, 0.00, 0.00, 6.00, 14.00],
            [2390, 0.00, 0.00, 0.00, 6.00, 12.00],
            [2392, 0.00, 0.00, 0.00, 6.00, 12.00],
            [2424, 0.00, 0.00, 0.00, 4.00, 10.00],
            [2426, 0.00, 0.00, 0.00, 4.00, 10.00],
            [2458, 0.00, 0.00, 0.00, 2.00, 10.00],
            [2460, 0.00, 0.00, 0.00, 2.00, 10.00],
            [2492, 0.00, 0.00, 0.00, 2.00, 8.00],
            [2494, 0.00, 0.00, 0.00, 2.00, 8.00],
            [2526, 0.00, 0.00, 0.00, 0.00, 6.00],
            [2528, 0.00, 0.00, 0.00, 0.00, 6.00],
            [2696, 0.00, 0.00, 0.00, 0.00, 0.00],
            [2698, 0.00, 0.00, 0.00, 0.00, 0.00],
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
        float $children5
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

        $scale = new Nat1004Scale2();

        $this->adjustment->spouse = true;
        $this->adjustment->children = 0;

        Assert::assertEquals($spouseOnly, $this->adjustment->getAdjustmentAmount($payer, $payee, $scale, $earning) * -1);

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
    public function monthlyData(): array
    {
        return [
            [1893.67, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00],
            [1898.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00],
            [2132.00, 22.00, 22.00, 22.00, 22.00, 22.00, 22.00],
            [2136.33, 26.00, 26.00, 26.00, 26.00, 26.00, 26.00],
            [2370.33, 48.00, 48.00, 48.00, 48.00, 48.00, 48.00],
            [2374.67, 48.00, 48.00, 48.00, 48.00, 48.00, 48.00],
            [2491.67, 52.00, 52.00, 52.00, 52.00, 52.00, 52.00],
            [2496.00, 52.00, 52.00, 52.00, 52.00, 52.00, 52.00],
            [2613.00, 52.00, 52.00, 52.00, 52.00, 52.00, 52.00],
            [2617.33, 52.00, 52.00, 52.00, 52.00, 52.00, 52.00],
            [2734.33, 56.00, 56.00, 56.00, 56.00, 56.00, 56.00],
            [2738.67, 56.00, 56.00, 56.00, 56.00, 56.00, 56.00],
            [2855.67, 56.00, 56.00, 56.00, 56.00, 56.00, 56.00],
            [2860.00, 56.00, 56.00, 56.00, 56.00, 56.00, 56.00],
            [2977.00, 61.00, 61.00, 61.00, 61.00, 61.00, 61.00],
            [2981.33, 61.00, 61.00, 61.00, 61.00, 61.00, 61.00],
            [3098.33, 61.00, 61.00, 61.00, 61.00, 61.00, 61.00],
            [3102.67, 61.00, 61.00, 61.00, 61.00, 61.00, 61.00],
            [3219.67, 61.00, 65.00, 65.00, 65.00, 65.00, 65.00],
            [3224.00, 61.00, 65.00, 65.00, 65.00, 65.00, 65.00],
            [3341.00, 52.00, 65.00, 65.00, 65.00, 65.00, 65.00],
            [3345.33, 52.00, 65.00, 65.00, 65.00, 65.00, 65.00],
            [3462.33, 43.00, 69.00, 69.00, 69.00, 69.00, 69.00],
            [3466.67, 43.00, 69.00, 69.00, 69.00, 69.00, 69.00],
            [3583.67, 35.00, 65.00, 74.00, 74.00, 74.00, 74.00],
            [3588.00, 35.00, 61.00, 74.00, 74.00, 74.00, 74.00],
            [3705.00, 26.00, 52.00, 74.00, 74.00, 74.00, 74.00],
            [3709.33, 22.00, 52.00, 74.00, 74.00, 74.00, 74.00],
            [3826.33, 13.00, 43.00, 74.00, 78.00, 78.00, 78.00],
            [3830.67, 13.00, 43.00, 74.00, 78.00, 78.00, 78.00],
            [3947.67, 4.00, 35.00, 65.00, 78.00, 78.00, 78.00],
            [3952.00, 4.00, 35.00, 65.00, 78.00, 78.00, 78.00],
            [4069.00, 0.00, 26.00, 52.00, 82.00, 82.00, 82.00],
            [4073.33, 0.00, 26.00, 52.00, 82.00, 82.00, 82.00],
            [4190.33, 0.00, 13.00, 43.00, 74.00, 82.00, 82.00],
            [4194.67, 0.00, 13.00, 43.00, 74.00, 82.00, 82.00],
            [4311.67, 0.00, 4.00, 35.00, 65.00, 87.00, 87.00],
            [4316.00, 0.00, 4.00, 35.00, 65.00, 87.00, 87.00],
            [4433.00, 0.00, 0.00, 26.00, 52.00, 82.00, 87.00],
            [4437.33, 0.00, 0.00, 26.00, 52.00, 82.00, 87.00],
            [4554.33, 0.00, 0.00, 13.00, 43.00, 74.00, 91.00],
            [4558.67, 0.00, 0.00, 13.00, 43.00, 74.00, 91.00],
            [4675.67, 0.00, 0.00, 4.00, 35.00, 65.00, 95.00],
            [4680.00, 0.00, 0.00, 4.00, 35.00, 65.00, 91.00],
            [5473.00, 0.00, 0.00, 0.00, 0.00, 0.00, 30.00],
            [5477.33, 0.00, 0.00, 0.00, 0.00, 0.00, 30.00],
            [5841.33, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00],
            [5845.67, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00],
        ];
    }
}
