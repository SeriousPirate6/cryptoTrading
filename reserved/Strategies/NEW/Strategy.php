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
    public $liquidity;
    public $currentRSI;
    public $candlesticks;
    public $utilityStrat;
    public $instrumentName;

    public function __construct($liquidity, $price, $quantity, $instrumentName) {
        $this->utilityStrat = new UtilityStrat(basename(__DIR__), $instrumentName);
        $this->instrumentName = $instrumentName;
        /**
         * Checking if table and datas are already present
         */
        if (!$this->utilityStrat->selectBalance()) {
            $this->price        = $price;
            $this->quantity     = $quantity;
            $this->liquidity    = $liquidity;
        } else {
            $this->liquidity    = $this->utilityStrat->selectLastBalance()['funds'];
            $this->quantity     = $this->utilityStrat->selectLastBalance()['asset_qnt'];
            $this->price        = $this->utilityStrat->selectLastBalance()['value_price'];
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
    public function setLiquidity($liquidity) {
        if (!$this->utilityStrat->selectLastBalance()) {
            $this->liquidity = $liquidity;
        } else {
            $this->liquidity = $this->utilityStrat->selectLastBalance()['funds'];
        }
    }

    public function setQuantity($quantity) {
        if (!$this->utilityStrat->selectLastBalance()) {
            $this->quantity = $quantity;
        } else {
            $this->quantity = $this->utilityStrat->selectLastBalance()['asset_qnt'];
        }
    }

    public function setPrice($price) {
        if (!$this->utilityStrat->selectLastBalance()) {
            $this->price = $price;
        } else {
            $this->price = $this->utilityStrat->selectLastBalance()['value_price'];
        }
    }

    /**
     * Update DB
     */
    public function updateDB() {
        $param = UtilityStrat::setBalanceParams(
            $this->instrumentName,
            $this->liquidity,
            $this->quantity,
            $this->price,
            "INITIALIZATION"
        );
        if (!$this->utilityStrat->selectBalance()) {
            $this->utilityStrat->insertBalance($param);
        }
    }

    /**
     * Buying Strategy
     */
    public function buy($print = false) {
        if ($print) TextFormatter::prettyPrint("BUY: ", '', Colors::orange);
        $lastPrice = $this->utilityStrat->getLastPrice();
        $lastClose = end($this->closes);

        // cases
        $rsi30      = $this->utilityStrat->rsiBelow($this->currentRSI, 30);
        $priceDown  = $this->utilityStrat->calcPercentage($lastPrice, $lastClose) < -0.5;

        if ($rsi30 || $priceDown) {
            if ($print) TextFormatter::prettyPrint($lastClose, $rsi30 ? "<30 LAST CLOSE: " : "PRICE DOWN LAST CLOSE: ", Colors::orange);

            $tot_funds = $this->liquidity + $this->price * $this->quantity;

            $buy = $rsi30 ?
                $this->utilityStrat->getPercentageOf(10, $tot_funds) :
                $this->utilityStrat->getPercentageOf(5, $tot_funds);

            if ($this->utilityStrat->isLiQuidityEnough($buy)) {
                $this->liquidity = $this->liquidity - $buy;
                $this->quantity = $this->quantity + ($buy / $lastClose);
                // update the balance
                $this->utilityStrat->insertBalance(
                    UtilityStrat::setBalanceParams(
                        $this->instrumentName,
                        $this->liquidity,
                        $this->quantity,
                        $lastClose,
                        $rsi30 ? "BUY 30" : "PRICE_DOWN"
                    )
                );
                // store the order
                $this->utilityStrat->insertOrders(
                    UtilityStrat::setOrderParams(
                        "BUY",
                        $lastClose,
                        $this->quantity,
                        $this->strumentName,
                    ),
                    true
                );
            } else TextFormatter::prettyPrint("NOT ENOUGH LIQUIDITY, DAAAMN!", '', Colors::red);
        }
    }

    /**
     * Selling Strategy
     */
    public function sell($print = false) {
        if ($print) TextFormatter::prettyPrint("SELL: ", '', Colors::purple);
        $lastPrice = $this->utilityStrat->getLastPrice();
        $lastClose = end($this->closes);

        // cases
        $rsi70      = $this->utilityStrat->rsiBelow($this->currentRSI, 30);
        $priceUp    = $this->utilityStrat->calcPercentage($lastPrice, $lastClose) > -0.5;

        if ($rsi70 || $priceUp) {
            if ($print) TextFormatter::prettyPrint($lastClose, $rsi70 ? ">70 LAST CLOSE: " : "PRICE UP LAST CLOSE: ", Colors::purple);

            $sell = $this->utilityStrat->getFirstBoughtQnt();
            TextFormatter::prettyPrint($sell);
            if ($this->utilityStrat->isAssetQntEnough($sell)) {
                $this->liquidity = $this->liquidity + $sell * $lastClose;
                $this->quantity = $this->quantity - $sell;
                // update the balance
                $this->utilityStrat->insertBalance(
                    UtilityStrat::setBalanceParams(
                        $this->instrumentName,
                        $this->liquidity,
                        $this->quantity,
                        $lastClose,
                        $rsi70 ? "SELL 70" : "PRICE_UP"
                    )
                );
                $closing_order_id = $this->utilityStrat->selectPriceBelowThan($lastClose)[0]["id"];
                TextFormatter::prettyPrint($closing_order_id, 'CLOSING ORDER', Colors::blue);

                // set order params
                $order_params = UtilityStrat::setOrderParams(
                    "SELL",
                    $lastClose,
                    $this->quantity,
                    $this->strumentName,
                );
                // close the pending order: move it from active order table to history table
                $this->utilityStrat->closeOrder($closing_order_id, $order_params);
            } else TextFormatter::prettyPrint("NOT ENOUGH ASSETS, DAAAMN!", '', Colors::yellow);
        }
    }
}

// initializing params
$liquidity      = 100;
$price          = 100;
$instrumentName = Currencies::ETH_USDT;
$utilityStrat   = new UtilityStrat(basename(__DIR__), $instrumentName);

// creating tables
$utilityStrat->createOrders(true);
$utilityStrat->createOrders(false);
$utilityStrat->createBalance();

// Initializing the strategy
$strat = new Strategy($liquidity, $price, 0, $instrumentName);
$quantity = $price / $strat->lastClose;
$strat->setQuantity($quantity);
$strat->updateDB();

TextFormatter::prettyPrint($strat->quantity, 'QNT', Colors::purple);

$datas = $utilityStrat->selectLastOrder();
$datas = $utilityStrat->selectOrders(true);

// $strat->buy(true);
$strat->sell(true);

/**
 * Create Order
 */
// $method                 = new GetMethods;
// $method->instrumentName = $instrumentName;
// $methodImpl = $method->createOrder($params);
// $request = SendRequest::sendReuquest($methodImpl, true);

TextFormatter::prettyPrint($strat->currentRSI, 'RSI', Colors::yellow);