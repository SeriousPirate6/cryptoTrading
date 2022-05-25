<?php
    class Math {
        // Percentage
        // Return the gain or loss percentage between two given values
        public static function percentage($n1, $n2) {
            return round(($n2 * 100 / $n1 - 100), 2, PHP_ROUND_HALF_EVEN); 
        }
        
        // Check if the candlestick is red or green
        // True: the candlestick is green
        // False: the candlestick is red
        public static function isGoingUp($candlestick) {
            if ($candlestick['c'] > $candlestick['o']) {
                echo ' - UP - ';
                return true;
            } else {
                echo ' - DOWN - ';
                return false;
            }
        }

        public static function getBodyCandle($candlestick) {
            if (Math::isGoingUp($candlestick)) return $candlestick['c'] - $candlestick['o'];
            return $candlestick['o'] - $candlestick['c'];
        }

        // Average True Range
        // Average of the last 14 candlesticks
        public static function getATR($candlestick) {
            $depth = sizeof($candlestick);

            if ($depth != 14) {
                return 'Array not valid. The ATR is calculated with 14 candlesticks';
            } else {
                $ATR = 0;
                foreach ($candlestick as $candle) {
                    $ATR = $ATR + $candle;
                }
                return $ATR / $depth;
            }
        }

        // Moving Average
        // Take the n candlestick closing price and get the average
        // Most used are last 20, 50 or 100 candlestick
        public static function getMA($candlestick) {
            $depth = sizeof($candlestick);

            if ($depth != 20 ^ $depth != 50 ^ $depth != 100) {
                return 'Array not valid. The MA is calculated with 20, 50 or 100 candlesticks';
            } else {
                $MA = 0;
                foreach ($candlestick as $candle) {
                    $MA = $MA + $candle;
                }
                return $MA / $depth;
            }
        }

        // 38.2% candlestick
        // Check if the close of a candlestick is above the 38.2% of the body of the candlestick itself. 
        public static function isThirtyEight($candlestick) {
            if (Math::isGoingUp($candlestick)) {
                $thirtyEight = $candlestick['h'] - (($candlestick['h'] - $candlestick['l']) / 100 * 38.2);
                var_dump($thirtyEight);
                if ($thirtyEight <= $candlestick['o']) return true;
                return false;
            } else {
                $thirtyEight = ($candlestick['h'] - $candlestick['l']) / 100 * 38.2;
                var_dump($thirtyEight);
                if ($thirtyEight >= $candlestick['o']) return true;
                return false;
            }
        }

        // Engulfing Candle
        // Return true if between two given candles, the second's body is greater than the first, and the trend is reversed
        public static function isEngulfingCandle($array) {
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
    }
?>