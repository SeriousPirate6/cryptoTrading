<?php

include '../../Services/Templates/Templates.php';
include '../../Utils/Trading/Formulas.php';
include '../../Utils/Trading/Indicators.php';
include '../[Utility]/UtilityFuncs.php';
include '../[Data]/Data.php';

class Strategy {
    public $price;
    public $closes;
    public $quantity;
    public $lastClose;
    public $currentRSI;
    public $candlesticks;
    public $utilityStrat;
    public $instrumentName;

    public function __construct($price, $quantity, $instrumentName) {
        $this->utilityStrat = new UtilityStrat(basename(__DIR__));
        $this->instrumentName = $instrumentName;
        /**
         * Checking if table and datas are already present
         */
        if (!$this->utilityStrat->selectLast()) {
            $this->price    = $price;
            $this->quantity = $quantity;
        } else {
            $this->price    = $this->utilityStrat->selectLast()[1];
            $this->quantity = $this->utilityStrat->selectLast()[2];
        }

        /**
         * Method setup, API call and data extraction
         */
        $method     = new GetMethods;
        $methodImpl = $method->getCandlestick($instrumentName, '1m', 60);
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
    public function setPrice($price) {
        if (!$this->utilityStrat->selectLast()) {
            $this->price = $price;
        } else {
            $this->price = $this->utilityStrat->selectLast()[1];
        }
    }

    public function setQuantity($quantity) {
        if (!$this->utilityStrat->selectLast()) {
            $this->quantity = $quantity;
        } else {
            $this->quantity = $this->utilityStrat->selectLast()[2];
        }
    }

    /**
     * Update DB
     */
    public function updateDB() {
        $param = UtilityStrat::setParams('START', $this->price, $this->quantity, $this->instrumentName);
        if (!$this->utilityStrat->selectLast()) {
            $this->utilityStrat->insertTable($param);
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

$price = 100;
$utilityStrat = new UtilityStrat(basename(__DIR__));
$instrumentName = Currencies::ETH_USDT;

$utilityStrat->dropTable();
$utilityStrat->createTable();

$strat = new Strategy($price, $quantity, Currencies::ETH_USDT);
$quantity = $price / $strat->lastClose;
$strat->setQuantity($quantity);
$strat->updateDB();

$orderList["order_id"] = 10;

$utilityStrat->insertTable(
    $orderList
);
$datas = $utilityStrat->selectLast();
$datas = $utilityStrat->select();
TextFormatter::prettyPrint($datas, '', Colors::orange);
$datas = $utilityStrat->selectPriceBelowThan(30000);
TextFormatter::prettyPrint($datas, '', Colors::blue);

// $strat->buy(true);
// $strat->sell(true);

$method                 = new GetMethods;
$method->instrumentName = $instrumentName;

$methodImpl = $method->createOrder($params);
$request = SendRequest::sendReuquest($methodImpl, true);

TextFormatter::prettyPrint($strat->currentRSI, 'RSI', Colors::yellow);