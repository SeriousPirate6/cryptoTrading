<?php
    class Math {
        // Percentage
        // Return the gain or loss percentage between two given values
        public static function percentage($n1, $n2) {
            return round(($n2 * 100 / $n1 - 100), 2, PHP_ROUND_HALF_EVEN); 
        }
        
        // IsGoingUp
        // Check if the candlestick is red or green
        // True: the candlestick is green
        // False: the candlestick is red
        public static function isGoingUp($candlestick) {
            if ($candlestick['c'] > $candlestick['o']) {
                return true;
            } else {
                return false;
            }
        }

        // GetBodyCandle
        // Return the absolute difference between the open and the close of a candlestick
        public static function getBodyCandle($candlestick) {
            if (Math::isGoingUp($candlestick)) return $candlestick['c'] - $candlestick['o'];
            return $candlestick['o'] - $candlestick['c'];
        }

        // Average True Range
        // Average of the last 14 candlesticks
        public static function getATR($candlestick) {
            $depth = sizeof($candlestick);

            // if ($depth != 14) {
            //     return 'Array not valid. The ATR is calculated with 14 candlesticks';
            // } else {
                $ATR = 0;
                foreach ($candlestick as $candle) {
                    $ATR = $ATR + $candle['c'];
                }
                return $ATR / $depth;
            // }
        }

        // Moving Average
        // Take the n candlestick closing price and get the average
        // Most used are last 20, 50 or 100 candlestick
        public static function getMA($candlestick) {
            $depth = sizeof($candlestick);
            var_dump($depth);

            // if ($depth != 7 ^ $depth != 14 ^ $depth != 20 ^ $depth != 50 ^ $depth != 100) {
            //     return 'Array not valid. The MA is calculated with 20, 50 or 100 candlesticks';
            // } else {
                $MA = 0;
                foreach ($candlestick as $candle) {
                    $MA = $MA + $candle['c'];
                }
                return $MA / $depth;
            // }
        }

        // 38.2% candlestick
        // Check if the close of a candlestick is above the 38.2% of the body of the candlestick itself. 
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

        // Engulfing Candle
        // Return true if between two given candles, the second's body is greater than the first, and the trend is reversed
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

        // Close Below Candle
        // Return true if between two given candles, the close of the second is lower than the top low of the first
        public static function isCloseBelow($array) {
            if (sizeof($array) != 2) {
                return 'Array must contains two candles to execute the function';
            }
            $firstCand  = $array[0];
            $secondCand = $array[1];
            if ($firstCand['l'] > $secondCand['c']) return true;
            return false;
        }

        // Close Below Candle
        // Return true if between two given candles, the close of the second is lower than the top low of the first
        // TO BE REVISIONED
        public static function isCloseAbove($array) {
            if (sizeof($array) != 2) {
                return 'Array must contains two candles to execute the function';
            }
            $firstCand  = $array[0];
            $secondCand = $array[1];
            if ($firstCand['h'] < $secondCand['c']) return true;
            return false;
        }

        // Average Gain and Average Loss
        // Return a two element array
        // The first element is the average gain
        // The second element is the average loss
        static function getAverageGainAndLoss($array) {
            $depth  = sizeof($array);
            $gain   = 0;
            $loss   = 0;
            for ($i = 1; $i < sizeof($array); $i++) {
                $curr = $array[$i]['c'];
                $prev = $array[$i-1]['c'];
                
                if ($curr > $prev) $gain = abs($curr - $prev) + $gain;
                else $loss = abs($curr - $prev) + $loss;
            }
            if ($depth < 2) return null;
            return [$gain, $loss];
        }

        // Relative Strength
        // return the ratio between average gain and average loss of a given period of time
        static function getRS($array) {
            $AGL = Math::getAverageGainAndLoss($array);
            if ($AGL == null) return null;
            return $AGL[0] / $AGL[1];
        }

        // Relative Strength Index
        // It is a momentum oscillator that measures the speed and change of price movements, it oscillates between 0 and 100.
        // Traditionally the RSI is considered overbought when above 70 and oversold when below 30.
        public static function getRSI($array) {
            $depth = sizeof($array);
            var_dump($depth);
            // if ($depth != 7 ^ $depth != 14 ^ $depth != 20 ^ $depth != 50 ^ $depth != 100) {
            //     return 'Array not valid. The RSI is calculated with 14, 20, 50 or 100 candlesticks';
            // }
            
            $RS = Math::getRS($array);
            if ($RS == null) return null;
            
            $RSI = 100 - (100 / (1 + $RS));
            return $RSI;
        }

        public static function getEMA($candle) {
            $days       = sizeof($candle);
            $EMAprev    = Math::getMA($candle);
            $lastVal    = $candle[$days-1]['c'];
            var_dump($candle[$days-1]['c']);

            $smoothing  = 2 / (1 + $days);
            $EMA        = ($lastVal * $smoothing) + ($EMAprev * (1 - $smoothing));
            
            return $EMA;
        }

        public static function getSMA($candles, $depth) {
            return array_sum($candles) / $depth;
        }

        public static function getRMA($candles, $depth) {
            $alpha   = 1 / $depth;
            return $alpha * array_sum($candles) + (1 - $alpha) * Math::getSMA($candles, $depth);
        }

        // Return Earn of different stacked coins and their relative A.P.Y.
        // $earn = [
            //     [148.6,     06],
            //     [85,        06],
            //     [131.7,     11],
            //     [608.1,     03],
            //     [396.63,    03],
            //     [322.42,    03],
            //     [106.43,    02],
            // ];
            // TextFormatter::prettyPrint('Earn year:  '.Math::getEarn($earn)[0]);
            // TextFormatter::prettyPrint('Earn month: '.Math::getEarn($earn)[1]);
            // TextFormatter::prettyPrint('Earn week:  '.Math::getEarn($earn)[2]);
        public static function getEarn($array) {
            $tot = 0;
            foreach ($array as $ar) {
                if (sizeof($ar) != 2) return 'Objects inside the main array must be two elements arrays';
                $tot = $ar[0] * $ar[1] / 100 + $tot;
            }
            $calc_tot = [$tot, $tot / 12, $tot / 52];
            return $calc_tot;
        }











        // TEST TEMPORARY FUNCTIONS


        static function getAGLTEST($array) {
            $depth  = sizeof($array);
            $gain   = array();
            $loss   = array();
            $gl     = array();
            $colors = array();
            $n = new TextFormatter('GAIN AND LOSS');

            array_push($gl, ['GAIN AND LOSS']);

            $n->addToPrint($gl);
            for ($i = 1; $i < $depth - 1; $i++) {
                // $curr = $array[$i];
                // $prev = $array[$i-1];
                $curr = $array[$i]['c'];
                $prev = $array[$i-1]['c'];
                if ($curr > $prev) {
                    array_push($gain, (abs($curr - $prev)));
                    array_push($colors, 'green');
                    $n->addToPrint(($curr - $prev), 'green');
                }
                else {
                    array_push($loss, abs($curr - $prev));
                    array_push($colors, 'red');
                    $n->addToPrint(($curr - $prev), 'red');
                }
                array_push($gl, ([$i => ($curr - $prev)]));
            }
            // TextFormatter::prettyPrint($gain, 'GAIN: ', Colors::green);
            // TextFormatter::prettyPrint($loss, 'LOSS: ', Colors::red);
            TextFormatter::arrayToTable($gl, $colors);
            $n->collapsablePrint($gl, $colors);
            if ($depth < 2) return null;
            TextFormatter::prettyPrint($depth, 'DEPTH: ');
            $res = [array_sum($gain), array_sum($loss)];
            TextFormatter::prettyPrint($res);
            return $res;
        }

        // Relative Strength
        // return the ratio between average gain and average loss of a given period of time
        static function getRSTest($array) {
            $AGL = Math::getAGLTEST($array);
            if ($AGL == null) return null;
            $res = $AGL[0] / $AGL[1];
            TextFormatter::prettyPrint($res);
            return $res;
        }

        public static function getRSITest($array) {
            $depth = sizeof($array);
            var_dump($depth);
            
            $RS = Math::getRSTest($array);
            if ($RS == null) return null;
            
            $RSI = 100 - (100 / (1 + $RS));
            return $RSI;
        }

        public static function getRSI_EMA($array) {
            $depth = sizeof($array);
            var_dump($depth);
            
            $RS = Math::getEMA($array);
            if ($RS == null) return null;
            
            $RSI = 100 - (100 / (1 + $RS));
            return $RSI;
        }
    }
?>