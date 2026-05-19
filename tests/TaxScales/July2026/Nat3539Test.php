<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\Tests\TaxScales\July2026;

use ManageIt\PaygTax\PaygTax;
use ManageIt\PaygTax\Tests\Fixtures\Earning;
use ManageIt\PaygTax\Tests\Fixtures\Payee;
use ManageIt\PaygTax\Tests\Fixtures\Payer;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;

#[\PHPUnit\Framework\Attributes\CoversClass(\ManageIt\PaygTax\TaxScales\Nat3539::class)]
final class Nat3539Test extends TestCase
{
    #[\PHPUnit\Framework\Attributes\DataProvider('weeklyData')]
    public function testWeeklyWithholding(
        int $gross,
        float $scale1,
        float $scale2,
        float $scale3,
        float $scale5,
        float $scale6,
    ): void {
        $payer = new Payer();

        $payee = new Payee();
        $payee->payCycle = Payee::PAY_CYCLE_WEEKLY;
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;
        $payee->stsl = true;

        $earning = new Earning();
        $earning->date = new \DateTime('2026-07-15');
        $earning->gross = $gross;

        // Scale 1

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($scale1, $payg->getTaxWithheldAmount());

        // Scale 2

        $payee->claimsTaxFreeThreshold = true;

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($scale2, $payg->getTaxWithheldAmount());

        // Scale 3

        $payee->residencyStatus = Payee::FOREIGN_RESIDENT;

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($scale3, $payg->getTaxWithheldAmount());

        // Scale 5

        $payee->residencyStatus = Payee::RESIDENT;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_FULL;

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($scale5, $payg->getTaxWithheldAmount());

        // Scale 6

        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_HALF;

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($scale6, $payg->getTaxWithheldAmount());
    }

    /**
     * @return \Iterator<int, array<int, (float | int)>>
     */
    public static function weeklyData(): \Iterator
    {
        yield [116, 17.0, 0.0, 35.0, 0.0, 0.0];
        yield [117, 18.0, 0.0, 35.0, 0.0, 0.0];
        yield [187, 28.0, 0.0, 56.0, 0.0, 0.0];
        yield [188, 28.0, 0.0, 56.0, 0.0, 0.0];
        yield [249, 41.0, 0.0, 75.0, 0.0, 0.0];
        yield [250, 41.0, 0.0, 75.0, 0.0, 0.0];
        yield [361, 64.0, 0.0, 108.0, 0.0, 0.0];
        yield [362, 65.0, 0.0, 109.0, 0.0, 0.0];
        yield [370, 66.0, 1.0, 111.0, 1.0, 1.0];
        yield [371, 66.0, 1.0, 111.0, 1.0, 1.0];
        yield [514, 92.0, 23.0, 154.0, 23.0, 23.0];
        yield [515, 92.0, 23.0, 154.0, 23.0, 23.0];
        yield [537, 99.0, 26.0, 161.0, 26.0, 26.0];
        yield [538, 100.0, 27.0, 161.0, 27.0, 27.0];
        yield [672, 143.0, 60.0, 202.0, 47.0, 47.0];
        yield [673, 143.0, 60.0, 202.0, 47.0, 47.0];
        yield [720, 158.0, 68.0, 216.0, 54.0, 54.0];
        yield [721, 159.0, 68.0, 216.0, 54.0, 54.0];
        yield [864, 205.0, 94.0, 259.0, 77.0, 77.0];
        yield [865, 205.0, 94.0, 259.0, 77.0, 77.0];
        yield [908, 219.0, 108.0, 272.0, 90.0, 90.0];
        yield [909, 219.0, 108.0, 273.0, 90.0, 90.0];
        yield [931, 227.0, 116.0, 279.0, 97.0, 98.0];
        yield [932, 227.0, 116.0, 280.0, 97.0, 98.0];
        yield [986, 244.0, 133.0, 296.0, 114.0, 117.0];
        yield [987, 245.0, 134.0, 296.0, 114.0, 118.0];
        yield [1134, 314.0, 181.0, 340.0, 158.0, 170.0];
        yield [1135, 314.0, 181.0, 340.0, 159.0, 170.0];
        yield [1281, 383.0, 229.0, 384.0, 203.0, 216.0];
        yield [1282, 383.0, 229.0, 385.0, 203.0, 216.0];
        yield [1336, 409.0, 246.0, 401.0, 219.0, 233.0];
        yield [1337, 409.0, 247.0, 402.0, 220.0, 233.0];
        yield [1844, 647.0, 485.0, 630.0, 448.0, 466.0];
        yield [1845, 648.0, 485.0, 630.0, 448.0, 467.0];
        yield [2119, 777.0, 614.0, 753.0, 572.0, 593.0];
        yield [2120, 777.0, 615.0, 754.0, 572.0, 593.0];
        yield [2143, 788.0, 625.0, 764.0, 583.0, 604.0];
        yield [2144, 788.0, 626.0, 765.0, 583.0, 604.0];
        yield [2245, 838.0, 673.0, 810.0, 628.0, 651.0];
        yield [2246, 838.0, 674.0, 811.0, 629.0, 651.0];
        yield [2491, 976.0, 789.0, 921.0, 739.0, 764.0];
        yield [2493, 977.0, 790.0, 922.0, 740.0, 765.0];
        yield [2494, 977.0, 790.0, 922.0, 740.0, 765.0];
        yield [2595, 1034.0, 840.0, 970.0, 788.0, 814.0];
        yield [2596, 1034.0, 840.0, 970.0, 788.0, 814.0];
        yield [2598, 1036.0, 842.0, 971.0, 790.0, 816.0];
        yield [2652, 1066.0, 872.0, 1000.0, 819.0, 845.0];
        yield [2653, 1066.0, 872.0, 1001.0, 819.0, 846.0];
        yield [2727, 1108.0, 914.0, 1041.0, 859.0, 886.0];
        yield [2728, 1108.0, 914.0, 1041.0, 860.0, 887.0];
        yield [2737, 1113.0, 919.0, 1046.0, 865.0, 892.0];
        yield [2898, 1192.0, 1010.0, 1133.0, 952.0, 981.0];
        yield [2899, 1192.0, 1010.0, 1134.0, 952.0, 981.0];
        yield [3302, 1390.0, 1236.0, 1351.0, 1170.0, 1203.0];
        yield [3303, 1390.0, 1236.0, 1352.0, 1170.0, 1203.0];
        yield [3576, 1546.0, 1389.0, 1499.0, 1318.0, 1353.0];
        yield [3577, 1546.0, 1390.0, 1500.0, 1318.0, 1354.0];
        yield [3652, 1589.0, 1427.0, 1535.0, 1353.0, 1390.0];
        yield [3653, 1590.0, 1427.0, 1536.0, 1354.0, 1390.0];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('fortnightlyData')]
    public function testFortnightlyWithholding(
        int $gross,
        float $scale1,
        float $scale2,
        float $scale3,
        float $scale5,
        float $scale6,
    ): void {
        $payer = new Payer();

        $payee = new Payee();
        $payee->payCycle = Payee::PAY_CYCLE_FORTNIGHTLY;
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;
        $payee->stsl = true;

        $earning = new Earning();
        $earning->date = new \DateTime('2026-07-15');
        $earning->gross = $gross;

        // Scale 1

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($scale1, $payg->getTaxWithheldAmount());

        // Scale 2

        $payee->claimsTaxFreeThreshold = true;

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($scale2, $payg->getTaxWithheldAmount());

        // Scale 3

        $payee->residencyStatus = Payee::FOREIGN_RESIDENT;

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($scale3, $payg->getTaxWithheldAmount());

        // Scale 5

        $payee->residencyStatus = Payee::RESIDENT;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_FULL;

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($scale5, $payg->getTaxWithheldAmount());

        // Scale 6

        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_HALF;

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($scale6, $payg->getTaxWithheldAmount());
    }

    /**
     * @return \Iterator<int, array<int, (float | int)>>
     */
    public static function fortnightlyData(): \Iterator
    {
        yield [232, 34.0, 0.0, 70.0, 0.0, 0.0];
        yield [234, 36.0, 0.0, 70.0, 0.0, 0.0];
        yield [374, 56.0, 0.0, 112.0, 0.0, 0.0];
        yield [376, 56.0, 0.0, 112.0, 0.0, 0.0];
        yield [498, 82.0, 0.0, 150.0, 0.0, 0.0];
        yield [500, 82.0, 0.0, 150.0, 0.0, 0.0];
        yield [722, 128.0, 0.0, 216.0, 0.0, 0.0];
        yield [724, 130.0, 0.0, 218.0, 0.0, 0.0];
        yield [740, 132.0, 2.0, 222.0, 2.0, 2.0];
        yield [742, 132.0, 2.0, 222.0, 2.0, 2.0];
        yield [1028, 184.0, 46.0, 308.0, 46.0, 46.0];
        yield [1030, 184.0, 46.0, 308.0, 46.0, 46.0];
        yield [1074, 198.0, 52.0, 322.0, 52.0, 52.0];
        yield [1076, 200.0, 54.0, 322.0, 54.0, 54.0];
        yield [1344, 286.0, 120.0, 404.0, 94.0, 94.0];
        yield [1346, 286.0, 120.0, 404.0, 94.0, 94.0];
        yield [1440, 316.0, 136.0, 432.0, 108.0, 108.0];
        yield [1442, 318.0, 136.0, 432.0, 108.0, 108.0];
        yield [1728, 410.0, 188.0, 518.0, 154.0, 154.0];
        yield [1730, 410.0, 188.0, 518.0, 154.0, 154.0];
        yield [1816, 438.0, 216.0, 544.0, 180.0, 180.0];
        yield [1818, 438.0, 216.0, 546.0, 180.0, 180.0];
        yield [1862, 454.0, 232.0, 558.0, 194.0, 196.0];
        yield [1864, 454.0, 232.0, 560.0, 194.0, 196.0];
        yield [1972, 488.0, 266.0, 592.0, 228.0, 234.0];
        yield [1974, 490.0, 268.0, 592.0, 228.0, 236.0];
        yield [2268, 628.0, 362.0, 680.0, 316.0, 340.0];
        yield [2270, 628.0, 362.0, 680.0, 318.0, 340.0];
        yield [2562, 766.0, 458.0, 768.0, 406.0, 432.0];
        yield [2564, 766.0, 458.0, 770.0, 406.0, 432.0];
        yield [2672, 818.0, 492.0, 802.0, 438.0, 466.0];
        yield [2674, 818.0, 494.0, 804.0, 440.0, 466.0];
        yield [3688, 1294.0, 970.0, 1260.0, 896.0, 932.0];
        yield [3690, 1296.0, 970.0, 1260.0, 896.0, 934.0];
        yield [4238, 1554.0, 1228.0, 1506.0, 1144.0, 1186.0];
        yield [4240, 1554.0, 1230.0, 1508.0, 1144.0, 1186.0];
        yield [4286, 1576.0, 1250.0, 1528.0, 1166.0, 1208.0];
        yield [4288, 1576.0, 1252.0, 1530.0, 1166.0, 1208.0];
        yield [4490, 1676.0, 1346.0, 1620.0, 1256.0, 1302.0];
        yield [4492, 1676.0, 1348.0, 1622.0, 1258.0, 1302.0];
        yield [4982, 1952.0, 1578.0, 1842.0, 1478.0, 1528.0];
        yield [4986, 1954.0, 1580.0, 1844.0, 1480.0, 1530.0];
        yield [4988, 1954.0, 1580.0, 1844.0, 1480.0, 1530.0];
        yield [5190, 2068.0, 1680.0, 1940.0, 1576.0, 1628.0];
        yield [5192, 2068.0, 1680.0, 1940.0, 1576.0, 1628.0];
        yield [5196, 2072.0, 1684.0, 1942.0, 1580.0, 1632.0];
        yield [5304, 2132.0, 1744.0, 2000.0, 1638.0, 1690.0];
        yield [5306, 2132.0, 1744.0, 2002.0, 1638.0, 1692.0];
        yield [5454, 2216.0, 1828.0, 2082.0, 1718.0, 1772.0];
        yield [5456, 2216.0, 1828.0, 2082.0, 1720.0, 1774.0];
        yield [5474, 2226.0, 1838.0, 2092.0, 1730.0, 1784.0];
        yield [5796, 2384.0, 2020.0, 2266.0, 1904.0, 1962.0];
        yield [5798, 2384.0, 2020.0, 2268.0, 1904.0, 1962.0];
        yield [6604, 2780.0, 2472.0, 2702.0, 2340.0, 2406.0];
        yield [6606, 2780.0, 2472.0, 2704.0, 2340.0, 2406.0];
        yield [7152, 3092.0, 2778.0, 2998.0, 2636.0, 2706.0];
        yield [7154, 3092.0, 2780.0, 3000.0, 2636.0, 2708.0];
        yield [7304, 3178.0, 2854.0, 3070.0, 2706.0, 2780.0];
        yield [7306, 3180.0, 2854.0, 3072.0, 2708.0, 2780.0];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('monthlyData')]
    public function testMonthlyWithholding(
        float $gross,
        float $scale1,
        float $scale2,
        float $scale3,
        float $scale5,
        float $scale6,
    ): void {
        $payer = new Payer();

        $payee = new Payee();
        $payee->payCycle = Payee::PAY_CYCLE_MONTHLY;
        $payee->residencyStatus = Payee::RESIDENT;
        $payee->tfn = true;
        $payee->claimsTaxFreeThreshold = false;
        $payee->stsl = true;

        $earning = new Earning();
        $earning->date = new \DateTime('2026-07-15');
        $earning->gross = $gross;

        // Scale 1

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($scale1, $payg->getTaxWithheldAmount());

        // Scale 2

        $payee->claimsTaxFreeThreshold = true;

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($scale2, $payg->getTaxWithheldAmount());

        // Scale 3

        $payee->residencyStatus = Payee::FOREIGN_RESIDENT;

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($scale3, $payg->getTaxWithheldAmount());

        // Scale 5

        $payee->residencyStatus = Payee::RESIDENT;
        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_FULL;

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($scale5, $payg->getTaxWithheldAmount());

        // Scale 6

        $payee->medicareLevyExemption = Payee::MEDICARE_LEVY_EXEMPTION_HALF;

        $payg = PaygTax::new()->setPayer($payer)->setPayee($payee)->setEarning($earning);

        Assert::assertEquals($scale6, $payg->getTaxWithheldAmount());
    }

    /**
     * @return \Iterator<int, array<int, (float | int)>>
     */
    public static function monthlyData(): \Iterator
    {
        yield [502.67, 74.0, 0.0, 152.0, 0.0, 0.0];
        yield [507.0, 78.0, 0.0, 152.0, 0.0, 0.0];
        yield [810.33, 121.0, 0.0, 243.0, 0.0, 0.0];
        yield [814.67, 121.0, 0.0, 243.0, 0.0, 0.0];
        yield [1079.0, 178.0, 0.0, 325.0, 0.0, 0.0];
        yield [1083.33, 178.0, 0.0, 325.0, 0.0, 0.0];
        yield [1564.33, 277.0, 0.0, 468.0, 0.0, 0.0];
        yield [1568.67, 282.0, 0.0, 472.0, 0.0, 0.0];
        yield [1603.33, 286.0, 4.0, 481.0, 4.0, 4.0];
        yield [1607.67, 286.0, 4.0, 481.0, 4.0, 4.0];
        yield [2227.33, 399.0, 100.0, 667.0, 100.0, 100.0];
        yield [2231.67, 399.0, 100.0, 667.0, 100.0, 100.0];
        yield [2327.0, 429.0, 113.0, 698.0, 113.0, 113.0];
        yield [2331.33, 433.0, 117.0, 698.0, 117.0, 117.0];
        yield [2912.0, 620.0, 260.0, 875.0, 204.0, 204.0];
        yield [2916.33, 620.0, 260.0, 875.0, 204.0, 204.0];
        yield [3120.0, 685.0, 295.0, 936.0, 234.0, 234.0];
        yield [3124.33, 689.0, 295.0, 936.0, 234.0, 234.0];
        yield [3744.0, 888.0, 407.0, 1122.0, 334.0, 334.0];
        yield [3748.33, 888.0, 407.0, 1122.0, 334.0, 334.0];
        yield [3934.67, 949.0, 468.0, 1179.0, 390.0, 390.0];
        yield [3939.0, 949.0, 468.0, 1183.0, 390.0, 390.0];
        yield [4034.33, 984.0, 503.0, 1209.0, 420.0, 425.0];
        yield [4038.67, 984.0, 503.0, 1213.0, 420.0, 425.0];
        yield [4272.67, 1057.0, 576.0, 1283.0, 494.0, 507.0];
        yield [4277.0, 1062.0, 581.0, 1283.0, 494.0, 511.0];
        yield [4914.0, 1361.0, 784.0, 1473.0, 685.0, 737.0];
        yield [4918.33, 1361.0, 784.0, 1473.0, 689.0, 737.0];
        yield [5551.0, 1660.0, 992.0, 1664.0, 880.0, 936.0];
        yield [5555.33, 1660.0, 992.0, 1668.0, 880.0, 936.0];
        yield [5789.33, 1772.0, 1066.0, 1738.0, 949.0, 1010.0];
        yield [5793.67, 1772.0, 1070.0, 1742.0, 953.0, 1010.0];
        yield [7990.67, 2804.0, 2102.0, 2730.0, 1941.0, 2019.0];
        yield [7995.0, 2808.0, 2102.0, 2730.0, 1941.0, 2024.0];
        yield [9182.33, 3367.0, 2661.0, 3263.0, 2479.0, 2570.0];
        yield [9186.67, 3367.0, 2665.0, 3267.0, 2479.0, 2570.0];
        yield [9286.33, 3415.0, 2708.0, 3311.0, 2526.0, 2617.0];
        yield [9290.67, 3415.0, 2713.0, 3315.0, 2526.0, 2617.0];
        yield [9728.33, 3631.0, 2916.0, 3510.0, 2721.0, 2821.0];
        yield [9732.67, 3631.0, 2921.0, 3514.0, 2726.0, 2821.0];
        yield [10794.33, 4229.0, 3419.0, 3991.0, 3202.0, 3311.0];
        yield [10803.0, 4234.0, 3423.0, 3995.0, 3207.0, 3315.0];
        yield [10807.33, 4234.0, 3423.0, 3995.0, 3207.0, 3315.0];
        yield [11245.0, 4481.0, 3640.0, 4203.0, 3415.0, 3527.0];
        yield [11249.33, 4481.0, 3640.0, 4203.0, 3415.0, 3527.0];
        yield [11258.0, 4489.0, 3649.0, 4208.0, 3423.0, 3536.0];
        yield [11492.0, 4619.0, 3779.0, 4333.0, 3549.0, 3662.0];
        yield [11496.33, 4619.0, 3779.0, 4338.0, 3549.0, 3666.0];
        yield [11817.0, 4801.0, 3961.0, 4511.0, 3722.0, 3839.0];
        yield [11821.33, 4801.0, 3961.0, 4511.0, 3727.0, 3844.0];
        yield [11860.33, 4823.0, 3982.0, 4533.0, 3748.0, 3865.0];
        yield [12558.0, 5165.0, 4377.0, 4910.0, 4125.0, 4251.0];
        yield [12562.33, 5165.0, 4377.0, 4914.0, 4125.0, 4251.0];
        yield [14308.67, 6023.0, 5356.0, 5854.0, 5070.0, 5213.0];
        yield [14313.0, 6023.0, 5356.0, 5859.0, 5070.0, 5213.0];
        yield [15496.0, 6699.0, 6019.0, 6496.0, 5711.0, 5863.0];
        yield [15500.33, 6699.0, 6023.0, 6500.0, 5711.0, 5867.0];
        yield [15825.33, 6886.0, 6184.0, 6652.0, 5863.0, 6023.0];
        yield [15829.67, 6890.0, 6184.0, 6656.0, 5867.0, 6023.0];
    }
}
