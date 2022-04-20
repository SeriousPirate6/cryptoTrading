<?php
    include '../../Utils/RunQuery/RunQuery.php';
    include '../../Services/Requests/Requests.php';
    include '../../Services/Methods/MethodsImpl.php';

    $method     = new getMethods;
    $methodImpl = $method->getCandlestick(Currencies::BTC_USDT, m1);

    $request    = sendRequest::sendReuquestFromMethod($methodImpl);
    $inst_name  = $request['result']['instrument_name'];
    $curr_value = $request['result']['data'][0]['o'];

    $query      = insertTable::currencyValue($inst_name, $curr_value, Trend::UP);

    RunQuery::insert($query);

    TextFormatter::prettyPrint($request);
    TextFormatter::prettyPrint($query);
?>