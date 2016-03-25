<?php
require('vendor/autoload.php');

use Silex\Application;
use Symfony\Component\HttpFoundation\RequestMatcher;
use Silex\Provider\{
    SerializerServiceProvider,
    SecurityServiceProvider
};
use App\Classes\Security\Provider\{
    ApiKeyUserServiceProvider,
    ApiKeyServiceProvider
};

$app = new Application();
$app['debug'] = true;

$config = include_once 'config/config.php';

$app['users'] = $config['users'];

$app->register(new SerializerServiceProvider());
$app->register(new ApiKeyUserServiceProvider());
$app->register(new ApiKeyServiceProvider());
$app->register(new SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'api' => array(
            'apikey' => true,
            'pattern' => '^/vf/ru/rest',
            'stateless' => false
        )
    ),
    'security.access_rules' => array(
        array(new RequestMatcher('^/vf/ru/rest'), array('ROLE_USER'))
    )
));


$app->get('/', 'App\Classes\First::index');
$app->get('/first', 'App\Classes\First::index');

$app->run(); // Запуск приложения