<?php

//ALTER TABLE `tb_product_customer` ADD `maximum_stock` INT NOT NULL AFTER `safety_stock`;

require_once("BaseModel.php");
class ProductCustomerModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getProductCustomerBy($product_id,$customer_name_th = '',$customer_name_en = ''){
        $sql = " SELECT product_customer_id, tb_product_customer.customer_id, customer_code, customer_name_en, minimum_stock, safety_stock ,maximum_stock, product_status , product_customer_product_name  
        FROM tb_product_customer LEFT JOIN tb_customer ON (tb_product_customer.customer_id = tb_customer.customer_id)
        WHERE product_id = '$product_id' 
        AND ( customer_name_th LIKE ('%$customer_name_th%') 
        OR customer_name_en LIKE ('%$customer_name_en%') 
        ) ORDER BY customer_name_en  
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

    function getProductCustomerByID($id){
        $sql = " SELECT * 
        FROM tb_product_customer 
        WHERE product_customer_id = '$id' 
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
    function getProductCustomerByProductName($product_id,$customer_id){
        $sql = " SELECT * 
        FROM tb_product
        LEFT JOIN tb_invoice_customer_list ON tb_product.product_id = tb_invoice_customer_list.product_id
        LEFT JOIN tb_invoice_customer ON tb_invoice_customer_list.invoice_customer_id = tb_invoice_customer.invoice_customer_id
        WHERE  tb_invoice_customer_list.product_id = '$product_id' AND tb_invoice_customer.customer_id = '$customer_id'
        
        ";
// echo $sql;

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }

    }

    function updateProductCustomerByID($id,$data = []){
        $sql = " UPDATE tb_product_customer SET     
        product_id = '".$data['product_id']."', 
        customer_id = '".$data['customer_id']."',   
        minimum_stock = '".$data['minimum_stock']."', 
        safety_stock = '".$data['safety_stock']."', 
        maximum_stock = '".$data['maximum_stock']."', 
        product_status = '".$data['product_status']."', 
        product_customer_product_name = '".$data['product_customer_product_name']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE product_customer_id = $id 
        ";

        // echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function insertProductCustomer($data = []){
        $sql = " INSERT INTO tb_product_customer (
            product_id,
            customer_id,
            minimum_stock,
            safety_stock,
            maximum_stock,
            product_status,
            product_customer_product_name,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['product_id']."', 
            '".$data['customer_id']."', 
            '".$data['minimum_stock']."', 
            '".$data['safety_stock']."', 
            '".$data['maximum_stock']."', 
            '".$data['product_status']."', 
            '".$data['product_customer_product_name']."', 
            '".$data['addby']."', 
            NOW(), 
            '".$data['updateby']."', 
            NOW() 
        ); 
        ";

        // echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }


    function deleteProductCustomerByID($id){
        $sql = " DELETE FROM tb_product_customer WHERE product_customer_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 
    }

    function deleteProductCustomerByProductID($product_id){
        $sql = " DELETE FROM tb_product_customer WHERE product_id = '$product_id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 
    }

}
?>