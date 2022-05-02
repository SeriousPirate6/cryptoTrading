<?php
    include '../../Utils/RunQuery/RunQuery.php';
    include '../../Services/Requests/Requests.php';
    include '../../Services/Methods/MethodsImpl.php';

    // Singlerequest
    $method             = new GetMethods;
    $methodImpl         = $method->getOrderHistory(CurrenciesList::DOT_USDT);

    // Multirequest
    $currList           = CurrenciesList::getOptions();
    $multiMethods       = new GetMultipleMethods();
    $multiMethodsImpl   = $multiMethods->getCandlestick($currList, m1, d1);

    $requests           = SendRequest::sendMultiRequest($multiMethodsImpl);

    // Multiquery
    $queries            = MultipleInsertTable::currencyValue($requests);
    RunQuery::multipleInsert($queries);
?>