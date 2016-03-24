<?php
require('vendor/autoload.php');

use Silex\Application;
use Silex\Provider\SerializerServiceProvider;
use Classes\Security\Provider\ApiKeyUserServiceProvider;
use Classes\Security\Provider\ApiKeyServiceProvider;
use Symfony\Component\HttpFoundation\RequestMatcher;
use Silex\Provider\SecurityServiceProvider;

$app = new Application();
$app['debug'] = true;

$config = include_once 'config/config.php';

$app['users'] = $config['users'];

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

$app->register(new SerializerServiceProvider());


$app->get('agency', '\ParserApp\Classes\Agency::index');



$app->run(); // Запуск приложения