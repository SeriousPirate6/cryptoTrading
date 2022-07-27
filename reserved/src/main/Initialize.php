<?php
include '../../Services/Templates/Templates.php';
include '../../Utils/Calculate/Calculate.php';
include '../../../tradFunc.php';
include '../../../testRSIdesperate.php';

// Singlerequest
$method     = new GetMethods;
$methodImpl = $method->getCandlestick(CurrenciesList::ETH_USDT, '1m', 60);

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

// $AVG = Math::getAverageGainAndLoss($closes);

// TextFormatter::prettyPrint($AVG[0], 'GAIN: ', Colors::green);
// TextFormatter::prettyPrint($AVG[1], 'LOSS: ', Colors::red);
// TextFormatter::prettyPrint(Math::getRS($closes), 'RS: ', Colors::yellow);

$array = [30694.58, 30714.42, 30713.44, 30713.48, 30719.10, 30772.73, 30716.46, 30702.53];

// TextFormatter::prettyPrint(TechnicalAnalysis::SMA($closes, 14), 'SMA: ', Colors::yellow);
// TextFormatter::prettyPrint(TechnicalAnalysis::RMA($closes, 14), 'RMA: ', Colors::purple);
// TextFormatter::prettyPrint(TechnicalAnalysis::RSI($closes, 14), 'RSI: ', Colors::green);
TextFormatter::prettyPrint($closes, 'JSON: ', Colors::blue);

$closes = TestTa::jsonToArray(($closes));

TextFormatter::prettyPrint(TestTa::rsi($closes, 14), 'RSI TEST: ', Colors::green);
