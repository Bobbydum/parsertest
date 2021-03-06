<?php
require('vendor/autoload.php');
require('config/static.php');
ini_set("max_execution_time", "0");

use Silex\Application;
use Silex\Provider\{
    SerializerServiceProvider, TwigServiceProvider
};

$app['debug'] = true;

$app->register(new TwigServiceProvider(), array(
    'twig.path' => array(__DIR__ . '/views'),
    'twig.options' => array('cache' => __DIR__ . '/cache/twig'),
));

$app->register(new SerializerServiceProvider());

$app->get('/', 'App\Controllers\FirstPage::index');
$app->get('/import_consumer', 'App\Controllers\Consumer::index');
$app->post('/file/', 'App\Controllers\Upload::index');
$app->get('/file/', 'App\Controllers\Upload::index');

$app->run(); // Запуск приложения