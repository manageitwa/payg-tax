<?php

namespace ManageIt\PaygTax\Tests\TaxScales\July2022;

use ManageIt\PaygTax\PaygTax;
use ManageIt\PaygTax\TaxScales\July2022\Nat3539Scale1;
use ManageIt\PaygTax\TaxScales\July2022\Nat3539Scale2;
use ManageIt\PaygTax\TaxScales\July2022\Nat3539Scale3;
use ManageIt\PaygTax\TaxScales\July2022\Nat3539Scale5;
use ManageIt\PaygTax\TaxScales\July2022\Nat3539Scale6;
use ManageIt\PaygTax\Tests\Fixtures\Earning;
use ManageIt\PaygTax\Tests\Fixtures\Payee;
use ManageIt\PaygTax\Tests\Fixtures\Payer;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;

/**
 * @covers \ManageIt\PaygTax\TaxScales\July2022\Nat3539Scale1
 * @covers \ManageIt\PaygTax\TaxScales\July2022\Nat3539Scale2
 * @covers \ManageIt\PaygTax\TaxScales\July2022\Nat3539Scale3
 * @covers \ManageIt\PaygTax\TaxScales\July2022\Nat3539Scale5
 * @covers \ManageIt\PaygTax\TaxScales\July2022\Nat3539Scale6
 */
class Nat3539Test extends TestCase
{
    protected Nat3539Scale1 $scale1;
    protected Nat3539Scale2 $scale2;
    protected Nat3539Scale3 $scale3;
    protected Nat3539Scale5 $scale5;
    protected Nat3539Scale6 $scale6;

    public function setUp(): void
    {
        $this->scale1 = new Nat3539Scale1();
        $this->scale2 = new Nat3539Scale2();
        $this->scale3 = new Nat3539Scale3();
        $this->scale5 = new Nat3539Scale5();
        $this->scale6 = new Nat3539Scale6();
    }

    public function testEligibility(): void
    {
        $payer = new Payer();

        $payee = new Payee();
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;
        $payee->stsl = true;

        $earning = new Earning();
        $earning->date = new \DateTime('2022-07-15');

        Assert::assertTrue($this->scale1->isEligible($payer, $payee, $earning));
        Assert::assertFalse($this->scale2->isEligible($payer, $payee, $earning));
        Assert::assertFalse($this->scale3->isEligible($payer, $payee, $earning));
        Assert::assertFalse($this->scale5->isEligible($payer, $payee, $earning));
        Assert::assertFalse($this->scale6->isEligible($payer, $payee, $earning));

        $payee->residencyStatus = Payee::FOREIGN_RESIDENT;
        Assert::assertFalse($this->scale1->isEligible($payer, $payee, $earning));
        Assert::assertFalse($this->scale2->isEligible($payer, $payee, $earning));
        Assert::assertTrue($this->scale3->isEligible($payer, $payee, $earning));
        Assert::assertFalse($this->scale5->isEligible($payer, $payee, $earning));
        Assert::assertFalse($this->scale6->isEligible($payer, $payee, $earning));

        $payee->residencyStatus = Payee::WORKING_HOLIDAY_MAKER;
        Assert::assertFalse($this->scale1->isEligible($payer, $payee, $earning));
        Assert::assertFalse($this->scale2->isEligible($payer, $payee, $earning));
        Assert::assertFalse($this->scale3->isEligible($payer, $payee, $earning));
        Assert::assertFalse($this->scale5->isEligible($payer, $payee, $earning));
        Assert::assertFalse($this->scale6->isEligible($payer, $payee, $earning));

        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = false;
        Assert::assertFalse($this->scale1->isEligible($payer, $payee, $earning));
        Assert::assertFalse($this->scale2->isEligible($payer, $payee, $earning));
        Assert::assertFalse($this->scale3->isEligible($payer, $payee, $earning));
        Assert::assertFalse($this->scale5->isEligible($payer, $payee, $earning));
        Assert::assertFalse($this->scale6->isEligible($payer, $payee, $earning));

        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = true;
        Assert::assertFalse($this->scale1->isEligible($payer, $payee, $earning));
        Assert::assertTrue($this->scale2->isEligible($payer, $payee, $earning));
        Assert::assertFalse($this->scale3->isEligible($payer, $payee, $earning));
        Assert::assertFalse($this->scale5->isEligible($payer, $payee, $earning));
        Assert::assertFalse($this->scale6->isEligible($payer, $payee, $earning));

        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_FULL;
        Assert::assertFalse($this->scale1->isEligible($payer, $payee, $earning));
        Assert::assertFalse($this->scale2->isEligible($payer, $payee, $earning));
        Assert::assertFalse($this->scale3->isEligible($payer, $payee, $earning));
        Assert::assertTrue($this->scale5->isEligible($payer, $payee, $earning));
        Assert::assertFalse($this->scale6->isEligible($payer, $payee, $earning));

        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_HALF;
        Assert::assertFalse($this->scale1->isEligible($payer, $payee, $earning));
        Assert::assertFalse($this->scale2->isEligible($payer, $payee, $earning));
        Assert::assertFalse($this->scale3->isEligible($payer, $payee, $earning));
        Assert::assertFalse($this->scale5->isEligible($payer, $payee, $earning));
        Assert::assertTrue($this->scale6->isEligible($payer, $payee, $earning));

        $payee->seniorsOffset = Payee::SENIORS_OFFSET_COUPLE;
        Assert::assertFalse($this->scale1->isEligible($payer, $payee, $earning));
        Assert::assertFalse($this->scale2->isEligible($payer, $payee, $earning));
        Assert::assertFalse($this->scale3->isEligible($payer, $payee, $earning));
        Assert::assertFalse($this->scale5->isEligible($payer, $payee, $earning));
        Assert::assertFalse($this->scale6->isEligible($payer, $payee, $earning));

        $payee->seniorsOffset = Payee::SENIORS_OFFSET_NONE;
        $earning->date = new \DateTime('2019-08-01');
        Assert::assertFalse($this->scale1->isEligible($payer, $payee, $earning));
        Assert::assertFalse($this->scale2->isEligible($payer, $payee, $earning));
        Assert::assertFalse($this->scale3->isEligible($payer, $payee, $earning));
        Assert::assertFalse($this->scale5->isEligible($payer, $payee, $earning));
        Assert::assertFalse($this->scale6->isEligible($payer, $payee, $earning));
    }

    /**
     * @dataProvider weeklyData
     */
    public function testWeeklyWithholding(
        int $gross,
        float $scale1,
        float $scale2,
        float $scale3,
        float $scale5,
        float $scale6
    ): void {
        $payer = new Payer();

        $payee = new Payee();
        $payee->payCycle = Payee::PAY_CYCLE_WEEKLY;
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;
        $payee->stsl = true;

        $earning = new Earning();
        $earning->date = new \DateTime('2022-07-15');
        $earning->gross = $gross;

        // Scale 1

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($scale1, $payg->getTaxWithheldAmount());

        // Scale 2

        $payee->claimsTaxFreeThreshold = true;

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($scale2, $payg->getTaxWithheldAmount());

        // Scale 3

        $payee->residencyStatus = Payee::FOREIGN_RESIDENT;

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($scale3, $payg->getTaxWithheldAmount());

        // Scale 5

        $payee->residencyStatus = Payee::RESIDENT;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_FULL;

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($scale5, $payg->getTaxWithheldAmount());

        // Scale 6

        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_HALF;

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($scale6, $payg->getTaxWithheldAmount());
    }

    /**
     * @return array<int, array<int|float, int|float>>
     */
    public function weeklyData(): array
    {
        return [
            [87, 17.00, 0.00, 28.00, 0.00, 0.00],
            [88, 17.00, 0.00, 29.00, 0.00, 0.00],
            [116, 24.00, 0.00, 38.00, 0.00, 0.00],
            [117, 24.00, 0.00, 38.00, 0.00, 0.00],
            [249, 55.00, 0.00, 81.00, 0.00, 0.00],
            [250, 55.00, 0.00, 81.00, 0.00, 0.00],
            [358, 80.00, 0.00, 116.00, 0.00, 0.00],
            [359, 81.00, 0.00, 117.00, 0.00, 0.00],
            [370, 83.00, 2.00, 120.00, 2.00, 2.00],
            [371, 83.00, 2.00, 121.00, 2.00, 2.00],
            [437, 98.00, 15.00, 142.00, 15.00, 15.00],
            [438, 98.00, 15.00, 142.00, 15.00, 15.00],
            [514, 115.00, 37.00, 167.00, 30.00, 30.00],
            [515, 115.00, 37.00, 167.00, 30.00, 30.00],
            [547, 126.00, 47.00, 178.00, 36.00, 36.00],
            [548, 126.00, 47.00, 178.00, 36.00, 36.00],
            [579, 137.00, 53.00, 188.00, 42.00, 42.00],
            [580, 143.00, 54.00, 188.00, 42.00, 42.00],
            [720, 193.00, 83.00, 234.00, 69.00, 69.00],
            [721, 194.00, 83.00, 234.00, 69.00, 69.00],
            [722, 194.00, 83.00, 235.00, 69.00, 69.00],
            [723, 202.00, 84.00, 235.00, 69.00, 69.00],
            [739, 208.00, 87.00, 240.00, 72.00, 72.00],
            [740, 208.00, 87.00, 240.00, 73.00, 73.00],
            [787, 225.00, 98.00, 256.00, 82.00, 84.00],
            [788, 230.00, 98.00, 256.00, 82.00, 85.00],
            [855, 255.00, 113.00, 278.00, 96.00, 101.00],
            [856, 259.00, 113.00, 278.00, 96.00, 102.00],
            [864, 262.00, 115.00, 281.00, 97.00, 104.00],
            [865, 263.00, 115.00, 281.00, 98.00, 104.00],
            [923, 285.00, 135.00, 300.00, 117.00, 126.00],
            [924, 285.00, 135.00, 300.00, 117.00, 126.00],
            [927, 286.00, 136.00, 301.00, 118.00, 127.00],
            [928, 291.00, 137.00, 302.00, 118.00, 128.00],
            [929, 291.00, 137.00, 302.00, 119.00, 128.00],
            [930, 292.00, 147.00, 312.00, 128.00, 137.00],
            [931, 292.00, 147.00, 312.00, 129.00, 138.00],
            [932, 293.00, 148.00, 312.00, 129.00, 138.00],
            [1004, 320.00, 173.00, 336.00, 153.00, 163.00],
            [1005, 325.00, 174.00, 337.00, 154.00, 164.00],
            [1072, 351.00, 198.00, 359.00, 176.00, 187.00],
            [1073, 352.00, 209.00, 370.00, 187.00, 198.00],
            [1086, 362.00, 213.00, 375.00, 192.00, 203.00],
            [1137, 382.00, 232.00, 392.00, 209.00, 221.00],
            [1138, 382.00, 238.00, 398.00, 216.00, 227.00],
            [1172, 396.00, 251.00, 410.00, 228.00, 239.00],
            [1173, 402.00, 251.00, 411.00, 228.00, 240.00],
            [1175, 403.00, 252.00, 411.00, 229.00, 240.00],
            [1205, 414.00, 263.00, 422.00, 239.00, 251.00],
            [1206, 415.00, 270.00, 428.00, 246.00, 258.00],
            [1264, 444.00, 292.00, 449.00, 266.00, 279.00],
            [1277, 449.00, 296.00, 453.00, 271.00, 284.00],
            [1278, 450.00, 303.00, 460.00, 278.00, 290.00],
            [1281, 451.00, 304.00, 461.00, 279.00, 292.00],
            [1282, 451.00, 305.00, 462.00, 279.00, 292.00],
            [1354, 480.00, 332.00, 487.00, 305.00, 319.00],
            [1355, 480.00, 339.00, 495.00, 312.00, 326.00],
            [1360, 482.00, 341.00, 496.00, 314.00, 328.00],
            [1361, 490.00, 342.00, 497.00, 314.00, 328.00],
            [1435, 520.00, 370.00, 524.00, 341.00, 356.00],
            [1436, 520.00, 378.00, 531.00, 349.00, 363.00],
            [1463, 531.00, 388.00, 541.00, 359.00, 374.00],
            [1464, 539.00, 389.00, 542.00, 359.00, 374.00],
            [1522, 563.00, 411.00, 563.00, 381.00, 396.00],
            [1523, 563.00, 419.00, 571.00, 389.00, 404.00],
            [1572, 583.00, 439.00, 590.00, 407.00, 423.00],
            [1573, 591.00, 439.00, 590.00, 407.00, 423.00],
            [1613, 608.00, 455.00, 605.00, 422.00, 439.00],
            [1614, 608.00, 463.00, 613.00, 431.00, 447.00],
            [1687, 639.00, 492.00, 641.00, 459.00, 476.00],
            [1688, 647.00, 493.00, 641.00, 459.00, 476.00],
            [1710, 657.00, 502.00, 650.00, 467.00, 485.00],
            [1711, 657.00, 511.00, 659.00, 476.00, 493.00],
            [1809, 698.00, 550.00, 697.00, 514.00, 532.00],
            [1810, 708.00, 551.00, 697.00, 514.00, 533.00],
            [1813, 709.00, 552.00, 698.00, 516.00, 534.00],
            [1814, 709.00, 561.00, 708.00, 525.00, 543.00],
            [1844, 722.00, 574.00, 719.00, 537.00, 555.00],
            [1845, 723.00, 574.00, 720.00, 537.00, 556.00],
            [1922, 755.00, 606.00, 750.00, 567.00, 586.00],
            [1923, 756.00, 616.00, 760.00, 577.00, 596.00],
            [1939, 763.00, 622.00, 766.00, 584.00, 603.00],
            [1940, 773.00, 623.00, 766.00, 584.00, 603.00],
            [1956, 780.00, 629.00, 773.00, 590.00, 610.00],
            [1957, 780.00, 630.00, 773.00, 591.00, 610.00],
            [2037, 818.00, 663.00, 805.00, 622.00, 643.00],
            [2038, 819.00, 674.00, 815.00, 633.00, 653.00],
            [2076, 837.00, 690.00, 830.00, 648.00, 669.00],
            [2077, 847.00, 690.00, 831.00, 648.00, 669.00],
            [2119, 868.00, 708.00, 848.00, 665.00, 686.00],
            [2120, 868.00, 708.00, 848.00, 666.00, 687.00],
            [2159, 887.00, 724.00, 864.00, 681.00, 703.00],
            [2160, 887.00, 736.00, 875.00, 692.00, 714.00],
            [2222, 917.00, 762.00, 900.00, 718.00, 740.00],
            [2223, 929.00, 762.00, 900.00, 718.00, 740.00],
            [2289, 961.00, 790.00, 927.00, 745.00, 768.00],
            [2290, 961.00, 802.00, 939.00, 757.00, 779.00],
            [2306, 969.00, 809.00, 946.00, 763.00, 786.00],
            [2307, 969.00, 810.00, 946.00, 764.00, 787.00],
            [2376, 1003.00, 842.00, 978.00, 795.00, 819.00],
            [2377, 1015.00, 843.00, 978.00, 795.00, 819.00],
            [2426, 1039.00, 866.00, 1000.00, 818.00, 842.00],
            [2427, 1040.00, 879.00, 1013.00, 830.00, 855.00],
            [2490, 1071.00, 909.00, 1042.00, 859.00, 884.00],
            [2491, 1071.00, 910.00, 1042.00, 860.00, 885.00],
            [2572, 1111.00, 948.00, 1080.00, 897.00, 923.00],
            [2573, 1111.00, 962.00, 1093.00, 910.00, 936.00],
            [2652, 1150.00, 1000.00, 1130.00, 947.00, 974.00],
            [2653, 1150.00, 1001.00, 1130.00, 948.00, 974.00],
            [2726, 1186.00, 1036.00, 1164.00, 981.00, 1009.00],
            [2727, 1187.00, 1050.00, 1178.00, 996.00, 1023.00],
            [2736, 1191.00, 1055.00, 1183.00, 1000.00, 1027.00],
            [2737, 1192.00, 1055.00, 1183.00, 1000.00, 1028.00],
            [2898, 1270.00, 1134.00, 1259.00, 1076.00, 1105.00],
            [2899, 1271.00, 1134.00, 1259.00, 1076.00, 1105.00],
            [2913, 1278.00, 1141.00, 1266.00, 1083.00, 1112.00],
            [2914, 1278.00, 1142.00, 1266.00, 1083.00, 1113.00],
            [3110, 1374.00, 1238.00, 1358.00, 1176.00, 1207.00],
            [3111, 1375.00, 1238.00, 1359.00, 1176.00, 1207.00],
            [3460, 1574.00, 1409.00, 1523.00, 1340.00, 1375.00],
            [3461, 1574.00, 1410.00, 1523.00, 1341.00, 1375.00],
        ];
    }

    /**
     * @dataProvider fortnightlyData
     */
    public function testFortnightlyWithholding(
        int $gross,
        float $scale1,
        float $scale2,
        float $scale3,
        float $scale5,
        float $scale6
    ): void {
        $payer = new Payer();

        $payee = new Payee();
        $payee->payCycle = Payee::PAY_CYCLE_FORTNIGHTLY;
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;
        $payee->stsl = true;

        $earning = new Earning();
        $earning->date = new \DateTime('2022-07-15');
        $earning->gross = $gross;

        // Scale 1

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($scale1, $payg->getTaxWithheldAmount());

        // Scale 2

        $payee->claimsTaxFreeThreshold = true;

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($scale2, $payg->getTaxWithheldAmount());

        // Scale 3

        $payee->residencyStatus = Payee::FOREIGN_RESIDENT;

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($scale3, $payg->getTaxWithheldAmount());

        // Scale 5

        $payee->residencyStatus = Payee::RESIDENT;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_FULL;

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($scale5, $payg->getTaxWithheldAmount());

        // Scale 6

        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_HALF;

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($scale6, $payg->getTaxWithheldAmount());
    }

    /**
     * @return array<int, array<int|float, int|float>>
     */
    public function fortnightlyData(): array
    {
        return [
            [174, 34.00, 0.00, 56.00, 0.00, 0.00],
            [176, 34.00, 0.00, 58.00, 0.00, 0.00],
            [232, 48.00, 0.00, 76.00, 0.00, 0.00],
            [234, 48.00, 0.00, 76.00, 0.00, 0.00],
            [498, 110.00, 0.00, 162.00, 0.00, 0.00],
            [500, 110.00, 0.00, 162.00, 0.00, 0.00],
            [716, 160.00, 0.00, 232.00, 0.00, 0.00],
            [718, 162.00, 0.00, 234.00, 0.00, 0.00],
            [740, 166.00, 4.00, 240.00, 4.00, 4.00],
            [742, 166.00, 4.00, 242.00, 4.00, 4.00],
            [874, 196.00, 30.00, 284.00, 30.00, 30.00],
            [876, 196.00, 30.00, 284.00, 30.00, 30.00],
            [1028, 230.00, 74.00, 334.00, 60.00, 60.00],
            [1030, 230.00, 74.00, 334.00, 60.00, 60.00],
            [1094, 252.00, 94.00, 356.00, 72.00, 72.00],
            [1096, 252.00, 94.00, 356.00, 72.00, 72.00],
            [1158, 274.00, 106.00, 376.00, 84.00, 84.00],
            [1160, 286.00, 108.00, 376.00, 84.00, 84.00],
            [1440, 386.00, 166.00, 468.00, 138.00, 138.00],
            [1442, 388.00, 166.00, 468.00, 138.00, 138.00],
            [1444, 388.00, 166.00, 470.00, 138.00, 138.00],
            [1446, 404.00, 168.00, 470.00, 138.00, 138.00],
            [1478, 416.00, 174.00, 480.00, 144.00, 144.00],
            [1480, 416.00, 174.00, 480.00, 146.00, 146.00],
            [1574, 450.00, 196.00, 512.00, 164.00, 168.00],
            [1576, 460.00, 196.00, 512.00, 164.00, 170.00],
            [1710, 510.00, 226.00, 556.00, 192.00, 202.00],
            [1712, 518.00, 226.00, 556.00, 192.00, 204.00],
            [1728, 524.00, 230.00, 562.00, 194.00, 208.00],
            [1730, 526.00, 230.00, 562.00, 196.00, 208.00],
            [1846, 570.00, 270.00, 600.00, 234.00, 252.00],
            [1848, 570.00, 270.00, 600.00, 234.00, 252.00],
            [1854, 572.00, 272.00, 602.00, 236.00, 254.00],
            [1856, 582.00, 274.00, 604.00, 236.00, 256.00],
            [1858, 582.00, 274.00, 604.00, 238.00, 256.00],
            [1860, 584.00, 294.00, 624.00, 256.00, 274.00],
            [1862, 584.00, 294.00, 624.00, 258.00, 276.00],
            [1864, 586.00, 296.00, 624.00, 258.00, 276.00],
            [2008, 640.00, 346.00, 672.00, 306.00, 326.00],
            [2010, 650.00, 348.00, 674.00, 308.00, 328.00],
            [2144, 702.00, 396.00, 718.00, 352.00, 374.00],
            [2146, 704.00, 418.00, 740.00, 374.00, 396.00],
            [2172, 724.00, 426.00, 750.00, 384.00, 406.00],
            [2274, 764.00, 464.00, 784.00, 418.00, 442.00],
            [2276, 764.00, 476.00, 796.00, 432.00, 454.00],
            [2344, 792.00, 502.00, 820.00, 456.00, 478.00],
            [2346, 804.00, 502.00, 822.00, 456.00, 480.00],
            [2350, 806.00, 504.00, 822.00, 458.00, 480.00],
            [2410, 828.00, 526.00, 844.00, 478.00, 502.00],
            [2412, 830.00, 540.00, 856.00, 492.00, 516.00],
            [2528, 888.00, 584.00, 898.00, 532.00, 558.00],
            [2554, 898.00, 592.00, 906.00, 542.00, 568.00],
            [2556, 900.00, 606.00, 920.00, 556.00, 580.00],
            [2562, 902.00, 608.00, 922.00, 558.00, 584.00],
            [2564, 902.00, 610.00, 924.00, 558.00, 584.00],
            [2708, 960.00, 664.00, 974.00, 610.00, 638.00],
            [2710, 960.00, 678.00, 990.00, 624.00, 652.00],
            [2720, 964.00, 682.00, 992.00, 628.00, 656.00],
            [2722, 980.00, 684.00, 994.00, 628.00, 656.00],
            [2870, 1040.00, 740.00, 1048.00, 682.00, 712.00],
            [2872, 1040.00, 756.00, 1062.00, 698.00, 726.00],
            [2926, 1062.00, 776.00, 1082.00, 718.00, 748.00],
            [2928, 1078.00, 778.00, 1084.00, 718.00, 748.00],
            [3044, 1126.00, 822.00, 1126.00, 762.00, 792.00],
            [3046, 1126.00, 838.00, 1142.00, 778.00, 808.00],
            [3144, 1166.00, 878.00, 1180.00, 814.00, 846.00],
            [3146, 1182.00, 878.00, 1180.00, 814.00, 846.00],
            [3226, 1216.00, 910.00, 1210.00, 844.00, 878.00],
            [3228, 1216.00, 926.00, 1226.00, 862.00, 894.00],
            [3374, 1278.00, 984.00, 1282.00, 918.00, 952.00],
            [3376, 1294.00, 986.00, 1282.00, 918.00, 952.00],
            [3420, 1314.00, 1004.00, 1300.00, 934.00, 970.00],
            [3422, 1314.00, 1022.00, 1318.00, 952.00, 986.00],
            [3618, 1396.00, 1100.00, 1394.00, 1028.00, 1064.00],
            [3620, 1416.00, 1102.00, 1394.00, 1028.00, 1066.00],
            [3626, 1418.00, 1104.00, 1396.00, 1032.00, 1068.00],
            [3628, 1418.00, 1122.00, 1416.00, 1050.00, 1086.00],
            [3688, 1444.00, 1148.00, 1438.00, 1074.00, 1110.00],
            [3690, 1446.00, 1148.00, 1440.00, 1074.00, 1112.00],
            [3844, 1510.00, 1212.00, 1500.00, 1134.00, 1172.00],
            [3846, 1512.00, 1232.00, 1520.00, 1154.00, 1192.00],
            [3878, 1526.00, 1244.00, 1532.00, 1168.00, 1206.00],
            [3880, 1546.00, 1246.00, 1532.00, 1168.00, 1206.00],
            [3912, 1560.00, 1258.00, 1546.00, 1180.00, 1220.00],
            [3914, 1560.00, 1260.00, 1546.00, 1182.00, 1220.00],
            [4074, 1636.00, 1326.00, 1610.00, 1244.00, 1286.00],
            [4076, 1638.00, 1348.00, 1630.00, 1266.00, 1306.00],
            [4152, 1674.00, 1380.00, 1660.00, 1296.00, 1338.00],
            [4154, 1694.00, 1380.00, 1662.00, 1296.00, 1338.00],
            [4238, 1736.00, 1416.00, 1696.00, 1330.00, 1372.00],
            [4240, 1736.00, 1416.00, 1696.00, 1332.00, 1374.00],
            [4318, 1774.00, 1448.00, 1728.00, 1362.00, 1406.00],
            [4320, 1774.00, 1472.00, 1750.00, 1384.00, 1428.00],
            [4444, 1834.00, 1524.00, 1800.00, 1436.00, 1480.00],
            [4446, 1858.00, 1524.00, 1800.00, 1436.00, 1480.00],
            [4578, 1922.00, 1580.00, 1854.00, 1490.00, 1536.00],
            [4580, 1922.00, 1604.00, 1878.00, 1514.00, 1558.00],
            [4612, 1938.00, 1618.00, 1892.00, 1526.00, 1572.00],
            [4614, 1938.00, 1620.00, 1892.00, 1528.00, 1574.00],
            [4752, 2006.00, 1684.00, 1956.00, 1590.00, 1638.00],
            [4754, 2030.00, 1686.00, 1956.00, 1590.00, 1638.00],
            [4852, 2078.00, 1732.00, 2000.00, 1636.00, 1684.00],
            [4854, 2080.00, 1758.00, 2026.00, 1660.00, 1710.00],
            [4980, 2142.00, 1818.00, 2084.00, 1718.00, 1768.00],
            [4982, 2142.00, 1820.00, 2084.00, 1720.00, 1770.00],
            [5144, 2222.00, 1896.00, 2160.00, 1794.00, 1846.00],
            [5146, 2222.00, 1924.00, 2186.00, 1820.00, 1872.00],
            [5304, 2300.00, 2000.00, 2260.00, 1894.00, 1948.00],
            [5306, 2300.00, 2002.00, 2260.00, 1896.00, 1948.00],
            [5452, 2372.00, 2072.00, 2328.00, 1962.00, 2018.00],
            [5454, 2374.00, 2100.00, 2356.00, 1992.00, 2046.00],
            [5472, 2382.00, 2110.00, 2366.00, 2000.00, 2054.00],
            [5474, 2384.00, 2110.00, 2366.00, 2000.00, 2056.00],
            [5796, 2540.00, 2268.00, 2518.00, 2152.00, 2210.00],
            [5798, 2542.00, 2268.00, 2518.00, 2152.00, 2210.00],
            [5826, 2556.00, 2282.00, 2532.00, 2166.00, 2224.00],
            [5828, 2556.00, 2284.00, 2532.00, 2166.00, 2226.00],
            [6220, 2748.00, 2476.00, 2716.00, 2352.00, 2414.00],
            [6222, 2750.00, 2476.00, 2718.00, 2352.00, 2414.00],
            [6920, 3148.00, 2818.00, 3046.00, 2680.00, 2750.00],
            [6922, 3148.00, 2820.00, 3046.00, 2682.00, 2750.00],
        ];
    }

    /**
     * @dataProvider monthlyData
     */
    public function testMonthlyWithholding(
        float $gross,
        float $scale1,
        float $scale2,
        float $scale3,
        float $scale5,
        float $scale6
    ): void {
        $payer = new Payer();

        $payee = new Payee();
        $payee->payCycle = Payee::PAY_CYCLE_MONTHLY;
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;
        $payee->stsl = true;

        $earning = new Earning();
        $earning->date = new \DateTime('2022-07-15');
        $earning->gross = $gross;

        // Scale 1

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($scale1, $payg->getTaxWithheldAmount());

        // Scale 2

        $payee->claimsTaxFreeThreshold = true;

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($scale2, $payg->getTaxWithheldAmount());

        // Scale 3

        $payee->residencyStatus = Payee::FOREIGN_RESIDENT;

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($scale3, $payg->getTaxWithheldAmount());

        // Scale 5

        $payee->residencyStatus = Payee::RESIDENT;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_FULL;

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($scale5, $payg->getTaxWithheldAmount());

        // Scale 6

        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_HALF;

        $payg = PaygTax::new()
            ->setPayer($payer)
            ->setPayee($payee)
            ->setEarning($earning);

        Assert::assertEquals($scale6, $payg->getTaxWithheldAmount());
    }

    /**
     * @return array<int, array<int|float, int|float>>
     */
    public function monthlyData(): array
    {
        return [
            [377.00, 74.00, 0.00, 121.00, 0.00, 0.00],
            [381.33, 74.00, 0.00, 126.00, 0.00, 0.00],
            [502.67, 104.00, 0.00, 165.00, 0.00, 0.00],
            [507.00, 104.00, 0.00, 165.00, 0.00, 0.00],
            [1079.00, 238.00, 0.00, 351.00, 0.00, 0.00],
            [1083.33, 238.00, 0.00, 351.00, 0.00, 0.00],
            [1551.33, 347.00, 0.00, 503.00, 0.00, 0.00],
            [1555.67, 351.00, 0.00, 507.00, 0.00, 0.00],
            [1603.33, 360.00, 9.00, 520.00, 9.00, 9.00],
            [1607.67, 360.00, 9.00, 524.00, 9.00, 9.00],
            [1893.67, 425.00, 65.00, 615.00, 65.00, 65.00],
            [1898.00, 425.00, 65.00, 615.00, 65.00, 65.00],
            [2227.33, 498.00, 160.00, 724.00, 130.00, 130.00],
            [2231.67, 498.00, 160.00, 724.00, 130.00, 130.00],
            [2370.33, 546.00, 204.00, 771.00, 156.00, 156.00],
            [2374.67, 546.00, 204.00, 771.00, 156.00, 156.00],
            [2509.00, 594.00, 230.00, 815.00, 182.00, 182.00],
            [2513.33, 620.00, 234.00, 815.00, 182.00, 182.00],
            [3120.00, 836.00, 360.00, 1014.00, 299.00, 299.00],
            [3124.33, 841.00, 360.00, 1014.00, 299.00, 299.00],
            [3128.67, 841.00, 360.00, 1018.00, 299.00, 299.00],
            [3133.00, 875.00, 364.00, 1018.00, 299.00, 299.00],
            [3202.33, 901.00, 377.00, 1040.00, 312.00, 312.00],
            [3206.67, 901.00, 377.00, 1040.00, 316.00, 316.00],
            [3410.33, 975.00, 425.00, 1109.00, 355.00, 364.00],
            [3414.67, 997.00, 425.00, 1109.00, 355.00, 368.00],
            [3705.00, 1105.00, 490.00, 1205.00, 416.00, 438.00],
            [3709.33, 1122.00, 490.00, 1205.00, 416.00, 442.00],
            [3744.00, 1135.00, 498.00, 1218.00, 420.00, 451.00],
            [3748.33, 1140.00, 498.00, 1218.00, 425.00, 451.00],
            [3999.67, 1235.00, 585.00, 1300.00, 507.00, 546.00],
            [4004.00, 1235.00, 585.00, 1300.00, 507.00, 546.00],
            [4017.00, 1239.00, 589.00, 1304.00, 511.00, 550.00],
            [4021.33, 1261.00, 594.00, 1309.00, 511.00, 555.00],
            [4025.67, 1261.00, 594.00, 1309.00, 516.00, 555.00],
            [4030.00, 1265.00, 637.00, 1352.00, 555.00, 594.00],
            [4034.33, 1265.00, 637.00, 1352.00, 559.00, 598.00],
            [4038.67, 1270.00, 641.00, 1352.00, 559.00, 598.00],
            [4350.67, 1387.00, 750.00, 1456.00, 663.00, 706.00],
            [4355.00, 1408.00, 754.00, 1460.00, 667.00, 711.00],
            [4645.33, 1521.00, 858.00, 1556.00, 763.00, 810.00],
            [4649.67, 1525.00, 906.00, 1603.00, 810.00, 858.00],
            [4706.00, 1569.00, 923.00, 1625.00, 832.00, 880.00],
            [4927.00, 1655.00, 1005.00, 1699.00, 906.00, 958.00],
            [4931.33, 1655.00, 1031.00, 1725.00, 936.00, 984.00],
            [5078.67, 1716.00, 1088.00, 1777.00, 988.00, 1036.00],
            [5083.00, 1742.00, 1088.00, 1781.00, 988.00, 1040.00],
            [5091.67, 1746.00, 1092.00, 1781.00, 992.00, 1040.00],
            [5221.67, 1794.00, 1140.00, 1829.00, 1036.00, 1088.00],
            [5226.00, 1798.00, 1170.00, 1855.00, 1066.00, 1118.00],
            [5477.33, 1924.00, 1265.00, 1946.00, 1153.00, 1209.00],
            [5533.67, 1946.00, 1283.00, 1963.00, 1174.00, 1231.00],
            [5538.00, 1950.00, 1313.00, 1993.00, 1205.00, 1257.00],
            [5551.00, 1954.00, 1317.00, 1998.00, 1209.00, 1265.00],
            [5555.33, 1954.00, 1322.00, 2002.00, 1209.00, 1265.00],
            [5867.33, 2080.00, 1439.00, 2110.00, 1322.00, 1382.00],
            [5871.67, 2080.00, 1469.00, 2145.00, 1352.00, 1413.00],
            [5893.33, 2089.00, 1478.00, 2149.00, 1361.00, 1421.00],
            [5897.67, 2123.00, 1482.00, 2154.00, 1361.00, 1421.00],
            [6218.33, 2253.00, 1603.00, 2271.00, 1478.00, 1543.00],
            [6222.67, 2253.00, 1638.00, 2301.00, 1512.00, 1573.00],
            [6339.67, 2301.00, 1681.00, 2344.00, 1556.00, 1621.00],
            [6344.00, 2336.00, 1686.00, 2349.00, 1556.00, 1621.00],
            [6595.33, 2440.00, 1781.00, 2440.00, 1651.00, 1716.00],
            [6599.67, 2440.00, 1816.00, 2474.00, 1686.00, 1751.00],
            [6812.00, 2526.00, 1902.00, 2557.00, 1764.00, 1833.00],
            [6816.33, 2561.00, 1902.00, 2557.00, 1764.00, 1833.00],
            [6989.67, 2635.00, 1972.00, 2622.00, 1829.00, 1902.00],
            [6994.00, 2635.00, 2006.00, 2656.00, 1868.00, 1937.00],
            [7310.33, 2769.00, 2132.00, 2778.00, 1989.00, 2063.00],
            [7314.67, 2804.00, 2136.00, 2778.00, 1989.00, 2063.00],
            [7410.00, 2847.00, 2175.00, 2817.00, 2024.00, 2102.00],
            [7414.33, 2847.00, 2214.00, 2856.00, 2063.00, 2136.00],
            [7839.00, 3025.00, 2383.00, 3020.00, 2227.00, 2305.00],
            [7843.33, 3068.00, 2388.00, 3020.00, 2227.00, 2310.00],
            [7856.33, 3072.00, 2392.00, 3025.00, 2236.00, 2314.00],
            [7860.67, 3072.00, 2431.00, 3068.00, 2275.00, 2353.00],
            [7990.67, 3129.00, 2487.00, 3116.00, 2327.00, 2405.00],
            [7995.00, 3133.00, 2487.00, 3120.00, 2327.00, 2409.00],
            [8328.67, 3272.00, 2626.00, 3250.00, 2457.00, 2539.00],
            [8333.00, 3276.00, 2669.00, 3293.00, 2500.00, 2583.00],
            [8402.33, 3306.00, 2695.00, 3319.00, 2531.00, 2613.00],
            [8406.67, 3350.00, 2700.00, 3319.00, 2531.00, 2613.00],
            [8476.00, 3380.00, 2726.00, 3350.00, 2557.00, 2643.00],
            [8480.33, 3380.00, 2730.00, 3350.00, 2561.00, 2643.00],
            [8827.00, 3545.00, 2873.00, 3488.00, 2695.00, 2786.00],
            [8831.33, 3549.00, 2921.00, 3532.00, 2743.00, 2830.00],
            [8996.00, 3627.00, 2990.00, 3597.00, 2808.00, 2899.00],
            [9000.33, 3670.00, 2990.00, 3601.00, 2808.00, 2899.00],
            [9182.33, 3761.00, 3068.00, 3675.00, 2882.00, 2973.00],
            [9186.67, 3761.00, 3068.00, 3675.00, 2886.00, 2977.00],
            [9355.67, 3844.00, 3137.00, 3744.00, 2951.00, 3046.00],
            [9360.00, 3844.00, 3189.00, 3792.00, 2999.00, 3094.00],
            [9628.67, 3974.00, 3302.00, 3900.00, 3111.00, 3207.00],
            [9633.00, 4026.00, 3302.00, 3900.00, 3111.00, 3207.00],
            [9919.00, 4164.00, 3423.00, 4017.00, 3228.00, 3328.00],
            [9923.33, 4164.00, 3475.00, 4069.00, 3280.00, 3376.00],
            [9992.67, 4199.00, 3506.00, 4099.00, 3306.00, 3406.00],
            [9997.00, 4199.00, 3510.00, 4099.00, 3311.00, 3410.00],
            [10296.00, 4346.00, 3649.00, 4238.00, 3445.00, 3549.00],
            [10300.33, 4398.00, 3653.00, 4238.00, 3445.00, 3549.00],
            [10512.67, 4502.00, 3753.00, 4333.00, 3545.00, 3649.00],
            [10517.00, 4507.00, 3809.00, 4390.00, 3597.00, 3705.00],
            [10790.00, 4641.00, 3939.00, 4515.00, 3722.00, 3831.00],
            [10794.33, 4641.00, 3943.00, 4515.00, 3727.00, 3835.00],
            [11145.33, 4814.00, 4108.00, 4680.00, 3887.00, 4000.00],
            [11149.67, 4814.00, 4169.00, 4736.00, 3943.00, 4056.00],
            [11492.00, 4983.00, 4333.00, 4897.00, 4104.00, 4221.00],
            [11496.33, 4983.00, 4338.00, 4897.00, 4108.00, 4221.00],
            [11812.67, 5139.00, 4489.00, 5044.00, 4251.00, 4372.00],
            [11817.00, 5144.00, 4550.00, 5105.00, 4316.00, 4433.00],
            [11856.00, 5161.00, 4572.00, 5126.00, 4333.00, 4450.00],
            [11860.33, 5165.00, 4572.00, 5126.00, 4333.00, 4455.00],
            [12558.00, 5503.00, 4914.00, 5456.00, 4663.00, 4788.00],
            [12562.33, 5508.00, 4914.00, 5456.00, 4663.00, 4788.00],
            [12623.00, 5538.00, 4944.00, 5486.00, 4693.00, 4819.00],
            [12627.33, 5538.00, 4949.00, 5486.00, 4693.00, 4823.00],
            [13476.67, 5954.00, 5365.00, 5885.00, 5096.00, 5230.00],
            [13481.00, 5958.00, 5365.00, 5889.00, 5096.00, 5230.00],
            [14993.33, 6821.00, 6106.00, 6600.00, 5807.00, 5958.00],
            [14997.67, 6821.00, 6110.00, 6600.00, 5811.00, 5958.00],
        ];
    }
}
