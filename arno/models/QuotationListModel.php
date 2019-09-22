<?php

require_once("BaseModel.php");
class QuotationListModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getQuotationListBy($quotation_id){
        $sql = " SELECT tb_quotation_list.product_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,   
        quotation_list_id,  
        quotation_list_qty, 
        quotation_list_price, 
        quotation_list_sum, 
        quotation_list_discount, 
        quotation_list_discount_price, 
        quotation_list_discount_type, 
        quotation_list_total, 
        quotation_list_discount_price, 
        quotation_list_remark 
        FROM tb_quotation_list LEFT JOIN tb_product ON tb_quotation_list.product_id = tb_product.product_id 
        WHERE quotation_id = '$quotation_id' 
        ORDER BY quotation_list_id 
        ";
        $data = [];
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }


    function insertQuotationList($data = []){
        $sql = " INSERT INTO tb_quotation_list (
            quotation_id,
            product_id,
            quotation_list_qty,
            quotation_list_price,
            quotation_list_sum,
            quotation_list_discount,
            quotation_list_discount_price, 
            quotation_list_discount_type,
            quotation_list_total, 
            quotation_list_remark,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['quotation_id']."',  
            '".$data['product_id']."',  
            '".$data['quotation_list_qty']."',  
            '".$data['quotation_list_price']."',  
            '".$data['quotation_list_sum']."', 
            '".$data['quotation_list_discount']."', 
            '".$data['quotation_list_discount_price']."', 
            '".$data['quotation_list_discount_type']."', 
            '".$data['quotation_list_total']."',  
            '".$data['quotation_list_remark']."', 
            '".$data['addby']."', 
            NOW(), 
            '".$data['updateby']."', 
            NOW() 
        ); 
        ";
        // echo "<pre>";
        // echo $sql;
        // echo "</pre>";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }

    function updateQuotationListById($data,$id){

        $sql = " UPDATE tb_quotation_list 
            SET product_id = '".$data['product_id']."', 
            quotation_list_qty = '".$data['quotation_list_qty']."',
            quotation_list_price = '".$data['quotation_list_price']."', 
            quotation_list_sum = '".$data['quotation_list_sum']."', 
            quotation_list_discount = '".$data['quotation_list_discount']."', 
            quotation_list_discount_price = '".$data['quotation_list_discount_price']."', 
            quotation_list_discount_type = '".$data['quotation_list_discount_type']."', 
            quotation_list_total = '".$data['quotation_list_total']."', 
            quotation_list_discount_price  = '".$data['quotation_list_discount_price']."', 
            quotation_list_remark = '".static::$db->real_escape_string($data['quotation_list_remark'])."' ,
            updateby = '".$data['updateby']."' ,
            lastupdate = NOW()             
            WHERE quotation_list_id = '$id'
        ";

// echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function updatePurchaseOrderId($quotation_list_id,$purchase_order_list_id){
        $sql = " UPDATE tb_quotation_list 
            SET purchase_order_list_id = '$purchase_order_list_id' 
            WHERE quotation_list_id = '$quotation_list_id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function deleteQuotationListByID($id){
        $sql = "DELETE FROM tb_quotation_list WHERE quotation_list_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteQuotationListByQuotationID($id){
        $sql = "DELETE FROM tb_quotation_list WHERE quotation_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteQuotationListByQuotationIDNotIN($id,$data){
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

        $sql = "DELETE FROM tb_quotation_list WHERE quotation_id = '$id' AND quotation_list_id NOT IN ($str) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>