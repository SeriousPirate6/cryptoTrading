<?php
include '../../Variables/Methods.php';
include '../../R8tGJrTSPY8QPDNTMe4n/8HqzMTXCvquYdkRNr6kn.php';

class Method {
    public $visibility;
    public $method;
    public $type;
    public $bodyRequest;

    public function __construct() {
    }

    function setMethod($type, $visibility, $method, $params) {
        $this->type         = $type;
        $this->visibility   = $visibility;
        $this->method       = $method;

        if ($type == GET) {
            $paramsString       = $params->getParams();
            if (strlen($paramsString) > 0) {
                $this->method   = $method . '?' . $paramsString;
            } else {
                $this->method   = $method;
            }
        }
        if ($type == POST) {
            $this->bodyRequest  = $params;
            $this->bodyRequest->setDefault($type, $visibility, $method);

            if ($this->visibility  == _private) {
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
    }

    function getMethodString() {
        return $this->bodyRequest->getToEncrypt();
    }

    function toString() {
        if ($this->type == GET)   return $this->visibility . $this->method;
        if ($this->type == POST)  return $this->visibility . $this->method . ' -> ' . json_encode($this->bodyRequest->params, true);
    }
}

class BodyRequest {
    public $id;
    public $method;
    public $nonce;
    public $api_key;
    public $sig;
    public $params = array();

    public function __construct() {
    }

    function setApiKey($api_key) {
        $this->api_key = $api_key;
    }

    function setSig($sig) {
        $this->sig = $sig;
    }

    function setDefault($type, $visibility, $method) {
        $this->method   = $visibility . $method;
        if ($type == 'POST') {
            global $id;
            $this->id       = $id;
            $this->nonce    = round(microtime(true) * 1000);
        }
    }

    function addParam($key, $value) {
        $this->params[$key] = $value;
    }

    function getParam($key) {
        return $this->params[$key];
    }

    function addParams($params) {
        // Sort the key->value array by key.
        ksort($params);
        foreach ($params as $param => $val) {
            $this->params[$param] = $val;
        }
    }

    function getParams() {
        $stringParams   = '';
        $key            = '';

        while ($element = current($this->params)) {
            $key            = key($this->params);
            $stringParams   = $stringParams . '&' . $key . '=' . $this->params[$key];
            next($this->params);
        }

        return trim(substr($stringParams, 1));
    }

    function getToEncrypt($print = false) {
        $jsonParams = '';
        if (!empty($this->params)) $jsonParams = str_replace(['{', '"', ':', '}', ','], ['', '', '', '', ''], json_encode($this->params));
        if ($print) TextFormatter::prettyPrint(strval($this->method . $this->id . $this->api_key . $jsonParams . $this->nonce), 'TO_ENCRYPT: ', Colors::red);
        return strval($this->method . $this->id . $this->api_key . $jsonParams . $this->nonce);
    }
}