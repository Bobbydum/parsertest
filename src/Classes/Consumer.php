<?php
/**
 * Created by PhpStorm.
 * User: Jadviga
 * Date: 01.04.2016
 * Time: 4:31
 */

namespace App\Classes;

use App\Import\Managers\AmqpConsumer;
use Silex\Application;

class Consumer
{
    function index(Application $app)
    {
        new AmqpConsumer();
    }

}
