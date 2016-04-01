<?php

return [
    'upload' => '/web/uploads',
    'format' => ['xml' => 'xml', 'csv' => 'csv'],
    'amqp' => [
        'host' => '127.0.0.1',
        'port' => 5672,
        'user' => 'guest',
        'password' => 'guest',
        'vhost' => '/',
        'amqp_debug' => true,
        'parse_queue' => 'parse_queue',
        'amqp_debug' => 'false'
    ],
    'log' => 'log',
    'db_connect'=>[
        'db.options' => [
            'driver' => 'pdo_mysql',
            'dbname' => 'parser',
            'host' => '127.0.0.1',
            'user' => 'master',
            'password' => 'gtnhjdbx',
        ]
    ]
];