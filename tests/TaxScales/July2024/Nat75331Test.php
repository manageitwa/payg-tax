<?php

namespace ManageIt\PaygTax\Tests\TaxScales\July2024;

use ManageIt\PaygTax\PaygTax;
use ManageIt\PaygTax\TaxScales\Nat75331;
use ManageIt\PaygTax\Tests\Fixtures\Earning;
use ManageIt\PaygTax\Tests\Fixtures\Payee;
use ManageIt\PaygTax\Tests\Fixtures\Payer;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;

/**
 * @covers \ManageIt\PaygTax\TaxScales\Nat75331
 */
class Nat75331Test extends TestCase
{
    protected Nat75331 $scale;

    public function setUp(): void
    {
        $this->scale = new Nat75331();
    }

    /**
     * @dataProvider weeklyData
     */
    public function testWeeklyWithholding(
        int $gross,
        float $incomeBracket1, // <= 45000
        float $incomeBracket2, // <= 135000
        float $incomeBracket3, // <= 190000
        float $incomeBracket4, // > 190000
        float $noTfn, // No TFN
        float $payerNotRegistered // Payer not registered as WHM employer
    ): void {
        $payer = new Payer();
        $payer->whmEmployer = true;

        $payee = new Payee();
        $payee->payCycle = Payee::PAY_CYCLE_WEEKLY;
        $payee->residencyStatus = Payee::WORKING_HOLIDAY_MAKER;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;
        $payee->stsl = false;
        $payee->ytdGross = 450;

        $earning = new Earning();
        $earning->date = new \DateTime('2024-10-15');
        $earning->gross = $gross;

        // Scale 1 - Income Bracket 1 (less than or equal to $45,000)

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($incomeBracket1, $payg->getTaxWithheldAmount());

        // Scale 2 - Income Bracket 2 (more than $45,000, less than or equal to $135,000)

        $payee->ytdGross = 45020;

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($incomeBracket2, $payg->getTaxWithheldAmount());

        // Scale 3 - Income Bracket 3 (more than $135,000, less than or equal to $190,000)

        $payee->ytdGross = 135020;

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($incomeBracket3, $payg->getTaxWithheldAmount());

        // Scale 4 - Income Bracket 4 (more than $190,000)

        $payee->ytdGross = 190020;

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

        // Scale 6 - Payer not registered as WHM employer

        $payer->whmEmployer = false;
        $payee->ytdGross = 450;
        $payee->tfn = true;

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($payerNotRegistered, $payg->getTaxWithheldAmount());
    }

    /**
     * @return array<int, array<int|float, int|float>>
     */
    public function weeklyData(): array
    {
        return [
            [90, 14, 27, 33, 40, 40, 27],
            [172, 26, 52, 64, 77, 77, 52],
            [325, 49, 98, 120, 146, 146, 97],
            [449, 67, 135, 166, 202, 202, 135],
            [661, 99, 198, 245, 297, 297, 198],
            [820, 123, 246, 303, 369, 369, 246],
            [1024, 154, 307, 379, 460, 460, 307],
            [1273, 191, 382, 471, 572, 572, 382],
            [1559, 234, 468, 577, 701, 701, 468],
            [1888, 283, 566, 699, 849, 849, 566],
            [2033, 305, 610, 752, 914, 914, 610],
        ];
    }
}
