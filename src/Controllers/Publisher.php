<?php
/**
 * Created by PhpStorm.
 * User: Jadviga
 * Date: 01.04.2016
 * Time: 19:57
 */

namespace App\Controllers;

use App\Import\Managers\Import;
use App\Import\Models\User;

class Publicher
{
    function __construct(){
        $importManager = new Import();
        $user = new User();
        $allUsers = $user->getAllUserForImport();
        foreach ($allUsers as $user){
            $userId = $user['user_id'];
            $filePath =  $user['url_for_parse'];
            $importManager->userId = $userId;
            $importManager->checkFile($filePath);

            $message = $importManager->message;

            $importManager->importFile();
            $importManager->createQueue();
        }
        return true;

    }

}