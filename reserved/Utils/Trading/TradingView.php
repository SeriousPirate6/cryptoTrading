<?php

use TradingView as ta;

class TradingView {

    // JSON to array
    public static function jsonToArray($json) {
        $array = array(sizeof($json));
        for ($i = 0; $i < sizeof($json); $i++) {
            $array[$i] = $json[$i]['c'];
        }
        return $array;
    }

    // Simple moving average
    public static function sma($x, $y) {
        $len = sizeof($x);
        TextFormatter::prettyPrint($x, "SMA X: ", Colors::aqua);
        if ($len < $y) {
            echo "Array shorter than periodt requested.";
            return false;
        }
        $sum = 0;
        for ($i = $len - $y; $i < $len; $i++) {
            if ($x[$i] == 0) {
                // TextFormatter::prettyPrint($x[$i], "SMA: ", Colors::orange);
                // echo "equal to zero " . $i;
                continue;
            }
            $sum += $x[$i] / $y;
            TextFormatter::prettyPrint($sum, "SMA: ", Colors::yellow);
        }
        return $sum;
    }

    // Relativa moving average
    public static function rma($src, $length) {
        $len = sizeof($src);
        if ($len < $length) return false;
        $alpha = 1 / $length;
        $sum = array();
        for ($i = $len - $length; $i < $len; $i++) {
            if (!$sum[$i - 1]) $sum[$i] = ta::sma($src, $length);
            else $sum[$i] = $alpha * $src[$i] + (1 - $alpha) * $sum[$i - 1];
            TextFormatter::prettyPrint($sum[$i], "", Colors::purple);
        }
        // it returns the last calculated value
        return $sum[$len - 1];
    }

    // Exponential moving average
    public static function ema($src, $length) {
        $len = sizeof($src);
        if ($len < $length) return false;
        $alpha = 2 / ($length + 1);
        $sum = array();
        for ($i = $len - $length; $i < $len; $i++) {
            if (!$sum[$i - 1]) $sum[$i] = ta::sma($src, $length);
            else $sum[$i] = $alpha * $src + (1 - $alpha) * $sum[$i - 1];
        }
        // it returns the last calculated value
        return $sum[$len - 1];
    }

    // Relative streght index
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
        TextFormatter::prettyPrint($u, "", Colors::green);
        TextFormatter::prettyPrint($d, "", Colors::red);
        $rs = ta::rma($u, $y) / ta::rma($d, $y);
        $rsi = 100 - 100 / (1 + $rs);
        return $rsi;
    }
}