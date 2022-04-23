<?php
    include 'Methods.php';

    class GetMethods {
        public $bodyRequest;
        public $method;

        public function __construct() {
            $this->bodyRequest = new BodyRequest();
            $this->method      = new Method();
        }
        
        public function getInstruments() {
            $this->method->setMethodGET(_public, getInstruments, $this->bodyRequest);
            $this->bodyRequest = new BodyRequest();
            return $this->method;
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

    class GetMultipleMethods {
        public $methods;
        public $m;

        public function __construct() {
            $this->methods  = array();
            $this->m        = new GetMethods();
        }

        public function getCandlestick($instrumentNames, $timeFrame) {
            foreach ($instrumentNames as $instrumentName) {
                array_push($this->methods, $this->m->getCandlestick($instrumentName, $timeFrame));
                $this->m = new GetMethods();
            }
            return $this->methods;
        }
    }
?>