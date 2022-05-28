<?php
    include '../../MethodsImpl.php';
    
    class SendRequest {
        private static function perform_http_request($method, $url, $data = null) {
            $curl = curl_init();
        
            switch ($method) {
                case 'POST':
                    curl_setopt($curl, CURLOPT_POST, 1);
                    if ($data) {
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                            'Authorization: ',
                            'Content-Type: application/json')
                        );
                    }
                    break;
                default:
                    if ($data) {
                        $url = sprintf('%s?%s', $url, http_build_query($data));
                    }
            }
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            $result = curl_exec($curl);
            curl_close($curl);
            return $result;
        }

        public static function sendReuquest($method, $print = false) {
            $action = $method->type;
            $url = api_url.$method->visibility.$method->method;
            $parameters = str_replace('[]', '{}', str_replace('\\', '', json_encode((array) $method->bodyRequest)));
            
            if($method->type == GET)     $result = SendRequest::perform_http_request($action, $url);
            if($method->type == POST)    $result = SendRequest::perform_http_request($action, $url, $parameters);
            
            $result = json_decode($result, true);

            if ($print) {
                TextFormatter::prettyPrint(TextFormatter::jsonReadableDate($result), $method->toString(), Colors::light_blue);
            }

            return $result;
        }

        public static function sendMultiRequest($methods, $print = false) {
            $requests           = array();

            foreach ($methods as $method) {
                array_push($requests, SendRequest::sendReuquest($method, $print));
            }
            
            return $requests;
        }
    }
?>