<?php
class Math {
    /**
     * Percentage
     * Return the gain or loss percentage between two given values
     */
    public static function percentage($n1, $n2) {
        return round(($n2 * 100 / $n1 - 100), 2, PHP_ROUND_HALF_EVEN);
    }

    /**
     * IsGoingUp
     * Check if the candlestick is red or green
     * True: the candlestick is green
     * False: the candlestick is red
     */
    public static function isGoingUp($candlestick) {
        if ($candlestick['c'] > $candlestick['o']) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * GetBodyCandle
     * Return the absolute difference between the open and the close of a candlestick
     */
    public static function getBodyCandle($candlestick) {
        if (Math::isGoingUp($candlestick)) return $candlestick['c'] - $candlestick['o'];
        return $candlestick['o'] - $candlestick['c'];
    }

    /**
     * 38.2% candlestick
     * Check if the close of a candlestick is above the 38.2% of the body of the candlestick itself. 
     */
    public static function isThirtyEight($candlestick) {
        if (Math::isGoingUp($candlestick)) {
            $thirtyEight = $candlestick['h'] - (($candlestick['h'] - $candlestick['l']) / 100 * 38.2);
            if ($thirtyEight <= $candlestick['o']) return true;
            return false;
        } else {
            $thirtyEight = ($candlestick['h'] - $candlestick['l']) / 100 * 38.2;
            if ($thirtyEight >= $candlestick['o']) return true;
            return false;
        }
    }

    /**
     * Engulfing Candle
     * Return true if between two given candles, the second's body is greater than the first, and the trend is reversed
     */
    public static function isEngulfing($array) {
        if (sizeof($array) != 2) {
            return 'Array must contains two candles to execute the function';
        }
        $firstCand  = $array[0];
        $secondCand = $array[1];
        if (Math::isGoingUp($firstCand) != Math::isGoingUp($secondCand)) {
            if (Math::getBodyCandle($firstCand) <= Math::getBodyCandle($secondCand)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Close Below Candle
     * Return true if between two given candles, the close of the second is lower than the top low of the first
     */
    public static function isCloseBelow($array) {
        if (sizeof($array) != 2) {
            return 'Array must contains two candles to execute the function';
        }
        $firstCand  = $array[0];
        $secondCand = $array[1];
        if ($firstCand['l'] > $secondCand['c']) return true;
        return false;
    }

    /**
     * Close Below Candle
     * Return true if between two given candles, the close of the second is lower than the top low of the first
     * TO BE REVISIONED
     */
    public static function isCloseAbove($array) {
        if (sizeof($array) != 2) {
            return 'Array must contains two candles to execute the function';
        }
        $firstCand  = $array[0];
        $secondCand = $array[1];
        if ($firstCand['h'] < $secondCand['c']) return true;
        return false;
    }
}