<?php

include '../../Services/Templates/Templates.php';
include '../../Utils/Trading/Formulas.php';
include '../../Utils/Trading/Indicators.php';
include '../[Utility]/UtilityFuncs.php';
include '../[Data]/Data.php';

class Strategy {
    public $value;
    public $closes;
    public $lastClose;
    public $liquidity;
    public $currentRSI;
    public $candlesticks;
    public $utilityStrat;

    public function __construct($qnt1, $qnt2, $curr) {
        $this->utilityStrat = new UtilityStrat(basename(__DIR__));

        /**
         * Checking if table and datas are already present
         */
        if (!$this->utilityStrat->selectLast()) {
            $this->liquidity = $qnt1;
            $this->value = $qnt2;
        } else {
            $this->liquidity = $this->utilityStrat->selectLast()[1];
            $this->value = $this->utilityStrat->selectLast()[2];
        }

        /**
         * Method setup, API call and data extraction
         */
        $method     = new GetMethods;
        $this->curr = $curr;
        $methodImpl = $method->getCandlestick($this->curr, '1m', 60);
        $request = SendRequest::sendReuquest($methodImpl);
        $this->candlesticks = ExtractFromRequest::candlesticksCollapsableTable($request);
        $this->closes = ExtractFromRequest::closesCollapsableTable($request);

        /**
         * Closes
         */
        $this->closes = ExtractFromRequest::closesToArray($this->closes);

        /**
         * Current RSI
         */
        $this->currentRSI = TradingView::rsi($this->closes, 20);

        /**
         * Last closed candlestick
         */
        $this->lastClose = end($this->closes);
    }

    /**
     * Setters
     */
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

    /**
     * Update DB
     */
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

    /**
     * Buying Strategy
     */
    public function buy($print = false) {
        if ($print) TextFormatter::prettyPrint("BUY: ", '', Colors::orange);
        $buy = 0;
        if ($this->utilityStrat->rsiBelow($this->currentRSI, 30)) {
            $lastClose = end($this->closes);
            if ($print) TextFormatter::prettyPrint($lastClose, "<30 LAST CLOSE: ", Colors::orange);

            $buy = $this->utilityStrat->getPercentageOf(20, $this->liquidity);
            $this->utilityStrat->insertTable(
                $this->liquidity - $buy,
                $this->value + ($buy / $lastClose),
                $lastClose,
                "BUY 30"
            );
        }
    }

    /**
     * Selling Strategy
     */
    public function sell($print = false) {
        if ($print) TextFormatter::prettyPrint("SELL: ", '', Colors::purple);
        if ($this->utilityStrat->rsiAbove($this->currentRSI, 70)) {
            $lastClose = end($this->closes);
            if ($print) TextFormatter::prettyPrint($lastClose, ">70 LAST CLOSE: ", Colors::purple);

            $sell = $this->utilityStrat->getPercentageOf(20, $this->value);
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
$curr = Currencies::ETH_USDT;

$utilityStrat->createTable();

$strat = new Strategy($qnt1, $price, Currencies::ETH_USDT);
$price = $qnt1 / $strat->lastClose;
$strat->setQnt2($price);
$strat->updateDB();

$datas = $utilityStrat->selectLast();
TextFormatter::prettyPrint($datas);

$strat->buy(true);
$strat->sell(true);

$createCurrData = CreateTable::orders(false);
RunQuery::create($createCurrData);

$method     = new GetMethods;
$method->curr = $curr;


$instrument_name = "BTC_USDT";
$side = "BUY";
$type = "LIMIT";
$price =  27000;
$quantity = 1;

$methodImpl = $method->createOrder($params);
$request = SendRequest::sendReuquest($methodImpl, true);

TextFormatter::prettyPrint($strat->currentRSI, 'RSI', Colors::yellow);