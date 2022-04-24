<?php
    include '../../DB/Connection/Conn.php';
    include '../../DB/Queries/Queries.php';
    include '../../Variables/Currencies.php';

    abstract class RunQuery {
        public static function create($tableName, $query) {
            global $conn;
            $checkTable = "SELECT ID FROM $tableName";
            $result = mysqli_query($conn, $checkTable);
            
            if(empty($result)) {
                if ($conn->query($query) === TRUE) {
                    TextFormatter::prettyPrint('Table created successfully');
                } else {
                    TextFormatter::prettyPrint('Error creating table: '.$conn->error);
                }
                $conn->close();
            }
        }

        public static function insert($query) {
            global $conn;

            if ($conn->query($query) === FALSE) {
                TextFormatter::prettyPrint('Error entering data: '.$conn->error);
            }
            $conn->close();
        }

        public static function multipleInsert($queries) {
            global $conn;
            $gigaQuery = implode(PHP_EOL, $queries);
            TextFormatter::prettyPrint($gigaQuery);
            
            if ($conn->multi_query($gigaQuery) === FALSE) {
                TextFormatter::prettyPrint('Error entering data: '.$conn->error);
            }
            $conn->close();
        }        
    }
?>