<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\Tests\TaxScales\July2026;

use ManageIt\PaygTax\PaygTax;
use ManageIt\PaygTax\Tests\Fixtures\Earning;
use ManageIt\PaygTax\Tests\Fixtures\Payee;
use ManageIt\PaygTax\Tests\Fixtures\Payer;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;

#[\PHPUnit\Framework\Attributes\CoversClass(\ManageIt\PaygTax\TaxScales\Nat4466::class)]
final class Nat4466Test extends TestCase
{
    #[\PHPUnit\Framework\Attributes\DataProvider('weeklyData')]
    public function testWeeklyWithholding(
        int $gross,
        float $scale1,
        float $scale2,
        float $scale3,
        float $scale1MLExempt,
        float $scale2MLExempt,
        float $scale3MLExempt,
        float $scale1MLHalf,
        float $scale2MLHalf,
        float $scale3MLHalf,
    ): void {
        $payer = new Payer();

        $payee = new Payee();
        $payee->payCycle = Payee::PAY_CYCLE_WEEKLY;
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;
        $payee->stsl = false;
        $payee->seniorsOffset = Payee::SENIORS_OFFSET_SINGLE;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_NONE;

        $earning = new Earning();
        $earning->date = new \DateTime('2026-10-15');
        $earning->gross = $gross;

        // Scale 1 - Single

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($scale1, $payg->getTaxWithheldAmount());

        // Scale 2 - Illness Separated

        $payee->seniorsOffset = Payee::SENIORS_OFFSET_ILLNESS_SEPARATED;

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($scale2, $payg->getTaxWithheldAmount());

        // Scale 3 - Member of a couple

        $payee->seniorsOffset = Payee::SENIORS_OFFSET_COUPLE;

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($scale3, $payg->getTaxWithheldAmount());

        // Scale 1 - Single (Full Medicare Levy Exemption)

        $payee->seniorsOffset = Payee::SENIORS_OFFSET_SINGLE;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_FULL;

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($scale1MLExempt, $payg->getTaxWithheldAmount());

        // Scale 2 - Illness Separated (Full Medicare Levy Exemption)

        $payee->seniorsOffset = Payee::SENIORS_OFFSET_ILLNESS_SEPARATED;

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($scale2MLExempt, $payg->getTaxWithheldAmount());

        // Scale 3 - Member of a couple (Full Medicare Levy Exemption)

        $payee->seniorsOffset = Payee::SENIORS_OFFSET_COUPLE;

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($scale3MLExempt, $payg->getTaxWithheldAmount());

        // Scale 1 - Single (Half Medicare Levy Exemption)

        $payee->seniorsOffset = Payee::SENIORS_OFFSET_SINGLE;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_HALF;

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($scale1MLHalf, $payg->getTaxWithheldAmount());

        // Scale 2 - Illness Separated (Half Medicare Levy Exemption)

        $payee->seniorsOffset = Payee::SENIORS_OFFSET_ILLNESS_SEPARATED;

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($scale2MLHalf, $payg->getTaxWithheldAmount());

        // Scale 3 - Member of a couple (Half Medicare Levy Exemption)

        $payee->seniorsOffset = Payee::SENIORS_OFFSET_COUPLE;

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($scale3MLHalf, $payg->getTaxWithheldAmount());
    }

    /**
     * @return \Iterator<int, array<int, (float | int)>>
     */
    public static function weeklyData(): \Iterator
    {
        yield [566, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0];
        yield [567, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0];
        yield [590, 0.0, 0.0, 3.0, 0.0, 0.0, 3.0, 0.0, 0.0, 3.0];
        yield [611, 0.0, 0.0, 7.0, 0.0, 0.0, 7.0, 0.0, 0.0, 7.0];
        yield [612, 0.0, 0.0, 7.0, 0.0, 0.0, 7.0, 0.0, 0.0, 7.0];
        yield [618, 0.0, 0.0, 9.0, 0.0, 0.0, 9.0, 0.0, 0.0, 9.0];
        yield [622, 0.0, 0.0, 10.0, 0.0, 0.0, 10.0, 0.0, 0.0, 10.0];
        yield [623, 0.0, 0.0, 10.0, 0.0, 0.0, 10.0, 0.0, 0.0, 10.0];
        yield [636, 0.0, 2.0, 13.0, 0.0, 2.0, 13.0, 0.0, 2.0, 13.0];
        yield [647, 0.0, 4.0, 16.0, 0.0, 4.0, 16.0, 0.0, 4.0, 16.0];
        yield [648, 0.0, 4.0, 17.0, 0.0, 4.0, 17.0, 0.0, 4.0, 17.0];
        yield [658, 2.0, 5.0, 20.0, 2.0, 5.0, 20.0, 2.0, 5.0, 20.0];
        yield [667, 3.0, 7.0, 22.0, 3.0, 7.0, 22.0, 3.0, 7.0, 22.0];
        yield [668, 3.0, 7.0, 22.0, 3.0, 7.0, 22.0, 3.0, 7.0, 22.0];
        yield [680, 5.0, 10.0, 26.0, 5.0, 10.0, 26.0, 5.0, 10.0, 26.0];
        yield [691, 7.0, 13.0, 29.0, 7.0, 13.0, 29.0, 7.0, 13.0, 29.0];
        yield [692, 7.0, 13.0, 29.0, 7.0, 13.0, 29.0, 7.0, 13.0, 29.0];
        yield [706, 11.0, 17.0, 33.0, 11.0, 17.0, 33.0, 11.0, 17.0, 33.0];
        yield [720, 14.0, 21.0, 37.0, 14.0, 21.0, 37.0, 14.0, 21.0, 37.0];
        yield [721, 15.0, 21.0, 37.0, 15.0, 21.0, 37.0, 15.0, 21.0, 37.0];
        yield [786, 33.0, 40.0, 55.0, 33.0, 40.0, 55.0, 33.0, 40.0, 55.0];
        yield [850, 51.0, 58.0, 73.0, 51.0, 58.0, 73.0, 51.0, 58.0, 73.0];
        yield [851, 52.0, 58.0, 74.0, 52.0, 58.0, 74.0, 52.0, 58.0, 74.0];
        yield [854, 53.0, 60.0, 75.0, 52.0, 59.0, 75.0, 52.0, 59.0, 75.0];
        yield [857, 54.0, 61.0, 76.0, 53.0, 60.0, 75.0, 53.0, 60.0, 75.0];
        yield [858, 54.0, 61.0, 77.0, 54.0, 60.0, 76.0, 54.0, 60.0, 76.0];
        yield [862, 56.0, 63.0, 78.0, 55.0, 61.0, 76.0, 55.0, 61.0, 76.0];
        yield [864, 57.0, 63.0, 78.0, 55.0, 62.0, 77.0, 55.0, 62.0, 77.0];
        yield [865, 57.0, 64.0, 78.0, 56.0, 62.0, 77.0, 56.0, 62.0, 77.0];
        yield [924, 88.0, 95.0, 102.0, 81.0, 88.0, 95.0, 81.0, 88.0, 95.0];
        yield [981, 118.0, 125.0, 125.0, 105.0, 112.0, 112.0, 105.0, 112.0, 112.0];
        yield [982, 119.0, 126.0, 126.0, 106.0, 112.0, 112.0, 106.0, 112.0, 112.0];
        yield [1009, 133.0, 136.0, 136.0, 117.0, 121.0, 121.0, 117.0, 121.0, 121.0];
        yield [1035, 147.0, 147.0, 147.0, 128.0, 128.0, 128.0, 128.0, 128.0, 128.0];
        yield [1036, 147.0, 147.0, 147.0, 129.0, 129.0, 129.0, 129.0, 129.0, 129.0];
        yield [1050, 153.0, 153.0, 153.0, 133.0, 133.0, 133.0, 133.0, 133.0, 133.0];
        yield [1063, 158.0, 158.0, 158.0, 137.0, 137.0, 137.0, 137.0, 137.0, 137.0];
        yield [1064, 158.0, 158.0, 158.0, 137.0, 137.0, 137.0, 137.0, 137.0, 137.0];
        yield [1124, 178.0, 178.0, 178.0, 155.0, 155.0, 155.0, 155.0, 155.0, 155.0];
        yield [1184, 197.0, 197.0, 197.0, 174.0, 174.0, 174.0, 174.0, 174.0, 174.0];
        yield [1185, 198.0, 198.0, 198.0, 174.0, 174.0, 174.0, 174.0, 174.0, 174.0];
        yield [1234, 213.0, 213.0, 213.0, 189.0, 189.0, 189.0, 191.0, 191.0, 191.0];
        yield [1281, 229.0, 229.0, 229.0, 203.0, 203.0, 203.0, 208.0, 208.0, 208.0];
        yield [1282, 229.0, 229.0, 229.0, 203.0, 203.0, 203.0, 208.0, 208.0, 208.0];
        yield [1382, 261.0, 261.0, 261.0, 233.0, 233.0, 233.0, 243.0, 243.0, 243.0];
        yield [1480, 292.0, 292.0, 292.0, 263.0, 263.0, 263.0, 277.0, 277.0, 277.0];
        yield [1481, 293.0, 293.0, 293.0, 263.0, 263.0, 263.0, 278.0, 278.0, 278.0];
        yield [2038, 471.0, 471.0, 471.0, 430.0, 430.0, 430.0, 450.0, 450.0, 450.0];
        yield [2595, 649.0, 649.0, 649.0, 597.0, 597.0, 597.0, 623.0, 623.0, 623.0];
        yield [2596, 649.0, 649.0, 649.0, 597.0, 597.0, 597.0, 623.0, 623.0, 623.0];
        yield [3124, 855.0, 855.0, 855.0, 793.0, 793.0, 793.0, 824.0, 824.0, 824.0];
        yield [3652, 1061.0, 1061.0, 1061.0, 988.0, 988.0, 988.0, 1025.0, 1025.0, 1025.0];
        yield [3653, 1062.0, 1062.0, 1062.0, 989.0, 989.0, 989.0, 1025.0, 1025.0, 1025.0];
    }
}
