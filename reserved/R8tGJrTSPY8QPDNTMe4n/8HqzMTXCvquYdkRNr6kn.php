<?php
    // API url
    const api_url       = 'https://api.crypto.com/v2/';

    // API and secret key
    const api_key       = 'm7diRwwyBPFWnTpZQeeRnq';
    const secret_key    = 'kUdjA2nAGQEMmTzoJCsjky';

    // DB credentials
    const db_cred       = ([
        'hostname'      => 'localhost',
        'username'      => 'incucinaconmarcello',
        'password'      => '',
        'name'          => 'my_incucinaconmarcello'
    ]);

    // Tables
    abstract class Tables {
        const currencyValue = 'CURRENCY_VALUE';
    }

    // Trend
    abstract class Trend {
        const UP    = 'UP';
        const DOWN  = 'DOWN';
    }
?>