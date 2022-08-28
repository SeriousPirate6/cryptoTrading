<?php
include 'Methods.php';

class GetMethods {
    public $bodyRequest;
    public $method;

    public function __construct() {
        $this->bodyRequest = new BodyRequest();
        $this->method      = new Method();
    }

    public function getInstruments($print = false) {
        $this->method->setMethodGET(_public, getInstruments, $this->bodyRequest);
        $this->bodyRequest = new BodyRequest();

        if ($print) TextFormatter::prettyPrint($this->method);
        else TextFormatter::prettyPrint($this->method->toString());

        return $this->method;
    }

    public function getBook($instrumentName, $print = false) {
        if ($instrumentName) $this->bodyRequest->addParam(instrumentName, $instrumentName);
        $this->method->setMethodGET(_public, getBook, $this->bodyRequest);
        $this->bodyRequest = new BodyRequest();

        if ($print) TextFormatter::prettyPrint($this->method);
        else TextFormatter::prettyPrint($this->method->toString());

        return $this->method;
    }

    public function getCandlestick($instrumentName, $timeFrame, $depth, $print = false) {
        if ($instrumentName) $this->bodyRequest->addParam(instrumentName, $instrumentName);
        if ($timeFrame)      $this->bodyRequest->addParam(timeFrame, $timeFrame);
        if ($depth)          $this->bodyRequest->addParam(depth, $depth);
        $this->method->setMethodGET(_public, getCandlestick, $this->bodyRequest);
        $this->bodyRequest = new BodyRequest();

        if ($print) TextFormatter::prettyPrint($this->method);
        else TextFormatter::prettyPrint($this->method->toString());

        return $this->method;
    }

    public function getTicker($instrumentName, $timeFrame, $print = false) {
        if ($instrumentName) $this->bodyRequest->addParam(instrumentName, $instrumentName);
        if ($timeFrame)      $this->bodyRequest->addParam(timeFrame, $timeFrame);
        $this->method->setMethodGET(_public, getTicker, $this->bodyRequest);
        $this->bodyRequest = new BodyRequest();

        if ($print) TextFormatter::prettyPrint($this->method);
        else TextFormatter::prettyPrint($this->method->toString());

        return $this->method;
    }

    public function getCurrencyNetwork($print = false) {
        $this->bodyRequest->setDefault(POST, _private . getCurrencyNetwork);
        $this->method->setMethodPOST(_private, getCurrencyNetwork, $this->bodyRequest);
        $this->bodyRequest = new BodyRequest();

        if ($print) TextFormatter::prettyPrint($this->method);
        else TextFormatter::prettyPrint($this->method->toString());

        return $this->method;
    }

    public function getOrderHistory($instrumentName, $print = false) {
        $this->bodyRequest->addParam(instrumentName, $instrumentName);
        $this->bodyRequest->setDefault(POST, _private . getOrderHistory);
        $this->method->setMethodPOST(_private, getOrderHistory, $this->bodyRequest);
        $this->bodyRequest = new BodyRequest();

        if ($print) TextFormatter::prettyPrint($this->method);
        else TextFormatter::prettyPrint($this->method->toString());

        return $this->method;
    }

    public function createOrder($params, $print = false) {
        // $this->bodyRequest->addParams($params);
        $this->bodyRequest->addParam(instrumentName, $params[instrumentName]);
        $this->bodyRequest->addParam(price, $params[price]);
        $this->bodyRequest->addParam(quantity, $params[quantity]);
        $this->bodyRequest->addParam(side, $params[side]);
        $this->bodyRequest->addParam(type, $params[type]);
        $this->bodyRequest->setDefault(POST, _private . createOrder);
        $this->method->setMethodPOST(_private, createOrder, $this->bodyRequest);
        TextFormatter::prettyPrint($this->bodyRequest->getParams(), '', Colors::yellow);
        $this->bodyRequest = new BodyRequest();

        if ($print) TextFormatter::prettyPrint($this->method);
        else TextFormatter::prettyPrint($this->method->toString());

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

    public function getCandlestick($instrumentNames, $timeFrame, $depth, $print = false) {
        foreach ($instrumentNames as $instrumentName) {
            array_push($this->methods, $this->m->getCandlestick($instrumentName, $timeFrame, $depth, $print));
            $this->m = new GetMethods();
        }
        return $this->methods;
    }
}