<?php

use TestTa as ta;

class TestTa {
    public static function jsonToArray($json) {
        $array = array(sizeof($json));
        for ($i = 0; $i < sizeof($json); $i++) {
            $array[$i] = $json[$i]['c'];
        }
        return $array;
    }

    public static function gainAndLoss($array) {
        $u = array();
        $d = array();
        for ($i = 1; $i < sizeof($array); $i++) {
            array_push($u, max($array[$i] - $array[$i - 1], 0));
            array_push($d, max($array[$i - 1] - $array[$i], 0));
        }
        return [array_sum($u), array_sum($d)];
    }

    public static function ma($real, $length) {
        return $real / $length;
    }

    public static function rs($array, $length) {
        $avgGain = ta::ma(ta::gainAndLoss($array)[0], $length);
        $avgLoss = ta::ma(ta::gainAndLoss($array)[1], $length);
        return $avgGain / $avgLoss;
    }

    public static function rsi($array, $length) {
        $rs = ta::rs($array, $length);
        return 100 - (100 / (1 + $rs));
    }
}

$array = [0.12475, 0.12465, 0.12470, 0.12440, 0.12435, 0.12435, 0.12435, 0.12435, 0.12445, 0.12450, 0.12435];

// TextFormatter::prettyPrint(ta::rsi($array, $length), "RSI Test: ", Colors::aqua);
// TextFormatter::prettyPrint($array, "", Colors::light_blue);

$array = array_reverse($array);
