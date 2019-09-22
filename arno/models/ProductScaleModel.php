<?php

require_once("BaseModel.php");
class ProductScaleModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getProductScaleByProductID($product_id){ 
        $sql = " SELECT tb_product_scale.* , product_code, product_name 
        FROM tb_product_scale LEFT JOIN tb_product ON tb_product_scale.product_id = tb_product.product_id 
        WHERE tb_product_scale.product_id = '$product_id'   
        ORDER BY product_scale_qty 
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

    function getProductByProductIDAndQty($product_id,$product_scale_qty){ 
        $sql = " SELECT tb_product_scale.* , product_code, product_name 
        FROM tb_product_scale LEFT JOIN tb_product ON tb_product_scale.product_id = tb_product.product_id 
        WHERE tb_product_scale.product_id = '$product_id'    
        AND tb_product_scale.product_scale_qty = '$product_scale_qty'    
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

    function getProductScaleByID($product_id,$product_scale_qty){
        $sql = " SELECT * 
        FROM tb_product_scale 
        WHERE product_id = '$id' 
        AND product_scale_qty = '$id' 
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

    function updateProductScaleByID($product_id, $product_scale_qty,$data = []){
        $sql = " UPDATE tb_product_scale SET      
        product_scale_price = '".$data['product_scale_price']."',
        product_scale_remark = '".$data['product_scale_remark']."' 
        WHERE product_scale_qty = '$product_scale_qty' AND  product_id = '$product_id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }
     

    function insertProductScale($data = []){
        $sql = " INSERT INTO tb_product_scale (
            product_id, 
            product_scale_qty, 
            product_scale_price,
            product_scale_remark 
        ) VALUES (
            '".$data['product_id']."', 
            '".$data['product_scale_qty']."', 
            '".$data['product_scale_price']."',  
            '".$data['product_scale_remark']."' 
        ); 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }

    function deleteProductScaleByProductID($product_id){
        $sql = " DELETE FROM tb_product_scale WHERE product_id = '$product_id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
 

    function deleteProductScaleByID($product_scale_qty,$product_id){
        $sql = " DELETE FROM tb_product_scale WHERE product_scale_qty = '$product_scale_qty' AND product_id = '$product_id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>