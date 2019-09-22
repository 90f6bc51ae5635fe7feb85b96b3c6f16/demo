<?php

require_once("BaseModel.php");
class ProductSupplierModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getProductSupplierBy($product_id, $supplier_name_th = '', $supplier_name_en = '',$product_supplier_status = '' ){
        $str ="";
        if($product_supplier_status != ""){
            $str ="AND product_supplier_status='$product_supplier_status'";
        }
        $sql = " SELECT tb_product_supplier.supplier_id, product_supplier_id, supplier_code, product_supplier_qty,supplier_name_en, product_buyprice, lead_time, product_supplier_status   
        FROM tb_product_supplier LEFT JOIN tb_supplier ON tb_product_supplier.supplier_id = tb_supplier.supplier_id 
        WHERE product_id = '$product_id' 
        AND ( supplier_name_en LIKE ('%$supplier_name_en%') 
        OR supplier_name_th LIKE ('%$supplier_name_th%')
        ) $str ORDER BY supplier_name_en  
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
    function getProductSupplierByAjax($product_id, $supplier_name_th = '', $supplier_name_en = '',$product_supplier_status = '' ){
        $str ="";
        if($product_supplier_status != ""){
            $str ="AND product_supplier_status='$product_supplier_status'";
        }
        $sql = " SELECT tb_product_supplier.supplier_id, product_supplier_id, supplier_code, product_supplier_qty,supplier_name_en, product_buyprice, lead_time, product_supplier_status   
        FROM tb_product_supplier LEFT JOIN tb_supplier ON tb_product_supplier.supplier_id = tb_supplier.supplier_id 
        WHERE product_id = '$product_id' 
        AND ( supplier_name_en LIKE ('%$supplier_name_en%') 
        OR supplier_name_th LIKE ('%$supplier_name_th%')
        ) $str
        GROUP BY tb_product_supplier.supplier_id 
        ORDER BY supplier_name_en  
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
    function getProductSupplierBySupplierID($supplier_id = ''){ 

        $sql = " SELECT  tb_product_supplier.supplier_id, product_buyprice,
        tb_product_supplier.product_id,  CONCAT(product_code_first,product_code) as product_code, product_drawing, product_name, product_description 
        FROM tb_product_supplier 
        LEFT JOIN tb_supplier ON tb_product_supplier.supplier_id = tb_supplier.supplier_id 
        LEFT JOIN tb_product ON tb_product_supplier.product_id = tb_product.product_id 
        WHERE tb_supplier.supplier_id = '$supplier_id' 
        AND tb_supplier.supplier_id IS NOT NULL    
        AND tb_product.product_id IS NOT NULL
        GROUP BY tb_product_supplier.product_id
        ORDER BY product_name 

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

    function getProductBySupplierID($supplier_id){
        $sql = " SELECT tb_product_supplier.product_id, CONCAT(product_code_first,product_code) as product_code, product_name, product_description , product_type, product_status, product_buyprice   
        FROM tb_product_supplier LEFT JOIN tb_product ON tb_product_supplier.product_id = tb_product.product_id 
        WHERE supplier_id = '$supplier_id' AND product_supplier_status = 'Active' 
        ORDER BY product_name  
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

    function getProductSupplierByID($id){
        $sql = " SELECT * 
        FROM tb_product_supplier 
        WHERE product_supplier_id = '$id' 
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
    
    function getProductSupplierPriceByID($product_id,$supplier_id){
        $sql = " SELECT * 
        FROM tb_product_supplier 
        WHERE product_id = '$product_id' AND supplier_id = '$supplier_id' 
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

    function updateProductSupplierByID($id,$data = []){
        $sql = " UPDATE tb_product_supplier SET     
        supplier_id = '".$data['supplier_id']."', 
        product_buyprice = '".$data['product_buyprice']."', 
        product_supplier_status = '".$data['product_supplier_status']."',
        product_supplier_qty = '".$data['product_supplier_qty']."', 
        lead_time = '".$data['lead_time']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE product_supplier_id = $id 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }
    
    function updateProductSupplierPriceByID($data = []){
        $sql = " UPDATE tb_product_supplier SET      
        product_buyprice = '".$data['product_buyprice']."',   
        product_supplier_status = 'Active' 
        WHERE supplier_id = '".$data['supplier_id']."' AND product_id = '".$data['product_id']."' 
        "; 
        
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function insertProductSupplier($data = []){
        $sql = " INSERT INTO tb_product_supplier (
            product_id,
            supplier_id,
            product_buyprice,
            product_supplier_qty,
            lead_time,
            product_supplier_status, 
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['product_id']."', 
            '".$data['supplier_id']."', 
            '".$data['product_buyprice']."', 
            '".$data['product_supplier_qty']."', 
            '".$data['lead_time']."', 
            '".$data['product_supplier_status']."',
            '".$data['addby']."', 
            NOW(), 
            '".$data['updateby']."', 
            NOW() 
        ); 
        "; 
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }

    function deleteProductSupplierByID($id){
        $sql = " DELETE FROM tb_product_supplier WHERE product_supplier_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteProductSupplierByProductID($product_id){
        $sql = " DELETE FROM tb_product_supplier WHERE product_id = '$product_id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>