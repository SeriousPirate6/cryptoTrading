<?php
    include 'Query.php';
    include '../Connection/Conn.php';
    include '../../Variables/Tables.php';

    class CreateTable {
        public $query;

        public static function currencyValue() {
            global $constants;
            $instance = new self();
            $instance->query = Query::fill(
                $constants['Tables']['currencyValue'],
                CREATE,
                "CREATE TABLE {$constants['Tables']['currencyValue']} (
                        ID          INT         (10)    UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        CURRENCY    VARCHAR     (30)    NOT NULL,
                        PRICE       FLOAT       (30)    NOT NULL,
                        TREND       VARCHAR     (4),
                        TIMEST      TIMESTAMP           DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                );"
            );
            $instance->query->addParam('ID',        'INT',      10, true);
            $instance->query->addParam('CURRENCY',  'VARCHAR',  30, true);
            $instance->query->addParam('PRICE',     'FLOAT',    30, true);
            $instance->query->addParam('TREND',     'VARCHAR',  4       );
            $instance->query->addParam('TIMEST',    'TIMESTAMP'         );
            TextFormatter::prettyPrint($instance->query);
            return $instance->query;
        }

        public static function currencyData() {
            global $constants;
            return "CREATE TABLE {$constants['Tables']['currencyData']} (
                        ID                  INT         (10)    UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        INSTRUMENT_NAME     VARCHAR     (30)    NOT NULL,
                        QUOTE_CURRENCY      VARCHAR     (10)    NOT NULL,
                        BASE_CURRENCY       VARCHAR     (10)    NOT NULL,
                        PRICE_DECIMALS      INT         (10),
                        QUANTITY_DECIMALS   INT         (10),
                        MAX_QUANTITY        FLOAT       (20),
                        MIN_QUANTITY        FLOAT       (20),
                        TIMEST              TIMESTAMP           DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                    );";
        }
    }
    
    abstract class InsertTable {
        public static function currencyValue($currency, $price, $trend) {
            global $constants;
            return "INSERT INTO {$constants['Tables']['currencyValue']} (CURRENCY, PRICE, TREND) VALUES ('$currency', $price, '$trend');";
        }
    }

    abstract class MultipleInsertTable {
        public static function currencyValue($array) {
            global $constants;
            $queries = array();

            foreach ($array as $key => $val) {
                $currency   = $val['result']['instrument_name'];
                $price      = $val['result']['data'][0]['o'];
                
                array_push($queries, "INSERT INTO {$constants['Tables']['currencyValue']} (CURRENCY, PRICE, TREND) VALUES ('$currency', $price, 'UP');");
            }

            return $queries;
        }
    }

    CreateTable::currencyValue();
?>