<?php
$instrumentName = Currencies::BTC_USDT;

$params = [
    "instrument_name"       => "BTC_USDT",
    "side"                  => "BUY",
    "type"                  => "LIMIT",
    "price"                 => 27000,
    "quantity"              => 1,
];

define('orderList', ([
    "id"                    => 1,
    "status"                => "TEST",
    "side"                  => "SELL",
    "price"                 => 22000,
    "quantity"              => 0.5,
    "reason"                => 20002,
    "order_id"              => "1",
    "client_oid"            => 1661627518325,
    "create_time"           => 1661627518325,
    "update_time"           => "",
    "type"                  => "MARKET",
    "instrument_name"       => $instrumentName,
    "avg_price"             => 0.00,
    "cumulative_quantity"   => 0,
    "cumulative_value"      => 0,
    "fee_currency"          => "",
    "exec_inst"             => "",
    "time_in_force"         => "GOOD_TILL_CANCEL",
]));

define('balance', ([
    "instrument_name"       => $instrumentName,
    "funds"                 => 100,
    "asset_qnt"             => 0.5,
    "price"                 => 22000,
    "order_reason"          => "INITIALIZATION"
]));

$paramsData = get_defined_constants(true);
$paramsData = $paramsData['user'];