<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 25.03.16
 * Time: 22:51
 */

namespace App\Import\Interfaces;


interface Ampq
{
    public function getServer();

    public function queueDeclare($params, $queueendcallback, $queueendcallbackarguments, $queueconsumercontroller);

    public function activateQueue();

    public function subscribe($callback);

    public function loadByQueueID($queueID);

    public function subscribeAll();

    public function getCheckQueueStateAndCloseIfNeed();


}