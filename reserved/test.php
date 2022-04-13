<?php

include 'methods.php';
include 'R8tGJrTSPY8QPDNTMe4n/8HqzMTXCvquYdkRNr6kn.php';

TextFormatter::prettyPrint(db_cred['name']);

class CurlHelper {
    public static function perform_http_request($method, $url, $data = false) {
        $curl = curl_init();
    
        switch ($method) {
            case 'POST':
                curl_setopt($curl, CURLOPT_POST, 1);
    
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    TextFormatter::prettyPrint('POST WITH PARAMS');
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                        'Authorization: ',
                        'Content-Type: application/json')
                    );
                break;
            case 'PUT':
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data)
                    $url = sprintf('%s?%s', $url, http_build_query($data));
        }
    
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    
        $result = curl_exec($curl);
    
        curl_close($curl);
    
        return $result;
    }
    
}

$test = new getMethods;
$test2 = $test->getCurrencyNetwork();
TextFormatter::prettyPrint($test2);

$action = $test2->type;
$url = api_url.$test2->visibility.$test2->method;
$parameters = str_replace('[]', '{}', str_replace('\\', '', json_encode((array) $test2->bodyRequest)));
$result = CurlHelper::perform_http_request($action, $url, $parameters);
TextFormatter::prettyPrint($action);
TextFormatter::prettyPrint($url);
TextFormatter::prettyPrint($parameters);

TextFormatter::prettyPrint(json_decode($result, true));
TextFormatter::prettyPrint($mynewarray['result']['data']['k']);

$conn = new mysqli(db_cred['name'], db_cred['username'], db_cred['password'], db_cred['name']);

if ($conn->connect_error) {
  die('Connection failed: ' . $conn->connect_error);
}
TextFormatter::prettyPrint('Connected successfully');

$query = 'SELECT ID FROM USERS';
$result = mysqli_query($conn, $query);

if(empty($result)) {
    $sql = 'CREATE TABLE MyGuests (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        firstname VARCHAR(30) NOT NULL,
        lastname VARCHAR(30) NOT NULL,
        email VARCHAR(50),
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )';
        
        if ($conn->query($sql) === TRUE) {
            TextFormatter::prettyPrint('Table MyGuests created successfully');
        } else {
            TextFormatter::prettyPrint('Error creating table: '.$conn->error);
        }
        
    $conn->close();
}



?>