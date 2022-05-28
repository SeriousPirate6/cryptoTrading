<?php
    include '../../Services/Templates/Templates.php';
    include '../../Utils/Calculate/Calculate.php';

    // Singlerequest
    $method     = new GetMethods;
    $methodImpl = $method->getCandlestick(CurrenciesList::BTC_USDT, m1, 8);

    $request    = SendRequest::sendReuquest($methodImpl);      

    // Templates::candlestick();

    // // // Templates::currencyData();

    // RunQuery::select(selectFrom::currencyData());
    // RunQuery::select(selectFrom::currencyValue());

    $candles = ExtractFromRequest::extractCandlesticks($request);
    $closes = ExtractFromRequest::extractCloses($request);

    TextFormatter::prettyPrint(Math::percentage(1.4397, 2.4502), 'PERCENTAGE: ', Colors::yellow);
    
    TextFormatter::prettyPrint(Math::getATR($closes), 'ATR: ', Colors::violet);

    TextFormatter::prettyPrint($candles, 'CANDLES: ', Colors::blue);
    TextFormatter::prettyPrint($closes, 'CLOSES: ', Colors::purple);
    TextFormatter::prettyPrint(Math::getMA($closes), 'MA: ', Colors::aqua);

    TextFormatter::prettyPrint(sizeof($candles), 'SIZE OF CANDLES: ');
    TextFormatter::prettyPrint(Math::isThirtyEight($candles[0]), '38,2%', Colors::aqua);
    TextFormatter::prettyPrint(Math::getBodyCandle($candles[0]), 'BODY CANDLE: ', Colors::yellow);
    TextFormatter::prettyPrint(Math::isEngulfing(ExtractFromRequest::extractLastCandlesticks($request, 2)), 'ENGULFING: ', Colors::orange);

    $AVG = Math::getAverageGainAndLoss($closes);

    TextFormatter::prettyPrint($AVG[0], 'GAIN: ', Colors::green);
    TextFormatter::prettyPrint($AVG[1], 'LOSS: ', Colors::red);
    TextFormatter::prettyPrint(Math::getRS($closes), 'RS: ', Colors::yellow);
    TextFormatter::prettyPrint(Math::getRSI(array_reverse($closes)), 'RSI: ', Colors::violet);
?>