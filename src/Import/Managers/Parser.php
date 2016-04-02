<?php
/**
 * Created by PhpStorm.
 * User: Jadviga
 * Date: 02.04.2016
 * Time: 12:13
 */

namespace App\Import\Managers;


use App\Import\Models\Products;
use App\Import\Models\Relation;
use App\Import\Models\User;
use App\Import\Models\ValueRelation;

class Parser
{
    public $dataObject;
    public $product;
    public $userId;
    public static $dataField = "data";
    public static  $userField = "user_id";
    private $relation;
    private $user;
    private $valueRelation;
    private $productTable;
    private $newProduct;

    /**
     * Parser constructor.
     */
    public function __construct()
    {
        $this->relation = new Relation();
        $this->user = new User();
        $this->valueRelation = new ValueRelation();
        $this->productTable = new Products();
    }


    public function parseData(){


        $date = date("Y-m-d H:i:s", time());
        $this->user->updateTimeImport($date,$this->userId);
        foreach ($this->dataObject as $key => $product){
            $this->product = $product;
            $this->parseProduct();
        }

    }
    
    public function parseProduct(){

        $this->newProduct = [];
        $userName = $this->user->getUserName($this->userId);
        $relatedKey = $this->valueRelation->getIdRelatedFieldsForUser($this->userId);
        foreach ($relatedKey as $key){
            if(array_key_exists($key,$this->product)){
                if($this->product[$key]){
                    $relatedID = $this->relation->getRelatedProduct($this->userId,$this->product[$key]);
                    if($relatedID){
                        $userKeyField = $key;
                        $ourKey = $relatedID;
                        $userKey = $this->product[$key];
                        break;
                    }else{
                        $userKeyField = $key;
                        $userKey = $this->product[$key];
                        break;
                    }
                }else{
                    $log = new Log('error_parse_'.$userName);
                    $log->writeLog("Key fil is empty: ". $key.implode('#',$this->product));
                }
            }
        }
        if($userKeyField){
            $this->getData($userKeyField,$this->product);
            $validProduct = $this->productTable->checkAllFilds($this->newProduct);
            if($validProduct['sucess'] == 1 ){
                if($ourKey ||$relatedID){
                    $this->parseExistProduct($ourKey,$userKeyField);
                }else{
                    $this->parseNewProduct($userKey,$userKeyField);
                }

            }else{
                $log = new Log('error_parse_'.$userName);
                $log->writeLog("Product with ".$userKeyField."=".$userKey." dont have : ". $validProduct['error']);
            }
        }

    }
    
    public function getData($idField,$data){
        $this->newProduct['user_id'] = $this->userId;
        $relatedFieldArr = $this->productTable->relatedObject;
        $allRelatedValues = $this->valueRelation->getAllRelatedValues($this->userId);
        $allRelatedValuesWithDefoult = array_intersect_key($allRelatedValues,$relatedFieldArr);
        foreach ($allRelatedValuesWithDefoult as $key=>$value){
            $userValue = $data[$value];
            $this->newProduct[$key] = $this->valueRelation->getRelatedValueWithDefault($relatedFieldArr[$key],$userValue,$this->userId);
            unset($data[$value]);
        }
        foreach ($data as $userKey=>$userValue){
            if($userKey != $idField){
                $ourKey = $this->valueRelation->getRelatedValue($userKey,$this->userId);
                if($ourKey){
                    $this->newProduct[$ourKey] = $userValue;
                    unset($data[$ourKey]);
                }
            }
        }
        $import = new Import();
        $this->newProduct['data'] = $import->createMessage($data);
    }

    public function parseNewProduct($userId,$idField){
        $userName = $this->user->getUserName($this->userId);
        $date = date("Y-m-d H:i:s", time());
        $this->newProduct['created_at']=$date;
        $newID = $this->productTable->insertNewProduct($this->newProduct);
        $log = new Log('id_New_Product_'.$userName);
        $log->writeLog('New id is - '.$newID);
        $this->relation->addNewRelation($newID,$userId,$this->userId);

    }

    public function parseExistProduct($id,$idField){
        $date = date("Y-m-d H:i:s", time());
        $this->newProduct['created_at']=$date;
        $this->productTable->updateRelatedProduct($this->newProduct,$id);
        $userName = $this->user->getUserName($this->userId);
        $log = new Log('id_Updated_Product_'.$userName);
        $log->writeLog('New id is - '.$id);

    }

}