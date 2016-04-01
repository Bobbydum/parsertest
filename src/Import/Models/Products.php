<?php
/**
 * Created by PhpStorm.
 * User: Jadviga
 * Date: 01.04.2016
 * Time: 19:54
 */

namespace App\Import\Models;


class Products
{

    public $id;
    public $user_id;
    public $state;
    public $order_payment;
    public $currency_id;
    public $created_at;
    public $data;
    public $merchant_id;

    public $needDefault = [
        
    ];


    public function getAllProducts()
    {

    }

    public function checkRelatedProduct($userProductId)
    {

    }

    public function updateRelatedProduct()
    {

    }

}