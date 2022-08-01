<?php
include 'Style.html';
include 'Components/CollpapsibleTable.php';

class TextFormatter {

    public static function prettyPrint($uglyText, $description = false, $color = false) {
        if ($description) echo '<h4 color=' . $color . '>' . print_r($description, true) . '</h4>';
        echo '<pre color=' . $color . '>' . print_r($uglyText ? $uglyText : 'false', true) . '</pre>';
    }

    private static function millsToDate($timestamp) {
        if (strlen($timestamp) == 13) $timestamp = $timestamp / 1000;
        $date = date('Y-m-d H:i:s', $timestamp);
        return $date;
    }

    public static function jsonReadableDate($result) {
        $edRes = $result['result']['data'];
        for ($i = 0; $i < sizeof($edRes); $i++) {
            $readableDate = $edRes[$i]['t'];
            $edRes[$i]['t'] = $readableDate . ' => ' . TextFormatter::millsToDate((int) $readableDate);
        }
        $result['result']['data'] = $edRes;
        return $result;
    }

    public function collapsibleTable() {
        include '../../Utils/Formatter/Components/CollapsibleTable.php';
    }

    public static function switchButton($seconds) {
        include '../../Utils/Formatter/Components/SwitchButton.php';
    }

    public static function dropdown($list) {
        include '../../Utils/Formatter/Components/Dropdown.php';
    }
}