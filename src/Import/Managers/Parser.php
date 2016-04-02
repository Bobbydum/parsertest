<?php
/**
 * Created by PhpStorm.
 * User: Jadviga
 * Date: 02.04.2016
 * Time: 12:13
 */

namespace App\Import\Managers;


class Parser
{
    public $dataObject;
    public $userId;
    public static $dataFild = "data";
    public static  $userFild = "user_id";

    public function parseData(){

        $log = new Log();
        $log->writeLog(print_r($this->dataObject, true));

    }

}