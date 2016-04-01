<?php

return array(
    'upload' => '/web/uploads',
    'format' => ['xml' => 'xml', 'csv' => 'csv'],
    'amqp' => [
        'host' => '127.0.0.1',
        'port' => 5672,
        'user' => 'guest',
        'password' => 'guest',
        'vhost' => '/',
        'amqp_debug' => true
    ]
);