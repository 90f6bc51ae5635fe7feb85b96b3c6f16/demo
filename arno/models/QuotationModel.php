<?php

require_once("BaseModel.php");
class QuotationModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getQuotationBy($date_start = "",$date_end = "",$customer_id = "",$keyword = "",$user_id = "",$keywords=""){
        $str_customer = "";
        $str_date = "";
        $str_user = "";
        $sts_keywords = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(quotation_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(quotation_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(quotation_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(quotation_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND tb.employee_id = '$user_id' ";
        }

        if($customer_id != ""){
            $str_customer = "AND tb2.customer_id = '$customer_id' ";
        }

        if($keywords == ""){
            $sts_keyword = " CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%') 
            OR quotation_contact_name LIKE ('%$keyword%')
            OR  quotation_code LIKE ('%$keyword%') 
            OR  product_name LIKE ('%$keyword%') 
            OR  product_code LIKE ('%$keyword%')  ";
        }
          
        else{
            $sts_keywords = " (quotation_contact_name LIKE ('%$keywords%') 
            OR quotation_contact_tel LIKE ('%$keywords%') 
            OR quotation_contact_email LIKE ('%$keywords%')  ) ";
            
        }

        
        $sql = "   SELECT tb.quotation_id,  
        tb.employee_id,
        quotation_date,  
        quotation_rewrite_id, 
        IFNULL(( 
            SELECT COUNT(*) FROM tb_quotation WHERE quotation_rewrite_id = tb.quotation_id 
        ),0) as count_rewrite, 
        quotation_rewrite_no, 
        quotation_code,  
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        user_signature,
        user_name,
        user_lastname,
        quotation_total, 
        IFNULL( tb2.customer_name_en ,'-') as customer_name, 
        quotation_contact_name, 
        quotation_contact_tel, 
        quotation_contact_email, 
        quotation_cancelled, 
        quotation_remark  
        FROM tb_quotation as tb  
        LEFT JOIN tb_user as tb1 ON tb.employee_id = tb1.user_id  
        LEFT JOIN tb_customer as tb2 ON tb.customer_id = tb2.customer_id  
        LEFT JOIN tb_quotation_list ON tb.quotation_id =  tb_quotation_list.quotation_id 
        LEFT JOIN tb_product ON tb_quotation_list.product_id =  tb_product.product_id 
        WHERE (
            $sts_keyword 
            $sts_keywords 

            )
        
        $str_customer 
        $str_date 
        $str_user   
        
        GROUP BY tb.quotation_id  
        ORDER BY STR_TO_DATE(quotation_date,'%d-%m-%Y %H:%i:%s') , quotation_code DESC 
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
    function getQuotationByMobile($date_start = "",$date_end = "",$customer_id = "",$keyword = "",$user_id = ""){
        $str_customer = "";
        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(quotation_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(quotation_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(quotation_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(quotation_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND tb.employee_id = '$user_id' ";
        }

        if($customer_id != ""){
            $str_customer = "AND tb2.customer_id = '$customer_id' ";
        }


        
        $sql = "   SELECT tb.quotation_id,  
        tb.employee_id,
        quotation_date,  
        quotation_rewrite_id, 
        IFNULL(( 
            SELECT COUNT(*) FROM tb_quotation WHERE quotation_rewrite_id = tb.quotation_id 
        ),0) as count_rewrite, 
        quotation_rewrite_no, 
        quotation_code,  
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        quotation_total, 
        IFNULL(tb2.customer_name_en,'-') as customer_name, 
        quotation_contact_name, 
        quotation_cancelled, 
        quotation_remark  
        FROM tb_quotation as tb  
        LEFT JOIN tb_user as tb1 ON tb.employee_id = tb1.user_id  
        LEFT JOIN tb_customer as tb2 ON tb.customer_id = tb2.customer_id  
        LEFT JOIN tb_quotation_list ON tb.quotation_id =  tb_quotation_list.quotation_id 
        LEFT JOIN tb_product ON tb_quotation_list.product_id =  tb_product.product_id 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%') 
            OR  quotation_contact_name LIKE ('%$keyword%') 
            OR  quotation_code LIKE ('%$keyword%') 
            OR  product_name LIKE ('%$keyword%') 
            OR  product_code LIKE ('%$keyword%') 
            OR  tb2.customer_name_en LIKE ('%$keyword%') 
        ) 
        $str_customer 
        $str_date 
        $str_user   
        GROUP BY tb.quotation_id 
        ORDER BY STR_TO_DATE(quotation_date,'%d-%m-%Y %H:%i:%s') , quotation_code DESC  
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

    function getQuotationByID($id){
        $sql = " SELECT * 
        FROM tb_quotation 
        WHERE quotation_id = '$id' 
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
    function getQuotationContact(){
        $sql = " SELECT DISTINCT  quotation_contact_name,quotation_contact_tel,quotation_contact_email 
        FROM tb_quotation  
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
    function getQuotationContactBy($quotation_contact_name="",$keywords){
        $str_quotation_contact_name = "";
        if($quotation_contact_name != ""){
            $str_quotation_contact_name = "AND tb_quotation.quotation_contact_name = '$quotation_contact_name' ";
        }


        $sql = " SELECT  DISTINCT  quotation_contact_name,quotation_contact_tel,quotation_contact_email 
        FROM tb_quotation
        WHERE 1
            $str_quotation_contact_name
            
        GROUP BY  quotation_contact_name  
        ORDER BY quotation_contact_name,quotation_contact_tel,quotation_contact_email
        ";
            
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data =[];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

        }

    function getQuotationViewByID($id){
        $sql = " SELECT *   
        FROM tb_quotation 
        LEFT JOIN tb_user ON tb_quotation.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_customer ON tb_quotation.customer_id = tb_customer.customer_id 
        WHERE quotation_id = '$id' 
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

    function getQuotationLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(quotation_code,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  quotation_lastcode 
        FROM tb_quotation 
        WHERE quotation_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['quotation_lastcode'];
        }

    }

   
    function updateQuotationByID($id,$data = []){
        $sql = " UPDATE tb_quotation SET 
        customer_id = '".$data['customer_id']."', 
        employee_id = '".$data['employee_id']."', 
        quotation_code = '".$data['quotation_code']."', 
        quotation_date = '".$data['quotation_date']."', 
        quotation_contact_name = '".$data['quotation_contact_name']."', 
        quotation_contact_tel = '".$data['quotation_contact_tel']."', 
        quotation_contact_email = '".$data['quotation_contact_email']."', 
        quotation_total = '".$data['quotation_total']."', 
        quotation_vat = '".$data['quotation_vat']."', 
        quotation_vat_price = '".$data['quotation_vat_price']."', 
        quotation_vat_net = '".$data['quotation_vat_net']."', 
        quotation_remark = '".$data['quotation_remark']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE quotation_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function cancelQuotationByID($id){
        $sql = " UPDATE tb_quotation SET 
        quotation_cancelled = '1', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE quotation_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function uncancelQuotationByID($id){
        $sql = " UPDATE tb_quotation SET 
        quotation_cancelled = '0', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE quotation_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    

    function insertQuotation($data = []){
        $sql = " INSERT INTO tb_quotation (
            quotation_rewrite_id,
            quotation_rewrite_no,
            customer_id,
            employee_id,
            quotation_code,
            quotation_date,
            quotation_contact_name,
            quotation_contact_tel,
            quotation_contact_email,
            quotation_total,
            quotation_vat,
            quotation_vat_price,
            quotation_vat_net,
            quotation_remark,
            quotation_cancelled,
            addby,
            adddate
        ) VALUES ('".
        $data['quotation_rewrite_id']."','".
        $data['quotation_rewrite_no']."','".
        $data['customer_id']."','".
        $data['employee_id']."','".
        $data['quotation_code']."','".
        $data['quotation_date']."','".
        $data['quotation_contact_name']."','".
        $data['quotation_contact_tel']."','".
        $data['quotation_contact_email']."','".
        $data['quotation_total']."','".
        $data['quotation_vat']."','".
        $data['quotation_vat_price']."','".
        $data['quotation_vat_net']."','".
        $data['quotation_remark']."','".
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


    function deleteQuotationByID($id){
        $sql = " DELETE FROM tb_quotation WHERE quotation_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        $sql = " DELETE FROM tb_quotation_list WHERE quotation_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>