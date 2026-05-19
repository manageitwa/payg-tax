<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\Tests\TaxScales\October2020;

use ManageIt\PaygTax\PaygTax;
use ManageIt\PaygTax\TaxScales\Nat75331;
use ManageIt\PaygTax\Tests\Fixtures\Earning;
use ManageIt\PaygTax\Tests\Fixtures\Payee;
use ManageIt\PaygTax\Tests\Fixtures\Payer;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;

#[\PHPUnit\Framework\Attributes\CoversClass(\ManageIt\PaygTax\TaxScales\Nat75331::class)]
final class Nat75331Test extends TestCase
{
    public function setUp(): void
    {
        $scale = new Nat75331();
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('weeklyData')]
    public function testWeeklyWithholding(
        int $gross,
        float $incomeBracket1, // <= 45000
        float $incomeBracket2, // <= 120000
        float $incomeBracket3, // <= 180000
        float $incomeBracket4, // > 180000
        float $noTfn, // No TFN
        float $payerNotRegistered, // Payer not registered as WHM employer
    ): void {
        $payer = new Payer();
        $payer->whmEmployer = true;

        $payee = new Payee();
        $payee->payCycle = Payee::PAY_CYCLE_WEEKLY;
        $payee->residencyStatus = Payee::WORKING_HOLIDAY_MAKER;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;
        $payee->stsl = false;

        $earning = new Earning();
        $earning->date = new \DateTime('2022-10-15');
        $earning->gross = $gross;

        // Scale 1 - Income Bracket 1 (less than or equal to $45,000)

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($incomeBracket1, $payg->getTaxWithheldAmount());

        // Scale 2 - Income Bracket 2 (more than $45,000, less than or equal to $120,000)

        $payee->ytdGross = 45020;

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($incomeBracket2, $payg->getTaxWithheldAmount());

        // Scale 3 - Income Bracket 3 (more than $120,000, less than or equal to $180,000)

        $payee->ytdGross = 120020;

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($incomeBracket3, $payg->getTaxWithheldAmount());

        // Scale 4 - Income Bracket 4 (more than $180,000)

        $payee->ytdGross = 180020;

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($incomeBracket4, $payg->getTaxWithheldAmount());

        // Scale 5 - No TFN Provided

        $payee->ytdGross = 450;
        $payee->tfn = false;

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($noTfn, $payg->getTaxWithheldAmount());

        // Scale 6 - Payer not registered as WHM employer

        $payer->whmEmployer = false;
        $payee->ytdGross = 450;
        $payee->tfn = true;

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($payerNotRegistered, $payg->getTaxWithheldAmount());
    }

    /**
     * @return \Iterator<int, array<int, (float | int)>>
     */
    public static function weeklyData(): \Iterator
    {
        yield [90, 14, 29, 33, 40, 40, 29];
        yield [172, 26, 56, 64, 77, 77, 56];
        yield [325, 49, 106, 120, 146, 146, 106];
        yield [449, 67, 146, 166, 202, 202, 146];
        yield [661, 99, 215, 245, 297, 297, 215];
        yield [820, 123, 267, 303, 369, 369, 266];
        yield [1024, 154, 333, 379, 460, 460, 333];
        yield [1273, 191, 414, 471, 572, 572, 414];
        yield [1559, 234, 507, 577, 701, 701, 507];
        yield [1888, 283, 614, 699, 849, 849, 614];
        yield [2033, 305, 661, 752, 914, 914, 661];
    }
}
