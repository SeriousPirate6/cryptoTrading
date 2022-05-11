<?php
    include '../../DB/Connection/Conn.php';
    include '../../DB/Queries/Queries.php';
    include '../../Variables/Currencies.php';

    abstract class RunQuery {
        public static function create($query, $print = false) {
            global $conn;
            $conn = Database::connect();

            $checkTable = "SELECT ID FROM $query->tableName";
            $result = mysqli_query($conn, $checkTable);
            if ($print) TextFormatter::prettyPrint($query);
            else TextFormatter::prettyPrint($query->sqlCommand);
            
            if(empty($result)) {
                if ($conn->query($query->sqlCommand) === TRUE) {
                    TextFormatter::prettyPrint('Table created successfully');
                } else {
                    TextFormatter::prettyPrint('Error creating table: '.$conn->error);
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
                TextFormatter::prettyPrint('Error entering data: '.$conn->error);
            }
            $conn->close();
        }

        public static function select($query, $print = false) {
            global $conn;
            $conn = Database::connect();
            
            if ($print) TextFormatter::prettyPrint($query);
            else TextFormatter::prettyPrint($query->sqlCommand);

            $result = $conn->query($query->sqlCommand);

            if ($result === FALSE) {
                TextFormatter::prettyPrint('Error selecting data: '.$conn->error);
            }
            $conn->close();

            $vals   = array();

            if ($result->num_rows > 0) {
                $text = new TextFormatter($query->type.', '.$query->tableName.', '.$result->num_rows.' records, '.$result->field_count.' fields');
                if (!$print) {
                    foreach ($query->queryParams as $val) {
                        array_push($vals, $val->name);
                    }
                    $text->addToPrint($vals);
                    $vals = array();
                }
                while ($row = $result->fetch_assoc()) {
                    if ($print) TextFormatter::prettyPrint($query);
                    else {
                        foreach ($query->queryParams as $val) {
                            array_push($vals, $row[$val->name]);
                        }
                        $text->addToPrint($vals);
                        $query->addValue($vals);
                        $vals = array();
                    }
                }
                $text->collapsablePrint($text->array);
            } else {
                echo "0 results";
            }
        }

        public static function multipleInsert($queries, $print = false) {
            global $conn;
            $conn = Database::connect();
            $gigaQuery = '';
            
            foreach (array_reverse($queries) as $query) {
                $gigaQuery = $query->sqlCommand."\n".$gigaQuery;
                if ($print) TextFormatter::prettyPrint($query);
            }

            $gigaQuery = trim($gigaQuery);
            if (!$print) TextFormatter::prettyPrint($gigaQuery);
            
            if ($conn->multi_query($gigaQuery) === FALSE) {
                TextFormatter::prettyPrint('Error entering data: '.$conn->error);
            }
            $conn->close();
        }        
    }
?>