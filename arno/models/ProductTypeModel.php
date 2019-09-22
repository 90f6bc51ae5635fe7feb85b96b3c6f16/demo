<?php

require_once("BaseModel.php");
class ProductTypeModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getProductTypeBy($product_type_auto = '',$product_type_name = ''){
        $str_type_auto = '' ;
        if($product_type_auto != ''  ){
            $str_type_auto = "AND product_type_auto = '$product_type_auto' ";
        }
        $sql = " SELECT *    
        FROM tb_product_type 
        WHERE product_type_name LIKE ('%$product_type_name%') 
        $str_type_auto
        ORDER BY product_type_name  
        ";
        // echo $sql;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getProductTypeByID($id){
        $sql = " SELECT * 
        FROM tb_product_type 
        WHERE product_type_id = '$id' 
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

    function getProductTypeByName($name){
        $sql = " SELECT * 
        FROM tb_product_type 
        WHERE product_type_name = '$name' 
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

    function updateProductTypeByID($id,$data = []){
        $sql = " UPDATE tb_product_type SET     
        product_type_name = '".$data['product_type_name']."', 
        product_type_first_char = '".$data['product_type_first_char']."', 
        product_type_auto = '".$data['product_type_auto']."', 
        product_type_digit = '".$data['product_type_digit']."', 
        product_type_detail = '".$data['product_type_detail']."'  
        WHERE product_type_id = $id 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    

    function insertProductType($data = []){
        $sql = " INSERT INTO tb_product_type (
            product_type_name,
            product_type_first_char,
            product_type_auto, 
            product_type_digit, 
            product_type_detail
        ) VALUES (
            '".$data['product_type_name']."', 
            '".$data['product_type_first_char']."', 
            '".$data['product_type_auto']."', 
            '".$data['product_type_digit']."', 
            '".$data['product_type_detail']."' 
        ); 
        ";

        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }


    function deleteProductTypeByID($id){
        $sql = " DELETE FROM tb_product_type WHERE product_type_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>