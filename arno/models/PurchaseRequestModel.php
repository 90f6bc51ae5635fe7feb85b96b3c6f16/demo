<?php

require_once("BaseModel.php");
class PurchaseRequestModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getPurchaseRequestBy($date_start = "",$date_end = "",$keyword = "",$user_id = ""){

        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(purchase_request_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(purchase_request_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(purchase_request_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(purchase_request_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND tb.employee_id  = '$user_id' ";
        }


        $sql = " SELECT purchase_request_id, 
        tb.employee_id , 
        purchase_request_date, 
        purchase_request_rewrite_id,
        IFNULL((
            SELECT COUNT(*) FROM tb_purchase_request WHERE purchase_request_rewrite_id = tb.purchase_request_id 
        ),0) as count_rewrite,
        purchase_request_rewrite_no,
        purchase_request_code, 
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as request_name, 
        purchase_request_type, 
        purchase_request_accept_status, 
        IFNULL(CONCAT(tb2.user_name,' ',tb2.user_lastname),'-') as accept_name, 
        purchase_request_cancelled, 
        purchase_request_total, 
        purchase_request_remark 
        FROM tb_purchase_request as tb 
        LEFT JOIN tb_user as tb1 ON tb.employee_id = tb1.user_id 
        LEFT JOIN tb_user as tb2 ON tb.purchase_request_accept_by = tb2.user_id 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%') 
            OR  CONCAT(tb2.user_name,' ',tb2.user_lastname) LIKE ('%$keyword%') 
            OR  purchase_request_code LIKE ('%$keyword%') 
        ) 
        $str_date 
        $str_user  
        ORDER BY purchase_request_code DESC 
         ";
        // echo $sql;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }

    
    function getPurchaseRequestByStatus($approve_status = [], $date_start = '', $date_end = '', $keyword = ''){

        $str_date = ""; 
        $str_apporved = '';

        if(is_array($approve_status)){ 
            for($i=0; $i < count($approve_status) ;$i++){
                $str_apporved .= " '".$approve_status[$i]."' ";
                if($i + 1 < count($approve_status)){
                    $str_apporved .= ",";
                }
            }
        }else if ($data != ''){
            $str_apporved = " '".$approve_status."' ";
        }else{
            $str_apporved="''";
        }


        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(purchase_request_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(purchase_request_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(purchase_request_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(purchase_request_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        $sql =  "   SELECT purchase_request_id, 
                    tb.employee_id , 
                    purchase_request_date, 
                    purchase_request_rewrite_id,
                    IFNULL((
                        SELECT COUNT(*) FROM tb_purchase_request WHERE purchase_request_rewrite_id = tb.purchase_request_id 
                    ),0) as count_rewrite,
                    purchase_request_rewrite_no,
                    purchase_request_code, 
                    IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as request_name, 
                    purchase_request_type, 
                    purchase_request_accept_status, 
                    IFNULL(CONCAT(tb2.user_name,' ',tb2.user_lastname),'-') as accept_name, 
                    purchase_request_cancelled, 
                    purchase_request_total, 
                    purchase_request_remark 
                    FROM tb_purchase_request as tb 
                    LEFT JOIN tb_user as tb1 ON tb.employee_id = tb1.user_id 
                    LEFT JOIN tb_user as tb2 ON tb.purchase_request_accept_by = tb2.user_id 
                    WHERE purchase_request_accept_status IN ($str_apporved)  
                    AND ( 
                        CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%') 
                        OR  CONCAT(tb2.user_name,' ',tb2.user_lastname) LIKE ('%$keyword%') 
                        OR  purchase_request_code LIKE ('%$keyword%') 
                    ) 
                    $str_date  
                    ORDER BY STR_TO_DATE(purchase_request_date,'%d-%m-%Y %H:%i:%s') DESC  
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


    function getPurchaseRequestByNoApproved($date_start = "",$date_end = "",$keyword = "",$user_id = ""){
        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(purchase_request_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(purchase_request_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(purchase_request_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(purchase_request_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND tb.employee_id  = '$user_id' ";
        }


        $sql = " SELECT purchase_request_id, 
        tb.employee_id , 
        purchase_request_date, 
        purchase_request_rewrite_id,
        IFNULL((
            SELECT COUNT(*) FROM tb_purchase_request WHERE purchase_request_rewrite_id = tb.purchase_request_id 
        ),0) as count_rewrite,
        purchase_request_rewrite_no,
        purchase_request_code, 
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as request_name, 
        purchase_request_type, 
        purchase_request_accept_status, 
        IFNULL(CONCAT(tb2.user_name,' ',tb2.user_lastname),'-') as accept_name, 
        purchase_request_cancelled, 
        purchase_request_total, 
        purchase_request_remark 
        FROM tb_purchase_request as tb 
        LEFT JOIN tb_user as tb1 ON tb.employee_id = tb1.user_id 
        LEFT JOIN tb_user as tb2 ON tb.purchase_request_accept_by = tb2.user_id 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%') 
            OR  CONCAT(tb2.user_name,' ',tb2.user_lastname) LIKE ('%$keyword%') 
            OR  purchase_request_code LIKE ('%$keyword%') 
        ) 
        AND purchase_request_status NOT LIKE ('Approved') 
        $str_date 
        $str_user  
        ORDER BY STR_TO_DATE(purchase_request_date,'%d-%m-%Y %H:%i:%s') DESC 
         ";
        // echo $sql;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getPurchaseOrderByPurchaseRequestId($purchase_request_id){

        $sql =  "   SELECT tb_purchase_order.purchase_order_id,purchase_order_code
                    FROM  tb_purchase_request_list
                    LEFT JOIN tb_purchase_order_list ON tb_purchase_request_list.purchase_order_list_id = tb_purchase_order_list.purchase_order_list_id    
                    LEFT JOIN tb_purchase_order ON tb_purchase_order_list.purchase_order_id = tb_purchase_order.purchase_order_id
                    WHERE purchase_request_id = '$purchase_request_id' 
                    GROUP BY tb_purchase_order_list.purchase_order_id
                ";
         //echo $sql;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }


    function getInvoiceSuppliertByPurchaseRequestId($purchase_request_id){

        $sql =  "   SELECT tb_invoice_supplier_list.invoice_supplier_id,invoice_supplier_code_gen
                    FROM tb_purchase_request_list 
                    LEFT JOIN tb_purchase_order_list ON tb_purchase_request_list.purchase_order_list_id = tb_purchase_order_list.purchase_order_list_id
                    LEFT JOIN tb_invoice_supplier_list ON tb_purchase_order_list.purchase_order_list_id = tb_invoice_supplier_list.purchase_order_list_id
                    LEFT JOIN tb_invoice_supplier ON tb_invoice_supplier_list.invoice_supplier_id = tb_invoice_supplier.invoice_supplier_id
                    WHERE purchase_request_id = '$purchase_request_id' 
                    GROUP BY invoice_supplier_code_gen
                
                ";
        //   echo $sql;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }


    function getPurchaseOrderByPurchaseRequestListId($purchase_request_list_id){

        $sql =  "   SELECT tb_purchase_order.purchase_order_id,purchase_order_code
                    FROM  tb_purchase_request_list
                    LEFT JOIN tb_purchase_order_list ON tb_purchase_request_list.purchase_order_list_id = tb_purchase_order_list.purchase_order_list_id    
                    LEFT JOIN tb_purchase_order ON tb_purchase_order_list.purchase_order_id = tb_purchase_order.purchase_order_id
                    WHERE purchase_request_list_id = '$purchase_request_list_id' 
                    GROUP BY tb_purchase_order_list.purchase_order_id
                ";
        //  echo $sql;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getInvoiceSuppliertByPurchaseRequestListId($purchase_request_list_id){

        $sql =  "   SELECT tb_invoice_supplier_list.invoice_supplier_id,invoice_supplier_code_gen
                    FROM tb_purchase_request_list 
                    LEFT JOIN tb_purchase_order_list ON tb_purchase_request_list.purchase_order_list_id = tb_purchase_order_list.purchase_order_list_id
                    LEFT JOIN tb_invoice_supplier_list ON tb_purchase_order_list.purchase_order_list_id = tb_invoice_supplier_list.purchase_order_list_id
                    LEFT JOIN tb_invoice_supplier ON tb_invoice_supplier_list.invoice_supplier_id = tb_invoice_supplier.invoice_supplier_id
                    WHERE purchase_request_list_id = '$purchase_request_list_id' 
                    GROUP BY invoice_supplier_code_gen
                
                ";
        //   echo $sql;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }


    //////////////////////////////////////////////////////////////////////////////////////////
    function getPurchaseRequestLitsBy($date_start = "",$date_end = "",$keyword = "",$user_id = ""){

        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(purchase_request_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(purchase_request_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(purchase_request_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(purchase_request_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND tb.employee_id  = '$user_id' ";
        }


            $sql =  "   SELECT tb_purchase_request.purchase_request_id,purchase_request_list_id,purchase_request_date,purchase_request_code,purchase_request_remark,purchase_request_list_qty,product_name,product_code,purchase_order_code,invoice_supplier_code_gen
                        FROM tb_purchase_request                    
                        LEFT JOIN tb_purchase_request_list ON tb_purchase_request.purchase_request_id = tb_purchase_request_list.purchase_request_id
                        LEFT JOIN tb_product ON tb_purchase_request_list.product_id = tb_product.product_id
                        LEFT JOIN tb_purchase_order_list ON tb_purchase_request_list.purchase_order_list_id = tb_purchase_order_list.purchase_order_list_id
                        LEFT JOIN tb_purchase_order ON tb_purchase_order_list.purchase_order_id = tb_purchase_order.purchase_order_id
                        LEFT JOIN tb_invoice_supplier_list ON tb_purchase_order_list.purchase_order_list_id = tb_invoice_supplier_list.purchase_order_list_id
                        LEFT JOIN tb_invoice_supplier ON tb_invoice_supplier_list.invoice_supplier_id = tb_invoice_supplier.invoice_supplier_id
                    
                    ";
        // echo $sql;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }
    ///////////////////////////////////////////////////////////////////////////////////////////


    function getPurchaseRequestByID($id){
        $sql = " SELECT * 
        FROM tb_purchase_request 
        WHERE purchase_request_id = '$id' 
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


    function getPurchaseRequestByCode($code){
        $sql = " SELECT * 
        FROM tb_purchase_request 
        WHERE purchase_request_code = '$code' 
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
    function getPurchaseRequestByAutoComplete($keyword){
        $sql = " SELECT purchase_request_code,purchase_request_date 
        FROM tb_purchase_request  
        WHERE purchase_request_code LIKE ('%$keyword%') 
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


    function getPurchaseRequestViewByID($id){
        $sql = " SELECT * 
        FROM tb_purchase_request 
        LEFT JOIN tb_user ON tb_purchase_request.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_customer ON tb_purchase_request.customer_id = tb_customer.customer_id  
        WHERE purchase_request_id = '$id' 
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

    function getPurchaseRequestLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(purchase_request_code,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  purchase_request_lastcode 
        FROM tb_purchase_request 
        WHERE purchase_request_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['purchase_request_lastcode'];
        }

    }

    function cancelPurchaseRequestByID($id){
        $sql = " UPDATE tb_purchase_request SET 
        purchase_request_cancelled = '1', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE purchase_request_id = '$id' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function uncancelPurchaseRequestByID($id){
        $sql = " UPDATE tb_purchase_request SET 
        purchase_request_cancelled = '0', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE purchase_request_id = '$id' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

   
    function updatePurchaseRequestByID($id,$data = []){
        $sql = " UPDATE tb_purchase_request SET  
        purchase_request_alert = '".$data['purchase_request_alert']."', 
        purchase_request_code = '".$data['purchase_request_code']."', 
        purchase_request_type = '".$data['purchase_request_type']."', 
        customer_id = '".$data['customer_id']."',  
        employee_id = '".$data['employee_id']."', 
        purchase_request_total = '".$data['purchase_request_total']."', 
        purchase_request_date = '".$data['purchase_request_date']."', 
        purchase_request_accept_status = '".$data['purchase_request_accept_status']."', 
        purchase_request_remark = '".static::$db->real_escape_string($data['purchase_request_remark'])."', 
        updateby = '".$data['updateby']."', 
        lastupdate =  NOW() 
        WHERE purchase_request_id = '$id' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function updatePurchaseRequestAcceptByID($id,$data = []){
        $sql = " UPDATE tb_purchase_request SET 
        purchase_request_accept_status = '".$data['purchase_request_accept_status']."', 
        purchase_request_accept_by = '".$data['purchase_request_accept_by']."', 
        purchase_request_accept_date = NOW(), 
        purchase_request_status = '".$data['purchase_request_status']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE purchase_request_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    

    function insertPurchaseRequest($data = []){
        $sql = " INSERT INTO tb_purchase_request ( 
            purchase_request_rewrite_id,
            purchase_request_rewrite_no,
            purchase_request_alert,
            purchase_request_code,
            purchase_request_type,
            customer_id, 
            employee_id,
            purchase_request_total,
            purchase_request_date,
            purchase_request_remark,
            purchase_request_accept_status,
            addby,
            adddate) 
        VALUES ('". 
        $data['purchase_request_rewrite_id']."','".
        $data['purchase_request_rewrite_no']."','".
        $data['purchase_request_alert']."','".
        $data['purchase_request_code']."','".
        $data['purchase_request_type']."','".
        $data['customer_id']."','". 
        $data['employee_id']."','".
        $data['purchase_request_total']."','".
        $data['purchase_request_date']."','".
        static::$db->real_escape_string($data['purchase_request_remark'])."','".
        $data['purchase_request_accept_status']."','".
        $data['addby']."',".
        "NOW()); 
        ";

        //echo $sql;

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return '';
        }

    }


    function deletePurchaseRequestByID($id){
        $sql = " DELETE FROM tb_purchase_request WHERE purchase_request_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        $sql = " DELETE FROM tb_purchase_request_list WHERE purchase_request_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>