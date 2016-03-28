<?php
require('vendor/autoload.php');

use App\Classes\Security\Provider\{
    ApiKeyServiceProvider, ApiKeyUserServiceProvider
};
use Silex\Application;
use Silex\Provider\{
    SecurityServiceProvider, SerializerServiceProvider, TwigServiceProvider
};
use Symfony\Component\HttpFoundation\RequestMatcher;

$app = new Application();

$app['debug'] = true;

$config = include_once 'config/config.php';

$app['users'] = $config['users'];

define('UPLOAD_PATH', __DIR__ . $config['upload']);

//var_dump(UPLOAD_PATH);die();

$app->register(new TwigServiceProvider(), array(
    'twig.path' => array(__DIR__ . '/views'),
    'twig.options' => array('cache' => __DIR__ . '/cache/twig'),
));

$app->register(new SerializerServiceProvider());
$app->register(new ApiKeyUserServiceProvider());
$app->register(new ApiKeyServiceProvider());
$app->register(new SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'api' => array(
            'apikey' => true,
            'pattern' => '^/test/auth',
            'stateless' => false
        )
    ),
    'security.access_rules' => array(
        array(new RequestMatcher('^/test/auth'), array('ROLE_USER'))
    )
));


$app->get('/', 'App\Classes\First::index');
$app->get('/first/', 'App\Classes\First::index');
$app->get('/test/auth/', 'App\Classes\Test::index');
$app->post('/file/', 'App\Classes\Upload::index');
$app->get('/file/', 'App\Classes\Upload::index');

$app->run(); // Запуск приложения