<?php

require_once("BaseModel.php");
class ProductGroupModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getProductGroupBy($product_group_name = ''){
        $sql = " SELECT product_group_id, product_group_name, product_group_detail   
        FROM tb_product_group 
        WHERE product_group_name LIKE ('%$product_group_name%') 
        ORDER BY product_group_name  
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

    function getProductGroupByID($id){
        $sql = " SELECT * 
        FROM tb_product_group 
        WHERE product_group_id = '$id' 
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

    function updateProductGroupByID($id,$data = []){
        $sql = " UPDATE tb_product_group SET     
        product_group_name = '".$data['product_group_name']."', 
        product_group_detail = '".$data['product_group_detail']."'  
        WHERE product_group_id = $id 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function insertProductGroup($data = []){
        $sql = " INSERT INTO tb_product_group (
            product_group_name,
            product_group_detail
        ) VALUES (
            '".$data['product_group_name']."', 
            '".$data['product_group_detail']."' 
        ); 
        ";

        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }


    function deleteProductGroupByID($id){
        $sql = " DELETE FROM tb_product_group WHERE product_group_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>