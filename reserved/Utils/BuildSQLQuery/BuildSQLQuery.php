<?php
    include '../../Variables/Tables.php';
    include '../../Utils/Formatter/Formatter.php';
    include '../../DB/Queries/Query.php';

    abstract class QueryBuilder {
        public static function getSQL($query) {
            $type = $query->type;

            if ($type == CREATE) {
                $params = '';
                foreach (array_reverse($query->queryParams) as $param) {
                    $size = $param->size != 0 ? " (".$param->size.") " : '';
                    $params = "\n        ".trim($param->name." ".$param->type.$size.$param->constraint).",".$params;
                }
                return $query->type." TABLE ".$query->tableName.$query->name." (".substr($params, 0, -1)."\n    );";
            }

            if ($type == INSERT) {
                $names  = '';
                $values = '';
                foreach (array_reverse($query->queryParams) as $param) {
                    $name   = $param->required == true ? $param->name.", " : '';
                    $names  = $name.$names;
                    
                    $value  = $param->type == 'VARCHAR' ? "'".$param->value."'" : $param->value;
                    $value  = $value != '' ? $value.", " : '';
                    $values = $value.$values;
                }
                return $query->type." INTO ".$query->tableName." (".substr(trim($names), 0, -1).") VALUES (".substr(trim($values), 0, -1).");";
            }
        }
    }


    $test = Query::fill(
        $constants['Tables']['currencyValue'],
        INSERT
    );
    $test->addParam('ID',        'INT',         10, 'UNSIGNED AUTO_INCREMENT PRIMARY KEY',                  '',        false);
    $test->addParam('CURRENCY',  'VARCHAR',     30, 'NOT NULL',                                             'BTC_USDT'      );
    $test->addParam('PRICE',     'FLOAT',       30, 'NOT NULL',                                             '42000'         );
    $test->addParam('TREND',     'VARCHAR',     4,  '',                                                     'UP'            );
    $test->addParam('TIMEST',    'TIMESTAMP',   0,  'DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP', '',       false);

    // "INSERT INTO {$constants['Tables'][0]} (CURRENCY, PRICE, TREND) VALUES ('$currency', $price, 'UP');";

    $test->sqlCommand = QueryBuilder::getSQL($test);

    TextFormatter::prettyPrint($test);
?>