<?php

require_once("BaseModel.php");
class ProductUnitModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getProductUnitBy($product_unit_name = ''){
        $sql = " SELECT product_unit_id, product_unit_name, product_unit_detail   
        FROM tb_product_unit 
        WHERE product_unit_name LIKE ('%$product_unit_name%') 
        ORDER BY product_unit_name  
        ";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getProductUnitByID($id){
        $sql = " SELECT * 
        FROM tb_product_unit 
        WHERE product_unit_id = '$id' 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }

    }

    function updateProductUnitByID($id,$data = []){
        $sql = " UPDATE tb_product_unit SET     
        product_unit_name = '".$data['product_unit_name']."', 
        product_unit_detail = '".$data['product_unit_detail']."'  
        WHERE product_unit_id = $id 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function insertProductUnit($data = []){
        $sql = " INSERT INTO tb_product_unit (
            product_unit_name,
            product_unit_detail
        ) VALUES (
            '".$data['product_unit_name']."', 
            '".$data['product_unit_detail']."' 
        ); 
        ";

        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }


    function deleteProductUnitByID($id){
        $sql = " DELETE FROM tb_product_unit WHERE product_unit_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>