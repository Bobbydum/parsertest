<?php
require('vendor/autoload.php');

use Silex\Application;
use Silex\Provider\{
    SerializerServiceProvider, TwigServiceProvider
};

$app = new Application();

$app['debug'] = true;

define('CONFIG', include_once 'config/config.php');

define('UPLOAD_PATH', __DIR__ . CONFIG['upload']);

$app->register(new TwigServiceProvider(), array(
    'twig.path' => array(__DIR__ . '/views'),
    'twig.options' => array('cache' => __DIR__ . '/cache/twig'),
));

$app->register(new SerializerServiceProvider());

$app->get('/', 'App\Classes\First::index');
$app->get('/first/', 'App\Classes\First::index');
$app->get('/test/auth/', 'App\Classes\Test::index');
$app->post('/file/', 'App\Classes\Upload::index');
$app->get('/file/', 'App\Classes\Upload::index');

$app->run(); // Запуск приложения