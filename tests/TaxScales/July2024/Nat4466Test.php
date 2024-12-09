<?php

namespace ManageIt\PaygTax\Tests\TaxScales\July2024;

use ManageIt\PaygTax\PaygTax;
use ManageIt\PaygTax\Tests\Fixtures\Earning;
use ManageIt\PaygTax\Tests\Fixtures\Payee;
use ManageIt\PaygTax\Tests\Fixtures\Payer;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;

/**
 * @covers \ManageIt\PaygTax\TaxScales\Nat4466
 */
class Nat4466Test extends TestCase
{
    /**
     * @dataProvider weeklyData
     */
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
        float $scale3MLHalf
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
        $earning->date = new \DateTime('2024-10-15');
        $earning->gross = $gross;

        // Scale 1 - Single

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($scale1, $payg->getTaxWithheldAmount());

        // Scale 2 - Illness Separated

        $payee->seniorsOffset = Payee::SENIORS_OFFSET_ILLNESS_SEPARATED;

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($scale2, $payg->getTaxWithheldAmount());

        // Scale 3 - Member of a couple

        $payee->seniorsOffset = Payee::SENIORS_OFFSET_COUPLE;

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($scale3, $payg->getTaxWithheldAmount());

        // Scale 1 - Single (Full Medicare Levy Exemption)

        $payee->seniorsOffset = Payee::SENIORS_OFFSET_SINGLE;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_FULL;

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($scale1MLExempt, $payg->getTaxWithheldAmount());

        // Scale 2 - Illness Separated (Full Medicare Levy Exemption)

        $payee->seniorsOffset = Payee::SENIORS_OFFSET_ILLNESS_SEPARATED;

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($scale2MLExempt, $payg->getTaxWithheldAmount());

        // Scale 3 - Member of a couple (Full Medicare Levy Exemption)

        $payee->seniorsOffset = Payee::SENIORS_OFFSET_COUPLE;

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($scale3MLExempt, $payg->getTaxWithheldAmount());

        // Scale 1 - Single (Half Medicare Levy Exemption)

        $payee->seniorsOffset = Payee::SENIORS_OFFSET_SINGLE;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_HALF;

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($scale1MLHalf, $payg->getTaxWithheldAmount());

        // Scale 2 - Illness Separated (Half Medicare Levy Exemption)

        $payee->seniorsOffset = Payee::SENIORS_OFFSET_ILLNESS_SEPARATED;

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($scale2MLHalf, $payg->getTaxWithheldAmount());

        // Scale 3 - Member of a couple (Half Medicare Levy Exemption)

        $payee->seniorsOffset = Payee::SENIORS_OFFSET_COUPLE;

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($scale3MLHalf, $payg->getTaxWithheldAmount());
    }

    /**
     * @return array<int, array<int|float, int|float>>
     */
    public function weeklyData(): array
    {
        return [
            [553, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00],
            [554, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00],
            [575, 0.00, 0.00, 4.00, 0.00, 0.00, 4.00, 0.00, 0.00, 4.00],
            [595, 0.00, 0.00, 7.00, 0.00, 0.00, 7.00, 0.00, 0.00, 7.00],
            [596, 0.00, 0.00, 7.00, 0.00, 0.00, 7.00, 0.00, 0.00, 7.00],
            [601, 0.00, 0.00, 8.00, 0.00, 0.00, 8.00, 0.00, 0.00, 8.00],
            [605, 0.00, 0.00, 10.00, 0.00, 0.00, 10.00, 0.00, 0.00, 10.00],
            [606, 0.00, 0.00, 10.00, 0.00, 0.00, 10.00, 0.00, 0.00, 10.00],
            [618, 0.00, 2.00, 13.00, 0.00, 2.00, 13.00, 0.00, 2.00, 13.00],
            [628, 0.00, 4.00, 16.00, 0.00, 4.00, 16.00, 0.00, 4.00, 16.00],
            [629, 0.00, 4.00, 16.00, 0.00, 4.00, 16.00, 0.00, 4.00, 16.00],
            [638, 2.00, 5.00, 19.00, 2.00, 5.00, 19.00, 2.00, 5.00, 19.00],
            [647, 3.00, 7.00, 22.00, 3.00, 7.00, 22.00, 3.00, 7.00, 22.00],
            [648, 3.00, 7.00, 22.00, 3.00, 7.00, 22.00, 3.00, 7.00, 22.00],
            [660, 5.00, 10.00, 25.00, 5.00, 10.00, 25.00, 5.00, 10.00, 25.00],
            [670, 7.00, 13.00, 28.00, 7.00, 13.00, 28.00, 7.00, 13.00, 28.00],
            [671, 7.00, 13.00, 28.00, 7.00, 13.00, 28.00, 7.00, 13.00, 28.00],
            [696, 14.00, 20.00, 35.00, 14.00, 20.00, 35.00, 14.00, 20.00, 35.00],
            [720, 21.00, 27.00, 42.00, 21.00, 27.00, 42.00, 21.00, 27.00, 42.00],
            [721, 21.00, 28.00, 43.00, 21.00, 28.00, 43.00, 21.00, 28.00, 43.00],
            [756, 31.00, 38.00, 53.00, 31.00, 38.00, 53.00, 31.00, 38.00, 53.00],
            [789, 41.00, 48.00, 63.00, 41.00, 48.00, 63.00, 41.00, 48.00, 63.00],
            [790, 41.00, 48.00, 63.00, 41.00, 48.00, 63.00, 41.00, 48.00, 63.00],
            [816, 52.00, 58.00, 73.00, 49.00, 56.00, 71.00, 49.00, 56.00, 71.00],
            [841, 62.00, 68.00, 83.00, 56.00, 63.00, 78.00, 56.00, 63.00, 78.00],
            [842, 62.00, 68.00, 83.00, 57.00, 63.00, 78.00, 57.00, 63.00, 78.00],
            [854, 67.00, 73.00, 87.00, 60.00, 67.00, 80.00, 60.00, 67.00, 80.00],
            [864, 71.00, 77.00, 89.00, 63.00, 70.00, 82.00, 63.00, 70.00, 82.00],
            [865, 71.00, 78.00, 90.00, 64.00, 70.00, 82.00, 64.00, 70.00, 82.00],
            [914, 97.00, 103.00, 109.00, 84.00, 91.00, 97.00, 84.00, 91.00, 97.00],
            [961, 122.00, 128.00, 128.00, 105.00, 111.00, 111.00, 105.00, 111.00, 111.00],
            [962, 122.00, 129.00, 129.00, 105.00, 111.00, 111.00, 105.00, 111.00, 111.00],
            [974, 129.00, 134.00, 134.00, 110.00, 115.00, 115.00, 110.00, 115.00, 115.00],
            [986, 135.00, 138.00, 138.00, 115.00, 119.00, 119.00, 115.00, 119.00, 119.00],
            [987, 135.00, 139.00, 139.00, 116.00, 119.00, 119.00, 116.00, 119.00, 119.00],
            [1000, 141.00, 143.00, 143.00, 121.00, 123.00, 123.00, 121.00, 123.00, 123.00],
            [1013, 147.00, 147.00, 147.00, 127.00, 127.00, 127.00, 127.00, 127.00, 127.00],
            [1014, 147.00, 147.00, 147.00, 127.00, 127.00, 127.00, 127.00, 127.00, 127.00],
            [1056, 161.00, 161.00, 161.00, 140.00, 140.00, 140.00, 140.00, 140.00, 140.00],
            [1098, 175.00, 175.00, 175.00, 153.00, 153.00, 153.00, 153.00, 153.00, 153.00],
            [1099, 175.00, 175.00, 175.00, 153.00, 153.00, 153.00, 153.00, 153.00, 153.00],
            [1190, 204.00, 204.00, 204.00, 180.00, 180.00, 180.00, 185.00, 185.00, 185.00],
            [1281, 234.00, 234.00, 234.00, 208.00, 208.00, 208.00, 217.00, 217.00, 217.00],
            [1282, 234.00, 234.00, 234.00, 208.00, 208.00, 208.00, 217.00, 217.00, 217.00],
            [1328, 249.00, 249.00, 249.00, 222.00, 222.00, 222.00, 234.00, 234.00, 234.00],
            [1373, 263.00, 263.00, 263.00, 236.00, 236.00, 236.00, 249.00, 249.00, 249.00],
            [1374, 263.00, 263.00, 263.00, 236.00, 236.00, 236.00, 250.00, 250.00, 250.00],
            [1985, 459.00, 459.00, 459.00, 419.00, 419.00, 419.00, 439.00, 439.00, 439.00],
            [2595, 654.00, 654.00, 654.00, 602.00, 602.00, 602.00, 628.00, 628.00, 628.00],
            [2596, 655.00, 655.00, 655.00, 603.00, 603.00, 603.00, 629.00, 629.00, 629.00],
            [3124, 860.00, 860.00, 860.00, 798.00, 798.00, 798.00, 829.00, 829.00, 829.00],
            [3652, 1066.00, 1066.00, 1066.00, 993.00, 993.00, 993.00, 1030.00, 1030.00, 1030.00],
            [3653, 1067.00, 1067.00, 1067.00, 994.00, 994.00, 994.00, 1030.00, 1030.00, 1030.00],
        ];
    }
}
