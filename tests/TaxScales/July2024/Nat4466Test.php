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
        $earning->date = new \DateTime('2024-10-15');
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
     * @return array<int, array<int|float, int|float>>
     */
    public static function weeklyData(): array
    {
        return [
            [553, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0],
            [554, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0],
            [575, 0.0, 0.0, 4.0, 0.0, 0.0, 4.0, 0.0, 0.0, 4.0],
            [595, 0.0, 0.0, 7.0, 0.0, 0.0, 7.0, 0.0, 0.0, 7.0],
            [596, 0.0, 0.0, 7.0, 0.0, 0.0, 7.0, 0.0, 0.0, 7.0],
            [601, 0.0, 0.0, 8.0, 0.0, 0.0, 8.0, 0.0, 0.0, 8.0],
            [605, 0.0, 0.0, 10.0, 0.0, 0.0, 10.0, 0.0, 0.0, 10.0],
            [606, 0.0, 0.0, 10.0, 0.0, 0.0, 10.0, 0.0, 0.0, 10.0],
            [618, 0.0, 2.0, 13.0, 0.0, 2.0, 13.0, 0.0, 2.0, 13.0],
            [628, 0.0, 4.0, 16.0, 0.0, 4.0, 16.0, 0.0, 4.0, 16.0],
            [629, 0.0, 4.0, 16.0, 0.0, 4.0, 16.0, 0.0, 4.0, 16.0],
            [638, 2.0, 5.0, 19.0, 2.0, 5.0, 19.0, 2.0, 5.0, 19.0],
            [647, 3.0, 7.0, 22.0, 3.0, 7.0, 22.0, 3.0, 7.0, 22.0],
            [648, 3.0, 7.0, 22.0, 3.0, 7.0, 22.0, 3.0, 7.0, 22.0],
            [660, 5.0, 10.0, 25.0, 5.0, 10.0, 25.0, 5.0, 10.0, 25.0],
            [670, 7.0, 13.0, 28.0, 7.0, 13.0, 28.0, 7.0, 13.0, 28.0],
            [671, 7.0, 13.0, 28.0, 7.0, 13.0, 28.0, 7.0, 13.0, 28.0],
            [696, 14.0, 20.0, 35.0, 14.0, 20.0, 35.0, 14.0, 20.0, 35.0],
            [720, 21.0, 27.0, 42.0, 21.0, 27.0, 42.0, 21.0, 27.0, 42.0],
            [721, 21.0, 28.0, 43.0, 21.0, 28.0, 43.0, 21.0, 28.0, 43.0],
            [756, 31.0, 38.0, 53.0, 31.0, 38.0, 53.0, 31.0, 38.0, 53.0],
            [789, 41.0, 48.0, 63.0, 41.0, 48.0, 63.0, 41.0, 48.0, 63.0],
            [790, 41.0, 48.0, 63.0, 41.0, 48.0, 63.0, 41.0, 48.0, 63.0],
            [816, 52.0, 58.0, 73.0, 49.0, 56.0, 71.0, 49.0, 56.0, 71.0],
            [841, 62.0, 68.0, 83.0, 56.0, 63.0, 78.0, 56.0, 63.0, 78.0],
            [842, 62.0, 68.0, 83.0, 57.0, 63.0, 78.0, 57.0, 63.0, 78.0],
            [854, 67.0, 73.0, 87.0, 60.0, 67.0, 80.0, 60.0, 67.0, 80.0],
            [864, 71.0, 77.0, 89.0, 63.0, 70.0, 82.0, 63.0, 70.0, 82.0],
            [865, 71.0, 78.0, 90.0, 64.0, 70.0, 82.0, 64.0, 70.0, 82.0],
            [914, 97.0, 103.0, 109.0, 84.0, 91.0, 97.0, 84.0, 91.0, 97.0],
            [961, 122.0, 128.0, 128.0, 105.0, 111.0, 111.0, 105.0, 111.0, 111.0],
            [962, 122.0, 129.0, 129.0, 105.0, 111.0, 111.0, 105.0, 111.0, 111.0],
            [974, 129.0, 134.0, 134.0, 110.0, 115.0, 115.0, 110.0, 115.0, 115.0],
            [986, 135.0, 138.0, 138.0, 115.0, 119.0, 119.0, 115.0, 119.0, 119.0],
            [987, 135.0, 139.0, 139.0, 116.0, 119.0, 119.0, 116.0, 119.0, 119.0],
            [1000, 141.0, 143.0, 143.0, 121.0, 123.0, 123.0, 121.0, 123.0, 123.0],
            [1013, 147.0, 147.0, 147.0, 127.0, 127.0, 127.0, 127.0, 127.0, 127.0],
            [1014, 147.0, 147.0, 147.0, 127.0, 127.0, 127.0, 127.0, 127.0, 127.0],
            [1056, 161.0, 161.0, 161.0, 140.0, 140.0, 140.0, 140.0, 140.0, 140.0],
            [1098, 175.0, 175.0, 175.0, 153.0, 153.0, 153.0, 153.0, 153.0, 153.0],
            [1099, 175.0, 175.0, 175.0, 153.0, 153.0, 153.0, 153.0, 153.0, 153.0],
            [1190, 204.0, 204.0, 204.0, 180.0, 180.0, 180.0, 185.0, 185.0, 185.0],
            [1281, 234.0, 234.0, 234.0, 208.0, 208.0, 208.0, 217.0, 217.0, 217.0],
            [1282, 234.0, 234.0, 234.0, 208.0, 208.0, 208.0, 217.0, 217.0, 217.0],
            [1328, 249.0, 249.0, 249.0, 222.0, 222.0, 222.0, 234.0, 234.0, 234.0],
            [1373, 263.0, 263.0, 263.0, 236.0, 236.0, 236.0, 249.0, 249.0, 249.0],
            [1374, 263.0, 263.0, 263.0, 236.0, 236.0, 236.0, 250.0, 250.0, 250.0],
            [1985, 459.0, 459.0, 459.0, 419.0, 419.0, 419.0, 439.0, 439.0, 439.0],
            [2595, 654.0, 654.0, 654.0, 602.0, 602.0, 602.0, 628.0, 628.0, 628.0],
            [2596, 655.0, 655.0, 655.0, 603.0, 603.0, 603.0, 629.0, 629.0, 629.0],
            [3124, 860.0, 860.0, 860.0, 798.0, 798.0, 798.0, 829.0, 829.0, 829.0],
            [3652, 1066.0, 1066.0, 1066.0, 993.0, 993.0, 993.0, 1030.0, 1030.0, 1030.0],
            [3653, 1067.0, 1067.0, 1067.0, 994.0, 994.0, 994.0, 1030.0, 1030.0, 1030.0],
        ];
    }
}
