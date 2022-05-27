<?php
    include '../../Services/Templates/Templates.php';
    include '../../Utils/Calculate/Calculate.php';

    // Singlerequest
    $method     = new GetMethods;
    $methodImpl = $method->getCandlestick(CurrenciesList::DOT_USDT, m1, 15);

    $request    = SendRequest::sendReuquest($methodImpl);      

    // Templates::candlestick();

    // // Templates::currencyData();

    // RunQuery::select(selectFrom::currencyData());
    // RunQuery::select(selectFrom::currencyValue());

    TextFormatter::prettyPrint('Percentage: '.Math::percentage(1.4397, 2.4502));

    $array = [1.34, 2.34, 3.03942, 4.433, 5.123, 6.09234, 2, 6, 8, 10, 100, 2342, 121.45, 0.4343];
    
    TextFormatter::prettyPrint('ATR: '.Math::getATR($array));

    $candles = ExtractFromRequest::extractCandlesticks($request);
    TextFormatter::prettyPrint($candles);
    $closes = ExtractFromRequest::extractCloses($request);
    TextFormatter::prettyPrint($closes);
    TextFormatter::prettyPrint('MA: '.Math::getMA($closes));

    $arr =  ([
        't' => 1653336060000,
        'o' => 330,
        'h' => 400,
        'l' => 200,
        'c' => 360,
        'v' => 489.905
    ]);

    $ar2 =  [([
        't' => 1653336060000,
        'o' => 330,
        'h' => 400,
        'l' => 200,
        'c' => 360,
        'v' => 489.905
    ]),
    ([
        't' => 1653336080000,
        'o' => 200,
        'h' => 400,
        'l' => 150,
        'c' => 160,
        'v' => 489.905
    ])];

    // $candles = [14.5, 18.45, 12.75, 15.35, 13.05, 16.10, 12.20, 11.65, 13.25, 15.30, 14.85, 16.15, 19.05, 21.45, 17.55];
    TextFormatter::prettyPrint('SIZE: '.sizeof($candles));
    TextFormatter::prettyPrint('Is 38.2: '.Math::isThirtyEight($arr));
    TextFormatter::prettyPrint('Body candle: '.Math::getBodyCandle($arr));
    TextFormatter::prettyPrint('Is engulfing: '.Math::isEngulfing($ar2));

    $AVG = Math::getAverageGainAndLoss($closes);

    TextFormatter::prettyPrint('Gain: '.$AVG[0]);
    TextFormatter::prettyPrint('Loss: '.$AVG[1]);
    TextFormatter::prettyPrint('RS: '.Math::getRS($closes));
    TextFormatter::prettyPrint('RSI: '.Math::getRSI($closes));
?>