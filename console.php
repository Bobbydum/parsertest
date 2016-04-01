<?php
ini_set("max_execution_time", "0");
require('vendor/autoload.php');
require('config/static.php');

use App\Import\Managers\AmqpConsumer;

$message = new AmqpConsumer();
