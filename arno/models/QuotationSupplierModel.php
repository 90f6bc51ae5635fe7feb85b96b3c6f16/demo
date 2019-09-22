<?php

require_once("BaseModel.php");
class QuotationSupplierModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getQuotationSupplierBy($date_start = "",$date_end = "",$supplier_id = "",$keyword = "",$user_id = ""){
        $str_supplier = "";
        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(quotation_supplier_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(quotation_supplier_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(quotation_supplier_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(quotation_supplier_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND tb.employee_id = '$user_id' ";
        }

        if($supplier_id != ""){
            $str_supplier = "AND tb2.supplier_id = '$supplier_id' ";
        }


        
        $sql = "   SELECT tb.quotation_supplier_id,  
        tb.employee_id,
        quotation_supplier_date,   
        quotation_supplier_name,  
        quotation_supplier_tax,  
        quotation_supplier_address,  
        quotation_supplier_branch,  
        quotation_supplier_rewrite_id, 
        IFNULL(( 
            SELECT COUNT(*) FROM tb_quotation_supplier WHERE quotation_supplier_rewrite_id = tb.quotation_supplier_id 
        ),0) as count_rewrite, 
        quotation_supplier_rewrite_no, 
        quotation_supplier_code,  
        quotation_supplier_code_gen,  
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        quotation_supplier_total, 
        IFNULL(tb2.supplier_name_en ,'-') as supplier_name, 
        quotation_supplier_contact_name, 
        quotation_supplier_cancelled, 
        quotation_supplier_remark,  
        quotation_supplier_file    
        FROM tb_quotation_supplier as tb  
        LEFT JOIN tb_user as tb1 ON tb.employee_id = tb1.user_id  
        LEFT JOIN tb_supplier as tb2 ON tb.supplier_id = tb2.supplier_id  
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%') 
            OR  quotation_supplier_contact_name LIKE ('%$keyword%') 
            OR  quotation_supplier_code LIKE ('%$keyword%')  
        ) 
        $str_supplier 
        $str_date 
        $str_user   
        GROUP BY tb.quotation_supplier_id 
        ORDER BY STR_TO_DATE(quotation_supplier_date,'%d-%m-%Y %H:%i:%s') , quotation_supplier_code DESC  
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
    function getQuotationSupplierByMobile($date_start = "",$date_end = "",$supplier_id = "",$keyword = "",$user_id = ""){
        $str_supplier = "";
        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(quotation_supplier_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(quotation_supplier_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(quotation_supplier_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(quotation_supplier_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND tb.employee_id = '$user_id' ";
        }

        if($supplier_id != ""){
            $str_supplier = "AND tb2.supplier_id = '$supplier_id' ";
        }


        
        $sql = "   SELECT tb.quotation_supplier_id,  
        tb.employee_id,
        quotation_supplier_date,  
        quotation_supplier_name,  
        quotation_supplier_tax,  
        quotation_supplier_address,  
        quotation_supplier_branch,  
        quotation_supplier_rewrite_id,  
        IFNULL(( 
            SELECT COUNT(*) FROM tb_quotation_supplier WHERE quotation_supplier_rewrite_id = tb.quotation_supplier_id 
        ),0) as count_rewrite, 
        quotation_supplier_rewrite_no, 
        quotation_supplier_code,   
        quotation_supplier_code_gen,  
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        quotation_supplier_total, 
        IFNULL(tb2.supplier_name_en,'-') as supplier_name, 
        quotation_supplier_contact_name, 
        quotation_supplier_cancelled, 
        quotation_supplier_remark,  
        quotation_supplier_file  
        FROM tb_quotation_supplier as tb  
        LEFT JOIN tb_user as tb1 ON tb.employee_id = tb1.user_id  
        LEFT JOIN tb_supplier as tb2 ON tb.supplier_id = tb2.supplier_id   
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%') 
            OR  quotation_supplier_contact_name LIKE ('%$keyword%') 
            OR  quotation_supplier_code LIKE ('%$keyword%')  
            OR  tb2.supplier_name_en LIKE ('%$keyword%')  
        ) 
        $str_supplier 
        $str_date 
        $str_user   
        GROUP BY tb.quotation_supplier_id 
        ORDER BY STR_TO_DATE(quotation_supplier_date,'%d-%m-%Y %H:%i:%s') , quotation_supplier_code DESC  
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

    function getQuotationSupplierByAutoComplete($keyword){
        $sql = " SELECT 
        quotation_supplier_code_gen,
        quotation_supplier_code,
        quotation_supplier_date
        FROM tb_quotation_supplier   
        WHERE quotation_supplier_code LIKE ('%$keyword%')
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data=[];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getQuotationSupplierByID($id){
        $sql = " SELECT *   
        FROM tb_quotation_supplier 
        LEFT JOIN tb_user ON tb_quotation_supplier.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_supplier ON tb_quotation_supplier.supplier_id = tb_supplier.supplier_id 
        WHERE quotation_supplier_id = '$id' 
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

    function getQuotationSupplierByCodeGen($code){
        $sql = " SELECT * 
        FROM tb_quotation_supplier 
        WHERE quotation_supplier_code_gen = '$code' 
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

    function getQuotationSupplierViewByID($id){
        $sql = " SELECT *   
        FROM tb_quotation_supplier 
        LEFT JOIN tb_user ON tb_quotation_supplier.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_supplier ON tb_quotation_supplier.supplier_id = tb_supplier.supplier_id 
        WHERE quotation_supplier_id = '$id' 
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

    function getQuotationSupplierLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(quotation_supplier_code,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  quotation_supplier_lastcode 
        FROM tb_quotation_supplier 
        WHERE quotation_supplier_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['quotation_supplier_lastcode'];
        }

    }

   
    function updateQuotationSupplierByID($id,$data = []){
        $sql = " UPDATE tb_quotation_supplier SET 
        supplier_id = '".$data['supplier_id']."', 
        employee_id = '".$data['employee_id']."', 
        quotation_supplier_code = '".$data['quotation_supplier_code']."', 
        quotation_supplier_code_gen = '".$data['quotation_supplier_code_gen']."', 
        quotation_supplier_date = '".$data['quotation_supplier_date']."', 
        quotation_supplier_name = '".$data['quotation_supplier_name']."', 
        quotation_supplier_tax = '".$data['quotation_supplier_tax']."', 
        quotation_supplier_address = '".$data['quotation_supplier_address']."', 
        quotation_supplier_branch = '".$data['quotation_supplier_branch']."', 
        quotation_supplier_contact_name = '".$data['quotation_supplier_contact_name']."', 
        quotation_supplier_contact_tel = '".$data['quotation_supplier_contact_tel']."', 
        quotation_supplier_contact_email = '".$data['quotation_supplier_contact_email']."', 
        quotation_supplier_total = '".$data['quotation_supplier_total']."', 
        quotation_supplier_vat = '".$data['quotation_supplier_vat']."', 
        quotation_supplier_vat_price = '".$data['quotation_supplier_vat_price']."', 
        quotation_supplier_vat_net = '".$data['quotation_supplier_vat_net']."', 
        quotation_supplier_remark = '".$data['quotation_supplier_remark']."', 
        quotation_supplier_file = '".$data['quotation_supplier_file']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE quotation_supplier_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function cancelQuotationSupplierByID($id){
        $sql = " UPDATE tb_quotation_supplier SET 
        quotation_supplier_cancelled = '1', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE quotation_supplier_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function uncancelQuotationSupplierByID($id){
        $sql = " UPDATE tb_quotation_supplier SET 
        quotation_supplier_cancelled = '0', 
        updateby = '".$data['updateby']."', 
        lastupdate =  NOW()  
        WHERE quotation_supplier_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    

    function insertQuotationSupplier($data = []){
        $sql = " INSERT INTO tb_quotation_supplier (
            quotation_supplier_rewrite_id,
            quotation_supplier_rewrite_no,
            supplier_id,
            employee_id,
            quotation_supplier_code,
            quotation_supplier_code_gen,
            quotation_supplier_date,
            quotation_supplier_name,
            quotation_supplier_tax,
            quotation_supplier_address,
            quotation_supplier_branch,
            quotation_supplier_contact_name,
            quotation_supplier_contact_tel,
            quotation_supplier_contact_email,
            quotation_supplier_total,
            quotation_supplier_vat,
            quotation_supplier_vat_price,
            quotation_supplier_vat_net,
            quotation_supplier_remark,
            quotation_supplier_file,
            quotation_supplier_cancelled,
            addby,
            adddate
        ) VALUES ('".
        $data['quotation_supplier_rewrite_id']."','".
        $data['quotation_supplier_rewrite_no']."','".
        $data['supplier_id']."','".
        $data['employee_id']."','".
        $data['quotation_supplier_code']."','".
        $data['quotation_supplier_code_gen']."','".
        $data['quotation_supplier_date']."','".
        $data['quotation_supplier_name']."','".
        $data['quotation_supplier_tax']."','".
        $data['quotation_supplier_address']."','".
        $data['quotation_supplier_branch']."','".
        $data['quotation_supplier_contact_name']."','".
        $data['quotation_supplier_contact_tel']."','".
        $data['quotation_supplier_contact_email']."','".
        $data['quotation_supplier_total']."','".
        $data['quotation_supplier_vat']."','".
        $data['quotation_supplier_vat_price']."','".
        $data['quotation_supplier_vat_net']."','".
        $data['quotation_supplier_remark']."','".
        $data['quotation_supplier_file']."','".
        "0','".
        $data['addby']."',".
        "NOW()); 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }


    function deleteQuotationSupplierByID($id){

        
        $sql = "DELETE FROM tb_quotation_supplier_list WHERE quotation_supplier_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        
        $sql = " DELETE FROM tb_quotation_supplier WHERE quotation_supplier_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>