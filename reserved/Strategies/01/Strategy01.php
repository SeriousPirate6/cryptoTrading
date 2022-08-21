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
    public $lastClose;

    public function __construct($qnt1, $qnt2, $curr) {
        $profit = $this->profit;
        $high_profit = $this->high_profit;
        $this->liquidity = $qnt1;
        $this->value = $qnt2;
        $method     = new GetMethods;
        $this->curr = $curr;
        $methodImpl = $method->getCandlestick($this->curr, '1m', 60);
        $request = SendRequest::sendReuquest($methodImpl);
        $this->candlesticks = ExtractFromRequest::candlesticksCollapsableTable($request);
        $this->closes = ExtractFromRequest::closesCollapsableTable($request);
        $this->closes = ExtractFromRequest::closesToArray($this->closes);
        $this->currentRSI = TradingView::rsi($this->closes, 20);
        $this->lastClose = end($this->closes);
    }

    public function setQnt1($qnt1) {
        $this->liquidity = $qnt1;
    }

    public function setQnt2($qnt2) {
        $this->value = $qnt2;
    }

    public function updateDB() {
        if (!UtilityStrat01::selectLast()) {
            UtilityStrat01::insertTable(
                $this->liquidity,
                $this->value,
                $this->lastClose,
                ""
            );
        }
    }

    public function getCandlesticks() {
        $method     = new GetMethods;

        $methodImpl = $method->getCandlestick($this->curr, '1m', 60);

        $request    = SendRequest::sendReuquest($methodImpl);

        $this->candlesticks = ExtractFromRequest::candlesticksCollapsableTable($request);
        $this->closes = ExtractFromRequest::closesCollapsableTable($request);
    }

    public function buy($print = false) {
        if ($print) TextFormatter::prettyPrint("BUY: ", '', Colors::orange);
        $buy = 0;
        if (UtilityStrat01::rsiBelow30($this->candlesticks, $this->currentRSI)) {
            $lastClose = end($this->closes);
            if ($print) TextFormatter::prettyPrint($lastClose, "<30 LAST CLOSE: ", Colors::orange);

            $buy = UtilityStrat01::getPercentageOf(15, $this->liquidity);
            UtilityStrat01::insertTable(
                $this->liquidity - $buy,
                $this->value + $buy / $lastClose,
                $lastClose,
                "BUY 30"
            );
        }
        if (UtilityStrat01::rsiBelow45($this->candlesticks, $this->currentRSI)) {
            $lastClose = end($this->closes);
            if ($print) TextFormatter::prettyPrint($lastClose, "<45 LAST CLOSE: ", Colors::orange);

            $buy = UtilityStrat01::getPercentageOf(10, $this->liquidity);
            UtilityStrat01::insertTable(
                $this->liquidity - $buy,
                $this->value + $buy / $lastClose,
                $lastClose,
                "BUY 45"
            );
        }
    }
    public function sell($print = false) {
        if ($print) TextFormatter::prettyPrint("SELL: ", '', Colors::purple);
        if (UtilityStrat01::rsiAbove55($this->candlesticks, $this->currentRSI)) {
            $lastClose = end($this->closes);
            if ($print) TextFormatter::prettyPrint($lastClose, ">55 LAST CLOSE: ", Colors::purple);

            $sell = UtilityStrat01::getPercentageOf(10, $this->value);
            UtilityStrat01::insertTable(
                $this->liquidity + $sell * $lastClose,
                $this->value - $sell,
                $lastClose,
                "SELL 55"
            );
        }
        if (UtilityStrat01::rsiAbove70($this->candlesticks, $this->currentRSI)) {
            $lastClose = end($this->closes);
            if ($print) TextFormatter::prettyPrint($lastClose, ">70 LAST CLOSE: ", Colors::purple);

            $sell = UtilityStrat01::getPercentageOf(15, $this->value);
            UtilityStrat01::insertTable(
                $this->liquidity + $sell * $lastClose,
                $this->value - $sell,
                $lastClose,
                "SELL 70"
            );
        }
    }
}

$qnt1 = 100;
$qnt2 = 200;
// UtilityStrat01::dropTable();
UtilityStrat01::createTable();

$strat = new Strategy01($qnt1, $qnt2, Currencies::BTC_USDT);
$price = 100 / $strat->lastClose;
$strat->setQnt2($price);
$strat->updateDB();

$profit = 0.25;
$high_profit = 1;

$datas = UtilityStrat01::selectLast();
TextFormatter::prettyPrint($datas);

$strat->buy(true);
$strat->sell(true);

var_dump(UtilityStrat01::isQnt1Enough(100));
var_dump(UtilityStrat01::isQnt2Enough(200));
TextFormatter::prettyPrint($strat->currentRSI, 'RSI', Colors::yellow);
TextFormatter::prettyPrint(UtilityStrat01::calcPercentage(100, 19000, 9500), '', Colors::green);
TextFormatter::prettyPrint(UtilityStrat01::getPercentageOf(15, 200), '', Colors::violet);