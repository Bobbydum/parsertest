<?php

namespace App\Import\Managers;

use Silex\{
    Application
};
use Symfony\Component\HttpFoundation\File;



Class Import  {


    static function checkFile($file){

        $ext =  $file->getClientOriginalExtension();
        switch ($ext){
            case CONFIG['format']['xml'];
                return 'Вы залили ХМЛ';
                break;
            case CONFIG['format']['csv'];
                return 'Вы залили CSV';
                break;
        }
    }
    
}