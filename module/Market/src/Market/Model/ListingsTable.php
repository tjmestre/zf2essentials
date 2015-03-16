<?php

namespace Market\Model;


use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;

class ListingsTable extends TableGateway{
    public static $tableName = 'listings';


    public function getListingsByCategory($category){

        return $this->select(array("category" => $category));
    }

    public function getListingById($id){
        return $this->select(array("listings_id" => $id))->current();
    }

    public function getMostRecentListing(){

        $sql = new Select();

        $sql->from(self::$tableName)
            ->order("listings_id DESC")
            ->limit(1);

        return $this->selectWith($sql)->current();
    }


    public function addPosting($data){

        list($city,$country) = explode(",",$data["cityCode"]);

        $data["city"] = trim($city);
        $data["country"] = trim($country);

        $date = new \DateTime();
        if($data["expires"]){
            if($data["expires"] == 30){
                $date->add("P1M");
            }else{
                $date->add(new \DateInterval('P',$data["expires"].'D'));
            }
        }

        $data["date_expires"] = $date->format("Y-m-d H:i:s");
        unset($data["cityCode"],$data["expires"],$data["captcha"],$data["submit"]);

        $this->insert($data);
    }
}