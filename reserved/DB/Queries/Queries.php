<?php

use createTable as GlobalCreateTable;

    include '../Connection/Conn.php';
    include '../../Variables/Tables.php';

    abstract class createTable {
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
    
    abstract class insertTable {
        public static function currencyValue($currency, $price, $trend) {
            global $constants;
            return "INSERT INTO {$constants['Tables'][0]} (CURRENCY, PRICE, TREND) VALUES ('$currency', $price, '$trend');";
        }
    }

    abstract class multipleInsertTable {
        public static function currencyValue($array) {
            global $constants;
            $query = '';

            foreach ($array as $key => $val) {
                $query = "INSERT INTO {$constants['Tables'][0]} (CURRENCY, PRICE, TREND) VALUES ('$val[0]', $val[1], '$val[2]');\n".$query;
            }

            return $query;
        }
    }
?>