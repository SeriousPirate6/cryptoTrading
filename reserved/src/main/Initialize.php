<?php
    include '../../Services/Templates/Templates.php';

    // Singlerequest
    $method             = new GetMethods;
    $methodImpl         = $method->getOrderHistory(CurrenciesList::DOT_USDT);

    // Templates::candlestick();

    // Templates::currencyData();

    RunQuery::select(selectFrom::currencyData());
    // RunQuery::select(selectFrom::currencyValue(), true);
?>