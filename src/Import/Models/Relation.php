<?php
/**
 * Created by PhpStorm.
 * User: Jadviga
 * Date: 01.04.2016
 * Time: 19:54
 */

namespace App\Import\Models;


class Relation
{


    public $our_order_id;
    public $user_order_id;
    public $user_id;

    /**
     * @param $userID
     */
    public function getAllRelationForUser($userID)
    {
        
        $sql = "SELECT our_order_id FROM parser.relation where user_id = $userID;";

    }


}