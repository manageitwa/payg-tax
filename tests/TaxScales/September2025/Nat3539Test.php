<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\Tests\TaxScales\September2025;

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
        $earning->date = new \DateTime('2025-09-30');
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
        yield [116, 19.0, 0.0, 35.0, 0.0, 0.0];
        yield [117, 19.0, 0.0, 35.0, 0.0, 0.0];
        yield [149, 24.0, 0.0, 45.0, 0.0, 0.0];
        yield [150, 24.0, 0.0, 45.0, 0.0, 0.0];
        yield [249, 45.0, 0.0, 75.0, 0.0, 0.0];
        yield [250, 45.0, 0.0, 75.0, 0.0, 0.0];
        yield [360, 69.0, 0.0, 108.0, 0.0, 0.0];
        yield [361, 69.0, 0.0, 108.0, 0.0, 0.0];
        yield [370, 71.0, 2.0, 111.0, 2.0, 2.0];
        yield [371, 71.0, 2.0, 111.0, 2.0, 2.0];
        yield [499, 95.0, 22.0, 150.0, 22.0, 22.0];
        yield [500, 95.0, 22.0, 150.0, 22.0, 22.0];
        yield [514, 98.0, 26.0, 154.0, 25.0, 25.0];
        yield [515, 98.0, 26.0, 154.0, 25.0, 25.0];
        yield [624, 133.0, 55.0, 187.0, 42.0, 42.0];
        yield [625, 134.0, 55.0, 187.0, 42.0, 42.0];
        yield [720, 164.0, 72.0, 216.0, 58.0, 58.0];
        yield [721, 165.0, 72.0, 216.0, 58.0, 58.0];
        yield [842, 204.0, 95.0, 253.0, 78.0, 78.0];
        yield [843, 204.0, 95.0, 253.0, 78.0, 78.0];
        yield [865, 211.0, 99.0, 259.0, 82.0, 83.0];
        yield [866, 212.0, 100.0, 260.0, 82.0, 84.0];
        yield [931, 233.0, 121.0, 279.0, 102.0, 107.0];
        yield [932, 233.0, 121.0, 280.0, 102.0, 107.0];
        yield [937, 234.0, 123.0, 281.0, 104.0, 109.0];
        yield [938, 235.0, 123.0, 281.0, 104.0, 109.0];
        yield [1052, 288.0, 160.0, 316.0, 139.0, 149.0];
        yield [1053, 289.0, 160.0, 316.0, 139.0, 150.0];
        yield [1281, 396.0, 234.0, 384.0, 208.0, 221.0];
        yield [1282, 397.0, 234.0, 385.0, 208.0, 221.0];
        yield [1287, 399.0, 236.0, 386.0, 210.0, 223.0];
        yield [1288, 399.0, 236.0, 387.0, 210.0, 223.0];
        yield [1691, 589.0, 425.0, 568.0, 392.0, 408.0];
        yield [1692, 589.0, 426.0, 569.0, 392.0, 409.0];
        yield [1813, 646.0, 483.0, 623.0, 446.0, 465.0];
        yield [1814, 647.0, 483.0, 623.0, 447.0, 465.0];
        yield [1816, 647.0, 484.0, 624.0, 448.0, 466.0];
        yield [1817, 648.0, 485.0, 625.0, 448.0, 466.0];
        yield [1844, 661.0, 497.0, 637.0, 460.0, 479.0];
        yield [1845, 661.0, 498.0, 637.0, 461.0, 479.0];
        yield [1926, 699.0, 536.0, 674.0, 497.0, 517.0];
        yield [1943, 707.0, 544.0, 682.0, 505.0, 524.0];
        yield [1944, 708.0, 544.0, 682.0, 505.0, 525.0];
        yield [2041, 753.0, 590.0, 726.0, 549.0, 569.0];
        yield [2042, 754.0, 590.0, 726.0, 549.0, 570.0];
        yield [2044, 755.0, 591.0, 727.0, 550.0, 571.0];
        yield [2052, 758.0, 595.0, 731.0, 554.0, 575.0];
        yield [2053, 759.0, 596.0, 731.0, 554.0, 575.0];
        yield [2082, 773.0, 609.0, 744.0, 567.0, 588.0];
        yield [2119, 791.0, 627.0, 761.0, 584.0, 605.0];
        yield [2120, 792.0, 627.0, 761.0, 585.0, 606.0];
        yield [2163, 813.0, 647.0, 781.0, 604.0, 626.0];
        yield [2164, 813.0, 648.0, 781.0, 604.0, 626.0];
        yield [2227, 844.0, 677.0, 809.0, 633.0, 655.0];
        yield [2228, 845.0, 678.0, 810.0, 633.0, 655.0];
        yield [2245, 853.0, 686.0, 817.0, 641.0, 663.0];
        yield [2246, 854.0, 686.0, 818.0, 641.0, 664.0];
        yield [2293, 880.0, 708.0, 839.0, 662.0, 685.0];
        yield [2294, 880.0, 709.0, 839.0, 663.0, 686.0];
        yield [2381, 929.0, 750.0, 879.0, 702.0, 726.0];
        yield [2382, 930.0, 750.0, 879.0, 702.0, 726.0];
        yield [2402, 941.0, 760.0, 888.0, 711.0, 736.0];
        yield [2403, 941.0, 760.0, 889.0, 712.0, 736.0];
        yield [2431, 957.0, 774.0, 902.0, 725.0, 749.0];
        yield [2432, 958.0, 774.0, 902.0, 726.0, 750.0];
        yield [2490, 990.0, 803.0, 929.0, 753.0, 778.0];
        yield [2491, 991.0, 803.0, 930.0, 753.0, 778.0];
        yield [2545, 1021.0, 830.0, 955.0, 779.0, 804.0];
        yield [2546, 1022.0, 830.0, 956.0, 779.0, 805.0];
        yield [2577, 1039.0, 845.0, 970.0, 794.0, 820.0];
        yield [2578, 1039.0, 846.0, 971.0, 794.0, 820.0];
        yield [2595, 1049.0, 854.0, 979.0, 802.0, 828.0];
        yield [2596, 1050.0, 855.0, 979.0, 803.0, 829.0];
        yield [2596, 1050.0, 855.0, 979.0, 803.0, 829.0];
        yield [2597, 1050.0, 855.0, 980.0, 803.0, 829.0];
        yield [2652, 1077.0, 886.0, 1010.0, 833.0, 859.0];
        yield [2653, 1078.0, 887.0, 1010.0, 834.0, 860.0];
        yield [2719, 1110.0, 924.0, 1046.0, 869.0, 896.0];
        yield [2720, 1110.0, 924.0, 1046.0, 870.0, 897.0];
        yield [2731, 1116.0, 930.0, 1052.0, 876.0, 903.0];
        yield [2732, 1116.0, 931.0, 1053.0, 876.0, 903.0];
        yield [2736, 1118.0, 933.0, 1055.0, 878.0, 906.0];
        yield [2737, 1119.0, 934.0, 1055.0, 879.0, 906.0];
        yield [2895, 1196.0, 1022.0, 1141.0, 964.0, 993.0];
        yield [2896, 1197.0, 1023.0, 1141.0, 965.0, 994.0];
        yield [2898, 1198.0, 1024.0, 1142.0, 966.0, 995.0];
        yield [2899, 1198.0, 1024.0, 1143.0, 966.0, 995.0];
        yield [3069, 1281.0, 1120.0, 1235.0, 1058.0, 1089.0];
        yield [3070, 1282.0, 1120.0, 1235.0, 1059.0, 1089.0];
        yield [3302, 1396.0, 1250.0, 1361.0, 1184.0, 1217.0];
        yield [3303, 1396.0, 1251.0, 1361.0, 1185.0, 1218.0];
        yield [3446, 1478.0, 1331.0, 1438.0, 1262.0, 1296.0];
        yield [3447, 1478.0, 1331.0, 1439.0, 1262.0, 1297.0];
        yield [3652, 1595.0, 1432.0, 1535.0, 1359.0, 1395.0];
        yield [3653, 1596.0, 1432.0, 1536.0, 1359.0, 1396.0];
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
        $earning->date = new \DateTime('2025-09-30');
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
        yield [232, 38.0, 0.0, 70.0, 0.0, 0.0];
        yield [234, 38.0, 0.0, 70.0, 0.0, 0.0];
        yield [298, 48.0, 0.0, 90.0, 0.0, 0.0];
        yield [300, 48.0, 0.0, 90.0, 0.0, 0.0];
        yield [498, 90.0, 0.0, 150.0, 0.0, 0.0];
        yield [500, 90.0, 0.0, 150.0, 0.0, 0.0];
        yield [720, 138.0, 0.0, 216.0, 0.0, 0.0];
        yield [722, 138.0, 0.0, 216.0, 0.0, 0.0];
        yield [740, 142.0, 4.0, 222.0, 4.0, 4.0];
        yield [742, 142.0, 4.0, 222.0, 4.0, 4.0];
        yield [998, 190.0, 44.0, 300.0, 44.0, 44.0];
        yield [1000, 190.0, 44.0, 300.0, 44.0, 44.0];
        yield [1028, 196.0, 52.0, 308.0, 50.0, 50.0];
        yield [1030, 196.0, 52.0, 308.0, 50.0, 50.0];
        yield [1248, 266.0, 110.0, 374.0, 84.0, 84.0];
        yield [1250, 268.0, 110.0, 374.0, 84.0, 84.0];
        yield [1440, 328.0, 144.0, 432.0, 116.0, 116.0];
        yield [1442, 330.0, 144.0, 432.0, 116.0, 116.0];
        yield [1684, 408.0, 190.0, 506.0, 156.0, 156.0];
        yield [1686, 408.0, 190.0, 506.0, 156.0, 156.0];
        yield [1730, 422.0, 198.0, 518.0, 164.0, 166.0];
        yield [1732, 424.0, 200.0, 520.0, 164.0, 168.0];
        yield [1862, 466.0, 242.0, 558.0, 204.0, 214.0];
        yield [1864, 466.0, 242.0, 560.0, 204.0, 214.0];
        yield [1874, 468.0, 246.0, 562.0, 208.0, 218.0];
        yield [1876, 470.0, 246.0, 562.0, 208.0, 218.0];
        yield [2104, 576.0, 320.0, 632.0, 278.0, 298.0];
        yield [2106, 578.0, 320.0, 632.0, 278.0, 300.0];
        yield [2562, 792.0, 468.0, 768.0, 416.0, 442.0];
        yield [2564, 794.0, 468.0, 770.0, 416.0, 442.0];
        yield [2574, 798.0, 472.0, 772.0, 420.0, 446.0];
        yield [2576, 798.0, 472.0, 774.0, 420.0, 446.0];
        yield [3382, 1178.0, 850.0, 1136.0, 784.0, 816.0];
        yield [3384, 1178.0, 852.0, 1138.0, 784.0, 818.0];
        yield [3626, 1292.0, 966.0, 1246.0, 892.0, 930.0];
        yield [3628, 1294.0, 966.0, 1246.0, 894.0, 930.0];
        yield [3632, 1294.0, 968.0, 1248.0, 896.0, 932.0];
        yield [3634, 1296.0, 970.0, 1250.0, 896.0, 932.0];
        yield [3688, 1322.0, 994.0, 1274.0, 920.0, 958.0];
        yield [3690, 1322.0, 996.0, 1274.0, 922.0, 958.0];
        yield [3852, 1398.0, 1072.0, 1348.0, 994.0, 1034.0];
        yield [3886, 1414.0, 1088.0, 1364.0, 1010.0, 1048.0];
        yield [3888, 1416.0, 1088.0, 1364.0, 1010.0, 1050.0];
        yield [4082, 1506.0, 1180.0, 1452.0, 1098.0, 1138.0];
        yield [4084, 1508.0, 1180.0, 1452.0, 1098.0, 1140.0];
        yield [4088, 1510.0, 1182.0, 1454.0, 1100.0, 1142.0];
        yield [4104, 1516.0, 1190.0, 1462.0, 1108.0, 1150.0];
        yield [4106, 1518.0, 1192.0, 1462.0, 1108.0, 1150.0];
        yield [4164, 1546.0, 1218.0, 1488.0, 1134.0, 1176.0];
        yield [4238, 1582.0, 1254.0, 1522.0, 1168.0, 1210.0];
        yield [4240, 1584.0, 1254.0, 1522.0, 1170.0, 1212.0];
        yield [4326, 1626.0, 1294.0, 1562.0, 1208.0, 1252.0];
        yield [4328, 1626.0, 1296.0, 1562.0, 1208.0, 1252.0];
        yield [4454, 1688.0, 1354.0, 1618.0, 1266.0, 1310.0];
        yield [4456, 1690.0, 1356.0, 1620.0, 1266.0, 1310.0];
        yield [4490, 1706.0, 1372.0, 1634.0, 1282.0, 1326.0];
        yield [4492, 1708.0, 1372.0, 1636.0, 1282.0, 1328.0];
        yield [4586, 1760.0, 1416.0, 1678.0, 1324.0, 1370.0];
        yield [4588, 1760.0, 1418.0, 1678.0, 1326.0, 1372.0];
        yield [4762, 1858.0, 1500.0, 1758.0, 1404.0, 1452.0];
        yield [4764, 1860.0, 1500.0, 1758.0, 1404.0, 1452.0];
        yield [4804, 1882.0, 1520.0, 1776.0, 1422.0, 1472.0];
        yield [4806, 1882.0, 1520.0, 1778.0, 1424.0, 1472.0];
        yield [4862, 1914.0, 1548.0, 1804.0, 1450.0, 1498.0];
        yield [4864, 1916.0, 1548.0, 1804.0, 1452.0, 1500.0];
        yield [4980, 1980.0, 1606.0, 1858.0, 1506.0, 1556.0];
        yield [4982, 1982.0, 1606.0, 1860.0, 1506.0, 1556.0];
        yield [5090, 2042.0, 1660.0, 1910.0, 1558.0, 1608.0];
        yield [5092, 2044.0, 1660.0, 1912.0, 1558.0, 1610.0];
        yield [5154, 2078.0, 1690.0, 1940.0, 1588.0, 1640.0];
        yield [5156, 2078.0, 1692.0, 1942.0, 1588.0, 1640.0];
        yield [5190, 2098.0, 1708.0, 1958.0, 1604.0, 1656.0];
        yield [5192, 2100.0, 1710.0, 1958.0, 1606.0, 1658.0];
        yield [5192, 2100.0, 1710.0, 1958.0, 1606.0, 1658.0];
        yield [5194, 2100.0, 1710.0, 1960.0, 1606.0, 1658.0];
        yield [5304, 2154.0, 1772.0, 2020.0, 1666.0, 1718.0];
        yield [5306, 2156.0, 1774.0, 2020.0, 1668.0, 1720.0];
        yield [5438, 2220.0, 1848.0, 2092.0, 1738.0, 1792.0];
        yield [5440, 2220.0, 1848.0, 2092.0, 1740.0, 1794.0];
        yield [5462, 2232.0, 1860.0, 2104.0, 1752.0, 1806.0];
        yield [5464, 2232.0, 1862.0, 2106.0, 1752.0, 1806.0];
        yield [5472, 2236.0, 1866.0, 2110.0, 1756.0, 1812.0];
        yield [5474, 2238.0, 1868.0, 2110.0, 1758.0, 1812.0];
        yield [5790, 2392.0, 2044.0, 2282.0, 1928.0, 1986.0];
        yield [5792, 2394.0, 2046.0, 2282.0, 1930.0, 1988.0];
        yield [5796, 2396.0, 2048.0, 2284.0, 1932.0, 1990.0];
        yield [5798, 2396.0, 2048.0, 2286.0, 1932.0, 1990.0];
        yield [6138, 2562.0, 2240.0, 2470.0, 2116.0, 2178.0];
        yield [6140, 2564.0, 2240.0, 2470.0, 2118.0, 2178.0];
        yield [6604, 2792.0, 2500.0, 2722.0, 2368.0, 2434.0];
        yield [6606, 2792.0, 2502.0, 2722.0, 2370.0, 2436.0];
        yield [6892, 2956.0, 2662.0, 2876.0, 2524.0, 2592.0];
        yield [6894, 2956.0, 2662.0, 2878.0, 2524.0, 2594.0];
        yield [7304, 3190.0, 2864.0, 3070.0, 2718.0, 2790.0];
        yield [7306, 3192.0, 2864.0, 3072.0, 2718.0, 2792.0];
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
        $earning->date = new \DateTime('2025-09-30');
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
        yield [502.67, 82.0, 0.0, 152.0, 0.0, 0.0];
        yield [507.0, 82.0, 0.0, 152.0, 0.0, 0.0];
        yield [645.67, 104.0, 0.0, 195.0, 0.0, 0.0];
        yield [650.0, 104.0, 0.0, 195.0, 0.0, 0.0];
        yield [1079.0, 195.0, 0.0, 325.0, 0.0, 0.0];
        yield [1083.33, 195.0, 0.0, 325.0, 0.0, 0.0];
        yield [1560.0, 299.0, 0.0, 468.0, 0.0, 0.0];
        yield [1564.33, 299.0, 0.0, 468.0, 0.0, 0.0];
        yield [1603.33, 308.0, 9.0, 481.0, 9.0, 9.0];
        yield [1607.67, 308.0, 9.0, 481.0, 9.0, 9.0];
        yield [2162.33, 412.0, 95.0, 650.0, 95.0, 95.0];
        yield [2166.67, 412.0, 95.0, 650.0, 95.0, 95.0];
        yield [2227.33, 425.0, 113.0, 667.0, 108.0, 108.0];
        yield [2231.67, 425.0, 113.0, 667.0, 108.0, 108.0];
        yield [2704.0, 576.0, 238.0, 810.0, 182.0, 182.0];
        yield [2708.33, 581.0, 238.0, 810.0, 182.0, 182.0];
        yield [3120.0, 711.0, 312.0, 936.0, 251.0, 251.0];
        yield [3124.33, 715.0, 312.0, 936.0, 251.0, 251.0];
        yield [3648.67, 884.0, 412.0, 1096.0, 338.0, 338.0];
        yield [3653.0, 884.0, 412.0, 1096.0, 338.0, 338.0];
        yield [3748.33, 914.0, 429.0, 1122.0, 355.0, 360.0];
        yield [3752.67, 919.0, 433.0, 1127.0, 355.0, 364.0];
        yield [4034.33, 1010.0, 524.0, 1209.0, 442.0, 464.0];
        yield [4038.67, 1010.0, 524.0, 1213.0, 442.0, 464.0];
        yield [4060.33, 1014.0, 533.0, 1218.0, 451.0, 472.0];
        yield [4064.67, 1018.0, 533.0, 1218.0, 451.0, 472.0];
        yield [4558.67, 1248.0, 693.0, 1369.0, 602.0, 646.0];
        yield [4563.0, 1252.0, 693.0, 1369.0, 602.0, 650.0];
        yield [5551.0, 1716.0, 1014.0, 1664.0, 901.0, 958.0];
        yield [5555.33, 1720.0, 1014.0, 1668.0, 901.0, 958.0];
        yield [5577.0, 1729.0, 1023.0, 1673.0, 910.0, 966.0];
        yield [5581.33, 1729.0, 1023.0, 1677.0, 910.0, 966.0];
        yield [7327.67, 2552.0, 1842.0, 2461.0, 1699.0, 1768.0];
        yield [7332.0, 2552.0, 1846.0, 2466.0, 1699.0, 1772.0];
        yield [7856.33, 2799.0, 2093.0, 2700.0, 1933.0, 2015.0];
        yield [7860.67, 2804.0, 2093.0, 2700.0, 1937.0, 2015.0];
        yield [7869.33, 2804.0, 2097.0, 2704.0, 1941.0, 2019.0];
        yield [7873.67, 2808.0, 2102.0, 2708.0, 1941.0, 2019.0];
        yield [7990.67, 2864.0, 2154.0, 2760.0, 1993.0, 2076.0];
        yield [7995.0, 2864.0, 2158.0, 2760.0, 1998.0, 2076.0];
        yield [8346.0, 3029.0, 2323.0, 2921.0, 2154.0, 2240.0];
        yield [8419.67, 3064.0, 2357.0, 2955.0, 2188.0, 2271.0];
        yield [8424.0, 3068.0, 2357.0, 2955.0, 2188.0, 2275.0];
        yield [8844.33, 3263.0, 2557.0, 3146.0, 2379.0, 2466.0];
        yield [8848.67, 3267.0, 2557.0, 3146.0, 2379.0, 2470.0];
        yield [8857.33, 3272.0, 2561.0, 3150.0, 2383.0, 2474.0];
        yield [8892.0, 3285.0, 2578.0, 3168.0, 2401.0, 2492.0];
        yield [8896.33, 3289.0, 2583.0, 3168.0, 2401.0, 2492.0];
        yield [9022.0, 3350.0, 2639.0, 3224.0, 2457.0, 2548.0];
        yield [9182.33, 3428.0, 2717.0, 3298.0, 2531.0, 2622.0];
        yield [9186.67, 3432.0, 2717.0, 3298.0, 2535.0, 2626.0];
        yield [9373.0, 3523.0, 2804.0, 3384.0, 2617.0, 2713.0];
        yield [9377.33, 3523.0, 2808.0, 3384.0, 2617.0, 2713.0];
        yield [9650.33, 3657.0, 2934.0, 3506.0, 2743.0, 2838.0];
        yield [9654.67, 3662.0, 2938.0, 3510.0, 2743.0, 2838.0];
        yield [9728.33, 3696.0, 2973.0, 3540.0, 2778.0, 2873.0];
        yield [9732.67, 3701.0, 2973.0, 3545.0, 2778.0, 2877.0];
        yield [9936.33, 3813.0, 3068.0, 3636.0, 2869.0, 2968.0];
        yield [9940.67, 3813.0, 3072.0, 3636.0, 2873.0, 2973.0];
        yield [10317.67, 4026.0, 3250.0, 3809.0, 3042.0, 3146.0];
        yield [10322.0, 4030.0, 3250.0, 3809.0, 3042.0, 3146.0];
        yield [10408.67, 4078.0, 3293.0, 3848.0, 3081.0, 3189.0];
        yield [10413.0, 4078.0, 3293.0, 3852.0, 3085.0, 3189.0];
        yield [10534.33, 4147.0, 3354.0, 3909.0, 3142.0, 3246.0];
        yield [10538.67, 4151.0, 3354.0, 3909.0, 3146.0, 3250.0];
        yield [10790.0, 4290.0, 3480.0, 4026.0, 3263.0, 3371.0];
        yield [10794.33, 4294.0, 3480.0, 4030.0, 3263.0, 3371.0];
        yield [11028.33, 4424.0, 3597.0, 4138.0, 3376.0, 3484.0];
        yield [11032.67, 4429.0, 3597.0, 4143.0, 3376.0, 3488.0];
        yield [11167.0, 4502.0, 3662.0, 4203.0, 3441.0, 3553.0];
        yield [11171.33, 4502.0, 3666.0, 4208.0, 3441.0, 3553.0];
        yield [11245.0, 4546.0, 3701.0, 4242.0, 3475.0, 3588.0];
        yield [11249.33, 4550.0, 3705.0, 4242.0, 3480.0, 3592.0];
        yield [11249.33, 4550.0, 3705.0, 4242.0, 3480.0, 3592.0];
        yield [11253.67, 4550.0, 3705.0, 4247.0, 3480.0, 3592.0];
        yield [11492.0, 4667.0, 3839.0, 4377.0, 3610.0, 3722.0];
        yield [11496.33, 4671.0, 3844.0, 4377.0, 3614.0, 3727.0];
        yield [11782.33, 4810.0, 4004.0, 4533.0, 3766.0, 3883.0];
        yield [11786.67, 4810.0, 4004.0, 4533.0, 3770.0, 3887.0];
        yield [11834.33, 4836.0, 4030.0, 4559.0, 3796.0, 3913.0];
        yield [11838.67, 4836.0, 4034.0, 4563.0, 3796.0, 3913.0];
        yield [11856.0, 4845.0, 4043.0, 4572.0, 3805.0, 3926.0];
        yield [11860.33, 4849.0, 4047.0, 4572.0, 3809.0, 3926.0];
        yield [12545.0, 5183.0, 4429.0, 4944.0, 4177.0, 4303.0];
        yield [12549.33, 5187.0, 4433.0, 4944.0, 4182.0, 4307.0];
        yield [12558.0, 5191.0, 4437.0, 4949.0, 4186.0, 4312.0];
        yield [12562.33, 5191.0, 4437.0, 4953.0, 4186.0, 4312.0];
        yield [13299.0, 5551.0, 4853.0, 5352.0, 4585.0, 4719.0];
        yield [13303.33, 5555.0, 4853.0, 5352.0, 4589.0, 4719.0];
        yield [14308.67, 6049.0, 5417.0, 5898.0, 5131.0, 5274.0];
        yield [14313.0, 6049.0, 5421.0, 5898.0, 5135.0, 5278.0];
        yield [14932.67, 6405.0, 5768.0, 6231.0, 5469.0, 5616.0];
        yield [14937.0, 6405.0, 5768.0, 6236.0, 5469.0, 5620.0];
        yield [15825.33, 6912.0, 6205.0, 6652.0, 5889.0, 6045.0];
        yield [15829.67, 6916.0, 6205.0, 6656.0, 5889.0, 6049.0];
    }
}
