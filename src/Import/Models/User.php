<?php
/**
 * Created by PhpStorm.
 * User: Jadviga
 * Date: 01.04.2016
 * Time: 19:54
 */

namespace App\Import\Models;


use Silex\Application;

class User extends DbConnect
{

    public $user_id;
    public $update_interval;
    public $last_parse_time;
    public $url_for_parse;
    public $name;
    private $db;


    public function __construct()
    {
        $this->db = parent::connect()['db'];

    }


    public function getAllUserForImport()
    {
        $sql = "SELECT 
                    *
                FROM
                    parser.users
                WHERE
                    TIMESTAMPDIFF(MINUTE,
                        last_parse_time,
                        NOW()) >= update_interval;";

        $all_users = $this->db->fetchAll($sql);
        return $all_users;
    }

    public function getUsersId()
    {
        $users = [];
        $sql = "SELECT user_id , name FROM parser.users;";
        $all_users = $this->db->fetchAll($sql);
        array_walk($all_users, function ($value) use (&$users) {
            $users[$value['user_id']] = $value['name'];
        });
        return $users;
    }

    public function getUserName($userId){
        $sql = "SELECT name FROM parser.users WHERE user_id = $userId;";
        return $this->db->fetchColumn($sql);

    }
    public function updateTimeImport($time,$userId){
        $this->db->update('users', ['last_parse_time' => $time], ['user_id' => $userId]);

    }

}