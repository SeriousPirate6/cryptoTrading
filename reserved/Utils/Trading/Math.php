<?php
include '../../Variables/Styles.php';
include '../../Utils/Formatter/Formatter.php';

class Math {
    /*
    * Get Earn
    * @return Return Earn of different stacked coins and their relative A.P.Y.
    */
    public static function getEarn($array, $print = false) {
        $tot = 0;
        foreach ($array as $ar) {
            if (sizeof($ar) != 2) return 'Objects inside the main array must be two elements arrays';
            $tot = $ar[0] * $ar[1] / 100 + $tot;
        }
        $calc_tot = [$tot, $tot / 12, $tot / 52];
        if ($print) {
            TextFormatter::prettyPrint('Y: ' . $calc_tot[0]);
            TextFormatter::prettyPrint('M: ' . $calc_tot[1]);
            TextFormatter::prettyPrint('W: ' . $calc_tot[2]);
        }
        return $calc_tot;
    }

    public static function getProfit($array, $print = false, $onlyGain = false) {
        $profit = array();
        $tot    = 0;

        $text = new CollapsibleTable('PROFIT');
        $text->addToPrint(['BUY', 'SELL', '% PROFIT', 'TOT']);

        for ($i = 0; $i < sizeof($array); $i++) {
            $ar     = $array[$i];
            $perc   = round(
                (100 - $ar[0] / $ar[1] * 100),
                2,
                PHP_ROUND_HALF_DOWN
            );
            if ($onlyGain) {
                if ($perc < 0) continue;
            }
            $tot += $perc;
            $row = [$ar[0], $ar[1], $perc, $tot];
            array_push(
                $profit,
                $row
            );
            $text->addToPrint($row, $perc > 0 ? Colors::green : Colors::red);
        }
        if ($print) {
            $text->collapsablePrint($text->array, true);
        }
        return $profit;
    }
}

$earn = [
    [148.6,     06],
    [85,        06],
    [131.7,     11],
    [608.1,     03],
    [396.63,    03],
    [322.42,    03],
    [106.43,    02],
];

$profit2608 = [
    [21445, 21562],
    [21495, 21608],
    [21394, 21688],
    [21411, 21688],
    [21373, 21800],
    [21226, 21800],
    [21495, 21764],
    [20907, 20700],
    [20647, 20700],
    [20597, 20700],
];

$profit2708 = [
    [20283, 20368],
    [20276, 20368],
    [20148, 20368],
    [20142, 20245],
    [19942, 20245],
    [20112, 20229],
    [20027, 20090],
    [19820, 20040],
];

$onlyGain = false;
Math::getProfit($profit2708, true, $onlyGain);