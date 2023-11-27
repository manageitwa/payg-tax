<?php

namespace ManageIt\PaygTax\Tests\TaxScales\October2020;

use ManageIt\PaygTax\PaygTax;
use ManageIt\PaygTax\TaxScales\October2020\Nat75331;
use ManageIt\PaygTax\Tests\Fixtures\Earning;
use ManageIt\PaygTax\Tests\Fixtures\Payee;
use ManageIt\PaygTax\Tests\Fixtures\Payer;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;

/**
 * @covers \ManageIt\PaygTax\TaxScales\October2020\Nat75331
 */
class Nat75331Test extends TestCase
{
    protected Nat75331 $scale;

    public function setUp(): void
    {
        $this->scale = new Nat75331();
    }

    public function testEligibility(): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->residencyStatus = Payee::WORKING_HOLIDAY_MAKER;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;
        $payee->stsl = true;
        $payee->seniorsOffset = Payee::SENIORS_OFFSET_SINGLE;

        $earning = new Earning();
        $earning->date = new \DateTime('2022-10-10');

        Assert::assertTrue($this->scale->isEligible($payer, $payee, $earning));

        $payee->residencyStatus = Payee::FOREIGN_RESIDENT;
        Assert::assertFalse($this->scale->isEligible($payer, $payee, $earning));

        $payee->residencyStatus = Payee::RESIDENT;
        Assert::assertFalse($this->scale->isEligible($payer, $payee, $earning));

        $payee->residencyStatus = Payee::WORKING_HOLIDAY_MAKER;
        $payee->tfn = false;
        Assert::assertTrue($this->scale->isEligible($payer, $payee, $earning));

        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = true;
        Assert::assertTrue($this->scale->isEligible($payer, $payee, $earning));

        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_FULL;
        Assert::assertTrue($this->scale->isEligible($payer, $payee, $earning));

        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_HALF;
        Assert::assertTrue($this->scale->isEligible($payer, $payee, $earning));

        $payee->seniorsOffset = Payee::SENIORS_OFFSET_COUPLE;
        Assert::assertTrue($this->scale->isEligible($payer, $payee, $earning));

        $payee->seniorsOffset = Payee::SENIORS_OFFSET_ILLNESS_SEPARATED;
        Assert::assertTrue($this->scale->isEligible($payer, $payee, $earning));

        $payee->seniorsOffset = Payee::SENIORS_OFFSET_NONE;
        Assert::assertTrue($this->scale->isEligible($payer, $payee, $earning));

        $earning->date = new \DateTime('2019-08-01');
        Assert::assertFalse($this->scale->isEligible($payer, $payee, $earning));
    }

    /**
     * @dataProvider weeklyData
     */
    public function testWeeklyWithholding(
        int $gross,
        float $incomeBracket1, // <= 45000
        float $incomeBracket2, // <= 120000
        float $incomeBracket3, // <= 180000
        float $incomeBracket4, // > 180000
        float $noTfn, // No TFN
    ): void {
        $payer = new Payer();

        $payee = new Payee();
        $payee->payCycle = Payee::PAY_CYCLE_WEEKLY;
        $payee->residencyStatus = Payee::WORKING_HOLIDAY_MAKER;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;
        $payee->stsl = false;

        $earning = new Earning();
        $earning->date = new \DateTime('2022-10-10');
        $earning->gross = $gross;

        // Scale 1 - Income Bracket 1 (less than or equal to $45,000)

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($incomeBracket1, $payg->getTaxWithheldAmount());

        // Scale 2 - Income Bracket 2 (more than $45,000, less than or equal to $120,000)

        $payee->ytdGross = 45020;

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($incomeBracket2, $payg->getTaxWithheldAmount());

        // Scale 3 - Income Bracket 3 (more than $120,000, less than or equal to $180,000)

        $payee->ytdGross = 120020;

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($incomeBracket3, $payg->getTaxWithheldAmount());

        // Scale 4 - Income Bracket 4 (more than $180,000)

        $payee->ytdGross = 180020;

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($incomeBracket4, $payg->getTaxWithheldAmount());

        // Scale 5 - No TFN Provided

        $payee->ytdGross = 450;
        $payee->tfn = false;

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($noTfn, $payg->getTaxWithheldAmount());
    }

    /**
     * @return array<int, array<int|float, int|float>>
     */
    public function weeklyData(): array
    {
        return [
            [90, 14, 29, 33, 40, 40],
            [172, 26, 56, 64, 77, 77],
            [325, 49, 106, 120, 146, 146],
            [449, 67, 146, 166, 202, 202],
            [661, 99, 215, 245, 297, 297],
            [820, 123, 267, 303, 369, 369],
            [1024, 154, 333, 379, 460, 460],
            [1273, 191, 414, 471, 572, 572],
            [1559, 234, 507, 577, 701, 701],
            [1888, 283, 614, 699, 849, 849],
            [2033, 305, 661, 752, 914, 914],
        ];
    }
}
