<?php
/**
 * Created by PhpStorm.
 * User: Jadviga
 * Date: 01.04.2016
 * Time: 4:31
 */

namespace App\Classes;


use App\Import\Managers\AmqpConsumer;

class Consumer
{
    function index()
    {
        $message = new AmqpConsumer();
        $message = $message->readMessage();
    }

}