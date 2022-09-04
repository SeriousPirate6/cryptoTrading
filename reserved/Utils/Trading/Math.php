<?php
include 'TestData/Datas.php';
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

    public static function getProfit($array, $print = false, $name = 'PROFIT', $onlyGain = false) {
        $profit = array();
        $tot    = 0;

        $text = new CollapsibleTable($name == '' ? 'PROFIT' : $name);
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

$printable      = true;
$onlyGain       = false;
// Math::getProfit($profit20220826, $printable, '26/08', $onlyGain);
// Math::getProfit($profit20220827, $printable, '27/08', $onlyGain);
Math::getProfit($today, $printable, 'PROFIT PERCENTS', $onlyGain);