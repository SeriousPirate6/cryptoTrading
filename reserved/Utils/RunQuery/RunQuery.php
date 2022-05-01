<?php
    include '../../DB/Connection/Conn.php';
    include '../../DB/Queries/Queries.php';
    include '../../Variables/Currencies.php';

    abstract class RunQuery {
        public static function create($query) {
            global $conn;
            $conn = Database::connect();

            $checkTable = "SELECT ID FROM $query->tableName";
            $result = mysqli_query($conn, $checkTable);
            
            if(empty($result)) {
                if ($conn->query($query->sqlCommand) === TRUE) {
                    TextFormatter::prettyPrint('Table created successfully');
                } else {
                    TextFormatter::prettyPrint('Error creating table: '.$conn->error);
                }
                $conn->close();
            }
        }

        public static function insert($query) {
            global $conn;
            $conn = Database::connect();

            if ($conn->query($query->sqlCommand) === FALSE) {
                TextFormatter::prettyPrint('Error entering data: '.$conn->error);
            }
            $conn->close();
        }

        public static function multipleInsert($queries) {
            global $conn;
            $conn = Database::connect();
            $gigaQuery = '';
            
            foreach (array_reverse($queries) as $query) {
                $gigaQuery = $query->sqlCommand."\n".$gigaQuery;
            }

            $gigaQuery = trim($gigaQuery);
            
            if ($conn->multi_query($gigaQuery) === FALSE) {
                TextFormatter::prettyPrint('Error entering data: '.$conn->error);
            }
            $conn->close();
        }        
    }
?>