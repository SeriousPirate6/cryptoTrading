<?php

include 'R8tGJrTSPY8QPDNTMe4n/8HqzMTXCvquYdkRNr6kn.php';
include 'variables.php';

echo db_cred['name'];

class CurlHelper {
    public static function perform_http_request($method, $url, $data = false) {
        $curl = curl_init();
    
        switch ($method) {
            case 'POST':
                curl_setopt($curl, CURLOPT_POST, 1);
    
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
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

$action = 'GET';
$url = 'https://api.crypto.com/v2/public/get-ticker?instrument_name=BTC_USDT';
echo $url;
$parameters = array('param' => 'value');
$result = CurlHelper::perform_http_request($action, $url);
echo '<br>';echo '<br>';
echo print_r($result);
echo '<br>'; echo '<br>';
$mynewarray = json_decode($result, true);
echo print_r($mynewarray);
echo '<br>';echo '<br>';
print_r($mynewarray['result']['data']['k']);


$conn = new mysqli(db_cred['name'], db_cred['username'], db_cred['password'], db_cred['name']);

if ($conn->connect_error) {
  die('Connection failed: ' . $conn->connect_error);
}
echo '<br>';echo '<br>';
echo 'Connected successfully';

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
          echo 'Table MyGuests created successfully';
        } else {
          echo 'Error creating table: ' . $conn->error;
        }
        
    $conn->close();
}



?>