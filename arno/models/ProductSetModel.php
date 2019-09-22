<?php

require_once("BaseModel.php");
class ProductSetModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getProductSetByProductID($product_id){ 
        $sql = " SELECT tb_product_set.* , product_code, product_name 
        FROM tb_product_set LEFT JOIN tb_product ON tb_product_set.product_set_id = tb_product.product_id 
        WHERE tb_product_set.product_id = '$product_id'   
        ORDER BY product_set_no 
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

    function getProductByProductSetID($product_set_id){ 
        $sql = " SELECT tb_product_set.* , product_code, product_name 
        FROM tb_product_set LEFT JOIN tb_product ON tb_product_set.product_id = tb_product.product_id 
        WHERE tb_product_set.product_set_id = '$product_set_id'    
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

    function getProductSetByID($id){
        $sql = " SELECT * 
        FROM tb_product_set 
        WHERE product_set_id = '$id' 
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

    function updateProductSetByID($product_id, $product_set_id,$data = []){
        $sql = " UPDATE tb_product_set SET     
        product_id = '".$data['product_id']."', 
        product_set_id = '".$data['product_set_id']."', 
        product_set_no = '".$data['product_set_no']."',
        product_set_qty = '".$data['product_set_qty']."', 
        product_set_remark = '".$data['product_set_remark']."' 
        WHERE product_set_id = '$product_set_id' AND  product_id = '$product_id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }
     

    function insertProductSet($data = []){
        $sql = " INSERT INTO tb_product_set (
            product_id,
            product_set_id,
            product_set_no,
            product_set_qty, 
            product_set_remark 
        ) VALUES (
            '".$data['product_id']."', 
            '".$data['product_set_id']."', 
            '".$data['product_set_no']."', 
            '".$data['product_set_qty']."',  
            '".$data['product_set_remark']."' 
        ); 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }

    function deleteProductSetByProductID($product_id){
        $sql = " DELETE FROM tb_product_set WHERE product_id = '$product_id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteProductSetByProductSetID($product_set_id){
        $sql = " DELETE FROM tb_product_set WHERE product_set_id = '$product_set_id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteProductSetByID($product_set_id,$product_id){
        $sql = " DELETE FROM tb_product_set WHERE product_set_id = '$product_set_id' AND product_id = '$product_id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>