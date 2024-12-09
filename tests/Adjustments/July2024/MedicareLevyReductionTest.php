<?php

namespace ManageIt\PaygTax\Tests\Adjustments\July2024;

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

    public function testEligibility(): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = true;

        $scale = new Nat1004();

        $earning = new Earning();
        $earning->date = new \DateTime('2024-10-10');
        $earning->gross = 1000;

        $this->adjustment->spouse = true;
        Assert::assertTrue($this->adjustment->isEligible($payer, $payee, $scale, $earning));

        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_FULL;
        Assert::assertFalse($this->adjustment->isEligible($payer, $payee, $scale, $earning));

        $this->adjustment->children = 1;
        Assert::assertFalse($this->adjustment->isEligible($payer, $payee, $scale, $earning));

        $this->adjustment->children = 0;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_HALF;
        Assert::assertFalse($this->adjustment->isEligible($payer, $payee, $scale, $earning));

        $this->adjustment->children = 1;
        Assert::assertTrue($this->adjustment->isEligible($payer, $payee, $scale, $earning));

        $payee->residencyStatus = Payee::FOREIGN_RESIDENT;
        Assert::assertFalse($this->adjustment->isEligible($payer, $payee, $scale, $earning));

        $payee->residencyStatus = Payee::RESIDENT;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_NONE;
        $this->adjustment->children = 0;
        $this->adjustment->spouse = false;
        Assert::assertFalse($this->adjustment->isEligible($payer, $payee, $scale, $earning));

        $this->adjustment->spouse = true;
        $payee->tfn = false;
        Assert::assertFalse($this->adjustment->isEligible($payer, $payee, $scale, $earning));
    }

    /**
     * @dataProvider weeklyData
     */
    public function testWeeklyAdjustmentScale2(
        int $gross,
        int $spouseOnly,
        int $children1,
        int $children2,
        int $children3,
        int $children4,
        int $children5
    ): void {
        $payer = new Payer();

        $payee = new Payee();
        $payee->payCycle = Payee::PAY_CYCLE_WEEKLY;
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;

        $earning = new Earning();
        $earning->date = new \DateTime('2024-10-10');
        $earning->gross = $gross;

        $scale = new Nat1004();

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
            [499, 0, 0, 0, 0, 0, 0],
            [500, 0, 0, 0, 0, 0, 0],
            [561, 6, 6, 6, 6, 6, 6],
            [562, 6, 6, 6, 6, 6, 6],
            [624, 12, 12, 12, 12, 12, 12],
            [625, 13, 13, 13, 13, 13, 13],
            [656, 13, 13, 13, 13, 13, 13],
            [657, 13, 13, 13, 13, 13, 13],
            [688, 14, 14, 14, 14, 14, 14],
            [689, 14, 14, 14, 14, 14, 14],
            [720, 14, 14, 14, 14, 14, 14],
            [721, 14, 14, 14, 14, 14, 14],
            [752, 15, 15, 15, 15, 15, 15],
            [753, 15, 15, 15, 15, 15, 15],
            [784, 16, 16, 16, 16, 16, 16],
            [785, 16, 16, 16, 16, 16, 16],
            [816, 16, 16, 16, 16, 16, 16],
            [817, 16, 16, 16, 16, 16, 16],
            [848, 16, 17, 17, 17, 17, 17],
            [849, 16, 17, 17, 17, 17, 17],
            [880, 14, 18, 18, 18, 18, 18],
            [881, 14, 18, 18, 18, 18, 18],
            [912, 11, 18, 18, 18, 18, 18],
            [913, 11, 18, 18, 18, 18, 18],
            [944, 9, 16, 19, 19, 19, 19],
            [945, 9, 16, 19, 19, 19, 19],
            [976, 6, 14, 20, 20, 20, 20],
            [977, 6, 14, 20, 20, 20, 20],
            [1008, 4, 11, 19, 20, 20, 20],
            [1009, 4, 11, 19, 20, 20, 20],
            [1040, 1, 9, 17, 21, 21, 21],
            [1041, 1, 9, 16, 21, 21, 21],
            [1072, 0, 6, 14, 21, 21, 21],
            [1073, 0, 6, 14, 21, 21, 21],
            [1104, 0, 4, 11, 19, 22, 22],
            [1105, 0, 4, 11, 19, 22, 22],
            [1136, 0, 1, 9, 17, 23, 23],
            [1137, 0, 1, 9, 17, 23, 23],
            [1168, 0, 0, 6, 14, 22, 23],
            [1169, 0, 0, 6, 14, 22, 23],
            [1200, 0, 0, 4, 11, 19, 24],
            [1201, 0, 0, 4, 11, 19, 24],
            [1232, 0, 0, 1, 9, 17, 24],
            [1233, 0, 0, 1, 9, 17, 24],
            [1440, 0, 0, 0, 0, 0, 8],
            [1441, 0, 0, 0, 0, 0, 8],
            [1537, 0, 0, 0, 0, 0, 0],
            [1538, 0, 0, 0, 0, 0, 0],
        ];
    }

    /**
     * @dataProvider weeklyDataScale6
     */
    public function testWeeklyAdjustmentScale6(
        int $gross,
        int $children1,
        int $children2,
        int $children3,
        int $children4,
        int $children5
    ): void {
        $payer = new Payer();

        $payee = new Payee();
        $payee->payCycle = Payee::PAY_CYCLE_WEEKLY;
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_HALF;

        $earning = new Earning();
        $earning->date = new \DateTime('2024-10-10');
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
    public function weeklyDataScale6(): array
    {
        return [
            [842, 0, 0, 0, 0, 0],
            [843, 0, 0, 0, 0, 0],
            [947, 5, 5, 5, 5, 5],
            [948, 5, 5, 5, 5, 5],
            [1052, 10, 10, 10, 10, 10],
            [1053, 4, 8, 11, 11, 11],
            [1071, 3, 7, 11, 11, 11],
            [1072, 3, 7, 11, 11, 11],
            [1090, 2, 6, 10, 11, 11],
            [1091, 2, 6, 10, 11, 11],
            [1109, 2, 6, 9, 11, 11],
            [1110, 2, 5, 9, 11, 11],
            [1128, 1, 5, 9, 11, 11],
            [1129, 1, 5, 9, 11, 11],
            [1147, 0, 4, 8, 11, 11],
            [1148, 0, 4, 8, 11, 11],
            [1166, 0, 3, 7, 11, 12],
            [1167, 0, 3, 7, 11, 12],
            [1185, 0, 2, 6, 10, 12],
            [1186, 0, 2, 6, 10, 12],
            [1204, 0, 2, 6, 9, 12],
            [1205, 0, 2, 6, 9, 12],
            [1223, 0, 1, 5, 9, 12],
            [1224, 0, 1, 5, 9, 12],
            [1242, 0, 0, 4, 8, 12],
            [1243, 0, 0, 4, 8, 12],
            [1261, 0, 0, 3, 7, 11],
            [1262, 0, 0, 3, 7, 11],
            [1280, 0, 0, 3, 6, 10],
            [1281, 0, 0, 2, 6, 10],
            [1299, 0, 0, 2, 6, 10],
            [1300, 0, 0, 2, 6, 9],
            [1318, 0, 0, 1, 5, 9],
            [1319, 0, 0, 1, 5, 9],
            [1337, 0, 0, 0, 4, 8],
            [1338, 0, 0, 0, 4, 8],
            [1356, 0, 0, 0, 3, 7],
            [1357, 0, 0, 0, 3, 7],
            [1375, 0, 0, 0, 3, 6],
            [1376, 0, 0, 0, 3, 6],
            [1394, 0, 0, 0, 2, 6],
            [1395, 0, 0, 0, 2, 6],
            [1413, 0, 0, 0, 1, 5],
            [1414, 0, 0, 0, 1, 5],
            [1440, 0, 0, 0, 0, 4],
            [1441, 0, 0, 0, 0, 4],
            [1537, 0, 0, 0, 0, 0],
            [1538, 0, 0, 0, 0, 0],
        ];
    }

    /**
     * @dataProvider fortnightlyData
     */
    public function testFortnightlyAdjustmentScale2(
        int $gross,
        int $spouseOnly,
        int $children1,
        int $children2,
        int $children3,
        int $children4,
        int $children5
    ): void {
        $payer = new Payer();

        $payee = new Payee();
        $payee->payCycle = Payee::PAY_CYCLE_FORTNIGHTLY;
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;

        $earning = new Earning();
        $earning->date = new \DateTime('2024-10-10');
        $earning->gross = $gross;

        $scale = new Nat1004();

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
            [998, 0, 0, 0, 0, 0, 0],
            [1000, 0, 0, 0, 0, 0, 0],
            [1122, 12, 12, 12, 12, 12, 12],
            [1124, 12, 12, 12, 12, 12, 12],
            [1248, 24, 24, 24, 24, 24, 24],
            [1250, 26, 26, 26, 26, 26, 26],
            [1312, 26, 26, 26, 26, 26, 26],
            [1314, 26, 26, 26, 26, 26, 26],
            [1376, 28, 28, 28, 28, 28, 28],
            [1378, 28, 28, 28, 28, 28, 28],
            [1440, 28, 28, 28, 28, 28, 28],
            [1442, 28, 28, 28, 28, 28, 28],
            [1504, 30, 30, 30, 30, 30, 30],
            [1506, 30, 30, 30, 30, 30, 30],
            [1568, 32, 32, 32, 32, 32, 32],
            [1570, 32, 32, 32, 32, 32, 32],
            [1632, 32, 32, 32, 32, 32, 32],
            [1634, 32, 32, 32, 32, 32, 32],
            [1696, 32, 34, 34, 34, 34, 34],
            [1698, 32, 34, 34, 34, 34, 34],
            [1760, 28, 36, 36, 36, 36, 36],
            [1762, 28, 36, 36, 36, 36, 36],
            [1824, 22, 36, 36, 36, 36, 36],
            [1826, 22, 36, 36, 36, 36, 36],
            [1888, 18, 32, 38, 38, 38, 38],
            [1890, 18, 32, 38, 38, 38, 38],
            [1952, 12, 28, 40, 40, 40, 40],
            [1954, 12, 28, 40, 40, 40, 40],
            [2016, 8, 22, 38, 40, 40, 40],
            [2018, 8, 22, 38, 40, 40, 40],
            [2080, 2, 18, 34, 42, 42, 42],
            [2082, 2, 18, 32, 42, 42, 42],
            [2144, 0, 12, 28, 42, 42, 42],
            [2146, 0, 12, 28, 42, 42, 42],
            [2208, 0, 8, 22, 38, 44, 44],
            [2210, 0, 8, 22, 38, 44, 44],
            [2272, 0, 2, 18, 34, 46, 46],
            [2274, 0, 2, 18, 34, 46, 46],
            [2336, 0, 0, 12, 28, 44, 46],
            [2338, 0, 0, 12, 28, 44, 46],
            [2400, 0, 0, 8, 22, 38, 48],
            [2402, 0, 0, 8, 22, 38, 48],
            [2464, 0, 0, 2, 18, 34, 48],
            [2466, 0, 0, 2, 18, 34, 48],
            [2880, 0, 0, 0, 0, 0, 16],
            [2882, 0, 0, 0, 0, 0, 16],
            [3074, 0, 0, 0, 0, 0, 0],
            [3076, 0, 0, 0, 0, 0, 0],
        ];
    }

    /**
     * @dataProvider fortnightlyDataScale6
     */
    public function testFortnightlyAdjustmentScale6(
        int $gross,
        int $children1,
        int $children2,
        int $children3,
        int $children4,
        int $children5
    ): void {
        $payer = new Payer();

        $payee = new Payee();
        $payee->payCycle = Payee::PAY_CYCLE_FORTNIGHTLY;
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_HALF;

        $earning = new Earning();
        $earning->date = new \DateTime('2024-10-10');
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
    public function fortnightlyDataScale6(): array
    {
        return [
            [1684, 0, 0, 0, 0, 0],
            [1686, 0, 0, 0, 0, 0],
            [1894, 10, 10, 10, 10, 10],
            [1896, 10, 10, 10, 10, 10],
            [2104, 20, 20, 20, 20, 20],
            [2106, 8, 16, 22, 22, 22],
            [2142, 6, 14, 22, 22, 22],
            [2144, 6, 14, 22, 22, 22],
            [2180, 4, 12, 20, 22, 22],
            [2182, 4, 12, 20, 22, 22],
            [2218, 4, 12, 18, 22, 22],
            [2220, 4, 10, 18, 22, 22],
            [2256, 2, 10, 18, 22, 22],
            [2258, 2, 10, 18, 22, 22],
            [2294, 0, 8, 16, 22, 22],
            [2296, 0, 8, 16, 22, 22],
            [2332, 0, 6, 14, 22, 24],
            [2334, 0, 6, 14, 22, 24],
            [2370, 0, 4, 12, 20, 24],
            [2372, 0, 4, 12, 20, 24],
            [2408, 0, 4, 12, 18, 24],
            [2410, 0, 4, 12, 18, 24],
            [2446, 0, 2, 10, 18, 24],
            [2448, 0, 2, 10, 18, 24],
            [2484, 0, 0, 8, 16, 24],
            [2486, 0, 0, 8, 16, 24],
            [2522, 0, 0, 6, 14, 22],
            [2524, 0, 0, 6, 14, 22],
            [2560, 0, 0, 6, 12, 20],
            [2562, 0, 0, 4, 12, 20],
            [2598, 0, 0, 4, 12, 20],
            [2600, 0, 0, 4, 12, 18],
            [2636, 0, 0, 2, 10, 18],
            [2638, 0, 0, 2, 10, 18],
            [2674, 0, 0, 0, 8, 16],
            [2676, 0, 0, 0, 8, 16],
            [2712, 0, 0, 0, 6, 14],
            [2714, 0, 0, 0, 6, 14],
            [2750, 0, 0, 0, 6, 12],
            [2752, 0, 0, 0, 6, 12],
            [2788, 0, 0, 0, 4, 12],
            [2790, 0, 0, 0, 4, 12],
            [2826, 0, 0, 0, 2, 10],
            [2828, 0, 0, 0, 2, 10],
            [2880, 0, 0, 0, 0, 8],
            [2882, 0, 0, 0, 0, 8],
            [3074, 0, 0, 0, 0, 0],
            [3076, 0, 0, 0, 0, 0],
        ];
    }

    /**
     * @dataProvider monthlyData
     */
    public function testMonthlyAdjustmentScale2(
        float $gross,
        int $spouseOnly,
        int $children1,
        int $children2,
        int $children3,
        int $children4,
        int $children5
    ): void {
        $payer = new Payer();

        $payee = new Payee();
        $payee->payCycle = Payee::PAY_CYCLE_MONTHLY;
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;

        $earning = new Earning();
        $earning->date = new \DateTime('2024-10-10');
        $earning->gross = $gross;

        $scale = new Nat1004();

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
            [2162.33, 0, 0, 0, 0, 0, 0],
            [2166.67, 0, 0, 0, 0, 0, 0],
            [2431.00, 26, 26, 26, 26, 26, 26],
            [2435.33, 26, 26, 26, 26, 26, 26],
            [2704.00, 52, 52, 52, 52, 52, 52],
            [2708.33, 56, 56, 56, 56, 56, 56],
            [2842.67, 56, 56, 56, 56, 56, 56],
            [2847.00, 56, 56, 56, 56, 56, 56],
            [2981.33, 61, 61, 61, 61, 61, 61],
            [2985.67, 61, 61, 61, 61, 61, 61],
            [3120.00, 61, 61, 61, 61, 61, 61],
            [3124.33, 61, 61, 61, 61, 61, 61],
            [3258.67, 65, 65, 65, 65, 65, 65],
            [3263.00, 65, 65, 65, 65, 65, 65],
            [3397.33, 69, 69, 69, 69, 69, 69],
            [3401.67, 69, 69, 69, 69, 69, 69],
            [3536.00, 69, 69, 69, 69, 69, 69],
            [3540.33, 69, 69, 69, 69, 69, 69],
            [3674.67, 69, 74, 74, 74, 74, 74],
            [3679.00, 69, 74, 74, 74, 74, 74],
            [3813.33, 61, 78, 78, 78, 78, 78],
            [3817.67, 61, 78, 78, 78, 78, 78],
            [3952.00, 48, 78, 78, 78, 78, 78],
            [3956.33, 48, 78, 78, 78, 78, 78],
            [4090.67, 39, 69, 82, 82, 82, 82],
            [4095.00, 39, 69, 82, 82, 82, 82],
            [4229.33, 26, 61, 87, 87, 87, 87],
            [4233.67, 26, 61, 87, 87, 87, 87],
            [4368.00, 17, 48, 82, 87, 87, 87],
            [4372.33, 17, 48, 82, 87, 87, 87],
            [4506.67, 4, 39, 74, 91, 91, 91],
            [4511.00, 4, 39, 69, 91, 91, 91],
            [4645.33, 0, 26, 61, 91, 91, 91],
            [4649.67, 0, 26, 61, 91, 91, 91],
            [4784.00, 0, 17, 48, 82, 95, 95],
            [4788.33, 0, 17, 48, 82, 95, 95],
            [4922.67, 0, 4, 39, 74, 100, 100],
            [4927.00, 0, 4, 39, 74, 100, 100],
            [5061.33, 0, 0, 26, 61, 95, 100],
            [5065.67, 0, 0, 26, 61, 95, 100],
            [5200.00, 0, 0, 17, 48, 82, 104],
            [5204.33, 0, 0, 17, 48, 82, 104],
            [5338.67, 0, 0, 4, 39, 74, 104],
            [5343.00, 0, 0, 4, 39, 74, 104],
            [6240.00, 0, 0, 0, 0, 0, 35],
            [6244.33, 0, 0, 0, 0, 0, 35],
            [6660.33, 0, 0, 0, 0, 0, 0],
            [6664.67, 0, 0, 0, 0, 0, 0],
        ];
    }

    /**
     * @dataProvider monthlyDataScale6
     */
    public function testMonthlyAdjustmentScale6(
        float $gross,
        int $children1,
        int $children2,
        int $children3,
        int $children4,
        int $children5
    ): void {
        $payer = new Payer();

        $payee = new Payee();
        $payee->payCycle = Payee::PAY_CYCLE_MONTHLY;
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_HALF;

        $earning = new Earning();
        $earning->date = new \DateTime('2024-10-10');
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
    public function monthlyDataScale6(): array
    {
        return [
            [3648.67, 0, 0, 0, 0, 0],
            [3653.00, 0, 0, 0, 0, 0],
            [4103.67, 22, 22, 22, 22, 22],
            [4108.00, 22, 22, 22, 22, 22],
            [4558.67, 43, 43, 43, 43, 43],
            [4563.00, 17, 35, 48, 48, 48],
            [4641.00, 13, 30, 48, 48, 48],
            [4645.33, 13, 30, 48, 48, 48],
            [4723.33, 9, 26, 43, 48, 48],
            [4727.67, 9, 26, 43, 48, 48],
            [4805.67, 9, 26, 39, 48, 48],
            [4810.00, 9, 22, 39, 48, 48],
            [4888.00, 4, 22, 39, 48, 48],
            [4892.33, 4, 22, 39, 48, 48],
            [4970.33, 0, 17, 35, 48, 48],
            [4974.67, 0, 17, 35, 48, 48],
            [5052.67, 0, 13, 30, 48, 52],
            [5057.00, 0, 13, 30, 48, 52],
            [5135.00, 0, 9, 26, 43, 52],
            [5139.33, 0, 9, 26, 43, 52],
            [5217.33, 0, 9, 26, 39, 52],
            [5221.67, 0, 9, 26, 39, 52],
            [5299.67, 0, 4, 22, 39, 52],
            [5304.00, 0, 4, 22, 39, 52],
            [5382.00, 0, 0, 17, 35, 52],
            [5386.33, 0, 0, 17, 35, 52],
            [5464.33, 0, 0, 13, 30, 48],
            [5468.67, 0, 0, 13, 30, 48],
            [5546.67, 0, 0, 13, 26, 43],
            [5551.00, 0, 0, 9, 26, 43],
            [5629.00, 0, 0, 9, 26, 43],
            [5633.33, 0, 0, 9, 26, 39],
            [5711.33, 0, 0, 4, 22, 39],
            [5715.67, 0, 0, 4, 22, 39],
            [5793.67, 0, 0, 0, 17, 35],
            [5798.00, 0, 0, 0, 17, 35],
            [5876.00, 0, 0, 0, 13, 30],
            [5880.33, 0, 0, 0, 13, 30],
            [5958.33, 0, 0, 0, 13, 26],
            [5962.67, 0, 0, 0, 13, 26],
            [6040.67, 0, 0, 0, 9, 26],
            [6045.00, 0, 0, 0, 9, 26],
            [6123.00, 0, 0, 0, 4, 22],
            [6127.33, 0, 0, 0, 4, 22],
            [6240.00, 0, 0, 0, 0, 17],
            [6244.33, 0, 0, 0, 0, 17],
            [6660.33, 0, 0, 0, 0, 0],
            [6664.67, 0, 0, 0, 0, 0],
        ];
    }
}
