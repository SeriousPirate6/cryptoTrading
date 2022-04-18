<?php

include 'MethodsImpl.php';

TextFormatter::prettyPrint(db_cred['name']);

class CurlHelper {
    public static function perform_http_request($method, $url, $data = null) {
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
$test2 = $test->getCandlestick('BTC_USDT', '1m');
// $test2 = $test->getOrderHistory('BTC_USDT');
TextFormatter::prettyPrint($test2);

$action = $test2->type;
$url = api_url.$test2->visibility.$test2->method;
$parameters = str_replace('[]', '{}', str_replace('\\', '', json_encode((array) $test2->bodyRequest)));
if($test2->type == GET)     $result = CurlHelper::perform_http_request($action, $url);
if($test2->type == POST)    $result = CurlHelper::perform_http_request($action, $url, $parameters);
TextFormatter::prettyPrint($action);
TextFormatter::prettyPrint($url);
TextFormatter::prettyPrint($parameters);

TextFormatter::prettyPrint(json_decode($result, true));
$mynewarray = json_decode($result, true);

$conn = new mysqli(db_cred['name'], db_cred['username'], db_cred['password'], db_cred['name']);

if ($conn->connect_error) {
  die('Connection failed: ' . $conn->connect_error);
}
TextFormatter::prettyPrint('Connected successfully');

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

$currency   = $mynewarray['result']['instrument_name'];
$price      = $mynewarray['result']['data'][0]['o'];
$trend      = Trend::UP;

$query = "INSERT INTO $currencyValue (CURRENCY, PRICE, TREND) VALUES ('$currency', $price, '$trend');";
$result = mysqli_query($conn, $query);

TextFormatter::prettyPrint($query);
?>