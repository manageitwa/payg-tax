<?php

namespace ManageIt\PaygTax\Tests\Adjustments\July2026;

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
        $earning->date = new \DateTime('2026-10-10');
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
        $earning->date = new \DateTime('2026-10-10');
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
            [537, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0],
            [538, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0],
            [604, 7.0, 7.0, 7.0, 7.0, 7.0, 7.0],
            [605, 7.0, 7.0, 7.0, 7.0, 7.0, 7.0],
            [672, 13.0, 13.0, 13.0, 13.0, 13.0, 13.0],
            [673, 13.0, 13.0, 13.0, 13.0, 13.0, 13.0],
            [706, 14.0, 14.0, 14.0, 14.0, 14.0, 14.0],
            [707, 14.0, 14.0, 14.0, 14.0, 14.0, 14.0],
            [740, 15.0, 15.0, 15.0, 15.0, 15.0, 15.0],
            [741, 15.0, 15.0, 15.0, 15.0, 15.0, 15.0],
            [774, 15.0, 15.0, 15.0, 15.0, 15.0, 15.0],
            [775, 16.0, 16.0, 16.0, 16.0, 16.0, 16.0],
            [808, 16.0, 16.0, 16.0, 16.0, 16.0, 16.0],
            [809, 16.0, 16.0, 16.0, 16.0, 16.0, 16.0],
            [842, 17.0, 17.0, 17.0, 17.0, 17.0, 17.0],
            [843, 17.0, 17.0, 17.0, 17.0, 17.0, 17.0],
            [876, 18.0, 18.0, 18.0, 18.0, 18.0, 18.0],
            [877, 18.0, 18.0, 18.0, 18.0, 18.0, 18.0],
            [910, 18.0, 18.0, 18.0, 18.0, 18.0, 18.0],
            [911, 18.0, 18.0, 18.0, 18.0, 18.0, 18.0],
            [944, 15.0, 19.0, 19.0, 19.0, 19.0, 19.0],
            [945, 15.0, 19.0, 19.0, 19.0, 19.0, 19.0],
            [978, 13.0, 20.0, 20.0, 20.0, 20.0, 20.0],
            [979, 12.0, 20.0, 20.0, 20.0, 20.0, 20.0],
            [1012, 10.0, 18.0, 20.0, 20.0, 20.0, 20.0],
            [1013, 10.0, 18.0, 20.0, 20.0, 20.0, 20.0],
            [1046, 7.0, 15.0, 21.0, 21.0, 21.0, 21.0],
            [1047, 7.0, 15.0, 21.0, 21.0, 21.0, 21.0],
            [1080, 4.0, 13.0, 21.0, 22.0, 22.0, 22.0],
            [1081, 4.0, 13.0, 21.0, 22.0, 22.0, 22.0],
            [1114, 2.0, 10.0, 18.0, 22.0, 22.0, 22.0],
            [1115, 2.0, 10.0, 18.0, 22.0, 22.0, 22.0],
            [1148, 0.0, 7.0, 16.0, 23.0, 23.0, 23.0],
            [1149, 0.0, 7.0, 16.0, 23.0, 23.0, 23.0],
            [1182, 0.0, 5.0, 13.0, 21.0, 24.0, 24.0],
            [1183, 0.0, 4.0, 13.0, 21.0, 24.0, 24.0],
            [1216, 0.0, 2.0, 10.0, 19.0, 24.0, 24.0],
            [1217, 0.0, 2.0, 10.0, 18.0, 24.0, 24.0],
            [1250, 0.0, 0.0, 7.0, 16.0, 24.0, 25.0],
            [1251, 0.0, 0.0, 7.0, 16.0, 24.0, 25.0],
            [1284, 0.0, 0.0, 5.0, 13.0, 21.0, 26.0],
            [1285, 0.0, 0.0, 5.0, 13.0, 21.0, 26.0],
            [1318, 0.0, 0.0, 2.0, 10.0, 19.0, 26.0],
            [1319, 0.0, 0.0, 2.0, 10.0, 19.0, 26.0],
            [1551, 0.0, 0.0, 0.0, 0.0, 0.0, 8.0],
            [1552, 0.0, 0.0, 0.0, 0.0, 0.0, 8.0],
            [1655, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0],
            [1656, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0],
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
        $earning->date = new \DateTime('2026-10-10');
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
            [907, 0.0, 0.0, 0.0, 0.0, 0.0],
            [908, 0.0, 0.0, 0.0, 0.0, 0.0],
            [1020, 6.0, 6.0, 6.0, 6.0, 6.0],
            [1021, 6.0, 6.0, 6.0, 6.0, 6.0],
            [1134, 11.0, 11.0, 11.0, 11.0, 11.0],
            [1135, 4.0, 8.0, 11.0, 11.0, 11.0],
            [1154, 3.0, 8.0, 12.0, 12.0, 12.0],
            [1155, 3.0, 8.0, 12.0, 12.0, 12.0],
            [1174, 3.0, 7.0, 11.0, 12.0, 12.0],
            [1175, 3.0, 7.0, 11.0, 12.0, 12.0],
            [1194, 2.0, 6.0, 10.0, 12.0, 12.0],
            [1195, 2.0, 6.0, 10.0, 12.0, 12.0],
            [1214, 1.0, 5.0, 9.0, 12.0, 12.0],
            [1215, 1.0, 5.0, 9.0, 12.0, 12.0],
            [1234, 0.0, 4.0, 9.0, 12.0, 12.0],
            [1235, 0.0, 4.0, 8.0, 12.0, 12.0],
            [1254, 0.0, 4.0, 8.0, 12.0, 13.0],
            [1255, 0.0, 4.0, 8.0, 12.0, 13.0],
            [1274, 0.0, 3.0, 7.0, 11.0, 13.0],
            [1275, 0.0, 3.0, 7.0, 11.0, 13.0],
            [1294, 0.0, 2.0, 6.0, 10.0, 13.0],
            [1295, 0.0, 2.0, 6.0, 10.0, 13.0],
            [1314, 0.0, 1.0, 5.0, 10.0, 13.0],
            [1315, 0.0, 1.0, 5.0, 9.0, 13.0],
            [1334, 0.0, 0.0, 5.0, 9.0, 13.0],
            [1335, 0.0, 0.0, 4.0, 9.0, 13.0],
            [1354, 0.0, 0.0, 4.0, 8.0, 12.0],
            [1355, 0.0, 0.0, 4.0, 8.0, 12.0],
            [1374, 0.0, 0.0, 3.0, 7.0, 11.0],
            [1375, 0.0, 0.0, 3.0, 7.0, 11.0],
            [1394, 0.0, 0.0, 2.0, 6.0, 10.0],
            [1395, 0.0, 0.0, 2.0, 6.0, 10.0],
            [1414, 0.0, 0.0, 1.0, 6.0, 10.0],
            [1415, 0.0, 0.0, 1.0, 5.0, 10.0],
            [1434, 0.0, 0.0, 1.0, 5.0, 9.0],
            [1435, 0.0, 0.0, 0.0, 5.0, 9.0],
            [1454, 0.0, 0.0, 0.0, 4.0, 8.0],
            [1455, 0.0, 0.0, 0.0, 4.0, 8.0],
            [1474, 0.0, 0.0, 0.0, 3.0, 7.0],
            [1475, 0.0, 0.0, 0.0, 3.0, 7.0],
            [1494, 0.0, 0.0, 0.0, 2.0, 6.0],
            [1495, 0.0, 0.0, 0.0, 2.0, 6.0],
            [1514, 0.0, 0.0, 0.0, 2.0, 6.0],
            [1515, 0.0, 0.0, 0.0, 1.0, 6.0],
            [1551, 0.0, 0.0, 0.0, 0.0, 4.0],
            [1552, 0.0, 0.0, 0.0, 0.0, 4.0],
            [1655, 0.0, 0.0, 0.0, 0.0, 0.0],
            [1656, 0.0, 0.0, 0.0, 0.0, 0.0],
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
        $earning->date = new \DateTime('2026-10-10');
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
            [1074, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0],
            [1076, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0],
            [1208, 14.0, 14.0, 14.0, 14.0, 14.0, 14.0],
            [1210, 14.0, 14.0, 14.0, 14.0, 14.0, 14.0],
            [1344, 26.0, 26.0, 26.0, 26.0, 26.0, 26.0],
            [1346, 26.0, 26.0, 26.0, 26.0, 26.0, 26.0],
            [1412, 28.0, 28.0, 28.0, 28.0, 28.0, 28.0],
            [1414, 28.0, 28.0, 28.0, 28.0, 28.0, 28.0],
            [1480, 30.0, 30.0, 30.0, 30.0, 30.0, 30.0],
            [1482, 30.0, 30.0, 30.0, 30.0, 30.0, 30.0],
            [1548, 30.0, 30.0, 30.0, 30.0, 30.0, 30.0],
            [1550, 32.0, 32.0, 32.0, 32.0, 32.0, 32.0],
            [1616, 32.0, 32.0, 32.0, 32.0, 32.0, 32.0],
            [1618, 32.0, 32.0, 32.0, 32.0, 32.0, 32.0],
            [1684, 34.0, 34.0, 34.0, 34.0, 34.0, 34.0],
            [1686, 34.0, 34.0, 34.0, 34.0, 34.0, 34.0],
            [1752, 36.0, 36.0, 36.0, 36.0, 36.0, 36.0],
            [1754, 36.0, 36.0, 36.0, 36.0, 36.0, 36.0],
            [1820, 36.0, 36.0, 36.0, 36.0, 36.0, 36.0],
            [1822, 36.0, 36.0, 36.0, 36.0, 36.0, 36.0],
            [1888, 30.0, 38.0, 38.0, 38.0, 38.0, 38.0],
            [1890, 30.0, 38.0, 38.0, 38.0, 38.0, 38.0],
            [1956, 26.0, 40.0, 40.0, 40.0, 40.0, 40.0],
            [1958, 24.0, 40.0, 40.0, 40.0, 40.0, 40.0],
            [2024, 20.0, 36.0, 40.0, 40.0, 40.0, 40.0],
            [2026, 20.0, 36.0, 40.0, 40.0, 40.0, 40.0],
            [2092, 14.0, 30.0, 42.0, 42.0, 42.0, 42.0],
            [2094, 14.0, 30.0, 42.0, 42.0, 42.0, 42.0],
            [2160, 8.0, 26.0, 42.0, 44.0, 44.0, 44.0],
            [2162, 8.0, 26.0, 42.0, 44.0, 44.0, 44.0],
            [2228, 4.0, 20.0, 36.0, 44.0, 44.0, 44.0],
            [2230, 4.0, 20.0, 36.0, 44.0, 44.0, 44.0],
            [2296, 0.0, 14.0, 32.0, 46.0, 46.0, 46.0],
            [2298, 0.0, 14.0, 32.0, 46.0, 46.0, 46.0],
            [2364, 0.0, 10.0, 26.0, 42.0, 48.0, 48.0],
            [2366, 0.0, 8.0, 26.0, 42.0, 48.0, 48.0],
            [2432, 0.0, 4.0, 20.0, 38.0, 48.0, 48.0],
            [2434, 0.0, 4.0, 20.0, 36.0, 48.0, 48.0],
            [2500, 0.0, 0.0, 14.0, 32.0, 48.0, 50.0],
            [2502, 0.0, 0.0, 14.0, 32.0, 48.0, 50.0],
            [2568, 0.0, 0.0, 10.0, 26.0, 42.0, 52.0],
            [2570, 0.0, 0.0, 10.0, 26.0, 42.0, 52.0],
            [2636, 0.0, 0.0, 4.0, 20.0, 38.0, 52.0],
            [2638, 0.0, 0.0, 4.0, 20.0, 38.0, 52.0],
            [3102, 0.0, 0.0, 0.0, 0.0, 0.0, 16.0],
            [3104, 0.0, 0.0, 0.0, 0.0, 0.0, 16.0],
            [3310, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0],
            [3312, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0],
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
        $earning->date = new \DateTime('2026-10-10');
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
            [1814, 0.0, 0.0, 0.0, 0.0, 0.0],
            [1816, 0.0, 0.0, 0.0, 0.0, 0.0],
            [2040, 12.0, 12.0, 12.0, 12.0, 12.0],
            [2042, 12.0, 12.0, 12.0, 12.0, 12.0],
            [2268, 22.0, 22.0, 22.0, 22.0, 22.0],
            [2270, 8.0, 16.0, 22.0, 22.0, 22.0],
            [2308, 6.0, 16.0, 24.0, 24.0, 24.0],
            [2310, 6.0, 16.0, 24.0, 24.0, 24.0],
            [2348, 6.0, 14.0, 22.0, 24.0, 24.0],
            [2350, 6.0, 14.0, 22.0, 24.0, 24.0],
            [2388, 4.0, 12.0, 20.0, 24.0, 24.0],
            [2390, 4.0, 12.0, 20.0, 24.0, 24.0],
            [2428, 2.0, 10.0, 18.0, 24.0, 24.0],
            [2430, 2.0, 10.0, 18.0, 24.0, 24.0],
            [2468, 0.0, 8.0, 18.0, 24.0, 24.0],
            [2470, 0.0, 8.0, 16.0, 24.0, 24.0],
            [2508, 0.0, 8.0, 16.0, 24.0, 26.0],
            [2510, 0.0, 8.0, 16.0, 24.0, 26.0],
            [2548, 0.0, 6.0, 14.0, 22.0, 26.0],
            [2550, 0.0, 6.0, 14.0, 22.0, 26.0],
            [2588, 0.0, 4.0, 12.0, 20.0, 26.0],
            [2590, 0.0, 4.0, 12.0, 20.0, 26.0],
            [2628, 0.0, 2.0, 10.0, 20.0, 26.0],
            [2630, 0.0, 2.0, 10.0, 18.0, 26.0],
            [2668, 0.0, 0.0, 10.0, 18.0, 26.0],
            [2670, 0.0, 0.0, 8.0, 18.0, 26.0],
            [2708, 0.0, 0.0, 8.0, 16.0, 24.0],
            [2710, 0.0, 0.0, 8.0, 16.0, 24.0],
            [2748, 0.0, 0.0, 6.0, 14.0, 22.0],
            [2750, 0.0, 0.0, 6.0, 14.0, 22.0],
            [2788, 0.0, 0.0, 4.0, 12.0, 20.0],
            [2790, 0.0, 0.0, 4.0, 12.0, 20.0],
            [2828, 0.0, 0.0, 2.0, 12.0, 20.0],
            [2830, 0.0, 0.0, 2.0, 10.0, 20.0],
            [2868, 0.0, 0.0, 2.0, 10.0, 18.0],
            [2870, 0.0, 0.0, 0.0, 10.0, 18.0],
            [2908, 0.0, 0.0, 0.0, 8.0, 16.0],
            [2910, 0.0, 0.0, 0.0, 8.0, 16.0],
            [2948, 0.0, 0.0, 0.0, 6.0, 14.0],
            [2950, 0.0, 0.0, 0.0, 6.0, 14.0],
            [2988, 0.0, 0.0, 0.0, 4.0, 12.0],
            [2990, 0.0, 0.0, 0.0, 4.0, 12.0],
            [3028, 0.0, 0.0, 0.0, 4.0, 12.0],
            [3030, 0.0, 0.0, 0.0, 2.0, 12.0],
            [3102, 0.0, 0.0, 0.0, 0.0, 8.0],
            [3104, 0.0, 0.0, 0.0, 0.0, 8.0],
            [3310, 0.0, 0.0, 0.0, 0.0, 0.0],
            [3312, 0.0, 0.0, 0.0, 0.0, 0.0],
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
        $earning->date = new \DateTime('2026-10-10');
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
            [2327.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0],
            [2331.33, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0],
            [2617.33, 30.0, 30.0, 30.0, 30.0, 30.0, 30.0],
            [2621.67, 30.0, 30.0, 30.0, 30.0, 30.0, 30.0],
            [2912.0, 56.0, 56.0, 56.0, 56.0, 56.0, 56.0],
            [2916.33, 56.0, 56.0, 56.0, 56.0, 56.0, 56.0],
            [3059.33, 61.0, 61.0, 61.0, 61.0, 61.0, 61.0],
            [3063.67, 61.0, 61.0, 61.0, 61.0, 61.0, 61.0],
            [3206.67, 65.0, 65.0, 65.0, 65.0, 65.0, 65.0],
            [3211.0, 65.0, 65.0, 65.0, 65.0, 65.0, 65.0],
            [3354.0, 65.0, 65.0, 65.0, 65.0, 65.0, 65.0],
            [3358.33, 69.0, 69.0, 69.0, 69.0, 69.0, 69.0],
            [3501.33, 69.0, 69.0, 69.0, 69.0, 69.0, 69.0],
            [3505.67, 69.0, 69.0, 69.0, 69.0, 69.0, 69.0],
            [3648.67, 74.0, 74.0, 74.0, 74.0, 74.0, 74.0],
            [3653.0, 74.0, 74.0, 74.0, 74.0, 74.0, 74.0],
            [3796.0, 78.0, 78.0, 78.0, 78.0, 78.0, 78.0],
            [3800.33, 78.0, 78.0, 78.0, 78.0, 78.0, 78.0],
            [3943.33, 78.0, 78.0, 78.0, 78.0, 78.0, 78.0],
            [3947.67, 78.0, 78.0, 78.0, 78.0, 78.0, 78.0],
            [4090.67, 65.0, 82.0, 82.0, 82.0, 82.0, 82.0],
            [4095.0, 65.0, 82.0, 82.0, 82.0, 82.0, 82.0],
            [4238.0, 56.0, 87.0, 87.0, 87.0, 87.0, 87.0],
            [4242.33, 52.0, 87.0, 87.0, 87.0, 87.0, 87.0],
            [4385.33, 43.0, 78.0, 87.0, 87.0, 87.0, 87.0],
            [4389.67, 43.0, 78.0, 87.0, 87.0, 87.0, 87.0],
            [4532.67, 30.0, 65.0, 91.0, 91.0, 91.0, 91.0],
            [4537.0, 30.0, 65.0, 91.0, 91.0, 91.0, 91.0],
            [4680.0, 17.0, 56.0, 91.0, 95.0, 95.0, 95.0],
            [4684.33, 17.0, 56.0, 91.0, 95.0, 95.0, 95.0],
            [4827.33, 9.0, 43.0, 78.0, 95.0, 95.0, 95.0],
            [4831.67, 9.0, 43.0, 78.0, 95.0, 95.0, 95.0],
            [4974.67, 0.0, 30.0, 69.0, 100.0, 100.0, 100.0],
            [4979.0, 0.0, 30.0, 69.0, 100.0, 100.0, 100.0],
            [5122.0, 0.0, 22.0, 56.0, 91.0, 104.0, 104.0],
            [5126.33, 0.0, 17.0, 56.0, 91.0, 104.0, 104.0],
            [5269.33, 0.0, 9.0, 43.0, 82.0, 104.0, 104.0],
            [5273.67, 0.0, 9.0, 43.0, 78.0, 104.0, 104.0],
            [5416.67, 0.0, 0.0, 30.0, 69.0, 104.0, 108.0],
            [5421.0, 0.0, 0.0, 30.0, 69.0, 104.0, 108.0],
            [5564.0, 0.0, 0.0, 22.0, 56.0, 91.0, 113.0],
            [5568.33, 0.0, 0.0, 22.0, 56.0, 91.0, 113.0],
            [5711.33, 0.0, 0.0, 9.0, 43.0, 82.0, 113.0],
            [5715.67, 0.0, 0.0, 9.0, 43.0, 82.0, 113.0],
            [6721.0, 0.0, 0.0, 0.0, 0.0, 0.0, 35.0],
            [6725.33, 0.0, 0.0, 0.0, 0.0, 0.0, 35.0],
            [7171.67, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0],
            [7176.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0],
        ];
    }

    /**
     * @dataProvider monthlyDataScale6
     */
    public function testMonthlyAdjustmentScale6(
        float $gross,
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
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_HALF;

        $earning = new Earning();
        $earning->date = new \DateTime('2026-10-10');
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
    public static function monthlyDataScale6(): array
    {
        return [
            [3930.33, 0.0, 0.0, 0.0, 0.0, 0.0],
            [3934.67, 0.0, 0.0, 0.0, 0.0, 0.0],
            [4420.0, 26.0, 26.0, 26.0, 26.0, 26.0],
            [4424.33, 26.0, 26.0, 26.0, 26.0, 26.0],
            [4914.0, 48.0, 48.0, 48.0, 48.0, 48.0],
            [4918.33, 17.0, 35.0, 48.0, 48.0, 48.0],
            [5000.67, 13.0, 35.0, 52.0, 52.0, 52.0],
            [5005.0, 13.0, 35.0, 52.0, 52.0, 52.0],
            [5087.33, 13.0, 30.0, 48.0, 52.0, 52.0],
            [5091.67, 13.0, 30.0, 48.0, 52.0, 52.0],
            [5174.0, 9.0, 26.0, 43.0, 52.0, 52.0],
            [5178.33, 9.0, 26.0, 43.0, 52.0, 52.0],
            [5260.67, 4.0, 22.0, 39.0, 52.0, 52.0],
            [5265.0, 4.0, 22.0, 39.0, 52.0, 52.0],
            [5347.33, 0.0, 17.0, 39.0, 52.0, 52.0],
            [5351.67, 0.0, 17.0, 35.0, 52.0, 52.0],
            [5434.0, 0.0, 17.0, 35.0, 52.0, 56.0],
            [5438.33, 0.0, 17.0, 35.0, 52.0, 56.0],
            [5520.67, 0.0, 13.0, 30.0, 48.0, 56.0],
            [5525.0, 0.0, 13.0, 30.0, 48.0, 56.0],
            [5607.33, 0.0, 9.0, 26.0, 43.0, 56.0],
            [5611.67, 0.0, 9.0, 26.0, 43.0, 56.0],
            [5694.0, 0.0, 4.0, 22.0, 43.0, 56.0],
            [5698.33, 0.0, 4.0, 22.0, 39.0, 56.0],
            [5780.67, 0.0, 0.0, 22.0, 39.0, 56.0],
            [5785.0, 0.0, 0.0, 17.0, 39.0, 56.0],
            [5867.33, 0.0, 0.0, 17.0, 35.0, 52.0],
            [5871.67, 0.0, 0.0, 17.0, 35.0, 52.0],
            [5954.0, 0.0, 0.0, 13.0, 30.0, 48.0],
            [5958.33, 0.0, 0.0, 13.0, 30.0, 48.0],
            [6040.67, 0.0, 0.0, 9.0, 26.0, 43.0],
            [6045.0, 0.0, 0.0, 9.0, 26.0, 43.0],
            [6127.33, 0.0, 0.0, 4.0, 26.0, 43.0],
            [6131.67, 0.0, 0.0, 4.0, 22.0, 43.0],
            [6214.0, 0.0, 0.0, 4.0, 22.0, 39.0],
            [6218.33, 0.0, 0.0, 0.0, 22.0, 39.0],
            [6300.67, 0.0, 0.0, 0.0, 17.0, 35.0],
            [6305.0, 0.0, 0.0, 0.0, 17.0, 35.0],
            [6387.33, 0.0, 0.0, 0.0, 13.0, 30.0],
            [6391.67, 0.0, 0.0, 0.0, 13.0, 30.0],
            [6474.0, 0.0, 0.0, 0.0, 9.0, 26.0],
            [6478.33, 0.0, 0.0, 0.0, 9.0, 26.0],
            [6560.67, 0.0, 0.0, 0.0, 9.0, 26.0],
            [6565.0, 0.0, 0.0, 0.0, 4.0, 26.0],
            [6721.0, 0.0, 0.0, 0.0, 0.0, 17.0],
            [6725.33, 0.0, 0.0, 0.0, 0.0, 17.0],
            [7171.67, 0.0, 0.0, 0.0, 0.0, 0.0],
            [7176.0, 0.0, 0.0, 0.0, 0.0, 0.0],
        ];
    }
}
