<?php
    $currencyValue = Tables::currencyValue;

    $query = "SELECT ID FROM $currencyValue";
    $result = mysqli_query($conn, $query);
    
    if(empty($result)) {
        $sql = "CREATE TABLE $currencyValue (
            ID INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            CURRENCY VARCHAR(30) NOT NULL,
            PRICE FLOAT(30) NOT NULL,
            TREND VARCHAR(4),
            TIMEST TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
            
        if ($conn->query($sql) === TRUE) {
            TextFormatter::prettyPrint('Table created successfully');
        } else {
            TextFormatter::prettyPrint('Error creating table: '.$conn->error);
        }
            
        $conn->close();
    }
?>