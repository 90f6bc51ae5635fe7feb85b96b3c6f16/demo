<?php

require_once("BaseModel.php");
class ProductCustomerPriceModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getProductCustomerPriceBy(){
        $sql = " SELECT *     
        FROM tb_product_customer_price 
        LEFT JOIN tb_product ON (tb_product_customer_price.product_id = tb_product.product_id) 
        LEFT JOIN tb_customer ON (tb_product_customer_price.customer_id = tb_customer.customer_id)  
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

    function getProductCustomerPriceByID($product_id,$customer_id){
        $sql = " SELECT * 
        FROM tb_product_customer_price 
        WHERE product_id = '$product_id' AND customer_id = '$customer_id' 
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

    function updateProductCustomerPriceByID($data = []){
        $sql = " UPDATE tb_product_customer_price SET     
        product_price = '".$data['product_price']."' 
        WHERE product_id = '".$data['product_id']."' AND customer_id = '".$data['customer_id']."' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function insertProductCustomerPrice($data = []){
        $sql = " INSERT INTO tb_product_customer_price (
            product_id,
            customer_id,
            product_price
        ) VALUES (
            '".$data['product_id']."', 
            '".$data['customer_id']."', 
            '".$data['product_price']."' 
        ); 
        ";

        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }


    function deleteProductCustomerPriceByID($product_id, $customer_id){
        $sql = " DELETE FROM tb_product_customer_price  WHERE product_id = '$product_id' AND customer_id = '$customer_id'  ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>