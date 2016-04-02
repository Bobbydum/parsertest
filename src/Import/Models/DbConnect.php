<?php
/**
 * Created by PhpStorm.
 * User: Jadviga
 * Date: 02.04.2016
 * Time: 13:38
 */

namespace App\Import\Models;


use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;

class DbConnect
{

    public function connect()
    {
        $app  = new Application();
        $app->register(new DoctrineServiceProvider(), DB_CONNECT);
        return $app;
    }
}