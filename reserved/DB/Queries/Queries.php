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

    public static function orders($active = true, $test = null) {
        global $constants;
        $instance = new self();
        $tableName = $active ? $constants['Tables']['orders'] . '_ACTIVE' : $constants['Tables']['orders'] . '_HISTORY';
        $tableName = $test ? 'TEST_' . $test . '_' . $tableName : $tableName;
        $instance->query = Query::fill(
            $tableName,
            CREATE
        );
        $instance->query->addParam('ID',                    'INT',          10, 'UNSIGNED AUTO_INCREMENT PRIMARY KEY');
        $instance->query->addParam('STATUS',                'VARCHAR',      30, 'NOT NULL');
        $instance->query->addParam('SIDE',                  'VARCHAR',      30, 'NOT NULL');
        $instance->query->addParam('PRICE',                 'FLOAT',        30, 'NOT NULL');
        $instance->query->addParam('QUANTITY',              'FLOAT',        30, 'NOT NULL');
        $instance->query->addParam('REASON',                'FLOAT',        30);
        $instance->query->addParam('ORDER_ID',              'VARCHAR',      30, 'NOT NULL');
        $instance->query->addParam('CLIENT_OID',            'VARCHAR',      30, 'NOT NULL');
        $instance->query->addParam('CREATE_TIME',           'TIMESTAMP',    0,  'DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
        $instance->query->addParam('UPDATE_TIME',           'TIMESTAMP',    0,  'DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
        $instance->query->addParam('TYPE',                  'VARCHAR',      30, 'NOT NULL');
        $instance->query->addParam('INSTRUMENT_NAME',       'VARCHAR',      20, 'NOT NULL');
        $instance->query->addParam('AVG_PRICE',             'FLOAT',        30, 'NOT NULL');
        $instance->query->addParam('CUMULATIVE_QUANTITY',   'FLOAT',        30, 'NOT NULL');
        $instance->query->addParam('CUMULATIVE_VALUE',      'FLOAT',        30, 'NOT NULL');
        $instance->query->addParam('FEE_CURRENCY',          'VARCHAR',      30, 'NOT NULL');
        $instance->query->addParam('EXEC_INST',             'VARCHAR',      30, 'NOT NULL');
        $instance->query->addParam('TIME_IN_FORCE',         'VARCHAR',      50, 'NOT NULL');
        $instance->query->sqlCommand = QueryBuilder::getSQL($instance->query);
        return $instance->query;
    }

    public static function balance($test = null) {
        global $constants;
        $instance = new self();
        $tableName = $constants['Tables']['balance'];
        $tableName = $test ? 'TEST_' . $test . '_' . $tableName : $tableName;
        $instance->query = Query::fill(
            $tableName,
            CREATE
        );
        $instance->query->addParam('ID',                    'INT',          10, 'UNSIGNED AUTO_INCREMENT PRIMARY KEY');
        $instance->query->addParam('INSTRUMENT_NAME',       'VARCHAR',      20, 'NOT NULL');
        $instance->query->addParam('FUNDS',                 'FLOAT',        30, 'NOT NULL');
        $instance->query->addParam('VALUE_PRICE',           'FLOAT',        30, 'NOT NULL');
        $instance->query->addParam('ASSET_QNT',             'FLOAT',        30, 'NOT NULL');
        $instance->query->addParam('PRICE',                 'FLOAT',        30, 'NOT NULL');
        $instance->query->addParam('LAST_BUY',              'FLOAT',        30, 'NOT NULL');
        $instance->query->addParam('ORDER_REASON',          'VARCHAR',      20, 'NOT NULL');
        $instance->query->addParam('TOT_QNT',               'FLOAT',        30, 'NOT NULL');
        $instance->query->addParam('PROFIT',                'FLOAT',        30, 'NOT NULL');
        $instance->query->addParam('UPDATE_TIME',           'TIMESTAMP',    0,  'DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
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

    public static function orders($orderList, $active = true, $test = null) {
        global $constants;
        $instance = new self();
        $tableName = $active ? $constants['Tables']['orders'] . '_ACTIVE' : $constants['Tables']['orders'] . '_HISTORY';
        $tableName = $test ? 'TEST_' . $test . '_' . $tableName : $tableName;
        $instance->query = Query::fill(
            $tableName,
            INSERT
        );
        $instance->query->addParam('STATUS',                'VARCHAR',      30, $orderList['status']);
        $instance->query->addParam('SIDE',                  'VARCHAR',      30, $orderList['side']);
        $instance->query->addParam('PRICE',                 'FLOAT',        30, $orderList['price']);
        $instance->query->addParam('QUANTITY',              'FLOAT',        30, $orderList['quantity']);
        $instance->query->addParam('REASON',                'FLOAT',        30, $orderList['reason']);
        $instance->query->addParam('ORDER_ID',              'VARCHAR',      30, $orderList['order_id']);
        $instance->query->addParam('CLIENT_OID',            'VARCHAR',      30, $orderList['client_oid']);
        $instance->query->addParam('TYPE',                  'VARCHAR',      30, $orderList['type']);
        $instance->query->addParam('INSTRUMENT_NAME',       'VARCHAR',      20, $orderList['instrument_name']);
        $instance->query->addParam('AVG_PRICE',             'FLOAT',        30, $orderList['avg_price']);
        $instance->query->addParam('CUMULATIVE_QUANTITY',   'FLOAT',        30, $orderList['cumulative_quantity']);
        $instance->query->addParam('CUMULATIVE_VALUE',      'FLOAT',        30, $orderList['cumulative_value']);
        $instance->query->addParam('FEE_CURRENCY',          'VARCHAR',      30, $orderList['fee_currency']);
        $instance->query->addParam('EXEC_INST',             'VARCHAR',      30, $orderList['exec_inst']);
        $instance->query->addParam('TIME_IN_FORCE',         'VARCHAR',      50, $orderList['time_in_force']);
        $instance->query->sqlCommand = QueryBuilder::getSQL($instance->query);
        return $instance->query;
    }

    public static function balance($balance, $test = null, $firstTotFunds, $firstBuy) {
        global $constants;
        $instance = new self();
        $tableName = $constants['Tables']['balance'];
        $tableName = $test ? 'TEST_' . $test . '_' . $tableName : $tableName;
        $instance->query = Query::fill(
            $tableName,
            INSERT
        );
        $noTradTot = $firstTotFunds / $firstBuy * $balance['price'];
        $totQnt = $balance['funds'] + $balance['asset_qnt'] * $balance['price'];
        $instance->query->addParam('INSTRUMENT_NAME',       'VARCHAR',      20, $balance['instrument_name']);
        $instance->query->addParam('FUNDS',                 'FLOAT',        30, $balance['funds']);
        $instance->query->addParam('VALUE_PRICE',           'FLOAT',        30, $balance['asset_qnt'] * $balance['price']);
        $instance->query->addParam('ASSET_QNT',             'FLOAT',        30, $balance['asset_qnt']);
        $instance->query->addParam('PRICE',                 'FLOAT',        30, $balance['price']);
        $instance->query->addParam('LAST_BUY',              'FLOAT',        30, $balance['last_buy']);
        $instance->query->addParam('ORDER_REASON',          'VARCHAR',      20, $balance['order_reason']);
        $instance->query->addParam('TOT_QNT',               'FLOAT',        30, $totQnt);
        $instance->query->addParam('PROFIT',                'FLOAT',        30, $firstTotFunds ? UtilityStrat::calcPercentage($noTradTot, $totQnt) : 0);
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

    public static function orders($active = true, $test = null) {
        global $constants;
        $instance = new self();
        $tableName = $active ? $constants['Tables']['orders'] . '_ACTIVE' : $constants['Tables']['orders'] . '_HISTORY';
        $tableName = $test ? 'TEST_' . $test . '_' . $tableName : $tableName;
        $instance->query = Query::fill(
            $tableName,
            SELECT
        );
        $instance->query->addParam('ID');
        $instance->query->addParam('STATUS');
        $instance->query->addParam('PRICE');
        $instance->query->addParam('SIDE');
        $instance->query->addParam('QUANTITY');
        $instance->query->addParam('REASON');
        $instance->query->addParam('ORDER_ID');
        $instance->query->addParam('CLIENT_OID');
        $instance->query->addParam('CREATE_TIME');
        $instance->query->addParam('UPDATE_TIME');
        $instance->query->addParam('TYPE');
        $instance->query->addParam('INSTRUMENT_NAME');
        $instance->query->addParam('AVG_PRICE');
        $instance->query->addParam('CUMULATIVE_QUANTITY');
        $instance->query->addParam('CUMULATIVE_VALUE');
        $instance->query->addParam('FEE_CURRENCY');
        $instance->query->addParam('EXEC_INST');
        $instance->query->addParam('TIME_IN_FORCE');
        $instance->query->sqlCommand = QueryBuilder::getSQL($instance->query);
        return $instance->query;
    }

    public static function orderBelowCurrentPrice($price,  $active = true, $test = null) {
        global $constants;
        $instance = new self();
        $tableName = $active ? $constants['Tables']['orders'] . '_ACTIVE' : $constants['Tables']['orders'] . '_HISTORY';
        $tableName = $test ? 'TEST_' . $test . '_' . $tableName : $tableName;
        $instance->query = Query::fill(
            $tableName,
            SELECT
        );
        // Columns to show 
        $instance->query->addParam('ID');
        $instance->query->addParam('ORDER_ID');
        // Where conditions
        $instance->query->addWhereCondition('PRICE', lessThan, $price);
        $instance->query->sqlCommand = QueryBuilder::getSQL($instance->query);
        return $instance->query;
    }

    public static function balance($test = null) {
        global $constants;
        $instance = new self();
        $tableName = $constants['Tables']['balance'];
        $tableName = $test ? 'TEST_' . $test . '_' . $tableName : $tableName;
        $instance->query = Query::fill(
            $tableName,
            SELECT
        );
        $instance->query->addParam('ID');
        $instance->query->addParam('INSTRUMENT_NAME');
        $instance->query->addParam('FUNDS');
        $instance->query->addParam('VALUE_PRICE');
        $instance->query->addParam('ASSET_QNT');
        $instance->query->addParam('PRICE');
        $instance->query->addParam('LAST_BUY');
        $instance->query->addParam('ORDER_REASON');
        $instance->query->addParam('TOT_QNT');
        $instance->query->addParam('PROFIT');
        $instance->query->addParam('UPDATE_TIME');
        $instance->query->sqlCommand = QueryBuilder::getSQL($instance->query);
        return $instance->query;
    }

    public static function balanceForCurrency($instrumentName, $test = null) {
        global $constants;
        $instance = new self();
        $tableName = $constants['Tables']['balance'];
        $tableName = $test ? 'TEST_' . $test . '_' . $tableName : $tableName;
        $instance->query = Query::fill(
            $tableName,
            SELECT
        );
        // Columns to show
        $instance->query->addParam('ID');
        $instance->query->addParam('INSTRUMENT_NAME');
        $instance->query->addParam('FUNDS');
        $instance->query->addParam('VALUE_PRICE');
        $instance->query->addParam('ASSET_QNT');
        $instance->query->addParam('TOT_QNT');
        $instance->query->addParam('PRICE');
        $instance->query->addParam('UPDATE_TIME');
        // Where conditions
        $instance->query->addWhereCondition('INSTRUMENT_NAME', equal, $instrumentName);
        $instance->query->sqlCommand = QueryBuilder::getSQL($instance->query);
        return $instance->query;
    }
}

class DeleteFrom {
    public static function orders($order_id, $active = true, $test = null) {
        global $constants;
        $instance = new self();
        $tableName = $active ? $constants['Tables']['orders'] . '_ACTIVE' : $constants['Tables']['orders'] . '_HISTORY';
        $tableName = $test ? 'TEST_' . $test . '_' . $tableName : $tableName;
        $instance->query = Query::fill(
            $tableName,
            DELETE
        );
        $instance->query->addWhereCondition('ID', equal, $order_id);
        $instance->query->sqlCommand = QueryBuilder::getSQL($instance->query);
        return $instance->query;
    }
}

class DropTable {
    public static function orders($active = true, $test = null) {
        global $constants;
        $instance = new self();
        $tableName = $active ? $constants['Tables']['orders'] . '_ACTIVE' : $constants['Tables']['orders'] . '_HISTORY';
        $tableName = $test ? 'TEST_' . $test . '_' . $tableName : $tableName;
        $instance->query = Query::fill(
            $tableName,
            DROP
        );
        $instance->query->sqlCommand = QueryBuilder::getSQL($instance->query);
        return $instance->query;
    }

    public static function balance($test = null) {
        global $constants;
        $instance = new self();
        $tableName = $constants['Tables']['balance'];
        $tableName = $test ? 'TEST_' . $test . '_' . $tableName : $tableName;
        $instance->query = Query::fill(
            $tableName,
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