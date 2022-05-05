<?php
    include '../../Services/Templates/Templates.php';

    // Singlerequest
    $method             = new GetMethods;
    $methodImpl         = $method->getOrderHistory(CurrenciesList::DOT_USDT);

    Templates::candlestick();
?>