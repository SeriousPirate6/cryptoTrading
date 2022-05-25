<?php
    include '../../Services/Templates/Templates.php';
    include '../../Utils/Calculate/Calculate.php';

    // Singlerequest
    $method     = new GetMethods;
    $methodImpl = $method->getCandlestick(CurrenciesList::DOT_USDT, m1, 20);

    $request    = SendRequest::sendReuquest($methodImpl);      

    // Templates::candlestick();

    // // Templates::currencyData();

    // RunQuery::select(selectFrom::currencyData());
    // RunQuery::select(selectFrom::currencyValue());

    TextFormatter::prettyPrint(Math::percentage(1.4397, 2.4502));

    $array = [1.34, 2.34, 3.03942, 4.433, 5.123, 6.09234, 2, 6, 8, 10, 100, 2342, 121.45, 0.4343];
    
    TextFormatter::prettyPrint(Math::getATR($array));

    $closes = ExtractFromRequest::extractCloses($request);
    TextFormatter::prettyPrint($closes);
    TextFormatter::prettyPrint(Math::getMA($closes));

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

    TextFormatter::prettyPrint(Math::isThirtyEight($arr));
    TextFormatter::prettyPrint(Math::getBodyCandle($arr));
    TextFormatter::prettyPrint(Math::isEngulfing($ar2));
?>