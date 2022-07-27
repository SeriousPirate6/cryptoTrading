<?php

use TechnicalAnalysis as ta;

class TechnicalAnalysis {
    /*
    * JSON to Array
    * It estracts an array from the JSON response returned by the API call.
    */
    public static function jsonToArray($json) {
        $array = array(sizeof($json));
        for ($i = 0; $i < sizeof($json); $i++) {
            $array[$i] = $json[$i]['c'];
        }
        return $array;
    }

    /*
    * Gain & Losses
    * It returns a two elements array
    * The first element is the sum of the gains, the second one of the losses
    */
    public static function gainAndLoss($array) {
        $u = array();
        $d = array();
        for ($i = 1; $i < sizeof($array); $i++) {
            array_push($u, max($array[$i] - $array[$i - 1], 0));
            array_push($d, max($array[$i - 1] - $array[$i], 0));
        }
        return [array_sum($u), array_sum($d)];
    }

    /*
    * Moving Average
    * It returns the average value of the given array, by the given period.
    */
    public static function ma($real, $length) {
        return $real / $length;
    }

    /*
    * Relative Strength
    * It calculates the average gain and the average losses of the given array and the given period.
    * It returns the ratio between the average gain and the average losses
    */
    public static function rs($array, $length) {
        $avgGain = ta::ma(ta::gainAndLoss($array)[0], $length);
        $avgLoss = ta::ma(ta::gainAndLoss($array)[1], $length);
        return $avgGain / $avgLoss;
    }

    /*
    * Relative Strength Index
    * It calculates the RS of the given array and the given period.
    * It returns the RSI based on the calculated RS
    */
    public static function rsi($array, $length) {
        $rs = ta::rs($array, $length);
        return 100 - (100 / (1 + $rs));
    }
}
