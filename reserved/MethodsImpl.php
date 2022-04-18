<?php

include 'Utils/Formatter/Formatter.php';
include 'Variables/Methods.php';
include 'R8tGJrTSPY8QPDNTMe4n/8HqzMTXCvquYdkRNr6kn.php';

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
                echo $this->bodyRequest->getToEncrypt();
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

    class getMethods {
        public $bodyRequest;
        public $method;

        public function __construct() {
            $this->bodyRequest = new BodyRequest();
            $this->method      = new Method();
        }
        
        public function getBook($instrumentName) {
            if($instrumentName) $this->bodyRequest->addParam(instrumentName, $instrumentName);
            $this->method->setMethodGET(_public, getBook, $this->bodyRequest);
            $this->bodyRequest = new BodyRequest();
            return $this->method;
        }

        public function getCandlestick($instrumentName, $timeFrame) {
            if($instrumentName) $this->bodyRequest->addParam(instrumentName, $instrumentName);
            if($timeFrame)      $this->bodyRequest->addParam(timeFrame, $timeFrame);
            $this->method->setMethodGET(_public, getCandlestick, $this->bodyRequest);
            $this->bodyRequest = new BodyRequest();
            return $this->method;
        }

        public function getTicker($instrumentName, $timeFrame) {
            if($instrumentName) $this->bodyRequest->addParam(instrumentName, $instrumentName);
            if($timeFrame)      $this->bodyRequest->addParam(timeFrame, $timeFrame);
            $this->method->setMethodGET(_public, getTicker, $this->bodyRequest);
            $this->bodyRequest = new BodyRequest();
            return $this->method;
        }

        public function getCurrencyNetwork() {
            $this->bodyRequest->setDefault(POST, _private.getCurrencyNetwork);
            $this->method->setMethodPOST(_private, getCurrencyNetwork, $this->bodyRequest);
            $this->bodyRequest = new BodyRequest();
            return $this->method;
        }

        public function getOrderHistory($instrumentName) {
            $this->bodyRequest->addParam(instrumentName, $instrumentName);
            $this->bodyRequest->setDefault(POST, _private.getOrderHistory);
            $this->method->setMethodPOST(_private, getOrderHistory, $this->bodyRequest);
            $this->bodyRequest = new BodyRequest();
            return $this->method;
        }
    }

    $test = new getMethods;
    TextFormatter::prettyPrint($test->getBook(''));
    TextFormatter::prettyPrint($test->getCandlestick('ETH_USDT', '1m'));
    TextFormatter::prettyPrint($test->getTicker('ETH_USDT', ''));
    TextFormatter::prettyPrint($test->getCurrencyNetwork());
    TextFormatter::prettyPrint($test->getCurrencyNetwork('BTC_USDT'));

    echo hash_hmac(encType, $test->getTicker('ETH_USDT', '1m')->getMethodString(), secret_key);
?>