<?php
include '../Connection/Conn.php';
include '../../Variables/Tables.php';
include '../../Utils/BuildSQLQuery/BuildSQLQuery.php';

class CreateTable {
    public $query;

    public static function currencyValue() {
        global $constants;
        $instance = new self();
        $instance->query = Query::fill(
            $constants['Tables']['currencyValue'],
            CREATE
        );
        $instance->query->addParam('ID',        'INT',          10, 'UNSIGNED AUTO_INCREMENT PRIMARY KEY');
        $instance->query->addParam('CURRENCY',  'VARCHAR',      30, 'NOT NULL',);
        $instance->query->addParam('PRICE',     'FLOAT',        30, 'NOT NULL',);
        $instance->query->addParam('TREND',     'VARCHAR',      4);
        $instance->query->addParam('TIMEST',    'TIMESTAMP',    0,  'DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
        $instance->query->sqlCommand = QueryBuilder::getSQL($instance->query);
        return $instance->query;
    }

    public static function currencyData() {
        global $constants;
        $instance = new self();
        $instance->query = Query::fill(
            $constants['Tables']['currencyData'],
            CREATE
        );
        $instance->query->addParam('INSTRUMENT_NAME',      'VARCHAR',      30, 'PRIMARY KEY');
        $instance->query->addParam('QUOTE_CURRENCY',       'VARCHAR',      10, 'NOT NULL');
        $instance->query->addParam('BASE_CURRENCY',        'VARCHAR',      10, 'NOT NULL');
        $instance->query->addParam('PRICE_DECIMALS',       'INT',          10);
        $instance->query->addParam('QUANTITY_DECIMALS',    'INT',          10);
        $instance->query->addParam('MAX_QUANTITY',         'FLOAT',        20);
        $instance->query->addParam('MIN_QUANTITY',         'FLOAT',        20);
        $instance->query->addParam('TIMEST',               'TIMESTAMP',    0,  'DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
        $instance->query->sqlCommand = QueryBuilder::getSQL($instance->query);
        return $instance->query;
    }

    public static function testStrategy() {
        global $constants;
        $instance = new self();
        $instance->query = Query::fill(
            $constants['Tables']['testStrategy01'],
            CREATE
        );
        $instance->query->addParam('ID',        'INT',          10, 'UNSIGNED AUTO_INCREMENT PRIMARY KEY');
        $instance->query->addParam('QNT1',      'FLOAT',        30, 'NOT NULL');
        $instance->query->addParam('QNT2',      'FLOAT',        30, 'NOT NULL');
        $instance->query->addParam('PRICE',     'FLOAT',        30, 'NOT NULL');
        $instance->query->addParam('ACTION',    'VARCHAR',      10);
        $instance->query->addParam('TIMEST',    'TIMESTAMP',    0,  'DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
        $instance->query->sqlCommand = QueryBuilder::getSQL($instance->query);
        return $instance->query;
    }
}

class InsertTable {
    public static function currencyData($instName, $quote, $base, $priceDec, $quantityDec, $maxQuant, $minQuant) {
        global $constants;
        $instance = new self();
        $instance->query = Query::fill(
            $constants['Tables']['currencyData'],
            INSERT
        );
        $instance->query->addParam('INSTRUMENT_NAME',      'VARCHAR',      30, $instName);
        $instance->query->addParam('QUOTE_CURRENCY',       'VARCHAR',      10, $quote);
        $instance->query->addParam('BASE_CURRENCY',        'VARCHAR',      10, $base);
        $instance->query->addParam('PRICE_DECIMALS',       'INT',          10, $priceDec);
        $instance->query->addParam('QUANTITY_DECIMALS',    'INT',          10, $quantityDec);
        $instance->query->addParam('MAX_QUANTITY',         'FLOAT',        20, $maxQuant);
        $instance->query->addParam('MIN_QUANTITY',         'FLOAT',        20, $minQuant);
        $instance->query->sqlCommand = QueryBuilder::getSQL($instance->query);
        return $instance->query;
    }

    public static function currencyValue($currency, $price, $trend) {
        global $constants;
        $instance = new self();
        $instance->query = Query::fill(
            $constants['Tables']['currencyValue'],
            INSERT
        );
        $instance->query->addParam('CURRENCY',  'VARCHAR',  30, $currency);
        $instance->query->addParam('PRICE',     'FLOAT',    30, $price);
        $instance->query->addParam('TREND',     'VARCHAR',   4, $trend);
        $instance->query->sqlCommand = QueryBuilder::getSQL($instance->query);
        return $instance->query;
    }

    public static function testStrategy($qnt1, $qnt2, $price, $action) {
        global $constants;
        $instance = new self();
        $instance->query = Query::fill(
            $constants['Tables']['testStrategy01'],
            INSERT
        );
        $instance->query->addParam('QNT1',      'FLOAT',        30, $qnt1);
        $instance->query->addParam('QNT2',      'FLOAT',        30, $qnt2);
        $instance->query->addParam('PRICE',     'FLOAT',        30, $price);
        $instance->query->addParam('ACTION',    'VARCHAR',      10, $action);
        $instance->query->sqlCommand = QueryBuilder::getSQL($instance->query);
        return $instance->query;
    }
}

class SelectFrom {
    public static function currencyData() {
        global $constants;
        $instance = new self();
        $instance->query = Query::fill(
            $constants['Tables']['currencyData'],
            SELECT
        );
        $instance->query->addParam('INSTRUMENT_NAME');
        $instance->query->addParam('QUOTE_CURRENCY');
        $instance->query->addParam('BASE_CURRENCY');
        $instance->query->addParam('PRICE_DECIMALS');
        $instance->query->addParam('QUANTITY_DECIMALS');
        $instance->query->addParam('MAX_QUANTITY');
        $instance->query->addParam('MIN_QUANTITY');
        $instance->query->sqlCommand = QueryBuilder::getSQL($instance->query);
        return $instance->query;
    }

    public static function currencyValue() {
        global $constants;
        $instance = new self();
        $instance->query = Query::fill(
            $constants['Tables']['currencyValue'],
            SELECT
        );
        $instance->query->addParam('CURRENCY');
        $instance->query->addParam('PRICE');
        $instance->query->addParam('TREND');
        $instance->query->sqlCommand = QueryBuilder::getSQL($instance->query);
        return $instance->query;
    }

    public static function testStrategy() {
        global $constants;
        $instance = new self();
        $instance->query = Query::fill(
            $constants['Tables']['testStrategy01'],
            SELECT
        );
        $instance->query->addParam('ID');
        $instance->query->addParam('QNT1');
        $instance->query->addParam('QNT2');
        $instance->query->addParam('PRICE');
        $instance->query->addParam('ACTION');
        $instance->query->addParam('TIMEST');
        $instance->query->sqlCommand = QueryBuilder::getSQL($instance->query);
        return $instance->query;
    }
}

class DropTable {
    public static function testStrategy() {
        global $constants;
        $instance = new self();
        $instance->query = Query::fill(
            $constants['Tables']['testStrategy01'],
            DROP
        );
        $instance->query->sqlCommand = QueryBuilder::getSQL($instance->query);
        return $instance->query;
    }
}

class MultipleInsertTable {
    public static function currencyData($array) {
        $queries = array();

        foreach ($array['result']['instruments'] as $key => $val) {
            $instName       = $val['instrument_name'];
            $quote          = $val['quote_currency'];
            $base           = $val['base_currency'];
            $priceDec       = $val['price_decimals'];
            $quantityDec    = $val['quantity_decimals'];
            $maxQuant       = $val['max_quantity'];
            $minQuant       = $val['min_quantity'];

            array_push($queries, InsertTable::currencyData($instName, $quote, $base, $priceDec, $quantityDec, $maxQuant, $minQuant));
        }

        return $queries;
    }

    public static function currencyValue($array) {
        $queries = array();

        foreach ($array as $key => $val) {
            $currency   = $val['result']['instrument_name'];
            $price      = $val['result']['data'][0]['o'];
            $trend      = 'UP';

            array_push($queries, InsertTable::currencyValue($currency, $price, $trend));
        }

        return $queries;
    }
}