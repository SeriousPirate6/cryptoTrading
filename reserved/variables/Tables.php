<?php
    define('Tables', ([
        'currencyValue' => 'CURRENCY_VALUE',
        'currencyData'  => 'CURRENCY_DATA'
    ]));

    // Types
    const CREATE = 'CREATE';
    const INSERT = 'INSERT';

    $constants = get_defined_constants(true);
    $constants = $constants['user'];
?>