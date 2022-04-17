<?php
    include '../../utils/formatter.php';
    include '../../R8tGJrTSPY8QPDNTMe4n/8HqzMTXCvquYdkRNr6kn.php';

    // TEMP - needs to be modified !

    $currencyValue = 'CURRENCY_VALUE';

    const nameList       = ([
        'hostname'      => 'upayniggas',
        'username'      => 'weniggas',
        'password'      => 'aaaaaaaaa',
        'name'          => 'uwuwuwuwuwuwuwuwuwuwu'
    ]);

    function insertCurrencyNames($nameList) {
        $query = '';
        
        foreach ($nameList as $key => $val) {
            $query = "INSERT INTO CURRENCY_VALUE (CURRENCY, PRICE, TREND) VALUES ('$val', 444, 'UP');\n".$query;
        }
        TextFormatter::prettyPrint($query);
        $conn = new mysqli(db_cred['name'], db_cred['username'], db_cred['password'], db_cred['name']);
        $conn->multi_query($query);
    }

    insertCurrencyNames(nameList);
?>