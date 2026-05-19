<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\Tests\Adjustments\July2024;

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

    #[\PHPUnit\Framework\Attributes\DataProvider('weeklyData')]
    public function testWeeklyAdjustmentScale2(
        int $gross,
        int $spouseOnly,
        int $children1,
        int $children2,
        int $children3,
        int $children4,
        int $children5,
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
     * @return \Iterator<int, array<int, (float | int)>>
     */
    public static function weeklyData(): \Iterator
    {
        yield [499, 0, 0, 0, 0, 0, 0];
        yield [500, 0, 0, 0, 0, 0, 0];
        yield [561, 6, 6, 6, 6, 6, 6];
        yield [562, 6, 6, 6, 6, 6, 6];
        yield [624, 12, 12, 12, 12, 12, 12];
        yield [625, 13, 13, 13, 13, 13, 13];
        yield [656, 13, 13, 13, 13, 13, 13];
        yield [657, 13, 13, 13, 13, 13, 13];
        yield [688, 14, 14, 14, 14, 14, 14];
        yield [689, 14, 14, 14, 14, 14, 14];
        yield [720, 14, 14, 14, 14, 14, 14];
        yield [721, 14, 14, 14, 14, 14, 14];
        yield [752, 15, 15, 15, 15, 15, 15];
        yield [753, 15, 15, 15, 15, 15, 15];
        yield [784, 16, 16, 16, 16, 16, 16];
        yield [785, 16, 16, 16, 16, 16, 16];
        yield [816, 16, 16, 16, 16, 16, 16];
        yield [817, 16, 16, 16, 16, 16, 16];
        yield [848, 16, 17, 17, 17, 17, 17];
        yield [849, 16, 17, 17, 17, 17, 17];
        yield [880, 14, 18, 18, 18, 18, 18];
        yield [881, 14, 18, 18, 18, 18, 18];
        yield [912, 11, 18, 18, 18, 18, 18];
        yield [913, 11, 18, 18, 18, 18, 18];
        yield [944, 9, 16, 19, 19, 19, 19];
        yield [945, 9, 16, 19, 19, 19, 19];
        yield [976, 6, 14, 20, 20, 20, 20];
        yield [977, 6, 14, 20, 20, 20, 20];
        yield [1008, 4, 11, 19, 20, 20, 20];
        yield [1009, 4, 11, 19, 20, 20, 20];
        yield [1040, 1, 9, 17, 21, 21, 21];
        yield [1041, 1, 9, 16, 21, 21, 21];
        yield [1072, 0, 6, 14, 21, 21, 21];
        yield [1073, 0, 6, 14, 21, 21, 21];
        yield [1104, 0, 4, 11, 19, 22, 22];
        yield [1105, 0, 4, 11, 19, 22, 22];
        yield [1136, 0, 1, 9, 17, 23, 23];
        yield [1137, 0, 1, 9, 17, 23, 23];
        yield [1168, 0, 0, 6, 14, 22, 23];
        yield [1169, 0, 0, 6, 14, 22, 23];
        yield [1200, 0, 0, 4, 11, 19, 24];
        yield [1201, 0, 0, 4, 11, 19, 24];
        yield [1232, 0, 0, 1, 9, 17, 24];
        yield [1233, 0, 0, 1, 9, 17, 24];
        yield [1440, 0, 0, 0, 0, 0, 8];
        yield [1441, 0, 0, 0, 0, 0, 8];
        yield [1537, 0, 0, 0, 0, 0, 0];
        yield [1538, 0, 0, 0, 0, 0, 0];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('weeklyDataScale6')]
    public function testWeeklyAdjustmentScale6(
        int $gross,
        int $children1,
        int $children2,
        int $children3,
        int $children4,
        int $children5,
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
     * @return \Iterator<int, array<int, (float | int)>>
     */
    public static function weeklyDataScale6(): \Iterator
    {
        yield [842, 0, 0, 0, 0, 0];
        yield [843, 0, 0, 0, 0, 0];
        yield [947, 5, 5, 5, 5, 5];
        yield [948, 5, 5, 5, 5, 5];
        yield [1052, 10, 10, 10, 10, 10];
        yield [1053, 4, 8, 11, 11, 11];
        yield [1071, 3, 7, 11, 11, 11];
        yield [1072, 3, 7, 11, 11, 11];
        yield [1090, 2, 6, 10, 11, 11];
        yield [1091, 2, 6, 10, 11, 11];
        yield [1109, 2, 6, 9, 11, 11];
        yield [1110, 2, 5, 9, 11, 11];
        yield [1128, 1, 5, 9, 11, 11];
        yield [1129, 1, 5, 9, 11, 11];
        yield [1147, 0, 4, 8, 11, 11];
        yield [1148, 0, 4, 8, 11, 11];
        yield [1166, 0, 3, 7, 11, 12];
        yield [1167, 0, 3, 7, 11, 12];
        yield [1185, 0, 2, 6, 10, 12];
        yield [1186, 0, 2, 6, 10, 12];
        yield [1204, 0, 2, 6, 9, 12];
        yield [1205, 0, 2, 6, 9, 12];
        yield [1223, 0, 1, 5, 9, 12];
        yield [1224, 0, 1, 5, 9, 12];
        yield [1242, 0, 0, 4, 8, 12];
        yield [1243, 0, 0, 4, 8, 12];
        yield [1261, 0, 0, 3, 7, 11];
        yield [1262, 0, 0, 3, 7, 11];
        yield [1280, 0, 0, 3, 6, 10];
        yield [1281, 0, 0, 2, 6, 10];
        yield [1299, 0, 0, 2, 6, 10];
        yield [1300, 0, 0, 2, 6, 9];
        yield [1318, 0, 0, 1, 5, 9];
        yield [1319, 0, 0, 1, 5, 9];
        yield [1337, 0, 0, 0, 4, 8];
        yield [1338, 0, 0, 0, 4, 8];
        yield [1356, 0, 0, 0, 3, 7];
        yield [1357, 0, 0, 0, 3, 7];
        yield [1375, 0, 0, 0, 3, 6];
        yield [1376, 0, 0, 0, 3, 6];
        yield [1394, 0, 0, 0, 2, 6];
        yield [1395, 0, 0, 0, 2, 6];
        yield [1413, 0, 0, 0, 1, 5];
        yield [1414, 0, 0, 0, 1, 5];
        yield [1440, 0, 0, 0, 0, 4];
        yield [1441, 0, 0, 0, 0, 4];
        yield [1537, 0, 0, 0, 0, 0];
        yield [1538, 0, 0, 0, 0, 0];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('fortnightlyData')]
    public function testFortnightlyAdjustmentScale2(
        int $gross,
        int $spouseOnly,
        int $children1,
        int $children2,
        int $children3,
        int $children4,
        int $children5,
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
     * @return \Iterator<int, array<int, (float | int)>>
     */
    public static function fortnightlyData(): \Iterator
    {
        yield [998, 0, 0, 0, 0, 0, 0];
        yield [1000, 0, 0, 0, 0, 0, 0];
        yield [1122, 12, 12, 12, 12, 12, 12];
        yield [1124, 12, 12, 12, 12, 12, 12];
        yield [1248, 24, 24, 24, 24, 24, 24];
        yield [1250, 26, 26, 26, 26, 26, 26];
        yield [1312, 26, 26, 26, 26, 26, 26];
        yield [1314, 26, 26, 26, 26, 26, 26];
        yield [1376, 28, 28, 28, 28, 28, 28];
        yield [1378, 28, 28, 28, 28, 28, 28];
        yield [1440, 28, 28, 28, 28, 28, 28];
        yield [1442, 28, 28, 28, 28, 28, 28];
        yield [1504, 30, 30, 30, 30, 30, 30];
        yield [1506, 30, 30, 30, 30, 30, 30];
        yield [1568, 32, 32, 32, 32, 32, 32];
        yield [1570, 32, 32, 32, 32, 32, 32];
        yield [1632, 32, 32, 32, 32, 32, 32];
        yield [1634, 32, 32, 32, 32, 32, 32];
        yield [1696, 32, 34, 34, 34, 34, 34];
        yield [1698, 32, 34, 34, 34, 34, 34];
        yield [1760, 28, 36, 36, 36, 36, 36];
        yield [1762, 28, 36, 36, 36, 36, 36];
        yield [1824, 22, 36, 36, 36, 36, 36];
        yield [1826, 22, 36, 36, 36, 36, 36];
        yield [1888, 18, 32, 38, 38, 38, 38];
        yield [1890, 18, 32, 38, 38, 38, 38];
        yield [1952, 12, 28, 40, 40, 40, 40];
        yield [1954, 12, 28, 40, 40, 40, 40];
        yield [2016, 8, 22, 38, 40, 40, 40];
        yield [2018, 8, 22, 38, 40, 40, 40];
        yield [2080, 2, 18, 34, 42, 42, 42];
        yield [2082, 2, 18, 32, 42, 42, 42];
        yield [2144, 0, 12, 28, 42, 42, 42];
        yield [2146, 0, 12, 28, 42, 42, 42];
        yield [2208, 0, 8, 22, 38, 44, 44];
        yield [2210, 0, 8, 22, 38, 44, 44];
        yield [2272, 0, 2, 18, 34, 46, 46];
        yield [2274, 0, 2, 18, 34, 46, 46];
        yield [2336, 0, 0, 12, 28, 44, 46];
        yield [2338, 0, 0, 12, 28, 44, 46];
        yield [2400, 0, 0, 8, 22, 38, 48];
        yield [2402, 0, 0, 8, 22, 38, 48];
        yield [2464, 0, 0, 2, 18, 34, 48];
        yield [2466, 0, 0, 2, 18, 34, 48];
        yield [2880, 0, 0, 0, 0, 0, 16];
        yield [2882, 0, 0, 0, 0, 0, 16];
        yield [3074, 0, 0, 0, 0, 0, 0];
        yield [3076, 0, 0, 0, 0, 0, 0];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('fortnightlyDataScale6')]
    public function testFortnightlyAdjustmentScale6(
        int $gross,
        int $children1,
        int $children2,
        int $children3,
        int $children4,
        int $children5,
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
     * @return \Iterator<int, array<int, (float | int)>>
     */
    public static function fortnightlyDataScale6(): \Iterator
    {
        yield [1684, 0, 0, 0, 0, 0];
        yield [1686, 0, 0, 0, 0, 0];
        yield [1894, 10, 10, 10, 10, 10];
        yield [1896, 10, 10, 10, 10, 10];
        yield [2104, 20, 20, 20, 20, 20];
        yield [2106, 8, 16, 22, 22, 22];
        yield [2142, 6, 14, 22, 22, 22];
        yield [2144, 6, 14, 22, 22, 22];
        yield [2180, 4, 12, 20, 22, 22];
        yield [2182, 4, 12, 20, 22, 22];
        yield [2218, 4, 12, 18, 22, 22];
        yield [2220, 4, 10, 18, 22, 22];
        yield [2256, 2, 10, 18, 22, 22];
        yield [2258, 2, 10, 18, 22, 22];
        yield [2294, 0, 8, 16, 22, 22];
        yield [2296, 0, 8, 16, 22, 22];
        yield [2332, 0, 6, 14, 22, 24];
        yield [2334, 0, 6, 14, 22, 24];
        yield [2370, 0, 4, 12, 20, 24];
        yield [2372, 0, 4, 12, 20, 24];
        yield [2408, 0, 4, 12, 18, 24];
        yield [2410, 0, 4, 12, 18, 24];
        yield [2446, 0, 2, 10, 18, 24];
        yield [2448, 0, 2, 10, 18, 24];
        yield [2484, 0, 0, 8, 16, 24];
        yield [2486, 0, 0, 8, 16, 24];
        yield [2522, 0, 0, 6, 14, 22];
        yield [2524, 0, 0, 6, 14, 22];
        yield [2560, 0, 0, 6, 12, 20];
        yield [2562, 0, 0, 4, 12, 20];
        yield [2598, 0, 0, 4, 12, 20];
        yield [2600, 0, 0, 4, 12, 18];
        yield [2636, 0, 0, 2, 10, 18];
        yield [2638, 0, 0, 2, 10, 18];
        yield [2674, 0, 0, 0, 8, 16];
        yield [2676, 0, 0, 0, 8, 16];
        yield [2712, 0, 0, 0, 6, 14];
        yield [2714, 0, 0, 0, 6, 14];
        yield [2750, 0, 0, 0, 6, 12];
        yield [2752, 0, 0, 0, 6, 12];
        yield [2788, 0, 0, 0, 4, 12];
        yield [2790, 0, 0, 0, 4, 12];
        yield [2826, 0, 0, 0, 2, 10];
        yield [2828, 0, 0, 0, 2, 10];
        yield [2880, 0, 0, 0, 0, 8];
        yield [2882, 0, 0, 0, 0, 8];
        yield [3074, 0, 0, 0, 0, 0];
        yield [3076, 0, 0, 0, 0, 0];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('monthlyData')]
    public function testMonthlyAdjustmentScale2(
        float $gross,
        int $spouseOnly,
        int $children1,
        int $children2,
        int $children3,
        int $children4,
        int $children5,
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
     * @return \Iterator<int, array<int, (float | int)>>
     */
    public static function monthlyData(): \Iterator
    {
        yield [2162.33, 0, 0, 0, 0, 0, 0];
        yield [2166.67, 0, 0, 0, 0, 0, 0];
        yield [2431.0, 26, 26, 26, 26, 26, 26];
        yield [2435.33, 26, 26, 26, 26, 26, 26];
        yield [2704.0, 52, 52, 52, 52, 52, 52];
        yield [2708.33, 56, 56, 56, 56, 56, 56];
        yield [2842.67, 56, 56, 56, 56, 56, 56];
        yield [2847.0, 56, 56, 56, 56, 56, 56];
        yield [2981.33, 61, 61, 61, 61, 61, 61];
        yield [2985.67, 61, 61, 61, 61, 61, 61];
        yield [3120.0, 61, 61, 61, 61, 61, 61];
        yield [3124.33, 61, 61, 61, 61, 61, 61];
        yield [3258.67, 65, 65, 65, 65, 65, 65];
        yield [3263.0, 65, 65, 65, 65, 65, 65];
        yield [3397.33, 69, 69, 69, 69, 69, 69];
        yield [3401.67, 69, 69, 69, 69, 69, 69];
        yield [3536.0, 69, 69, 69, 69, 69, 69];
        yield [3540.33, 69, 69, 69, 69, 69, 69];
        yield [3674.67, 69, 74, 74, 74, 74, 74];
        yield [3679.0, 69, 74, 74, 74, 74, 74];
        yield [3813.33, 61, 78, 78, 78, 78, 78];
        yield [3817.67, 61, 78, 78, 78, 78, 78];
        yield [3952.0, 48, 78, 78, 78, 78, 78];
        yield [3956.33, 48, 78, 78, 78, 78, 78];
        yield [4090.67, 39, 69, 82, 82, 82, 82];
        yield [4095.0, 39, 69, 82, 82, 82, 82];
        yield [4229.33, 26, 61, 87, 87, 87, 87];
        yield [4233.67, 26, 61, 87, 87, 87, 87];
        yield [4368.0, 17, 48, 82, 87, 87, 87];
        yield [4372.33, 17, 48, 82, 87, 87, 87];
        yield [4506.67, 4, 39, 74, 91, 91, 91];
        yield [4511.0, 4, 39, 69, 91, 91, 91];
        yield [4645.33, 0, 26, 61, 91, 91, 91];
        yield [4649.67, 0, 26, 61, 91, 91, 91];
        yield [4784.0, 0, 17, 48, 82, 95, 95];
        yield [4788.33, 0, 17, 48, 82, 95, 95];
        yield [4922.67, 0, 4, 39, 74, 100, 100];
        yield [4927.0, 0, 4, 39, 74, 100, 100];
        yield [5061.33, 0, 0, 26, 61, 95, 100];
        yield [5065.67, 0, 0, 26, 61, 95, 100];
        yield [5200.0, 0, 0, 17, 48, 82, 104];
        yield [5204.33, 0, 0, 17, 48, 82, 104];
        yield [5338.67, 0, 0, 4, 39, 74, 104];
        yield [5343.0, 0, 0, 4, 39, 74, 104];
        yield [6240.0, 0, 0, 0, 0, 0, 35];
        yield [6244.33, 0, 0, 0, 0, 0, 35];
        yield [6660.33, 0, 0, 0, 0, 0, 0];
        yield [6664.67, 0, 0, 0, 0, 0, 0];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('monthlyDataScale6')]
    public function testMonthlyAdjustmentScale6(
        float $gross,
        int $children1,
        int $children2,
        int $children3,
        int $children4,
        int $children5,
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
     * @return \Iterator<int, array<int, (float | int)>>
     */
    public static function monthlyDataScale6(): \Iterator
    {
        yield [3648.67, 0, 0, 0, 0, 0];
        yield [3653.0, 0, 0, 0, 0, 0];
        yield [4103.67, 22, 22, 22, 22, 22];
        yield [4108.0, 22, 22, 22, 22, 22];
        yield [4558.67, 43, 43, 43, 43, 43];
        yield [4563.0, 17, 35, 48, 48, 48];
        yield [4641.0, 13, 30, 48, 48, 48];
        yield [4645.33, 13, 30, 48, 48, 48];
        yield [4723.33, 9, 26, 43, 48, 48];
        yield [4727.67, 9, 26, 43, 48, 48];
        yield [4805.67, 9, 26, 39, 48, 48];
        yield [4810.0, 9, 22, 39, 48, 48];
        yield [4888.0, 4, 22, 39, 48, 48];
        yield [4892.33, 4, 22, 39, 48, 48];
        yield [4970.33, 0, 17, 35, 48, 48];
        yield [4974.67, 0, 17, 35, 48, 48];
        yield [5052.67, 0, 13, 30, 48, 52];
        yield [5057.0, 0, 13, 30, 48, 52];
        yield [5135.0, 0, 9, 26, 43, 52];
        yield [5139.33, 0, 9, 26, 43, 52];
        yield [5217.33, 0, 9, 26, 39, 52];
        yield [5221.67, 0, 9, 26, 39, 52];
        yield [5299.67, 0, 4, 22, 39, 52];
        yield [5304.0, 0, 4, 22, 39, 52];
        yield [5382.0, 0, 0, 17, 35, 52];
        yield [5386.33, 0, 0, 17, 35, 52];
        yield [5464.33, 0, 0, 13, 30, 48];
        yield [5468.67, 0, 0, 13, 30, 48];
        yield [5546.67, 0, 0, 13, 26, 43];
        yield [5551.0, 0, 0, 9, 26, 43];
        yield [5629.0, 0, 0, 9, 26, 43];
        yield [5633.33, 0, 0, 9, 26, 39];
        yield [5711.33, 0, 0, 4, 22, 39];
        yield [5715.67, 0, 0, 4, 22, 39];
        yield [5793.67, 0, 0, 0, 17, 35];
        yield [5798.0, 0, 0, 0, 17, 35];
        yield [5876.0, 0, 0, 0, 13, 30];
        yield [5880.33, 0, 0, 0, 13, 30];
        yield [5958.33, 0, 0, 0, 13, 26];
        yield [5962.67, 0, 0, 0, 13, 26];
        yield [6040.67, 0, 0, 0, 9, 26];
        yield [6045.0, 0, 0, 0, 9, 26];
        yield [6123.0, 0, 0, 0, 4, 22];
        yield [6127.33, 0, 0, 0, 4, 22];
        yield [6240.0, 0, 0, 0, 0, 17];
        yield [6244.33, 0, 0, 0, 0, 17];
        yield [6660.33, 0, 0, 0, 0, 0];
        yield [6664.67, 0, 0, 0, 0, 0];
    }
}
