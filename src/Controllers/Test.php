<?php
/**
 * Created by PhpStorm.
 * User: Jadviga
 * Date: 01.04.2016
 * Time: 20:16
 */

namespace App\Controllers;


use App\Import\Models\Products;
use App\Import\Models\User;
use App\Import\Models\ValueRelation;

class Test
{
    public function index(){


        $user = new User();
        $allUsers = $user->getAllUserForImport();
        var_dump($allUsers);
        $userObj = new User();

        $users = $userObj->getUsersId();
        var_dump($users);


        $valrel = new ValueRelation();
        var_dump($valrel->getIdRelatedFieldsForUser(1));

        $allRelatedValues = $valrel->getAllRelatedValues(1);
        var_dump($allRelatedValues);

        $this->valueRelation = new ValueRelation();
        $this->productTable = new Products();

        $relatedFildArr = $this->productTable->relatedObject;

        var_dump($relatedFildArr);
        var_dump( array_intersect_key($allRelatedValues,$relatedFildArr));




        return true;
        
        
        
    }

}