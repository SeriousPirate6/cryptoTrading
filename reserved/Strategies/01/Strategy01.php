<?php

include '../../Services/Templates/Templates.php';
include '../../Utils/Trading/Formulas.php';
include '../../Utils/Trading/Indicators.php';
include 'UtilityFuncs.php';

class Strategy01 {
    public $profit;
    public $high_profit;
    public $liquidity = 100;
    public $value = 0;
    public $candlesticks;
    public $closes;
    public $currentRSI;

    public function __construct($profit, $high_profit, $curr) {
        $profit = $this->profit;
        $high_profit = $this->high_profit;
        $this->liquidity = 100;
        $this->value = 0;
        $method     = new GetMethods;
        $this->curr = $curr;
        $methodImpl = $method->getCandlestick($this->curr, '1m', 60);
        $request = SendRequest::sendReuquest($methodImpl);
        $this->candlesticks = ExtractFromRequest::candlesticksCollapsableTable($request);
        $this->closes = ExtractFromRequest::closesCollapsableTable($request);
        $this->closes = ExtractFromRequest::closesToArray($this->closes);
        $this->currentRSI = TradingView::rsi($this->closes, 20);
    }

    public function getCandlesticks() {
        $method     = new GetMethods;

        $methodImpl = $method->getCandlestick($this->curr, '1m', 60);

        $request    = SendRequest::sendReuquest($methodImpl);

        $this->candlesticks = ExtractFromRequest::candlesticksCollapsableTable($request);
        $this->closes = ExtractFromRequest::closesCollapsableTable($request);
    }

    public function buy($print = false) {
        if (UtilityStrat01::rsiBelow30($this->candlesticks, $this->currentRSI)) {
            $lastClose = end($this->closes);
            if ($print) TextFormatter::prettyPrint($lastClose, "LAST CLOSE: ", Colors::orange);
        }
        if (UtilityStrat01::rsiBelow45($this->candlesticks, $this->currentRSI)) {
            $lastClose = end($this->closes);
            if ($print) TextFormatter::prettyPrint($lastClose, "LAST CLOSE: ", Colors::orange);
        }
    }
    public function sell($print = false) {
        if (UtilityStrat01::rsiAbove55($this->candlesticks, $this->currentRSI)) {
            $lastClose = end($this->closes);
            if ($print) TextFormatter::prettyPrint($lastClose, "LAST CLOSE: ", Colors::purple);
        }
        if (UtilityStrat01::rsiAbove70($this->candlesticks, $this->currentRSI)) {
            $lastClose = end($this->closes);
            if ($print) TextFormatter::prettyPrint($lastClose, "LAST CLOSE: ", Colors::purple);
        }
    }
}

$qnt1 = 100;
$qnt2 = 200;
$price = 10;
$profit = 0.25;
$high_profit = 1;
$strat = new Strategy01($profit, $high_profit, Currencies::BTC_USDT);

UtilityStrat01::dropTable();
UtilityStrat01::createTable();
UtilityStrat01::insertTable($qnt1, $qnt2, $price);
$datas = UtilityStrat01::selectLast();
TextFormatter::prettyPrint($datas);

$strat->buy(true);
$strat->sell(true);

var_dump(UtilityStrat01::isQnt1Enough(100));
var_dump(UtilityStrat01::isQnt2Enough(200));