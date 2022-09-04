<?php
define('Tables', ([
    'balance'       => 'BALANCE',
    'currencyData'  => 'CURRENCY_DATA',
    'currencyValue' => 'CURRENCY_VALUE',
    'orders'        => 'ORDERS',
    'testStrategy'  => 'TEST_STRATEGY',
]));

// Types
const CREATE = 'CREATE';
const DELETE = 'DELETE';
const DROP   = 'DROP';
const INSERT = 'INSERT';
const SELECT = 'SELECT';

//Operators
const lessThan          = '<';
const lessOrEqual       = '<=';
const equal             = '=';
const greaterOrEqual    = '>=';
const greaterThan       = '>';

$constants = get_defined_constants(true);
$constants = $constants['user'];