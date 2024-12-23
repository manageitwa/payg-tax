<?php

namespace ManageIt\PaygTax\Tests\TaxScales\July2023;

use ManageIt\PaygTax\PaygTax;
use ManageIt\PaygTax\Tests\Fixtures\Earning;
use ManageIt\PaygTax\Tests\Fixtures\Payee;
use ManageIt\PaygTax\Tests\Fixtures\Payer;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;

/**
 * @covers \ManageIt\PaygTax\TaxScales\Nat3539
 */
class Nat3539Test extends TestCase
{
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
        $earning->date = new \DateTime('2023-07-15');
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
            [640, 158.00, 66.00, 208.00, 53.00, 53.00],
            [641, 165.00, 66.00, 208.00, 54.00, 54.00],
            [720, 193.00, 83.00, 234.00, 69.00, 69.00],
            [721, 194.00, 83.00, 234.00, 69.00, 69.00],
            [738, 200.00, 87.00, 240.00, 72.00, 72.00],
            [739, 200.00, 87.00, 240.00, 72.00, 72.00],
            [794, 228.00, 99.00, 258.00, 83.00, 86.00],
            [795, 228.00, 99.00, 258.00, 84.00, 86.00],
            [862, 253.00, 114.00, 280.00, 97.00, 103.00],
            [863, 258.00, 114.00, 280.00, 97.00, 103.00],
            [864, 258.00, 115.00, 281.00, 97.00, 104.00],
            [865, 258.00, 115.00, 281.00, 98.00, 104.00],
            [923, 280.00, 135.00, 300.00, 117.00, 126.00],
            [924, 280.00, 135.00, 300.00, 117.00, 126.00],
            [931, 283.00, 138.00, 303.00, 119.00, 129.00],
            [932, 283.00, 138.00, 303.00, 120.00, 129.00],
            [935, 284.00, 139.00, 304.00, 121.00, 130.00],
            [936, 289.00, 140.00, 304.00, 121.00, 130.00],
            [990, 310.00, 158.00, 322.00, 139.00, 148.00],
            [991, 310.00, 169.00, 332.00, 149.00, 159.00],
            [1012, 318.00, 176.00, 339.00, 156.00, 166.00],
            [1013, 323.00, 176.00, 339.00, 156.00, 166.00],
            [1094, 354.00, 205.00, 366.00, 184.00, 195.00],
            [1095, 360.00, 206.00, 367.00, 184.00, 195.00],
            [1143, 379.00, 223.00, 383.00, 200.00, 212.00],
            [1144, 379.00, 235.00, 395.00, 212.00, 223.00],
            [1181, 399.00, 248.00, 407.00, 225.00, 237.00],
            [1212, 411.00, 260.00, 418.00, 236.00, 248.00],
            [1213, 412.00, 266.00, 425.00, 242.00, 254.00],
            [1272, 435.00, 288.00, 445.00, 263.00, 276.00],
            [1273, 441.00, 289.00, 446.00, 263.00, 276.00],
            [1275, 442.00, 289.00, 446.00, 264.00, 277.00],
            [1281, 444.00, 292.00, 448.00, 266.00, 279.00],
            [1282, 445.00, 292.00, 449.00, 266.00, 279.00],
            [1286, 446.00, 300.00, 457.00, 274.00, 287.00],
            [1362, 476.00, 328.00, 484.00, 301.00, 315.00],
            [1363, 477.00, 336.00, 491.00, 308.00, 322.00],
            [1370, 480.00, 338.00, 493.00, 311.00, 325.00],
            [1371, 487.00, 339.00, 494.00, 311.00, 325.00],
            [1444, 516.00, 366.00, 520.00, 337.00, 352.00],
            [1445, 516.00, 374.00, 527.00, 345.00, 359.00],
            [1473, 528.00, 385.00, 538.00, 355.00, 370.00],
            [1474, 535.00, 385.00, 538.00, 356.00, 370.00],
            [1530, 558.00, 407.00, 558.00, 376.00, 391.00],
            [1531, 559.00, 415.00, 567.00, 384.00, 399.00],
            [1582, 579.00, 435.00, 585.00, 403.00, 419.00],
            [1583, 588.00, 435.00, 586.00, 403.00, 419.00],
            [1622, 604.00, 450.00, 600.00, 418.00, 434.00],
            [1623, 604.00, 459.00, 609.00, 426.00, 442.00],
            [1698, 635.00, 488.00, 637.00, 454.00, 471.00],
            [1699, 644.00, 489.00, 637.00, 455.00, 472.00],
            [1720, 652.00, 497.00, 645.00, 463.00, 480.00],
            [1721, 653.00, 506.00, 654.00, 472.00, 489.00],
            [1821, 694.00, 546.00, 692.00, 510.00, 528.00],
            [1822, 704.00, 546.00, 692.00, 510.00, 528.00],
            [1823, 704.00, 547.00, 693.00, 510.00, 529.00],
            [1824, 705.00, 556.00, 702.00, 520.00, 538.00],
            [1844, 713.00, 564.00, 710.00, 528.00, 546.00],
            [1845, 713.00, 565.00, 710.00, 528.00, 546.00],
            [1932, 750.00, 600.00, 744.00, 561.00, 581.00],
            [1933, 750.00, 610.00, 754.00, 572.00, 591.00],
            [1952, 758.00, 618.00, 761.00, 579.00, 598.00],
            [1953, 769.00, 618.00, 762.00, 579.00, 599.00],
            [1956, 770.00, 620.00, 763.00, 580.00, 600.00],
            [1957, 770.00, 620.00, 763.00, 581.00, 600.00],
            [2048, 813.00, 657.00, 799.00, 616.00, 637.00],
            [2049, 813.00, 668.00, 809.00, 627.00, 647.00],
            [2090, 833.00, 685.00, 826.00, 643.00, 664.00],
            [2091, 844.00, 685.00, 826.00, 644.00, 665.00],
            [2119, 857.00, 697.00, 837.00, 655.00, 676.00],
            [2120, 857.00, 697.00, 837.00, 655.00, 676.00],
            [2171, 882.00, 719.00, 858.00, 675.00, 697.00],
            [2172, 882.00, 730.00, 869.00, 686.00, 708.00],
            [2236, 913.00, 757.00, 894.00, 712.00, 734.00],
            [2237, 924.00, 757.00, 895.00, 712.00, 735.00],
            [2302, 955.00, 785.00, 921.00, 738.00, 761.00],
            [2303, 956.00, 796.00, 933.00, 750.00, 773.00],
            [2306, 957.00, 798.00, 934.00, 752.00, 775.00],
            [2307, 958.00, 798.00, 935.00, 752.00, 775.00],
            [2392, 999.00, 838.00, 973.00, 790.00, 814.00],
            [2393, 1011.00, 839.00, 973.00, 791.00, 815.00],
            [2440, 1034.00, 861.00, 995.00, 812.00, 836.00],
            [2441, 1034.00, 873.00, 1007.00, 825.00, 849.00],
            [2490, 1058.00, 897.00, 1030.00, 847.00, 872.00],
            [2491, 1059.00, 897.00, 1030.00, 847.00, 872.00],
            [2556, 1090.00, 928.00, 1060.00, 877.00, 902.00],
            [2557, 1103.00, 928.00, 1060.00, 877.00, 903.00],
            [2586, 1118.00, 942.00, 1073.00, 890.00, 916.00],
            [2587, 1118.00, 956.00, 1087.00, 904.00, 930.00],
            [2652, 1150.00, 987.00, 1117.00, 934.00, 960.00],
            [2653, 1150.00, 987.00, 1117.00, 934.00, 961.00],
            [2736, 1191.00, 1027.00, 1155.00, 972.00, 1000.00],
            [2737, 1192.00, 1028.00, 1156.00, 973.00, 1000.00],
            [2742, 1194.00, 1030.00, 1158.00, 975.00, 1003.00],
            [2743, 1195.00, 1044.00, 1172.00, 989.00, 1017.00],
            [2898, 1270.00, 1119.00, 1244.00, 1061.00, 1090.00],
            [2899, 1271.00, 1120.00, 1245.00, 1062.00, 1091.00],
            [2906, 1274.00, 1123.00, 1248.00, 1065.00, 1094.00],
            [2907, 1275.00, 1138.00, 1263.00, 1080.00, 1109.00],
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
        $earning->date = new \DateTime('2023-07-15');
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
            [1280, 316.00, 132.00, 416.00, 106.00, 106.00],
            [1282, 330.00, 132.00, 416.00, 108.00, 108.00],
            [1440, 386.00, 166.00, 468.00, 138.00, 138.00],
            [1442, 388.00, 166.00, 468.00, 138.00, 138.00],
            [1476, 400.00, 174.00, 480.00, 144.00, 144.00],
            [1478, 400.00, 174.00, 480.00, 144.00, 144.00],
            [1588, 456.00, 198.00, 516.00, 166.00, 172.00],
            [1590, 456.00, 198.00, 516.00, 168.00, 172.00],
            [1724, 506.00, 228.00, 560.00, 194.00, 206.00],
            [1726, 516.00, 228.00, 560.00, 194.00, 206.00],
            [1728, 516.00, 230.00, 562.00, 194.00, 208.00],
            [1730, 516.00, 230.00, 562.00, 196.00, 208.00],
            [1846, 560.00, 270.00, 600.00, 234.00, 252.00],
            [1848, 560.00, 270.00, 600.00, 234.00, 252.00],
            [1862, 566.00, 276.00, 606.00, 238.00, 258.00],
            [1864, 566.00, 276.00, 606.00, 240.00, 258.00],
            [1870, 568.00, 278.00, 608.00, 242.00, 260.00],
            [1872, 578.00, 280.00, 608.00, 242.00, 260.00],
            [1980, 620.00, 316.00, 644.00, 278.00, 296.00],
            [1982, 620.00, 338.00, 664.00, 298.00, 318.00],
            [2024, 636.00, 352.00, 678.00, 312.00, 332.00],
            [2026, 646.00, 352.00, 678.00, 312.00, 332.00],
            [2188, 708.00, 410.00, 732.00, 368.00, 390.00],
            [2190, 720.00, 412.00, 734.00, 368.00, 390.00],
            [2286, 758.00, 446.00, 766.00, 400.00, 424.00],
            [2288, 758.00, 470.00, 790.00, 424.00, 446.00],
            [2362, 798.00, 496.00, 814.00, 450.00, 474.00],
            [2424, 822.00, 520.00, 836.00, 472.00, 496.00],
            [2426, 824.00, 532.00, 850.00, 484.00, 508.00],
            [2544, 870.00, 576.00, 890.00, 526.00, 552.00],
            [2546, 882.00, 578.00, 892.00, 526.00, 552.00],
            [2550, 884.00, 578.00, 892.00, 528.00, 554.00],
            [2562, 888.00, 584.00, 896.00, 532.00, 558.00],
            [2564, 890.00, 584.00, 898.00, 532.00, 558.00],
            [2572, 892.00, 600.00, 914.00, 548.00, 574.00],
            [2724, 952.00, 656.00, 968.00, 602.00, 630.00],
            [2726, 954.00, 672.00, 982.00, 616.00, 644.00],
            [2740, 960.00, 676.00, 986.00, 622.00, 650.00],
            [2742, 974.00, 678.00, 988.00, 622.00, 650.00],
            [2888, 1032.00, 732.00, 1040.00, 674.00, 704.00],
            [2890, 1032.00, 748.00, 1054.00, 690.00, 718.00],
            [2946, 1056.00, 770.00, 1076.00, 710.00, 740.00],
            [2948, 1070.00, 770.00, 1076.00, 712.00, 740.00],
            [3060, 1116.00, 814.00, 1116.00, 752.00, 782.00],
            [3062, 1118.00, 830.00, 1134.00, 768.00, 798.00],
            [3164, 1158.00, 870.00, 1170.00, 806.00, 838.00],
            [3166, 1176.00, 870.00, 1172.00, 806.00, 838.00],
            [3244, 1208.00, 900.00, 1200.00, 836.00, 868.00],
            [3246, 1208.00, 918.00, 1218.00, 852.00, 884.00],
            [3396, 1270.00, 976.00, 1274.00, 908.00, 942.00],
            [3398, 1288.00, 978.00, 1274.00, 910.00, 944.00],
            [3440, 1304.00, 994.00, 1290.00, 926.00, 960.00],
            [3442, 1306.00, 1012.00, 1308.00, 944.00, 978.00],
            [3642, 1388.00, 1092.00, 1384.00, 1020.00, 1056.00],
            [3644, 1408.00, 1092.00, 1384.00, 1020.00, 1056.00],
            [3646, 1408.00, 1094.00, 1386.00, 1020.00, 1058.00],
            [3648, 1410.00, 1112.00, 1404.00, 1040.00, 1076.00],
            [3688, 1426.00, 1128.00, 1420.00, 1056.00, 1092.00],
            [3690, 1426.00, 1130.00, 1420.00, 1056.00, 1092.00],
            [3864, 1500.00, 1200.00, 1488.00, 1122.00, 1162.00],
            [3866, 1500.00, 1220.00, 1508.00, 1144.00, 1182.00],
            [3904, 1516.00, 1236.00, 1522.00, 1158.00, 1196.00],
            [3906, 1538.00, 1236.00, 1524.00, 1158.00, 1198.00],
            [3912, 1540.00, 1240.00, 1526.00, 1160.00, 1200.00],
            [3914, 1540.00, 1240.00, 1526.00, 1162.00, 1200.00],
            [4096, 1626.00, 1314.00, 1598.00, 1232.00, 1274.00],
            [4098, 1626.00, 1336.00, 1618.00, 1254.00, 1294.00],
            [4180, 1666.00, 1370.00, 1652.00, 1286.00, 1328.00],
            [4182, 1688.00, 1370.00, 1652.00, 1288.00, 1330.00],
            [4238, 1714.00, 1394.00, 1674.00, 1310.00, 1352.00],
            [4240, 1714.00, 1394.00, 1674.00, 1310.00, 1352.00],
            [4342, 1764.00, 1438.00, 1716.00, 1350.00, 1394.00],
            [4344, 1764.00, 1460.00, 1738.00, 1372.00, 1416.00],
            [4472, 1826.00, 1514.00, 1788.00, 1424.00, 1468.00],
            [4474, 1848.00, 1514.00, 1790.00, 1424.00, 1470.00],
            [4604, 1910.00, 1570.00, 1842.00, 1476.00, 1522.00],
            [4606, 1912.00, 1592.00, 1866.00, 1500.00, 1546.00],
            [4612, 1914.00, 1596.00, 1868.00, 1504.00, 1550.00],
            [4614, 1916.00, 1596.00, 1870.00, 1504.00, 1550.00],
            [4784, 1998.00, 1676.00, 1946.00, 1580.00, 1628.00],
            [4786, 2022.00, 1678.00, 1946.00, 1582.00, 1630.00],
            [4880, 2068.00, 1722.00, 1990.00, 1624.00, 1672.00],
            [4882, 2068.00, 1746.00, 2014.00, 1650.00, 1698.00],
            [4980, 2116.00, 1794.00, 2060.00, 1694.00, 1744.00],
            [4982, 2118.00, 1794.00, 2060.00, 1694.00, 1744.00],
            [5112, 2180.00, 1856.00, 2120.00, 1754.00, 1804.00],
            [5114, 2206.00, 1856.00, 2120.00, 1754.00, 1806.00],
            [5172, 2236.00, 1884.00, 2146.00, 1780.00, 1832.00],
            [5174, 2236.00, 1912.00, 2174.00, 1808.00, 1860.00],
            [5304, 2300.00, 1974.00, 2234.00, 1868.00, 1920.00],
            [5306, 2300.00, 1974.00, 2234.00, 1868.00, 1922.00],
            [5472, 2382.00, 2054.00, 2310.00, 1944.00, 2000.00],
            [5474, 2384.00, 2056.00, 2312.00, 1946.00, 2000.00],
            [5484, 2388.00, 2060.00, 2316.00, 1950.00, 2006.00],
            [5486, 2390.00, 2088.00, 2344.00, 1978.00, 2034.00],
            [5796, 2540.00, 2238.00, 2488.00, 2122.00, 2180.00],
            [5798, 2542.00, 2240.00, 2490.00, 2124.00, 2182.00],
            [5812, 2548.00, 2246.00, 2496.00, 2130.00, 2188.00],
            [5814, 2550.00, 2276.00, 2526.00, 2160.00, 2218.00],
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
        $earning->date = new \DateTime('2023-07-15');
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
            [2773.33, 685.00, 286.00, 901.00, 230.00, 230.00],
            [2777.67, 715.00, 286.00, 901.00, 234.00, 234.00],
            [3120.00, 836.00, 360.00, 1014.00, 299.00, 299.00],
            [3124.33, 841.00, 360.00, 1014.00, 299.00, 299.00],
            [3198.00, 867.00, 377.00, 1040.00, 312.00, 312.00],
            [3202.33, 867.00, 377.00, 1040.00, 312.00, 312.00],
            [3440.67, 988.00, 429.00, 1118.00, 360.00, 373.00],
            [3445.00, 988.00, 429.00, 1118.00, 364.00, 373.00],
            [3735.33, 1096.00, 494.00, 1213.00, 420.00, 446.00],
            [3739.67, 1118.00, 494.00, 1213.00, 420.00, 446.00],
            [3744.00, 1118.00, 498.00, 1218.00, 420.00, 451.00],
            [3748.33, 1118.00, 498.00, 1218.00, 425.00, 451.00],
            [3999.67, 1213.00, 585.00, 1300.00, 507.00, 546.00],
            [4004.00, 1213.00, 585.00, 1300.00, 507.00, 546.00],
            [4034.33, 1226.00, 598.00, 1313.00, 516.00, 559.00],
            [4038.67, 1226.00, 598.00, 1313.00, 520.00, 559.00],
            [4051.67, 1231.00, 602.00, 1317.00, 524.00, 563.00],
            [4056.00, 1252.00, 607.00, 1317.00, 524.00, 563.00],
            [4290.00, 1343.00, 685.00, 1395.00, 602.00, 641.00],
            [4294.33, 1343.00, 732.00, 1439.00, 646.00, 689.00],
            [4385.33, 1378.00, 763.00, 1469.00, 676.00, 719.00],
            [4389.67, 1400.00, 763.00, 1469.00, 676.00, 719.00],
            [4740.67, 1534.00, 888.00, 1586.00, 797.00, 845.00],
            [4745.00, 1560.00, 893.00, 1590.00, 797.00, 845.00],
            [4953.00, 1642.00, 966.00, 1660.00, 867.00, 919.00],
            [4957.33, 1642.00, 1018.00, 1712.00, 919.00, 966.00],
            [5117.67, 1729.00, 1075.00, 1764.00, 975.00, 1027.00],
            [5252.00, 1781.00, 1127.00, 1811.00, 1023.00, 1075.00],
            [5256.33, 1785.00, 1153.00, 1842.00, 1049.00, 1101.00],
            [5512.00, 1885.00, 1248.00, 1928.00, 1140.00, 1196.00],
            [5516.33, 1911.00, 1252.00, 1933.00, 1140.00, 1196.00],
            [5525.00, 1915.00, 1252.00, 1933.00, 1144.00, 1200.00],
            [5551.00, 1924.00, 1265.00, 1941.00, 1153.00, 1209.00],
            [5555.33, 1928.00, 1265.00, 1946.00, 1153.00, 1209.00],
            [5572.67, 1933.00, 1300.00, 1980.00, 1187.00, 1244.00],
            [5902.00, 2063.00, 1421.00, 2097.00, 1304.00, 1365.00],
            [5906.33, 2067.00, 1456.00, 2128.00, 1335.00, 1395.00],
            [5936.67, 2080.00, 1465.00, 2136.00, 1348.00, 1408.00],
            [5941.00, 2110.00, 1469.00, 2141.00, 1348.00, 1408.00],
            [6257.33, 2236.00, 1586.00, 2253.00, 1460.00, 1525.00],
            [6261.67, 2236.00, 1621.00, 2284.00, 1495.00, 1556.00],
            [6383.00, 2288.00, 1668.00, 2331.00, 1538.00, 1603.00],
            [6387.33, 2318.00, 1668.00, 2331.00, 1543.00, 1603.00],
            [6630.00, 2418.00, 1764.00, 2418.00, 1629.00, 1694.00],
            [6634.33, 2422.00, 1798.00, 2457.00, 1664.00, 1729.00],
            [6855.33, 2509.00, 1885.00, 2535.00, 1746.00, 1816.00],
            [6859.67, 2548.00, 1885.00, 2539.00, 1746.00, 1816.00],
            [7028.67, 2617.00, 1950.00, 2600.00, 1811.00, 1881.00],
            [7033.00, 2617.00, 1989.00, 2639.00, 1846.00, 1915.00],
            [7358.00, 2752.00, 2115.00, 2760.00, 1967.00, 2041.00],
            [7362.33, 2791.00, 2119.00, 2760.00, 1972.00, 2045.00],
            [7453.33, 2825.00, 2154.00, 2795.00, 2006.00, 2080.00],
            [7457.67, 2830.00, 2193.00, 2834.00, 2045.00, 2119.00],
            [7891.00, 3007.00, 2366.00, 2999.00, 2210.00, 2288.00],
            [7895.33, 3051.00, 2366.00, 2999.00, 2210.00, 2288.00],
            [7899.67, 3051.00, 2370.00, 3003.00, 2210.00, 2292.00],
            [7904.00, 3055.00, 2409.00, 3042.00, 2253.00, 2331.00],
            [7990.67, 3090.00, 2444.00, 3077.00, 2288.00, 2366.00],
            [7995.00, 3090.00, 2448.00, 3077.00, 2288.00, 2366.00],
            [8372.00, 3250.00, 2600.00, 3224.00, 2431.00, 2518.00],
            [8376.33, 3250.00, 2643.00, 3267.00, 2479.00, 2561.00],
            [8458.67, 3285.00, 2678.00, 3298.00, 2509.00, 2591.00],
            [8463.00, 3332.00, 2678.00, 3302.00, 2509.00, 2596.00],
            [8476.00, 3337.00, 2687.00, 3306.00, 2513.00, 2600.00],
            [8480.33, 3337.00, 2687.00, 3306.00, 2518.00, 2600.00],
            [8874.67, 3523.00, 2847.00, 3462.00, 2669.00, 2760.00],
            [8879.00, 3523.00, 2895.00, 3506.00, 2717.00, 2804.00],
            [9056.67, 3610.00, 2968.00, 3579.00, 2786.00, 2877.00],
            [9061.00, 3657.00, 2968.00, 3579.00, 2791.00, 2882.00],
            [9182.33, 3714.00, 3020.00, 3627.00, 2838.00, 2929.00],
            [9186.67, 3714.00, 3020.00, 3627.00, 2838.00, 2929.00],
            [9407.67, 3822.00, 3116.00, 3718.00, 2925.00, 3020.00],
            [9412.00, 3822.00, 3163.00, 3766.00, 2973.00, 3068.00],
            [9689.33, 3956.00, 3280.00, 3874.00, 3085.00, 3181.00],
            [9693.67, 4004.00, 3280.00, 3878.00, 3085.00, 3185.00],
            [9975.33, 4138.00, 3402.00, 3991.00, 3198.00, 3298.00],
            [9979.67, 4143.00, 3449.00, 4043.00, 3250.00, 3350.00],
            [9992.67, 4147.00, 3458.00, 4047.00, 3259.00, 3358.00],
            [9997.00, 4151.00, 3458.00, 4052.00, 3259.00, 3358.00],
            [10365.33, 4329.00, 3631.00, 4216.00, 3423.00, 3527.00],
            [10369.67, 4381.00, 3636.00, 4216.00, 3428.00, 3532.00],
            [10573.33, 4481.00, 3731.00, 4312.00, 3519.00, 3623.00],
            [10577.67, 4481.00, 3783.00, 4364.00, 3575.00, 3679.00],
            [10790.00, 4585.00, 3887.00, 4463.00, 3670.00, 3779.00],
            [10794.33, 4589.00, 3887.00, 4463.00, 3670.00, 3779.00],
            [11076.00, 4723.00, 4021.00, 4593.00, 3800.00, 3909.00],
            [11080.33, 4780.00, 4021.00, 4593.00, 3800.00, 3913.00],
            [11206.00, 4845.00, 4082.00, 4650.00, 3857.00, 3969.00],
            [11210.33, 4845.00, 4143.00, 4710.00, 3917.00, 4030.00],
            [11492.00, 4983.00, 4277.00, 4840.00, 4047.00, 4160.00],
            [11496.33, 4983.00, 4277.00, 4840.00, 4047.00, 4164.00],
            [11856.00, 5161.00, 4450.00, 5005.00, 4212.00, 4333.00],
            [11860.33, 5165.00, 4455.00, 5009.00, 4216.00, 4333.00],
            [11882.00, 5174.00, 4463.00, 5018.00, 4225.00, 4346.00],
            [11886.33, 5178.00, 4524.00, 5079.00, 4286.00, 4407.00],
            [12558.00, 5503.00, 4849.00, 5391.00, 4598.00, 4723.00],
            [12562.33, 5508.00, 4853.00, 5395.00, 4602.00, 4728.00],
            [12592.67, 5521.00, 4866.00, 5408.00, 4615.00, 4741.00],
            [12597.00, 5525.00, 4931.00, 5473.00, 4680.00, 4806.00],
            [12623.00, 5538.00, 4944.00, 5486.00, 4693.00, 4819.00],
            [12627.33, 5538.00, 4949.00, 5486.00, 4693.00, 4823.00],
            [13476.67, 5954.00, 5365.00, 5885.00, 5096.00, 5230.00],
            [13481.00, 5958.00, 5365.00, 5889.00, 5096.00, 5230.00],
            [14993.33, 6821.00, 6106.00, 6600.00, 5807.00, 5958.00],
            [14997.67, 6821.00, 6110.00, 6600.00, 5811.00, 5958.00],
        ];
    }
}
