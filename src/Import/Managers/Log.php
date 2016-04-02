<?php
/**
 * Created by PhpStorm.
 * User: Jadviga
 * Date: 02.04.2016
 * Time: 12:29
 */

namespace App\Import\Managers;

use Silex\Application;
use Silex\Provider\MonologServiceProvider;

class Log
{
    private $app;

    /**
     * Log constructor.
     */
    public function __construct()
    {
        $this->app = new Application();
        $this->app->register(new MonologServiceProvider(), array(
            'monolog.logfile' => LOG_DIR.'/parser.log',
        ));
    }

    public function writeLog($message){

        $this->app['monolog']->addInfo($message);
    }
}