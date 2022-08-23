<?php

include '../../Services/Templates/Templates.php';
include '../../Utils/Trading/Formulas.php';
include '../../Utils/Trading/Indicators.php';
include '../[Utility]/UtilityFuncs.php';

class Strategy {
    public $liquidity;
    public $value;
    public $candlesticks;
    public $closes;
    public $currentRSI;
    public $lastClose;
    public $utilityStrat;

    public function __construct($qnt1, $qnt2, $curr) {
        $this->utilityStrat = new UtilityStrat(basename(__DIR__));
        if (!$this->utilityStrat->selectLast()) {
            $this->liquidity = $qnt1;
            $this->value = $qnt2;
        } else {
            $this->liquidity = $this->utilityStrat->selectLast()[1];
            $this->value = $this->utilityStrat->selectLast()[2];
        }
        $method     = new GetMethods;
        $this->curr = $curr;
        $methodImpl = $method->getCandlestick($this->curr, '1m', 60);
        $request = SendRequest::sendReuquest($methodImpl);
        $this->candlesticks = ExtractFromRequest::candlesticksCollapsableTable($request);
        $this->closes = ExtractFromRequest::closesCollapsableTable($request);
        $this->closes = ExtractFromRequest::closesToArray($this->closes);
        $this->currentRSI = TradingView::rsi($this->closes, 10);
        $this->lastClose = end($this->closes);
    }

    public function setQnt1($qnt1) {
        if (!$this->utilityStrat->selectLast()) {
            $this->liquidity = $qnt1;
        } else {
            $this->liquidity = $this->utilityStrat->selectLast()[1];
        }
    }

    public function setQnt2($qnt2) {
        if (!$this->utilityStrat->selectLast()) {
            $this->value = $qnt2;
        } else {
            $this->value = $this->utilityStrat->selectLast()[2];
        }
    }

    public function updateDB() {
        if (!$this->utilityStrat->selectLast()) {
            $this->utilityStrat->insertTable(
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
        if ($this->utilityStrat->rsiBelow($this->currentRSI, 30)) {
            $lastClose = end($this->closes);
            if ($print) TextFormatter::prettyPrint($lastClose, "<30 LAST CLOSE: ", Colors::orange);

            $buy = $this->utilityStrat->getPercentageOf(80, $this->liquidity);
            $this->utilityStrat->insertTable(
                $this->liquidity - $buy,
                $this->value + ($buy / $lastClose),
                $lastClose,
                "BUY 30"
            );
        }
    }

    public function sell($print = false) {
        if ($print) TextFormatter::prettyPrint("SELL: ", '', Colors::purple);
        if ($this->utilityStrat->rsiAbove($this->currentRSI, 70)) {
            $lastClose = end($this->closes);
            if ($print) TextFormatter::prettyPrint($lastClose, ">70 LAST CLOSE: ", Colors::purple);

            $sell = $this->utilityStrat->getPercentageOf(80, $this->value);
            $this->utilityStrat->insertTable(
                $this->liquidity + $sell * $lastClose,
                $this->value - $sell,
                $lastClose,
                "SELL 70"
            );
        }
    }
}

$qnt1 = 100;
$utilityStrat = new UtilityStrat(basename(__DIR__));
$utilityStrat->createTable();

$strat = new Strategy($qnt1, $price, Currencies::BTC_USDT);
$price = $qnt1 / $strat->lastClose;
$strat->setQnt2($price);
$strat->updateDB();

$datas = $utilityStrat->selectLast();
TextFormatter::prettyPrint($datas);

$strat->buy(true);
$strat->sell(true);

TextFormatter::prettyPrint($strat->currentRSI, 'RSI', Colors::yellow);