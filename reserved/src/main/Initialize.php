<?php
    include '../../Utils/RunQuery/RunQuery.php';
    include '../../Services/Requests/Requests.php';
    include '../../Services/Methods/MethodsImpl.php';

    $method     = new GetMethods;
    $methodImpl = $method->getCandlestick(Currencies::MATIC_USDT, m1, d1);

    // $request    = SendRequest::sendReuquest($methodImpl);
    // $inst_name  = $request['result']['instrument_name'];
    // $curr_value = $request['result']['data'][0]['o'];

    // $query      = InsertTable::currencyValue($inst_name, $curr_value, Trend::UP);

    // RunQuery::insert($query);

    // TextFormatter::prettyPrint($request);
    // TextFormatter::prettyPrint($query);

    $currList = CurrenciesList::getOptions();
    $multiMethods = new GetMultipleMethods();
    $multiMethods = $multiMethods->getCandlestick($currList, m1, d1);
    TextFormatter::prettyPrint($multiMethods);

    $requests = SendRequest::sendMultiRequest($multiMethods);

    TextFormatter::prettyPrint($requests);

    $queries = MultipleInsertTable::currencyValue($requests);

    TextFormatter::prettyPrint($queries);

    RunQuery::multipleInsert($queries);

    RunQuery::insert("INSERT INTO CURRENCY_VALUE (CURRENCY, PRICE, TREND) VALUES ('BTC_USDT', 42000, 'UP');");
?>