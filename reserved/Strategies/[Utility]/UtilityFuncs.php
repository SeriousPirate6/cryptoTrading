<?php
class UtilityStrat {

    public static $tableName;

    public function __construct($tableName) {
        $this->tableName = $tableName;
    }

    public function createTable() {
        $createCurrData = CreateTable::testStrategy($this->tableName);
        RunQuery::create($createCurrData);
    }

    public function insertTable($qnt1, $qnt2, $price, $action) {
        $insertCurrData = InsertTable::testStrategy($this->tableName, $qnt1, $qnt2, $price, $action);
        RunQuery::insert($insertCurrData);
    }

    public function selectLast($table = false) {
        $selectCurrData = SelectFrom::testStrategy($this->tableName);
        $rows = RunQuery::select($selectCurrData, $table);
        $lastRow = $rows ? end($rows) : null;
        return $lastRow;
    }

    public function dropTable() {
        $dropCurrData = DropTable::testStrategy($this->tableName);
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