<?php

namespace ManageIt\PaygTax\Tests\TaxScales\July2024;

use ManageIt\PaygTax\PaygTax;
use ManageIt\PaygTax\TaxScales\July2024\Nat1004Scale1;
use ManageIt\PaygTax\Tests\Fixtures\Earning;
use ManageIt\PaygTax\Tests\Fixtures\Payee;
use ManageIt\PaygTax\Tests\Fixtures\Payer;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;

/**
 * @covers \ManageIt\PaygTax\TaxScales\July2024\Nat1004Scale1
 */
class Nat1004Scale1Test extends TestCase
{
    protected Nat1004Scale1 $scale;

    public function setUp(): void
    {
        $this->scale = new Nat1004Scale1();
    }

    public function testEligibility(): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;
        $payee->stsl = false;

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
        $payee->claimsTaxFreeThreshold = true;
        Assert::assertFalse($this->scale->isEligible($payer, $payee, $earning));

        $payee->claimsTaxFreeThreshold = false;
        $payee->stsl = true;
        Assert::assertFalse($this->scale->isEligible($payer, $payee, $earning));

        $payee->stsl = false;
        // A person claiming the levy exemption but not the tax free threshold cannot claim the exemption.
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_FULL;
        Assert::assertTrue($this->scale->isEligible($payer, $payee, $earning));

        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_NONE;
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
        $payee->claimsTaxFreeThreshold = false;
        $payee->stsl = false;

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
            [116, 19],
            [117, 19],
            [149, 24],
            [150, 24],
            [249, 45],
            [250, 45],
            [360, 69],
            [361, 69],
            [370, 71],
            [371, 71],
            [499, 95],
            [500, 95],
            [514, 98],
            [515, 98],
            [624, 133],
            [625, 134],
            [720, 164],
            [721, 165],
            [842, 204],
            [843, 204],
            [864, 211],
            [865, 211],
            [931, 233],
            [932, 233],
            [1052, 271],
            [1053, 272],
            [1281, 345],
            [1282, 345],
            [1844, 525],
            [1845, 525],
            [2119, 613],
            [2120, 613],
            [2245, 653],
            [2246, 653],
            [2490, 749],
            [2491, 749],
            [2595, 789],
            [2596, 790],
            [2652, 812],
            [2653, 812],
            [2736, 844],
            [2737, 845],
            [2898, 908],
            [2899, 908],
            [3302, 1065],
            [3303, 1066],
            [3652, 1230],
            [3653, 1230],
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
        $payee->claimsTaxFreeThreshold = false;
        $payee->stsl = false;

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
            [232, 38],
            [234, 38],
            [298, 48],
            [300, 48],
            [498, 90],
            [500, 90],
            [720, 138],
            [722, 138],
            [740, 142],
            [742, 142],
            [998, 190],
            [1000, 190],
            [1028, 196],
            [1030, 196],
            [1248, 266],
            [1250, 268],
            [1440, 328],
            [1442, 330],
            [1684, 408],
            [1686, 408],
            [1728, 422],
            [1730, 422],
            [1862, 466],
            [1864, 466],
            [2104, 542],
            [2106, 544],
            [2562, 690],
            [2564, 690],
            [3688, 1050],
            [3690, 1050],
            [4238, 1226],
            [4240, 1226],
            [4490, 1306],
            [4492, 1306],
            [4980, 1498],
            [4982, 1498],
            [5190, 1578],
            [5192, 1580],
            [5304, 1624],
            [5306, 1624],
            [5472, 1688],
            [5474, 1690],
            [5796, 1816],
            [5798, 1816],
            [6604, 2130],
            [6606, 2132],
            [7304, 2460],
            [7306, 2460],
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
        $payee->claimsTaxFreeThreshold = false;
        $payee->stsl = false;

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
            [502.67, 82],
            [507.00, 82],
            [645.67, 104],
            [650.00, 104],
            [1079.00, 195],
            [1083.33, 195],
            [1560.00, 299],
            [1564.33, 299],
            [1603.33, 308],
            [1607.67, 308],
            [2162.33, 412],
            [2166.67, 412],
            [2227.33, 425],
            [2231.67, 425],
            [2704.00, 576],
            [2708.33, 581],
            [3120.00, 711],
            [3124.33, 715],
            [3648.67, 884],
            [3653.00, 884],
            [3744.00, 914],
            [3748.33, 914],
            [4034.33, 1010],
            [4038.67, 1010],
            [4558.67, 1174],
            [4563.00, 1179],
            [5551.00, 1495],
            [5555.33, 1495],
            [7990.67, 2275],
            [7995.00, 2275],
            [9182.33, 2656],
            [9186.67, 2656],
            [9728.33, 2830],
            [9732.67, 2830],
            [10790.00, 3246],
            [10794.33, 3246],
            [11245.00, 3419],
            [11249.33, 3423],
            [11492.00, 3519],
            [11496.33, 3519],
            [11856.00, 3657],
            [11860.33, 3662],
            [12558.00, 3935],
            [12562.33, 3935],
            [14308.67, 4615],
            [14313.00, 4619],
            [15825.33, 5330],
            [15829.67, 5330],
        ];
    }
}
