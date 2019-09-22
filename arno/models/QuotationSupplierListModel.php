<?php

require_once("BaseModel.php");
class QuotationSupplierListModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getQuotationSupplierListBy($quotation_supplier_id){
        $sql = " SELECT tb_quotation_supplier_list.product_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,   
        quotation_supplier_list_id,  
        quotation_supplier_list_qty, 
        quotation_supplier_list_price, 
        quotation_supplier_list_sum, 
        quotation_supplier_list_discount, 
        quotation_supplier_list_discount_type, 
        quotation_supplier_list_total, 
        quotation_supplier_list_remark 
        FROM tb_quotation_supplier_list LEFT JOIN tb_product ON tb_quotation_supplier_list.product_id = tb_product.product_id 
        WHERE quotation_supplier_id = '$quotation_supplier_id' 
        ORDER BY quotation_supplier_list_id 
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


    function insertQuotationSupplierList($data = []){
        $sql = " INSERT INTO tb_quotation_supplier_list (
            quotation_supplier_id,
            product_id,
            quotation_supplier_list_qty,
            quotation_supplier_list_price,
            quotation_supplier_list_sum,
            quotation_supplier_list_discount,
            quotation_supplier_list_discount_type,
            quotation_supplier_list_total,
            quotation_supplier_list_remark,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['quotation_supplier_id']."',  
            '".$data['product_id']."',  
            '".$data['quotation_supplier_list_qty']."',  
            '".$data['quotation_supplier_list_price']."',  
            '".$data['quotation_supplier_list_sum']."', 
            '".$data['quotation_supplier_list_discount']."', 
            '".$data['quotation_supplier_list_discount_type']."', 
            '".$data['quotation_supplier_list_total']."', 
            '".$data['quotation_supplier_list_remark']."', 
            '".$data['addby']."', 
            NOW(), 
            '".$data['updateby']."', 
            NOW() 
        ); 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }

    function updateQuotationSupplierListById($data,$id){

        $sql = " UPDATE tb_quotation_supplier_list 
            SET product_id = '".$data['product_id']."', 
            quotation_supplier_list_qty = '".$data['quotation_supplier_list_qty']."',
            quotation_supplier_list_price = '".$data['quotation_supplier_list_price']."', 
            quotation_supplier_list_sum = '".$data['quotation_supplier_list_sum']."', 
            quotation_supplier_list_discount = '".$data['quotation_supplier_list_discount']."', 
            quotation_supplier_list_discount_type = '".$data['quotation_supplier_list_discount_type']."', 
            quotation_supplier_list_total = '".$data['quotation_supplier_list_total']."', 
            quotation_supplier_list_remark = '".static::$db->real_escape_string($data['quotation_supplier_list_remark'])."' ,
            updateby = '".$data['updateby']."' ,
            lastupdate = NOW()             
            WHERE quotation_supplier_list_id = '$id'
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }
 


    function deleteQuotationSupplierListByID($id){
        $sql = "DELETE FROM tb_quotation_supplier_list WHERE quotation_supplier_list_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteQuotationSupplierListByQuotationSupplierID($id){
        $sql = "DELETE FROM tb_quotation_supplier_list WHERE quotation_supplier_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteQuotationSupplierListByQuotationSupplierIDNotIN($id,$data){
        $str ='';
        if(is_array($data)){ 
            for($i=0; $i < count($data) ;$i++){
                $str .= $data[$i];
                if($i + 1 < count($data)){
                    $str .= ',';
                }
            }
        }else if ($data != ''){
            $str = $data;
        }else{
            $str='0';
        }

        $sql = "DELETE FROM tb_quotation_supplier_list WHERE quotation_supplier_id = '$id' AND quotation_supplier_list_id NOT IN ($str) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>