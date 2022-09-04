<?php
  include '../../Variables/Styles.php';
  include '../../Utils/Formatter/Formatter.php';
  include '../../R8tGJrTSPY8QPDNTMe4n/8HqzMTXCvquYdkRNr6kn.php';

  class Database {
    static function connect() {
      $conn = new mysqli(db_cred['name'], db_cred['username'], db_cred['password'], db_cred['name']);
      
      if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
      }
      return $conn;
    }
  }