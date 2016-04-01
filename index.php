<?php
require('vendor/autoload.php');
require('config.php');

use Silex\Application;
use Silex\Provider\{
    SerializerServiceProvider, TwigServiceProvider
};

$app = new Application();

$app['debug'] = true;

$app->register(new TwigServiceProvider(), array(
    'twig.path' => array(__DIR__ . '/views'),
    'twig.options' => array('cache' => __DIR__ . '/cache/twig'),
));

$app->register(new SerializerServiceProvider());

$app->get('/', 'App\Classes\First::index');
$app->get('/import_consumer', 'App\Classes\Consumer::index');
$app->post('/file/', 'App\Classes\Upload::index');
$app->get('/file/', 'App\Classes\Upload::index');

$app->run(); // Запуск приложения