<?php
class Math {
    // Average True Range
    // Average of the last 14 candlesticks
    private static function getATR($candlestick) {
        $depth = sizeof($candlestick);

        // if ($depth != 14) {
        // return 'Array not valid. The ATR is calculated with 14 candlesticks';
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
    private static function getMA($candlestick) {
        $depth = sizeof($candlestick);
        var_dump($depth);

        // if ($depth != 7 ^ $depth != 14 ^ $depth != 20 ^ $depth != 50 ^ $depth != 100) {
        // return 'Array not valid. The MA is calculated with 20, 50 or 100 candlesticks';
        // } else {
        $MA = 0;
        foreach ($candlestick as $candle) {
            $MA = $MA + $candle['c'];
        }
        return $MA / $depth;
        // }
    }

    // Average Gain and Average Loss
    // Return a two element array
    // The first element is the average gain
    // The second element is the average loss
    static function getAverageGainAndLoss($array) {
        $depth  = sizeof($array);
        $gain   = array();
        $loss   = array();
        for (
            $i = 1;
            $i < sizeof($array);
            $i++
        ) {
            $curr = $array[$i]['c'];
            $prev = $array[$i - 1]['c'];

            if ($curr > $prev) {
                array_push($gain, abs($curr - $prev));
                array_push($loss, 0);
            } else {
                array_push($loss, abs($curr - $prev));
                array_push($gain, 0);
            }
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
    private static function getRSI($array) {
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

    private static function getEMA($candle) {
        $days       = sizeof($candle);
        $EMAprev    = Math::getMA($candle);
        $lastVal    = $candle[$days - 1]['c'];
        var_dump($candle[$days - 1]['c']);

        $smoothing  = 2 / (1 + $days);
        $EMA        = ($lastVal * $smoothing) + ($EMAprev * (1 - $smoothing));

        return $EMA;
    }

    // getSMA
    // Returns the Simple Moving Average of a set of candlestick.
    private static function getSMA($candles) {
        $depth = sizeof($candles);
        $sum = 0;
        foreach ($candles as $c) {
            $sum = $sum + $c / $depth;
        }
        return $sum;
    }

    // getRMA
    // Returns the Relative Moving Average of a set of candlestick.
    // It refers to TradingView function write in Pinescript.
    private static function getRMA($candles, $depth) {
        $sum    = array();
        $alpha  = 1 / $depth;
        // TextFormatter::prettyPrint($candles);
        for ($i = 0; $i < sizeof($candles); $i++) {
            if ($i == 0) $temp = Math::getSMA($candles);
            else $temp = $alpha * $candles[$i] + (1 - $alpha) * $sum[$i - 1];
            array_push($sum, $temp);
        }
        return $sum;
    }

    private static function getTradingViewRSI($candles, $depth) {
        $RSI    = array();
        $up     = Math::getAverageGainAndLoss($candles)[0];
        $down   = Math::getAverageGainAndLoss($candles)[1];

        TextFormatter::prettyPrint($up, 'UP', Colors::aqua);
        TextFormatter::prettyPrint($down, 'DOWN', Colors::green);

        TextFormatter::prettyPrint(Math::getRMA($up, $depth), 'RMA UP', Colors::yellow);
        TextFormatter::prettyPrint(
            Math::getRMA($down, $depth),
            'RMA DOWN',
            Colors::purple
        );
        $RM_up      = array_sum(Math::getRMA($up, $depth - 1));
        $RM_down    = array_sum(Math::getRMA($down, $depth - 1));

        $RS = $RM_up / $RM_down;
        $RSI = (100 - 100 / (1 + $RS));
        return $RSI;
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
    private static function getEarn($array) {
        $tot = 0;
        foreach ($array as $ar) {
            if (sizeof($ar) != 2) return 'Objects inside the main array must be two elements arrays';
            $tot = $ar[0] * $ar[1] / 100 + $tot;
        }
        $calc_tot = [$tot, $tot / 12, $tot / 52];
        return $calc_tot;
    }
}
