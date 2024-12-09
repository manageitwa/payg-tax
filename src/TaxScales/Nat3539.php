<?php

declare(strict_types=1);

namespace ManageIt\PaygTax\TaxScales;

use ManageIt\PaygTax\Entities\Earning;
use ManageIt\PaygTax\Entities\Payee;
use ManageIt\PaygTax\Entities\Payer;
use ManageIt\PaygTax\Utilities\Date;

/**
 * Tax scale for people on Study and Training Support Loans (STSL).
 */
class Nat3539 extends BaseCoefficientScale
{
        /**
     * Coefficients for each scale in NAT3539 (except Scale 4, which must use NAT1004), grouped by switchover date.
     *
     * @var array<string, array<string, array<int, array<int, int|float>>>>
     */
    protected array $scaledCoefficients = [
        '2020-10-01' => [
            'scale1' => [
                88 => [0.1900, 0.1900],
                371 => [0.2348, 3.9639],
                515 => [0.2190, -1.9003],
                546 => [0.3477, 64.4297],
                685 => [0.3577, 64.4297],
                747 => [0.3677, 64.4297],
                813 => [0.3727, 64.4297],
                882 => [0.3777, 64.4297],
                932 => [0.3827, 64.4297],
                956 => [0.3800, 61.9132],
                1035 => [0.3850, 61.9132],
                1118 => [0.3900, 61.9132],
                1206 => [0.3950, 61.9132],
                1299 => [0.4000, 61.9132],
                1398 => [0.4050, 61.9132],
                1503 => [0.4100, 61.9132],
                1615 => [0.4150, 61.9132],
                1732 => [0.4200, 61.9132],
                1855 => [0.4250, 61.9132],
                1957 => [0.4300, 61.9132],
                1990 => [0.4750, 150.0093],
                2130 => [0.4800, 150.0093],
                2279 => [0.4850, 150.0093],
                3111 => [0.4900, 150.0093],
                999999999 => [0.57, 398.9324],
            ],
            'scale2' => [
                359 => [0.0000, 0.0000],
                438 => [0.1900, 68.3462],
                548 => [0.2900, 112.1942],
                721 => [0.2100, 68.3465],
                865 => [0.2190, 74.8369],
                896 => [0.3477, 186.2119],
                1035 => [0.3577, 186.2119],
                1097 => [0.3677, 186.2119],
                1163 => [0.3727, 186.2119],
                1232 => [0.3777, 186.2119],
                1282 => [0.3827, 186.2119],
                1306 => [0.3800, 182.7504],
                1385 => [0.3850, 182.7504],
                1468 => [0.3900, 182.7504],
                1556 => [0.3950, 182.7504],
                1649 => [0.4000, 182.7504],
                1748 => [0.4050, 182.7504],
                1853 => [0.4100, 182.7504],
                1965 => [0.4150, 182.7504],
                2082 => [0.4200, 182.7504],
                2205 => [0.4250, 182.7504],
                2307 => [0.4300, 182.7504],
                2340 => [0.4750, 286.5965],
                2480 => [0.4800, 286.5965],
                2629 => [0.4850, 286.5965],
                3461 => [0.4900, 286.5965],
                999999999 => [0.57, 563.5196],
            ],
            'scale3' => [
                896 => [0.3250, 0.3250],
                1035 => [0.3350, 0.3250],
                1097 => [0.3450, 0.3250],
                1163 => [0.3500, 0.3250],
                1232 => [0.3550, 0.3250],
                1306 => [0.3600, 0.3250],
                1385 => [0.3650, 0.3250],
                1468 => [0.3700, 0.3250],
                1556 => [0.3750, 0.3250],
                1649 => [0.3800, 0.3250],
                1748 => [0.3850, 0.3250],
                1853 => [0.3900, 0.3250],
                1965 => [0.3950, 0.3250],
                2082 => [0.4000, 0.3250],
                2205 => [0.4050, 0.3250],
                2307 => [0.4100, 0.3250],
                2340 => [0.4550, 103.8462],
                2480 => [0.4600, 103.8462],
                2629 => [0.4650, 103.8462],
                3461 => [0.4700, 103.8462],
                999999999 => [0.55, 380.7692],
            ],
            'scale5' => [
                359 => [0.0000, 0.0000],
                721 => [0.1900, 68.3462],
                865 => [0.1990, 74.8365],
                896 => [0.3277, 186.2115],
                1035 => [0.3377, 186.2115],
                1097 => [0.3477, 186.2115],
                1163 => [0.3527, 186.2115],
                1232 => [0.3577, 186.2115],
                1282 => [0.3627, 186.2115],
                1306 => [0.3600, 182.7500],
                1385 => [0.3650, 182.7500],
                1468 => [0.3700, 182.7500],
                1556 => [0.3750, 182.7500],
                1649 => [0.3800, 182.7500],
                1748 => [0.3850, 182.7500],
                1853 => [0.3900, 182.7500],
                1965 => [0.3950, 182.7500],
                2082 => [0.4000, 182.7500],
                2205 => [0.4050, 182.7500],
                2307 => [0.4100, 182.7500],
                2340 => [0.4550, 286.5962],
                2480 => [0.4600, 286.5962],
                2629 => [0.4650, 286.5962],
                3461 => [0.4700, 286.5962],
                999999999 => [0.55, 563.5192],
            ],
            'scale6' => [
                359 => [0.0000, 0.0000],
                721 => [0.1900, 68.3462],
                739 => [0.1990, 74.8365],
                865 => [0.2490, 111.8308],
                896 => [0.3777, 223.2058],
                924 => [0.3877, 223.2058],
                1035 => [0.3477, 186.2119],
                1097 => [0.3577, 186.2119],
                1163 => [0.3627, 186.2119],
                1232 => [0.3677, 186.2119],
                1282 => [0.3727, 186.2119],
                1306 => [0.3700, 182.7504],
                1385 => [0.3750, 182.7504],
                1468 => [0.3800, 182.7504],
                1556 => [0.3850, 182.7504],
                1649 => [0.3900, 182.7504],
                1748 => [0.3950, 182.7504],
                1853 => [0.4000, 182.7504],
                1965 => [0.4050, 182.7504],
                2082 => [0.4100, 182.7504],
                2205 => [0.4150, 182.7504],
                2307 => [0.4200, 182.7504],
                2340 => [0.4650, 286.5965],
                2480 => [0.4700, 286.5965],
                2629 => [0.4750, 286.5965],
                3461 => [0.4800, 286.5965],
                999999999 => [0.56, 563.5196],
            ],
        ],
        '2022-07-01' => [
            'scale1' => [
                88 => [0.1900, 0.1900],
                371 => [0.2348, 3.9639],
                515 => [0.2190, -1.9003],
                580 => [0.3477, 64.4297],
                723 => [0.3577, 64.4297],
                788 => [0.3677, 64.4297],
                856 => [0.3727, 64.4297],
                928 => [0.3777, 64.4297],
                932 => [0.3827, 64.4297],
                1005 => [0.3800, 61.9132],
                1086 => [0.3850, 61.9132],
                1173 => [0.3900, 61.9132],
                1264 => [0.3950, 61.9132],
                1361 => [0.4000, 61.9132],
                1464 => [0.4050, 61.9132],
                1573 => [0.4100, 61.9132],
                1688 => [0.4150, 61.9132],
                1810 => [0.4200, 61.9132],
                1940 => [0.4250, 61.9132],
                1957 => [0.4300, 61.9132],
                2077 => [0.4750, 150.0093],
                2223 => [0.4800, 150.0093],
                2377 => [0.4850, 150.0093],
                3111 => [0.4900, 150.0093],
                999999999 => [0.5700, 398.9324],
            ],
            'scale2' => [
                359 => [0.0000, 0.0000],
                438 => [0.1900, 68.3462],
                548 => [0.2900, 112.1942],
                721 => [0.2100, 68.3465],
                865 => [0.2190, 74.8369],
                930 => [0.3477, 186.2119],
                1073 => [0.3577, 186.2119],
                1138 => [0.3677, 186.2119],
                1206 => [0.3727, 186.2119],
                1278 => [0.3777, 186.2119],
                1282 => [0.3827, 186.2119],
                1355 => [0.3800, 182.7504],
                1436 => [0.3850, 182.7504],
                1523 => [0.3900, 182.7504],
                1614 => [0.3950, 182.7504],
                1711 => [0.4000, 182.7504],
                1814 => [0.4050, 182.7504],
                1923 => [0.4100, 182.7504],
                2038 => [0.4150, 182.7504],
                2160 => [0.4200, 182.7504],
                2290 => [0.4250, 182.7504],
                2307 => [0.4300, 182.7504],
                2427 => [0.4750, 286.5965],
                2573 => [0.4800, 286.5965],
                2727 => [0.4850, 286.5965],
                3461 => [0.4900, 286.5965],
                999999999 => [0.57, 563.5196],
            ],
            'scale3' => [
                930 => [0.3250, 0.3250],
                1073 => [0.3350, 0.3250],
                1138 => [0.3450, 0.3250],
                1206 => [0.3500, 0.3250],
                1278 => [0.3550, 0.3250],
                1355 => [0.3600, 0.3250],
                1436 => [0.3650, 0.3250],
                1523 => [0.3700, 0.3250],
                1614 => [0.3750, 0.3250],
                1711 => [0.3800, 0.3250],
                1814 => [0.3850, 0.3250],
                1923 => [0.3900, 0.3250],
                2038 => [0.3950, 0.3250],
                2160 => [0.4000, 0.3250],
                2290 => [0.4050, 0.3250],
                2307 => [0.4100, 0.3250],
                2427 => [0.4550, 103.8462],
                2573 => [0.4600, 103.8462],
                2727 => [0.4650, 103.8462],
                3461 => [0.4700, 103.8462],
                999999999 => [0.55, 380.7692],
            ],
            'scale5' => [
                359 => [0.0000, 0.0000],
                721 => [0.1900, 68.3462],
                865 => [0.1990, 74.8365],
                930 => [0.3277, 186.2115],
                1073 => [0.3377, 186.2115],
                1138 => [0.3477, 186.2115],
                1206 => [0.3527, 186.2115],
                1278 => [0.3577, 186.2115],
                1282 => [0.3627, 186.2115],
                1355 => [0.3600, 182.7500],
                1436 => [0.3650, 182.7500],
                1523 => [0.3700, 182.7500],
                1614 => [0.3750, 182.7500],
                1711 => [0.3800, 182.7500],
                1814 => [0.3850, 182.7500],
                1923 => [0.3900, 182.7500],
                2038 => [0.3950, 182.7500],
                2160 => [0.4000, 182.7500],
                2290 => [0.4050, 182.7500],
                2307 => [0.4100, 182.7500],
                2427 => [0.4550, 286.5962],
                2573 => [0.4600, 286.5962],
                2727 => [0.4650, 286.5962],
                3461 => [0.4700, 286.5962],
                999999999 => [0.55, 563.5192],
            ],
            'scale6' => [
                359 => [0.0000, 0.0000],
                721 => [0.1900, 68.3462],
                739 => [0.1990, 74.8365],
                865 => [0.2490, 111.8308],
                924 => [0.3777, 223.2058],
                930 => [0.3377, 186.2119],
                1073 => [0.3477, 186.2119],
                1138 => [0.3577, 186.2119],
                1206 => [0.3627, 186.2119],
                1278 => [0.3677, 186.2119],
                1282 => [0.3727, 186.2119],
                1355 => [0.3700, 182.7504],
                1436 => [0.3750, 182.7504],
                1523 => [0.3800, 182.7504],
                1614 => [0.3850, 182.7504],
                1711 => [0.3900, 182.7504],
                1814 => [0.3950, 182.7504],
                1923 => [0.4000, 182.7504],
                2038 => [0.4050, 182.7504],
                2160 => [0.4100, 182.7504],
                2290 => [0.4150, 182.7504],
                2307 => [0.4200, 182.7504],
                2427 => [0.4650, 286.5965],
                2573 => [0.4700, 286.5965],
                2727 => [0.4750, 286.5965],
                3461 => [0.4800, 286.5965],
                999999999 => [0.56, 563.5196],
            ],
        ],
        '2023-07-01' => [
            'scale1' => [
                88 => [0.1900, 0.1900],
                371 => [0.2348, 3.9639],
                515 => [0.2190, -1.9003],
                641 => [0.3477, 64.4297],
                794 => [0.3577, 64.4297],
                863 => [0.3677, 64.4297],
                932 => [0.3727, 64.4297],
                936 => [0.3700, 61.9132],
                1013 => [0.3750, 61.9132],
                1095 => [0.3800, 61.9132],
                1181 => [0.3850, 61.9132],
                1273 => [0.3900, 61.9132],
                1371 => [0.3950, 61.9132],
                1474 => [0.4000, 61.9132],
                1583 => [0.4050, 61.9132],
                1699 => [0.4100, 61.9132],
                1822 => [0.4150, 61.9132],
                1953 => [0.4200, 61.9132],
                1957 => [0.4250, 61.9132],
                2091 => [0.4700, 150.0093],
                2237 => [0.4750, 150.0093],
                2393 => [0.4800, 150.0093],
                2557 => [0.4850, 150.0093],
                3111 => [0.4900, 150.0093],
                999999999 => [0.5700, 398.9324],
            ],
            'scale2' => [
                359 => [0.0000, 0.0000],
                438 => [0.1900, 68.3462],
                548 => [0.2900, 112.1942],
                721 => [0.2100, 68.3465],
                865 => [0.2190, 74.8369],
                991 => [0.3477, 186.2119],
                1144 => [0.3577, 186.2119],
                1213 => [0.3677, 186.2119],
                1282 => [0.3727, 186.2119],
                1286 => [0.3700, 182.7504],
                1363 => [0.3750, 182.7504],
                1445 => [0.3800, 182.7504],
                1531 => [0.3850, 182.7504],
                1623 => [0.3900, 182.7504],
                1721 => [0.3950, 182.7504],
                1824 => [0.4000, 182.7504],
                1933 => [0.4050, 182.7504],
                2049 => [0.4100, 182.7504],
                2172 => [0.4150, 182.7504],
                2303 => [0.4200, 182.7504],
                2307 => [0.4250, 182.7504],
                2441 => [0.4700, 286.5965],
                2587 => [0.4750, 286.5965],
                2743 => [0.4800, 286.5965],
                2907 => [0.4850, 286.5965],
                3461 => [0.4900, 286.5965],
                999999999 => [0.57, 563.5196],
            ],
            'scale3' => [
                991 => [0.3250, 0.3250],
                1144 => [0.3350, 0.3250],
                1213 => [0.3450, 0.3250],
                1286 => [0.3500, 0.3250],
                1363 => [0.3550, 0.3250],
                1445 => [0.3600, 0.3250],
                1531 => [0.3650, 0.3250],
                1623 => [0.3700, 0.3250],
                1721 => [0.3750, 0.3250],
                1824 => [0.3800, 0.3250],
                1933 => [0.3850, 0.3250],
                2049 => [0.3900, 0.3250],
                2172 => [0.3950, 0.3250],
                2303 => [0.4000, 0.3250],
                2307 => [0.4050, 0.3250],
                2441 => [0.4500, 103.8462],
                2587 => [0.4550, 103.8462],
                2743 => [0.4600, 103.8462],
                2907 => [0.4650, 103.8462],
                3461 => [0.4700, 103.8462],
                999999999 => [0.55, 380.7692],
            ],
            'scale5' => [
                359 => [0.0000, 0.0000],
                721 => [0.1900, 68.3462],
                865 => [0.1990, 74.8365],
                991 => [0.3277, 186.2115],
                1144 => [0.3377, 186.2115],
                1213 => [0.3477, 186.2115],
                1282 => [0.3527, 186.2115],
                1286 => [0.3500, 182.7500],
                1363 => [0.3550, 182.7500],
                1445 => [0.3600, 182.7500],
                1531 => [0.3650, 182.7500],
                1623 => [0.3700, 182.7500],
                1721 => [0.3750, 182.7500],
                1824 => [0.3800, 182.7500],
                1933 => [0.3850, 182.7500],
                2049 => [0.3900, 182.7500],
                2172 => [0.3950, 182.7500],
                2303 => [0.4000, 182.7500],
                2307 => [0.4050, 182.7500],
                2441 => [0.4500, 286.5962],
                2587 => [0.4550, 286.5962],
                2743 => [0.4600, 286.5962],
                2907 => [0.4650, 286.5962],
                3461 => [0.4700, 286.5962],
                999999999 => [0.55, 563.5192],
            ],
            'scale6' => [
                359 => [0.0000, 0.0000],
                721 => [0.1900, 68.3462],
                739 => [0.1990, 74.8365],
                865 => [0.2490, 111.8308],
                924 => [0.3777, 223.2058],
                991 => [0.3377, 186.2119],
                1144 => [0.3477, 186.2119],
                1213 => [0.3577, 186.2119],
                1282 => [0.3627, 186.2119],
                1286 => [0.3600, 182.7504],
                1363 => [0.3650, 182.7504],
                1445 => [0.3700, 182.7504],
                1531 => [0.3750, 182.7504],
                1623 => [0.3800, 182.7504],
                1721 => [0.3850, 182.7504],
                1824 => [0.3900, 182.7504],
                1933 => [0.3950, 182.7504],
                2049 => [0.4000, 182.7504],
                2172 => [0.4050, 182.7504],
                2303 => [0.4100, 182.7504],
                2307 => [0.4150, 182.7504],
                2441 => [0.4600, 286.5965],
                2587 => [0.4650, 286.5965],
                2743 => [0.4700, 286.5965],
                2907 => [0.4750, 286.5965],
                3461 => [0.4800, 286.5965],
                999999999 => [0.56, 563.5196],
            ],
        ],
        '2024-07-01' => [
            'scale1' => [
                150 => [0.1600, 0.1600],
                371 => [0.2117, 7.7550],
                515 => [0.1890, -0.6702],
                696 => [0.3227, 68.2367],
                858 => [0.3327, 68.2367],
                931 => [0.3427, 68.2367],
                1008 => [0.3450, 65.7202],
                1089 => [0.3500, 65.7202],
                1175 => [0.3550, 65.7202],
                1267 => [0.3600, 65.7202],
                1364 => [0.3650, 65.7202],
                1467 => [0.3700, 65.7202],
                1576 => [0.3750, 65.7202],
                1692 => [0.3800, 65.7202],
                1814 => [0.3850, 65.7202],
                1944 => [0.3900, 65.7202],
                2082 => [0.3950, 65.7202],
                2228 => [0.4000, 65.7202],
                2246 => [0.4050, 65.7202],
                2382 => [0.4750, 222.9510],
                2546 => [0.4800, 222.9510],
                2720 => [0.4850, 222.9510],
                3303 => [0.4900, 222.9510],
                999999999 => [0.5700, 487.2587],
            ],
            'scale2' => [
                361 => [0.0000, 0.0000],
                500 => [0.1600, 57.8462],
                625 => [0.2600, 107.8462],
                721 => [0.1800, 57.8462],
                865 => [0.1890, 64.3365],
                1046 => [0.3227, 180.0385],
                1208 => [0.3327, 180.0385],
                1281 => [0.3427, 180.0385],
                1358 => [0.3450, 176.5769],
                1439 => [0.3500, 176.5769],
                1525 => [0.3550, 176.5769],
                1617 => [0.3600, 176.5769],
                1714 => [0.3650, 176.5769],
                1817 => [0.3700, 176.5769],
                1926 => [0.3750, 176.5769],
                2042 => [0.3800, 176.5769],
                2164 => [0.3850, 176.5769],
                2294 => [0.3900, 176.5769],
                2432 => [0.3950, 176.5769],
                2578 => [0.4000, 176.5769],
                2596 => [0.4050, 176.5769],
                2732 => [0.4750, 358.3077],
                2896 => [0.4800, 358.3077],
                3070 => [0.4850, 358.3077],
                3653 => [0.4900, 358.3077],
                999999999 => [0.5700, 650.6154],
            ],
            'scale3' => [
                1046 => [0.3000, 0.3000],
                1208 => [0.3100, 0.3000],
                1281 => [0.3200, 0.3000],
                1358 => [0.3250, 0.3000],
                1439 => [0.3300, 0.3000],
                1525 => [0.3350, 0.3000],
                1617 => [0.3400, 0.3000],
                1714 => [0.3450, 0.3000],
                1817 => [0.3500, 0.3000],
                1926 => [0.3550, 0.3000],
                2042 => [0.3600, 0.3000],
                2164 => [0.3650, 0.3000],
                2294 => [0.3700, 0.3000],
                2432 => [0.3750, 0.3000],
                2578 => [0.3800, 0.3000],
                2596 => [0.3850, 0.3000],
                2732 => [0.4550, 181.7308],
                2896 => [0.4600, 181.7308],
                3070 => [0.4650, 181.7308],
                3653 => [0.4700, 181.7308],
                999999999 => [0.5500, 474.0385],
            ],
            'scale5' => [
                361 => [0.0000, 0.0000],
                721 => [0.1600, 57.8462],
                865 => [0.1690, 64.3365],
                1046 => [0.3027, 180.0385],
                1208 => [0.3127, 180.0385],
                1281 => [0.3227, 180.0385],
                1358 => [0.3250, 176.5769],
                1439 => [0.3300, 176.5769],
                1525 => [0.3350, 176.5769],
                1617 => [0.3400, 176.5769],
                1714 => [0.3450, 176.5769],
                1817 => [0.3500, 176.5769],
                1926 => [0.3550, 176.5769],
                2042 => [0.3600, 176.5769],
                2164 => [0.3650, 176.5769],
                2294 => [0.3700, 176.5769],
                2432 => [0.3750, 176.5769],
                2578 => [0.3800, 176.5769],
                2596 => [0.3850, 176.5769],
                2732 => [0.4550, 358.3077],
                2896 => [0.4600, 358.3077],
                3070 => [0.4650, 358.3077],
                3653 => [0.4700, 358.3077],
                999999999 => [0.5500, 650.6154],
            ],
            'scale6' => [
                361 => [0.0000, 0.0000],
                721 => [0.1600, 57.8462],
                843 => [0.1690, 64.3365],
                865 => [0.2190, 106.4962],
                1046 => [0.3527, 222.1981],
                1053 => [0.3627, 222.1981],
                1208 => [0.3227, 180.0385],
                1281 => [0.3327, 180.0385],
                1358 => [0.3350, 176.5769],
                1439 => [0.3400, 176.5769],
                1525 => [0.3450, 176.5769],
                1617 => [0.3500, 176.5769],
                1714 => [0.3550, 176.5769],
                1817 => [0.3600, 176.5769],
                1926 => [0.3650, 176.5769],
                2042 => [0.3700, 176.5769],
                2164 => [0.3750, 176.5769],
                2294 => [0.3800, 176.5769],
                2432 => [0.3850, 176.5769],
                2578 => [0.3900, 176.5769],
                2596 => [0.3950, 176.5769],
                2732 => [0.4650, 358.3077],
                2896 => [0.4700, 358.3077],
                3070 => [0.4750, 358.3077],
                3653 => [0.4800, 358.3077],
                999999999 => [0.5600, 650.6154],
            ],
        ],
    ];

    /**
     * {@inheritDoc}
     */
    public function getCoefficients(Payer $payer, Payee $payee, Earning $earning): array
    {
        // Work out date to apply
        $coefficientDate = null;

        foreach (array_keys($this->scaledCoefficients) as $date) {
            if (!Date::from($earning->getPayDate(), $date)) {
                break;
            }
            $coefficientDate = $date;
        }

        if (is_null($coefficientDate)) {
            return [];
        }

        // Foreign residents always use scale 3
        if ($payee->getResidencyStatus() === \ManageIt\PaygTax\Entities\Payee::FOREIGN_RESIDENT) {
            return $this->scaledCoefficients[$coefficientDate]['scale3'];
        }

        // People not claiming tax free threshold must use scale 1
        if (!$payee->claimsTaxFreeThreshold()) {
            return $this->scaledCoefficients[$coefficientDate]['scale1'];
        }

        // People claiming full Medicare levy exemption
        if ($payee->getMedicareLevyExemption() === \ManageIt\PaygTax\Entities\Payee::MEDICARE_LEVY_EXEMPTION_FULL) {
            return $this->scaledCoefficients[$coefficientDate]['scale5'];
        }

        // People claiming half Medicare levy exemption
        if ($payee->getMedicareLevyExemption() === \ManageIt\PaygTax\Entities\Payee::MEDICARE_LEVY_EXEMPTION_HALF) {
            return $this->scaledCoefficients[$coefficientDate]['scale6'];
        }

        return $this->scaledCoefficients[$coefficientDate]['scale2'];
    }
}
