<?php

require_once("BaseModel.php");
class CreditPurchasingModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getCreditPurchasingBy($date_start = "",$date_end = "",$supplier_id = "",$keyword = "",$user_id = ""){
        $str_supplier = "";
        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(credit_purchasing_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(credit_purchasing_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(credit_purchasing_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(credit_purchasing_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($supplier_id != ""){
            $str_supplier = "AND tb2.supplier_id = '$supplier_id' ";
        }


        
        $sql = "   SELECT tb.credit_purchasing_id,  
        purchase_order_code,
        credit_purchasing_date, 
        credit_purchasing_code,  
        credit_purchasing_net, 
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        IFNULL(CONCAT(tb2.supplier_name_th,' (',tb2.supplier_name_th,')' ) ,'-') as supplier_name, 
        credit_purchasing_remark  
        FROM tb_credit_purchasing as tb  
        LEFT JOIN tb_user as tb1 ON tb.addby = tb1.user_id  
        LEFT JOIN tb_supplier as tb2 ON tb.supplier_id = tb2.supplier_id  
        LEFT JOIN tb_credit_purchasing_list ON tb.credit_purchasing_id =  tb_credit_purchasing_list.credit_purchasing_id 
        LEFT JOIN tb_stock_group ON tb_credit_purchasing_list.stock_group_id =  tb_stock_group.stock_group_id 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%') 
            OR  credit_purchasing_remark LIKE ('%$keyword%') 
            OR  credit_purchasing_code LIKE ('%$keyword%') 
            OR  stock_group_name LIKE ('%$keyword%')
        ) 
        $str_supplier 
        $str_date 
        $str_user   
        GROUP BY tb.credit_purchasing_id 
        ORDER BY STR_TO_DATE(credit_purchasing_date,'%d-%m-%Y %H:%i:%s') , credit_purchasing_code DESC  
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

    function getCreditPurchasingByID($id){
        $sql = " SELECT * 
        FROM tb_credit_purchasing 
        WHERE credit_purchasing_id = '$id' 
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

    function getCreditPurchasingViewByID($id){
        $sql = " SELECT *   
        FROM tb_credit_purchasing 
        LEFT JOIN tb_user ON tb_credit_purchasing.addby = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_supplier ON tb_credit_purchasing.supplier_id = tb_supplier.supplier_id 
        WHERE credit_purchasing_id = '$id' 
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

    function getCreditPurchasingLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(RIGHT(credit_purchasing_code,3) AS SIGNED)),0) + 1,$digit,'0' )) AS  credit_purchasing_lastcode 
        FROM tb_credit_purchasing 
        WHERE credit_purchasing_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['credit_purchasing_lastcode'];
        }

    }

   
    function updateCreditPurchasingByID($id,$data = []){
        $sql = " UPDATE tb_credit_purchasing SET 
        supplier_id = '".$data['supplier_id']."', 
        credit_purchasing_code = '".$data['credit_purchasing_code']."', 
        credit_purchasing_date = '".$data['credit_purchasing_date']."', 
        purchase_order_id = '".$data['purchase_order_id']."', 
        credit_purchasing_credit_day = '".$data['credit_purchasing_credit_day']."', 
        credit_purchasing_credit_date = '".$data['credit_purchasing_credit_date']."', 
        credit_purchasing_delivery_by = '".$data['credit_purchasing_delivery_by']."', 
        credit_purchasing_vat_type = '".$data['credit_purchasing_vat_type']."', 
        credit_purchasing_total = '".$data['credit_purchasing_total']."', 
        credit_purchasing_discount = '".$data['credit_purchasing_discount']."', 
        credit_purchasing_discount_type = '".$data['credit_purchasing_discount_type']."', 
        credit_purchasing_vat = '".$data['credit_purchasing_vat']."', 
        credit_purchasing_vat_value = '".$data['credit_purchasing_vat_value']."', 
        credit_purchasing_net = '".$data['credit_purchasing_net']."', 
        credit_purchasing_remark = '".$data['credit_purchasing_remark']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE credit_purchasing_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    

    function insertCreditPurchasing($data = []){
        $sql = " INSERT INTO tb_credit_purchasing (
            supplier_id,
            credit_purchasing_code,
            credit_purchasing_date,
            purchase_order_id,
            credit_purchasing_credit_day,
            credit_purchasing_credit_date,
            credit_purchasing_delivery_by,
            credit_purchasing_vat_type,
            credit_purchasing_total,
            credit_purchasing_discount,
            credit_purchasing_discount_type,
            credit_purchasing_vat,
            credit_purchasing_vat_value,
            credit_purchasing_net,
            credit_purchasing_remark,
            addby,
            adddate
        ) VALUES ('".
        $data['supplier_id']."','".
        $data['credit_purchasing_code']."','".
        $data['credit_purchasing_date']."','".
        $data['purchase_order_id']."','".
        $data['credit_purchasing_credit_day']."','".
        $data['credit_purchasing_credit_date']."','".
        $data['credit_purchasing_delivery_by']."','".
        $data['credit_purchasing_vat_type']."','".
        $data['credit_purchasing_total']."','".
        $data['credit_purchasing_discount']."','".
        $data['credit_purchasing_discount_type']."','".
        $data['credit_purchasing_vat']."','".
        $data['credit_purchasing_vat_value']."','".
        $data['credit_purchasing_net']."','".
        $data['credit_purchasing_remark']."','".
        $data['addby']."',".
        "NOW()); 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }


    function deleteCreditPurchasingByID($id){
        $sql = " DELETE FROM tb_credit_purchasing WHERE credit_purchasing_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        $sql = " DELETE FROM tb_credit_purchasing_list WHERE credit_purchasing_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>