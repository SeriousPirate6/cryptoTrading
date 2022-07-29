<?php
include '../../Services/Templates/Templates.php';
include '../../Utils/Trading/Formulas.php';
include '../../Utils/Trading/Indicators.php';

// Singlerequest
$method     = new GetMethods;
$methodImpl = $method->getCandlestick(CurrenciesList::BTC_USDT, '1m', 60);

$request    = SendRequest::sendReuquest($methodImpl);

// Templates::candlestick();

// // // Templates::currencyData();

// RunQuery::select(selectFrom::currencyData());
// RunQuery::select(selectFrom::currencyValue());

$candles = ExtractFromRequest::candlesticksCollapsableTable($request);
$closes = ExtractFromRequest::closesCollapsableTable($request);

// TextFormatter::prettyPrint(Math::percentage(1.4397, 2.4502), 'PERCENTAGE: ', Colors::yellow);

// TextFormatter::prettyPrint(Math::getATR($closes), 'ATR: ', Colors::violet);

// TextFormatter::prettyPrint(Math::getMA($closes), 'MA: ', Colors::aqua);

// TextFormatter::prettyPrint(sizeof($candles), 'SIZE OF CANDLES: ');
// TextFormatter::prettyPrint(Math::isThirtyEight($candles[0]), '38,2%', Colors::aqua);
// TextFormatter::prettyPrint(Math::getBodyCandle($candles[0]), 'BODY CANDLE: ', Colors::yellow);
// TextFormatter::prettyPrint(Math::isEngulfing(ExtractFromRequest::extractLastCandlesticks($request, 2)), 'ENGULFING: ', Colors::orange);

$array = [30694.58, 30714.42, 30713.44, 30713.48, 30719.10, 30772.73, 30716.46, 30702.53];

$closes = ExtractFromRequest::closesToArray($closes);

$bool = false;

if ($bool) {
    $seconds = 10;
    echo "<h1>DYNAMIC " . $seconds . " S</h1>";
    echo "<html>
    <head>
    <meta http-equiv=\"refresh\" content=\"" . $seconds . "\">"
        .
        TextFormatter::prettyPrint(TradingView::rsi($closes, 20), 'RSI RMA: ', Colors::purple) .
        TextFormatter::prettyPrint(TradingView::sma($closes, 20), 'SMA: ', Colors::yellow) .
        TextFormatter::prettyPrint(TradingView::rma($closes, 20), 'RMA: ', Colors::violet) .
        TextFormatter::prettyPrint(TradingView::atr($candles, 20), 'ATR: ', Colors::orange) .
        "</head>
    <body>";
} else {
    echo "<h1>STATIC</h1>";
    TextFormatter::prettyPrint(TradingView::rsi($closes, 20), 'RSI RMA: ', Colors::purple);
    TextFormatter::prettyPrint(TradingView::sma($closes, 20), 'SMA: ', Colors::yellow);
    TextFormatter::prettyPrint(TradingView::rma($closes, 20), 'RMA: ', Colors::violet);
    TextFormatter::prettyPrint(TradingView::atr($candles, 20), 'ATR: ', Colors::orange);
}