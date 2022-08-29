<?php
class UtilityStrat {

    public static $tableName;

    public function __construct($tableName) {
        $this->tableName = $tableName;
    }

    public function createTable() {
        $createCurrData = CreateTable::orders(true, $this->tableName);
        RunQuery::create($createCurrData);
    }

    public function insertTable($params) {
        $insertCurrData = InsertTable::orders($params, true, $this->tableName);
        RunQuery::insert($insertCurrData);
    }

    public function select($table = false) {
        $selectCurrData = SelectFrom::orders(true, $this->tableName);
        $rows = RunQuery::select($selectCurrData, $table);
        $lastRow = $rows ? $rows : null;
        return $lastRow;
    }

    public function selectLast($table = false) {
        $selectCurrData = SelectFrom::orders(true, $this->tableName);
        $rows = RunQuery::select($selectCurrData, $table);
        $lastRow = $rows ? end($rows) : null;
        return $lastRow;
    }

    public function dropTable() {
        $dropCurrData = DropTable::orders(true, $this->tableName);
        RunQuery::drop($dropCurrData);
    }

    public static function rsiBelow($currentRSI, $value) {
        if ($currentRSI < $value) return true;
    }

    public static function rsiAbove($currentRSI, $value) {
        if ($currentRSI > $value) return true;
    }

    public function isQnt1Enough($qnt) {
        $instance = new self($this->tableName);
        if ($instance->selectLast()[1] >= $qnt) return true;
        return false;
    }

    public function isQnt2Enough($qnt) {
        $instance = new self($this->tableName);
        if ($instance->selectLast()[2] >= $qnt) return true;
        return false;
    }

    public static function calcPercentage($qnt, $price1, $price2) {
        $ratio = $price2 / $price1;
        return $qnt * $ratio;
    }

    public static function getPercentageOf($perc, $tot) {
        return $tot / 100 * $perc;
    }
}