<?php

use TradingView as ta;

class TradingView {

    /*
    * Average True Range
    * It calculates the true range, max(high - low, abs(high - close[1]), abs(low - close[1])).
    * @return the RMA of the true range.
    */
    public static function atr($array, $length) {
        $len = sizeof($array);
        $trueRange = array();
        $count = 0;
        for ($i = $len - $length; $i < $len; $i++) {
            $curr = $array[$i];
            $prev = $array[$i - 1];
            if (!$prev['h']) {
                $trueRange[$count] = $curr['h'] - $curr['l'];
            } else {
                $trueRange[$count] = max(
                    max(
                        $curr['h'] - $curr['l'],
                        abs($curr['h'] - $prev['c'])
                    ),
                    abs($curr['l'] - $prev['c'])
                );
            }
            $count++;
        }
        return ta::rma($trueRange, $length);
    }

    /*
    * Simple Moving Average
    * @return the the sum of last y values of x, divided by y.
    */
    public static function sma($x, $y) {
        $len = sizeof($x);
        if ($len < $y) {
            echo "Array shorter than periodt requested.";
            return false;
        }
        $sum = 0;
        for ($i = $len - $y; $i < $len; $i++) {
            if ($x[$i] == 0) {
                continue;
            }
            $sum += $x[$i] / $y;
        }
        return $sum;
    }

    /*
    * Relative Moving Average
    * @return the exponentially weighted moving average with alpha = 1 / length.
    */
    public static function rma($src, $length) {
        $len = sizeof($src);
        if ($len < $length) return false;
        $alpha = 1 / $length;
        $sum = array();
        for ($i = $len - $length; $i < $len; $i++) {
            if (!$sum[$i - 1]) {
                $sum[$i] = ta::sma($src, $length);
            } else {
                $sum[$i] = $alpha * $src[$i] + (1 - $alpha) * $sum[$i - 1];
            }
        }
        // it returns the last calculated value
        return $sum[$len - 1];
    }

    /*
    * Exponenatial Moving Average
    * @return the exponentially weighted moving average with alpha = 2 / (length + 1).
    */
    public static function ema($src, $length) {
        $len = sizeof($src);
        if ($len < $length) return false;
        $alpha = 2 / ($length + 1);
        $sum = array();
        for ($i = $len - $length; $i < $len; $i++) {
            if (!$sum[$i - 1]) $sum[$i] = ta::sma($src, $length);
            else $sum[$i] = $alpha * $src[$i] + (1 - $alpha) * $sum[$i - 1];
        }
        // it returns the last calculated value
        return $sum[$len - 1];
    }

    /*
    * Relative Strenght Index
    * @return the RSI calculated using the ta.rma() of upward and downward changes of x over the last y bars.
    */
    public static function rsi($x, $y) {
        $len = sizeof($x);
        // less or equal because we check for $x[$i - 1]
        if ($len <= $y) return false;
        $u = array();
        $d = array();
        $count = 0;
        for ($i = $len - $y; $i < $len; $i++) {
            $u[$count] = max($x[$i] - $x[$i - 1], 0);
            $d[$count] = max($x[$i - 1] - $x[$i], 0);
            $count++;
        }
        $rs = ta::rma($u, $y) / ta::rma($d, $y);
        $rsi = 100 - 100 / (1 + $rs);
        return $rsi;
    }
}