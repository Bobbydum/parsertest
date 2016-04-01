<?php
require('vendor/autoload.php');
require('config.php');

use App\Import\Managers\AmqpConsumer;

$message = new AmqpConsumer();
