<?php
    include '../../Variables/Methods.php';
    include '../../R8tGJrTSPY8QPDNTMe4n/8HqzMTXCvquYdkRNr6kn.php';

    class Method {
        public $visibility;
        public $method;
        public $type;
        public $bodyRequest;

        public function __construct() {}

        function setMethodGET($visibility, $method, $params) {
            $this->visibility   = $visibility;
            $this->type         = GET;
            $paramsString       = $params->getParams();
            if(strlen($paramsString) > 0) {
                $this->method   = $method.'?'.$paramsString;
            } else {
                $this->method   = $method;
            }
        }

        function setMethodPOST($visibility, $method, $bodyRequest) {
            $this->visibility   = $visibility;
            $this->method       = $method;
            $this->type         = POST;
            $this->bodyRequest  = $bodyRequest;
    
            if($this->visibility  == _private) {
                $this->bodyRequest->setApiKey(api_key);
                $this->bodyRequest->setSig(
                    hash_hmac(
                        encType,
                        $this->bodyRequest->getToEncrypt(),
                        secret_key
                    )
                );
            }
        }

        function getMethodString() {
            return $this->bodyRequest->getToEncrypt();
        }
    }

    class BodyRequest {
        public $id;
        public $method;
        public $nonce;
        public $api_key;
        public $sig;
        public $params = array();

        public function __construct() {}

        function setApiKey($api_key) {
            $this->api_key = $api_key;
        }

        function setSig($sig) {
            $this->sig = $sig;
        }

        function setDefault($type, $method) {
            if($type == 'POST') {
                global $id;
                $this->id       = $id;
                $this->method   = $method;
                $this->nonce    = round(microtime(true) * 1000);
            }
        }

        function addParam($key, $value) {
            $this->params[$key] = $value;
        }

        function getParam($key) {
            return $this->params[$key];
        }

        function getParams() {
            $stringParams   = '';
            $key            = '';

            while($element = current($this->params)) {
                $key            = key($this->params);
                $stringParams   = $stringParams.'&'.$key.'='.$this->params[$key];
                next($this->params);
            }

            return trim(substr($stringParams, 1));
        }

        function getToEncrypt() {
            $jsonParams = '';
            if(!empty($this->params)) $jsonParams = str_replace(['{', '"', ':', '}'], ['', '', '', ''], json_encode($this->params));
            return strval($this->method.$this->id.$this->api_key.$jsonParams.$this->nonce);
        }
    }
?>