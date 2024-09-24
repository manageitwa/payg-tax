<?php

namespace ManageIt\PaygTax\Tests\TaxScales\July2024;

use ManageIt\PaygTax\PaygTax;
use ManageIt\PaygTax\TaxScales\July2024\Nat1004Scale5;
use ManageIt\PaygTax\Tests\Fixtures\Earning;
use ManageIt\PaygTax\Tests\Fixtures\Payee;
use ManageIt\PaygTax\Tests\Fixtures\Payer;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;

/**
 * @covers \ManageIt\PaygTax\TaxScales\July2024\Nat1004Scale5
 */
class Nat1004Scale5Test extends TestCase
{
    protected Nat1004Scale5 $scale;

    public function setUp(): void
    {
        $this->scale = new Nat1004Scale5();
    }

    public function testEligibility(): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = true;
        $payee->stsl = false;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_FULL;

        $earning = new Earning();
        $earning->date = new \DateTime('2024-10-15');

        Assert::assertTrue($this->scale->isEligible($payer, $payee, $earning));

        $payee->residencyStatus = Payee::FOREIGN_RESIDENT;
        Assert::assertFalse($this->scale->isEligible($payer, $payee, $earning));

        $payee->residencyStatus = Payee::WORKING_HOLIDAY_MAKER;
        Assert::assertFalse($this->scale->isEligible($payer, $payee, $earning));

        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = false;
        Assert::assertFalse($this->scale->isEligible($payer, $payee, $earning));

        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;
        Assert::assertFalse($this->scale->isEligible($payer, $payee, $earning));

        $payee->claimsTaxFreeThreshold = true;
        $payee->stsl = true;
        Assert::assertFalse($this->scale->isEligible($payer, $payee, $earning));

        $payee->stsl = false;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_HALF;
        Assert::assertFalse($this->scale->isEligible($payer, $payee, $earning));

        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_FULL;
        $payee->seniorsOffset = Payee::SENIORS_OFFSET_COUPLE;
        Assert::assertFalse($this->scale->isEligible($payer, $payee, $earning));

        $payee->seniorsOffset = Payee::SENIORS_OFFSET_NONE;
        Assert::assertTrue($this->scale->isEligible($payer, $payee, $earning));

        $earning->date = new \DateTime('2019-08-01');
        Assert::assertFalse($this->scale->isEligible($payer, $payee, $earning));
    }

    /**
     * @dataProvider weeklyData
     */
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

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($withheld, $payg->getTaxWithheldAmount());
    }

    /**
     * @return array<int, array<int|float, int|float>>
     */
    public function weeklyData(): array
    {
        return [
            [116, 0],
            [117, 0],
            [149, 0],
            [150, 0],
            [249, 0],
            [250, 0],
            [360, 0],
            [361, 0],
            [370, 2],
            [371, 2],
            [499, 22],
            [500, 22],
            [514, 25],
            [515, 25],
            [624, 42],
            [625, 42],
            [720, 58],
            [721, 58],
            [842, 78],
            [843, 78],
            [864, 82],
            [865, 82],
            [931, 102],
            [932, 102],
            [1052, 139],
            [1053, 139],
            [1281, 208],
            [1282, 208],
            [1844, 377],
            [1845, 377],
            [2119, 459],
            [2120, 460],
            [2245, 497],
            [2246, 498],
            [2490, 571],
            [2491, 571],
            [2595, 602],
            [2596, 603],
            [2652, 623],
            [2653, 624],
            [2736, 654],
            [2737, 655],
            [2898, 714],
            [2899, 715],
            [3302, 864],
            [3303, 864],
            [3652, 993],
            [3653, 994],
        ];
    }

    /**
     * @dataProvider fortnightlyData
     */
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

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($withheld, $payg->getTaxWithheldAmount());
    }

    /**
     * @return array<int, array<int|float, int|float>>
     */
    public function fortnightlyData(): array
    {
        return [
            [232, 0],
            [234, 0],
            [298, 0],
            [300, 0],
            [498, 0],
            [500, 0],
            [720, 0],
            [722, 0],
            [740, 4],
            [742, 4],
            [998, 44],
            [1000, 44],
            [1028, 50],
            [1030, 50],
            [1248, 84],
            [1250, 84],
            [1440, 116],
            [1442, 116],
            [1684, 156],
            [1686, 156],
            [1728, 164],
            [1730, 164],
            [1862, 204],
            [1864, 204],
            [2104, 278],
            [2106, 278],
            [2562, 416],
            [2564, 416],
            [3688, 754],
            [3690, 754],
            [4238, 918],
            [4240, 920],
            [4490, 994],
            [4492, 996],
            [4980, 1142],
            [4982, 1142],
            [5190, 1204],
            [5192, 1206],
            [5304, 1246],
            [5306, 1248],
            [5472, 1308],
            [5474, 1310],
            [5796, 1428],
            [5798, 1430],
            [6604, 1728],
            [6606, 1728],
            [7304, 1986],
            [7306, 1988],
        ];
    }

    /**
     * @dataProvider monthlyData
     */
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

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($withheld, $payg->getTaxWithheldAmount());
    }

    /**
     * @return array<int, array<int|float, int|float>>
     */
    public function monthlyData(): array
    {
        return [
            [502.67, 0],
            [507.00, 0],
            [645.67, 0],
            [650.00, 0],
            [1079.00, 0],
            [1083.33, 0],
            [1560.00, 0],
            [1564.33, 0],
            [1603.33, 9],
            [1607.67, 9],
            [2162.33, 95],
            [2166.67, 95],
            [2227.33, 108],
            [2231.67, 108],
            [2704.00, 182],
            [2708.33, 182],
            [3120.00, 251],
            [3124.33, 251],
            [3648.67, 338],
            [3653.00, 338],
            [3744.00, 355],
            [3748.33, 355],
            [4034.33, 442],
            [4038.67, 442],
            [4558.67, 602],
            [4563.00, 602],
            [5551.00, 901],
            [5555.33, 901],
            [7990.67, 1634],
            [7995.00, 1634],
            [9182.33, 1989],
            [9186.67, 1993],
            [9728.33, 2154],
            [9732.67, 2158],
            [10790.00, 2474],
            [10794.33, 2474],
            [11245.00, 2609],
            [11249.33, 2613],
            [11492.00, 2700],
            [11496.33, 2704],
            [11856.00, 2834],
            [11860.33, 2838],
            [12558.00, 3094],
            [12562.33, 3098],
            [14308.67, 3744],
            [14313.00, 3744],
            [15825.33, 4303],
            [15829.67, 4307],
        ];
    }
}
