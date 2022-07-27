<?php

use TechnicalAnalysis as ta;

class TechnicalAnalysis {

    /**
     * The sma function returns the moving average, that is the sum of last y values of x, divided by y.
     */
    public static function SMA($x, $y) {
        $sum = 0;

        for ($i = sizeof($x) - $y; $i < sizeof($x); $i++) {
            $sum += $x[$i]['c'];
        }
        return $sum / $y;
    }

    /**
     * Moving average used in RSI. It is the exponentially weighted moving average with alpha = 1 / length.
     */
    public static function RMA($src, $lenght) {
        $alpha  = 1 / $lenght;
        $sum    = array();
        for ($i = sizeof($src) - $lenght + 1; $i <= sizeof($src); $i++) {
            if ($sum[$i - 1]) array_push($sum, ta::SMA($src, $lenght));
            else array_push($sum, $alpha * $src[$i - 1]['c'] + (1 - $alpha) * $sum[$i - 1]);
        }
        return array_sum($sum);
    }

    /**
     * Relative strength index. It is calculated using the `ta.rma()` of upward and downward changes of `source` over the last `length` bars.
     */
    public static function RSI($x, $y) {
        $u      = array();
        $d      = array();
        $rma_u  = array();
        $rma_d  = array();
        $rs     = 0;
        $rsi    = array();

        for ($i = 0; $i < $y; $i++) {
            var_dump($x[$i]['c']);
        }

        for ($i = sizeof($x) - $y + 1; $i < sizeof($x); $i++) {
            array_push($u, max($x[$i]['c'] - $x[$i - 1]['c'], 0));
            array_push($d, max($x[$i - 1]['c'] - $x[$i]['c'], 0));
            // TextFormatter::prettyPrint($u, 'RMA_UP: ');
            // TextFormatter::prettyPrint($d, 'RMA_DOWN: ');
        }

        $rs     = ta::RMA($u, $y) / ta::RMA($d, $y);
        array_push($rsi, 100 - 100 / (1 + $rs));



        return $rsi;
    }

    static function getRSI($x, $y) {
        $u = array();
        $d = array();

        for ($i = sizeof($x) - $y + 1; $i < sizeof($x); $i++) {
            array_push($u, max($x[$i]['c'] - $x[$i - 1]['c'], 0));
            array_push($d, max($x[$i - 1]['c'] - $x[$i]['c'], 0));
        }

        return (100 - 100 / (1 + array_sum($u) / array_sum($d)));
    }
}

$array = [0.12475, 0.12465, 0.12470, 0.12440, 0.12435, 0.12435, 0.12435, 0.12435, 0.12445, 0.12450, 0.12435];

$array = array_reverse($array);

// TextFormatter::prettyPrint($array, 'DATA: ');
// TextFormatter::prettyPrint(ta::SMA($array, 10), 'SMA: ', Colors::aqua);
// TextFormatter::prettyPrint(ta::RMA($array, 10), 'RMA: ', Colors::purple);
// TextFormatter::prettyPrint(ta::RSI($array, 10), 'RSI: ', Colors::yellow);

// TextFormatter::prettyPrint(ta::getRSI($array, 10), 'RSI: ', Colors::red);