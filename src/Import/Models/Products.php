<?php
/**
 * Created by PhpStorm.
 * User: Jadviga
 * Date: 01.04.2016
 * Time: 19:54
 */

namespace App\Import\Models;


class Products extends DbConnect
{

    public static $keyValue = "id";
    public static $id = 'id';
    public static $user_id = 'user_id';
    public static $state = 'state';
    public static $order_payment = 'order_payment';
    public $currency_id = 'currency_id';
    public $created_at = 'created_at';
    public $data = 'data';
    public $merchant_id = 'merchant_id';
    public $relatedObject = [
        'currency_id' => 'currency_relation',
        'state' => 'state_relation'
    ];
    public static $required_keys = [
        'user_id','state','order_payment','currency_id','merchant_id'
    ];
    private $db;

    public function __construct()
    {
        $this->db = parent::connect()['db'];

    }

    public function checkAllFilds($data){
        foreach ($this::$required_keys as $key){
            if(!array_key_exists($key,$data)){
                return ['error'=>$key];
            }
        }
        return['sucess'=>1];
    }


    public function getAllProducts()
    {

    }

    public function insertNewProduct($data)
    {
        $this->db->insert('products',$data);
        return $this->db->lastInsertId();

    }

    public function updateRelatedProduct($data,$our_product_id)
    {
        $this->db->update('products', $data, [$this::$keyValue => $our_product_id]);
    }

}