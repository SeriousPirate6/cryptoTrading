<?php
class UtilityStrat {

    public static $tableName;
    public static $instrumentName;

    /**
     * Constructor
     */
    public function __construct($tableName, $instrumentName) {
        $this->tableName = $tableName;
        $this->instrumentName = $instrumentName;
    }

    /**
     * Setting object to be saved in DB
     */
    public static function setOrderParams($side, $price, $quantity, $instrumentName) {
        global $paramsData;
        $paramsData['orderList']['side']             = $side;
        $paramsData['orderList']['price']            = $price;
        $paramsData['orderList']['quantity']         = $quantity;
        $paramsData['orderList']['instrumentName']   = $instrumentName;
        return $paramsData['orderList'];
    }

    public static function setBalanceParams($instrumentName, $funds, $asset_qnt, $price, $order_reason) {
        global $paramsData;
        $paramsData['balance']['instrument_name']    = $instrumentName;
        $paramsData['balance']['funds']              = $funds;
        $paramsData['balance']['asset_qnt']          = $asset_qnt;
        $paramsData['balance']['price']              = $price;
        $paramsData['balance']['order_reason']       = $order_reason;
        return $paramsData['balance'];
    }

    /**
     * Default SQL functions
     */
    public function createOrders($active) {
        $orders = CreateTable::orders($active, $this->tableName);
        RunQuery::create($orders);
    }

    public function createBalance() {
        $balance = CreateTable::balance($this->tableName);
        RunQuery::create($balance);
    }

    // Only insert and create need the "active" param
    public function insertOrders($params, $active) {
        $orders = InsertTable::orders($params, $active, $this->tableName);
        RunQuery::insert($orders);
    }

    public function insertBalance($params) {
        $balance = InsertTable::balance($params, $this->tableName);
        RunQuery::insert($balance);
    }

    public function dropOrders() {
        $dropOrders = DropTable::orders(true, $this->tableName);
        RunQuery::drop($dropOrders);
    }

    public function dropBalance() {
        $dropBalance = DropTable::balance($this->tableName);
        RunQuery::drop($dropBalance);
    }

    public function selectOrders($table = false) {
        $selectCurrData = SelectFrom::orders(true, $this->tableName);
        $rows = RunQuery::select($selectCurrData, $table);
        $lastRow = $rows ? $rows : null;
        return $lastRow;
    }

    public function selectBalance($table = false) {
        $balance = SelectFrom::balance($this->tableName);
        $rows = RunQuery::select($balance, $table);
        $lastRow = $rows ? $rows : null;
        return $lastRow;
    }

    private function deleteActiveOrder($id, $table = false) {
        // true for isActiveOrders, $tableName for the strategy name
        $order  = DeleteFrom::orders($id, true, $this->tableName);
        RunQuery::delete($order, $table);
    }

    public function closeOrder($id, $params) {
        $instance = new self($this->tableName, $this->instrumentName);
        $instance->insertOrders($params, false);
        $instance->deleteActiveOrder($id, $this->tableName);
    }

    /**
     * Extracting data functions
     */
    public function selectLastOrder($table = false) {
        $selectLastOrder = SelectFrom::orders(true, $this->tableName);
        $rows = RunQuery::select($selectLastOrder, $table);
        $lastRow = $rows ? end($rows) : null;
        return $lastRow;
    }

    public function selectLastBalance($table = false) {
        $selectLastBalance = SelectFrom::balanceForCurrency(
            $this->instrumentName,
            $this->tableName
        );
        $rows = RunQuery::select($selectLastBalance, $table);
        $lastRow = $rows ? end($rows) : null;
        return $lastRow;
    }

    public function getFirstBoughtQnt($table = false) {
        $selectLastBalance = SelectFrom::balanceForCurrency(
            $this->instrumentName,
            $this->tableName
        );
        $rows = RunQuery::select($selectLastBalance, $table);
        $lastPrice = $rows ? $rows[0]['asset_qnt'] : null;
        return $lastPrice;
    }

    /**
     * return last buyed price
     */
    public function getLastPrice($table = false) {
        $selectLastBalance = SelectFrom::balanceForCurrency(
            $this->instrumentName,
            $this->tableName
        );
        $rows = RunQuery::select($selectLastBalance, $table);
        $lastPrice = $rows ? end($rows)['price'] : null;
        return $lastPrice;
    }

    public function selectPriceBelowThan($price, $table = false) {
        $query = SelectFrom::orderBelowCurrentPrice($price, true, $this->tableName);
        $rows = RunQuery::select($query, $table);
        $lastRow = $rows ? $rows : null;
        return $lastRow;
    }

    /**
     * RSI functions
     */
    public static function rsiBelow($currentRSI, $value) {
        if ($currentRSI < $value) return true;
    }

    public static function rsiAbove($currentRSI, $value) {
        if ($currentRSI > $value) return true;
    }

    /**
     * Price functions
     */
    public function isLiquidityEnough($qnt) {
        $instance = new self($this->tableName, $this->instrumentName);
        if ($instance->selectLastBalance()['funds'] >= $qnt) return true;
        return false;
    }

    public function isAssetQntEnough($qnt) {
        $instance = new self($this->tableName, $this->instrumentName);
        if ($instance->selectLastBalance()['asset_qnt'] >= $qnt) return true;
        return false;
    }

    /**
     * Percentage functions
     */
    public static function calcPercentage($price1, $price2) {
        $ratio = 100 - $price2 / $price1 * 100;
        return $ratio;
    }

    public static function getPercentageOf($perc, $tot) {
        return $tot / 100 * $perc;
    }
}