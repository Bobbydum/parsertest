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

define('HOST', CONFIG['amqp']['host']);

define('PORT', CONFIG['amqp']['port']);

define('USER', CONFIG['amqp']['user']);

define('PASS', CONFIG['amqp']['password']);

define('VHOST', CONFIG['amqp']['vhost']);

//If this is enabled you can see AMQP output on the CLI
define('AMQP_DEBUG', CONFIG['amqp']['amqp_debug']);

$app->register(new TwigServiceProvider(), array(
    'twig.path' => array(__DIR__ . '/views'),
    'twig.options' => array('cache' => __DIR__ . '/cache/twig'),
));

$app->register(new SerializerServiceProvider());

$app->get('/', 'App\Classes\First::index');
$app->get('/import_consumer/', 'App\Classes\Consumer::index');
$app->post('/file/', 'App\Classes\Upload::index');
$app->get('/file/', 'App\Classes\Upload::index');

$app->run(); // Запуск приложения