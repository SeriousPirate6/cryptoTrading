<?php

include 'reserved/Utils/Formatter/Formatter.php';
include 'reserved/Variables/Styles.php';

use TechnicalAnalysis as ta;

$array = [7.937, 7.927, 7.916, 7.915, 7.925, 7.924, 7.917, 7.923, 7.929, 7.927, 7.955, 7.955, 7.963, 7.974];

$array = array_reverse($array);

class TechnicalAnalysis {

    /**
     * The sma function returns the moving average, that is the sum of last y values of x, divided by y.
     */
    public static function SMA($x, $y) {
        $sum = 0;
        for ($i = 0; $i < $y; $i++) {
            $sum += $x[$i];
        }
        return $sum / $y;
    }

    /**
     * Moving average used in RSI. It is the exponentially weighted moving average with alpha = 1 / length.
     */
    public static function RMA($src, $lenght) {
        $alpha  = 1 / $lenght;
        $sum    = array();
        for ($i = 0; $i < $lenght; $i++) {
            if ($sum[$i - 1]) array_push($sum, ta::SMA($src, $lenght));
            else array_push($sum, $alpha * $src[$i - 1] + (1 - $alpha) * $sum[$i - 1]);
        }
        return $sum;
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
        $rsi    = 0;

        for ($i = 1; $i < $y; $i++) {
            array_push($u, max($x[$i] - $x[$i - 1], 0));
            array_push($d, max($x[$i - 1] - $x[$i], 0));
        }

        $rma_u = ta::RMA($u, $y);
        $rma_d = ta::RMA($d, $y);

        TextFormatter::prettyPrint($rma_u, 'RMA_UP: ');
        TextFormatter::prettyPrint($rma_d, 'RMA_DOWN: ');

        $rs     = $rma_u[sizeof($rma_u) - 1] / $rma_d[sizeof($rma_d) - 1];
        $rsi    = 100 - 100 / (1 + $rs);

        return $rsi;
    }
}

TextFormatter::prettyPrint($array, 'DATA: ');
TextFormatter::prettyPrint(ta::SMA($array, 10), 'SMA: ', Colors::aqua);
TextFormatter::prettyPrint(ta::RMA($array, sizeof($array)), 'RMA: ', Colors::purple);
TextFormatter::prettyPrint(ta::RSI($array, sizeof($array)), 'RSI: ', Colors::yellow);