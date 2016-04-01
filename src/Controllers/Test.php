<?php
/**
 * Created by PhpStorm.
 * User: Jadviga
 * Date: 01.04.2016
 * Time: 20:16
 */

namespace App\Controllers;


use App\Import\Models\User;

class Test
{
    public function index(){
        $user = new User();
        $allUsers = $user->getAllUserForImport();
        var_dump($allUsers);
        $userObj = new User();

        $users = $userObj->getUsersId();
        var_dump($users);
        return true;
        
        
        
    }

}