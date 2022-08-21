<?php
class UtilityStrat01 {

    public static function createTable() {
        $createCurrData     = CreateTable::testStrategy();
        RunQuery::create($createCurrData);
    }

    public static function insertTable($qnt1, $qnt2, $price, $action) {
        $insertCurrData     = InsertTable::testStrategy($qnt1, $qnt2, $price, $action);
        RunQuery::insert($insertCurrData);
    }

    public static function selectLast($table = false) {
        $selectCurrData = SelectFrom::testStrategy();
        $rows = RunQuery::select($selectCurrData, $table);
        $lastRow = $rows ? end($rows) : null;
        return $lastRow;
    }

    public static function dropTable() {
        $dropCurrData     = DropTable::testStrategy();
        RunQuery::drop($dropCurrData);
    }

    public static function rsiBelow30($candlesticks, $currentRSI) {
        if ($currentRSI < 30) return true;
    }

    public static function rsiBelow45($candlesticks, $currentRSI) {
        if ($currentRSI < 45) return true;
    }

    public static function rsiAbove55($candlesticks, $currentRSI) {
        if ($currentRSI > 55) return true;
    }

    public static function rsiAbove70($candlesticks, $currentRSI) {
        if ($currentRSI > 70) return true;
    }

    public static function isQnt1Enough($qnt) {
        if (UtilityStrat01::selectLast()[1] >= $qnt) return true;
        return false;
    }

    public static function isQnt2Enough($qnt) {
        if (UtilityStrat01::selectLast()[2] >= $qnt) return true;
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