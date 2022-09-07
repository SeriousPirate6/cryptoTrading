<?php
include '../../DB/Connection/Conn.php';
include '../../DB/Queries/Queries.php';
include '../../Variables/Currencies.php';

abstract class RunQuery {
    public static function create($query, $print = false, $print_full = false) {
        global $conn;
        $conn = Database::connect();

        $checkTable = "SELECT ID FROM $query->tableName";
        $result = mysqli_query($conn, $checkTable);
        if ($print_full)    TextFormatter::prettyPrint($query);
        if ($print)         TextFormatter::prettyPrint($query->sqlCommand);

        if (empty($result)) {
            if ($conn->query($query->sqlCommand) === TRUE) {
                TextFormatter::prettyPrint('Table ' . $query->tableName . ' created successfully');
            } else {
                TextFormatter::prettyPrint('Error creating table: ' . $conn->error);
            }
            $conn->close();
        }
    }

    public static function insert($query, $print = false) {
        global $conn;
        $conn = Database::connect();

        if ($print) TextFormatter::prettyPrint($query);
        else TextFormatter::prettyPrint($query->sqlCommand);

        if ($conn->query($query->sqlCommand) === FALSE) {
            TextFormatter::prettyPrint('Error entering data: ' . $conn->error);
        }
        $conn->close();
    }

    public static function delete($query, $print = false) {
        RunQuery::insert($query, $print);
    }

    public static function drop($query, $print = false) {
        RunQuery::insert($query, $print);
    }

    public static function select($query, $table = false, $print = false) {
        global $conn;
        $conn = Database::connect();

        if ($print) TextFormatter::prettyPrint($query);
        if ($table) TextFormatter::prettyPrint($query->sqlCommand);

        $result = $conn->query($query->sqlCommand);

        if ($result === FALSE) {
            TextFormatter::prettyPrint('Error selecting data: ' . $conn->error);
        }
        $conn->close();

        $vals = array();

        if ($result->num_rows > 0) {
            $text = new CollapsibleTable($query->type . ', ' . $query->tableName . ', ' . $result->num_rows . ' records, ' . $result->field_count . ' fields');
            foreach ($query->queryParams as $val) {
                array_push($vals, $val->name);
            }
            $text->addToPrint($vals);
            $vals = array();
            $res = array();
            while ($row = $result->fetch_assoc()) {
                if ($print) TextFormatter::prettyPrint($query);
                $row_to_res = array();
                foreach ($query->queryParams as $val) {
                    array_push($vals, $row[$val->name]);
                    // Saving <Key, Val> array with lowercase table columns.
                    $row_to_res = array_merge($row_to_res, array(strtolower($val->name) => $row[$val->name]));
                }
                array_push($res, $row_to_res);
                $text->addToPrint($vals);
                $query->addValue($vals);
                $vals = array();
            }
            if ($table) $text->collapsablePrint($text->array);
            if ($print) TextFormatter::prettyPrint($res, 'RESULTS: ', Colors::purple);
            array_shift($text->array);
            return $res;
        } else {
            echo "0 results";
        }
    }

    public static function multipleInsert($queries, $print = false) {
        global $conn;
        $conn = Database::connect();
        $gigaQuery = '';

        foreach (array_reverse($queries) as $query) {
            $gigaQuery = $query->sqlCommand . "\n" . $gigaQuery;
            if ($print) TextFormatter::prettyPrint($query);
        }

        $gigaQuery = trim($gigaQuery);
        if (!$print) TextFormatter::prettyPrint($gigaQuery);

        if ($conn->multi_query($gigaQuery) === FALSE) {
            TextFormatter::prettyPrint('Error entering data: ' . $conn->error);
        }
        $conn->close();
    }
}