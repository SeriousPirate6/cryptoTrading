<?php
include '../../Variables/Tables.php';
include '../../DB/Queries/Query.php';

abstract class QueryBuilder {
    public static function getSQL($query) {
        $type = $query->type;

        if ($type == CREATE) {
            $params = '';
            foreach (array_reverse($query->queryParams) as $param) {
                $size = $param->size != 0 ? " (" . $param->size . ") " : ' ';
                $params = "\n        " . trim($param->name . " " . $param->type . $size . $param->constraint) . "," . $params;
            }
            return $query->type . " TABLE " . $query->tableName . $query->name . " (" . substr($params, 0, -1) . "\n    );";
        }

        if ($type == INSERT) {
            $names  = '';
            $values = '';
            foreach (array_reverse($query->queryParams) as $param) {
                $names   = $param->name . ", " . $names;

                $value  = $param->type == 'VARCHAR' ? "'" . $param->value . "'" : $param->value;
                $values = $value . ", " . $values;
            }
            return $query->type . " INTO " . $query->tableName . " (" . substr(trim($names), 0, -1) . ") VALUES (" . substr(trim($values), 0, -1) . ");";
        }

        if ($type == SELECT) {
            $names = '';
            $where = '';
            foreach (array_reverse($query->queryParams) as $param) {
                $names = $param->name . ", " . $names;
            }
            foreach (array_reverse($query->whereConditions) as $whCond) {
                $where = $whCond->name . " " . $whCond->operator . " " . $whCond->value . " AND " . $where;
            }
            if (empty($query->queryParams)) $names = "*";
            else $names = substr(trim($names), 0, -1);
            if (!empty($query->whereConditions)) $where = " WHERE " . substr(trim($where), 0, -3);
            return $query->type . " " . $names . " FROM " . $query->tableName . $where;
        }

        if ($type == DROP) {
            return $query->type . " TABLE " . $query->tableName;
        }
    }
}