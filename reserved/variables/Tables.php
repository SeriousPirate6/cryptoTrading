<?php
define('Tables', ([
    'currencyValue' => 'CURRENCY_VALUE',
    'currencyData'  => 'CURRENCY_DATA',
    'testStrategy'  => 'TEST_STRATEGY',
    'orders'        => 'ORDERS',
]));

// Types
const CREATE = 'CREATE';
const INSERT = 'INSERT';
const SELECT = 'SELECT';
const DROP   = 'DROP';

$constants = get_defined_constants(true);
$constants = $constants['user'];