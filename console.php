#!/usr/bin/env php
<?php
ini_set("max_execution_time", "0");
require('vendor/autoload.php');
require('config/static.php');

require __DIR__ . '/vendor/autoload.php';

use App\Controllers\Consumer;
use App\Controllers\Publicher;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new Consumer());
$application->add(new Publicher());
$application->run();