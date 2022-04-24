<?php
    include '../Connection/Conn.php';
    include '../../Variables/Tables.php';

    abstract class CreateTable {
        public static function currencyValue() {
            global $constants;
            return "CREATE TABLE {$constants['Tables'][0]} (
                        ID          INT         (10)    UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        CURRENCY    VARCHAR     (30)    NOT NULL,
                        PRICE       FLOAT       (30)    NOT NULL,
                        TREND       VARCHAR     (4),
                        TIMEST      TIMESTAMP           DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                    );";
        }
    }
    
    abstract class InsertTable {
        public static function currencyValue($currency, $price, $trend) {
            global $constants;
            return "INSERT INTO {$constants['Tables'][0]} (CURRENCY, PRICE, TREND) VALUES ('$currency', $price, '$trend');";
        }
    }

    abstract class MultipleInsertTable {
        public static function currencyValue($array) {
            global $constants;
            $queries = array();

            foreach ($array as $key => $val) {
                $currency   = $val['result']['instrument_name'];
                $price      = $val['result']['data'][0]['o'];
                
                array_push($queries, "INSERT INTO {$constants['Tables'][0]} (CURRENCY, PRICE, TREND) VALUES ('$currency', $price, 'UP');");
            }

            return $queries;
        }
    }
?>