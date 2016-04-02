<?php
/**
 * Created by PhpStorm.
 * User: Jadviga
 * Date: 01.04.2016
 * Time: 20:50
 */

namespace App\Import\Models;


/**
 * Class ValueRelation
 * @package App\Import\Models
 */
class ValueRelation extends DbConnect
{


    public $relation_id;
    public $our_value;
    public $user_value;
    public $user_id;
    private $db;


    public function __construct()
    {
        $this->db = parent::connect()['db'];

    }

    /**
     * @param $userValue
     */
    public function getRelatedValue($userValue, $userID)
    {
        $sql = "SELECT our_value FROM parser.values_relation where user_value = '$userValue' and user_id = $userID;";
        return $this->db->fetchColumn($sql);
    }

    /**
     * @param $userID
     */
    public function getIdRelatedFieldsForUser($userID)
    {
        $keyValue = Products::$keyValue;
        $sql = "SELECT user_value FROM parser.values_relation where our_value = '$keyValue' and user_id = $userID;";
        $arrayData = $this->db->fetchAll($sql);

        array_walk($arrayData, function (&$value) {
            $value = $value['user_value'];
        });

        return $arrayData;
    }

    public function getAllRelatedValues($userID)
    {
        $sql = "SELECT our_value, user_value FROM parser.values_relation where  user_id = $userID;";
        $arrayData = $this->db->fetchAll($sql);
        $values = [];
        array_walk($arrayData, function ($value) use (&$values) {
            $values[$value['our_value']] = $value['user_value'];
        });
        return $values;
    }

    public function getRelatedValueWithDefault($table, $userValue='', $userID)
    {
        $sql = "SELECT 
    IFNULL((SELECT 
                    our_value
                FROM
                    $table
                WHERE
                    user_id = 1
                        AND user_value = '$userValue'),
            (SELECT 
                    our_value
                FROM
                    $table
                WHERE
                    user_id = 1 AND `default` = $userID));";
        return $this->db->fetchColumn($sql);
    }

}