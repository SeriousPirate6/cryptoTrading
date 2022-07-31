<?php
// Values
abstract class Currencies {
    const BTC_USDT      = 'BTC_USDT';
    const ETH_USDT      = 'ETH_USDT';
    const XRP_USDT      = 'XRP_USDT';
    const SOL_USDT      = 'SOL_USDT';
    const ADA_USDT      = 'ADA_USDT';
    const AVAX_USDT     = 'AVAX_USDT';
    const DOT_USDT      = 'DOT_USDT';
    const CRO_USDT      = 'CRO_USDT';
    const MATIC_USDT    = 'MATIC_USDT';
}

class CurrenciesList extends Currencies {
    /*
    * Get Options
    * @return a JSON array populated with the above listed Currenies.
    */
    public static function getOptions() {
        $curr = new ReflectionClass(__CLASS__);
        $constants = $curr->getConstants();
        $currList = array();
        foreach ($constants as $name => $val) {
            $currList[$val] = $name;
        }
        return $currList;
    }

    /*
    * Get Options As List
    * @return a simple array populated with the above listed Currenies.
    */
    public static function getOptionsAsList() {
        $curr = new ReflectionClass(__CLASS__);
        $constants = $curr->getConstants();
        $currList = array();
        $count = 0;
        foreach ($constants as $name) {
            $currList[$count] = $name;
            $count++;
        }
        return $currList;
    }
}

// Trend
abstract class Trend {
    const UP    = 'UP';
    const DOWN  = 'DOWN';
}