<?php
include '../../Services/Templates/Templates.php';
include '../../Utils/Trading/Formulas.php';
include '../../Utils/Trading/Indicators.php';

echo TextFormatter::dropdown(CurrenciesList::getOptionsAsList());

// Singlerequest
$method     = new GetMethods;
// catch variable from PHP -> it was extremely difficult to find
// $curr = "<script>document.writeln(state);</script>";
$curr = $_COOKIE["gfg"];
$methodImpl = $method->getCandlestick($curr, '1m', 60);

$request    = SendRequest::sendReuquest($methodImpl);

// Templates::candlestick();

// // // Templates::currencyData();

// RunQuery::select(selectFrom::currencyData());
// RunQuery::select(selectFrom::currencyValue());

$candles = ExtractFromRequest::candlesticksCollapsableTable($request);
$closes = ExtractFromRequest::closesCollapsableTable($request);

// TextFormatter::prettyPrint(Math::percentage(1.4397, 2.4502), 'PERCENTAGE: ', Colors::yellow);

// TextFormatter::prettyPrint(sizeof($candles), 'SIZE OF CANDLES: ');
// TextFormatter::prettyPrint(Math::isThirtyEight($candles[0]), '38,2%', Colors::aqua);
// TextFormatter::prettyPrint(Math::getBodyCandle($candles[0]), 'BODY CANDLE: ', Colors::yellow);
// TextFormatter::prettyPrint(Math::isEngulfing(ExtractFromRequest::extractLastCandlesticks($request, 2)), 'ENGULFING: ', Colors::orange);

$array = [30694.58, 30714.42, 30713.44, 30713.48, 30719.10, 30772.73, 30716.46, 30702.53];

$closes = ExtractFromRequest::closesToArray($closes);

echo TextFormatter::switchButton(5);

$period = 20;

TextFormatter::prettyPrint(TradingView::rsi($closes, $period), 'RSI RMA: ', Colors::purple);
TextFormatter::prettyPrint(TradingView::sma($closes, $period), 'SMA: ', Colors::yellow);
TextFormatter::prettyPrint(TradingView::rma($closes, $period), 'RMA: ', Colors::violet);
TextFormatter::prettyPrint(TradingView::atr($candles, $period), 'ATR: ', Colors::orange);