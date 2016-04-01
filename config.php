<?php
define('CONFIG', include_once 'config/config.php');

define('UPLOAD_PATH', __DIR__ . CONFIG['upload']);

define('HOST', CONFIG['amqp']['host']);

define('PORT', CONFIG['amqp']['port']);

define('USER', CONFIG['amqp']['user']);

define('PASS', CONFIG['amqp']['password']);

define('VHOST', CONFIG['amqp']['vhost']);

//If this is enabled you can see AMQP output on the CLI
define('AMQP_DEBUG', CONFIG['amqp']['amqp_debug']);