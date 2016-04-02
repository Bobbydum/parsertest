<?php
/**
 * Created by PhpStorm.
 * User: Jadviga
 * Date: 01.04.2016
 * Time: 19:54
 */

namespace App\Import\Models;


use App\Import\Managers\Log;

class Relation extends DbConnect
{


    public static $our_product_id = 'our_product_id';
    public static $user_product_id = 'user_product_id';
    public static $user_id = 'user_id';
    private $db;

    public function __construct()
    {
        $this->db = parent::connect()['db'];

    }

    /**
     * @param $userID
     */
    public function getAllRelationForUser($userID)
    {

        $sql = "SELECT our_product_id FROM parser.relation where user_id = $userID;";
        return $this->db->fetchAll($sql);

    }

    public function getRelatedProduct($userID, $userProductId)
    {
        $sql = "SELECT our_product_id FROM parser.relation where user_id = $userID and user_product_id = $userProductId;";
        return $this->db->fetchColumn($sql);

    }
    
    public function addNewRelation($our_product_id,$user_product_id,$user_id){
        $data = [
            $this::$user_id=>$user_id,
            $this::$our_product_id=>$our_product_id,
            $this::$user_product_id=>$user_product_id
        ];
        $this->db->insert('relation',$data);
        return $this->db->lastInsertId();
        
    }


}