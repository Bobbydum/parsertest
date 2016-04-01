<?php
/**
 * Created by PhpStorm.
 * User: Jadviga
 * Date: 01.04.2016
 * Time: 19:54
 */

namespace App\Import\Models;


use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;

class User
{

    public $user_id;
    public $update_interval;
    public $last_parse_time;
    public $url_for_parse;
    public $name;

    public function getAllUserForImport()
    {
        $app = new Application();
        $app->register(new DoctrineServiceProvider(), DB_CONNECT);
        $sql = "SELECT * FROM parser.users;";
        $all_users = $app['db']->fetchAll($sql);
        return $all_users;
    }

    public function getUsersId(){
        $app = new Application();
        $users=[];
        $app->register(new DoctrineServiceProvider(), DB_CONNECT);
        $sql = "SELECT user_id , name FROM parser.users;";
        $all_users = $app['db']->fetchAll($sql);
        foreach ($all_users as $user){
            $users[$user['user_id']] = $user['name'];
        }
        return $users;
    }

}