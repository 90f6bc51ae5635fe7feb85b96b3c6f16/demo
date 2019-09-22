<?php

require_once("BaseModel.php");
class RequestSpecialModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getRequestSpecialBy($date_start = "",$date_end = "",$keyword = "",$user_id = ""){

        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(request_special_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(request_special_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(request_special_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(request_special_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND tb.employee_id = '$user_id' ";
        }


        $sql = " SELECT request_special_id, 
        tb.employee_id,
        request_special_date, 
        request_special_rewrite_id, 
        supplier_name_th, supplier_name_en, 
        IFNULL((
            SELECT COUNT(*) FROM tb_request_special WHERE request_special_rewrite_id = tb.request_special_id 
        ),0) as count_rewrite,
        request_special_rewrite_no,
        request_special_code, 
        purchase_order_open, 
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as request_name, 
        request_special_accept_status, 
        IFNULL(CONCAT(tb2.user_name,' ',tb2.user_lastname),'-') as accept_name, 
        request_special_cancelled, 
        request_special_remark 
        FROM tb_request_special as tb 
        LEFT JOIN tb_user as tb1 ON tb.employee_id = tb1.user_id 
        LEFT JOIN tb_user as tb2 ON tb.request_special_accept_by = tb2.user_id  
        LEFT JOIN tb_supplier as tb4 ON tb.supplier_id = tb4.supplier_id 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%') 
            OR  CONCAT(tb2.user_name,' ',tb2.user_lastname) LIKE ('%$keyword%') 
            OR  request_special_code LIKE ('%$keyword%') 
            OR  supplier_name_en LIKE ('%$keyword%') 
        ) 
        $str_date 
        $str_user  
        ORDER BY STR_TO_DATE(request_special_date,'%d-%m-%Y %H:%i:%s') DESC 
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

    function getRequestSpecialByID($id){
        $sql = " SELECT * 
        FROM tb_request_special 
        WHERE request_special_id = '$id' 
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

    function getRequestSpecialViewByID($id){
        $sql = " SELECT *   
        FROM tb_request_special 
        LEFT JOIN tb_supplier ON tb_request_special.supplier_id = tb_supplier.supplier_id 
        LEFT JOIN tb_user ON tb_request_special.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id  
        
        WHERE request_special_id = '$id' 
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

    function getRequestSpecialLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(request_special_code,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  request_special_lastcode 
        FROM tb_request_special 
        WHERE request_special_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['request_special_lastcode'];
        }

    }

    function cancelRequestSpecialByID($id){
        $sql = " UPDATE tb_request_special SET 
        request_special_cancelled = '1', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE request_special_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function uncancelRequestSpecialByID($id){
        $sql = " UPDATE tb_request_special SET 
        request_special_cancelled = '0', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE request_special_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

   
    function updateRequestSpecialByID($id,$data = []){
        $sql = " UPDATE tb_request_special SET 
        request_special_code = '".$data['request_special_code']."',   
        supplier_id = '".$data['supplier_id']."', 
        employee_id = '".$data['employee_id']."', 
        request_special_date = '".$data['request_special_date']."', 
        request_special_accept_status = '".$data['request_special_accept_status']."',  
        request_special_remark = '".$data['request_special_remark']."', 
        purchase_order_open = '".$data['purchase_order_open']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE request_special_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function updateRequestSpecialAcceptByID($id,$data = []){
        $sql = " UPDATE tb_request_special SET 
        request_special_accept_status = '".$data['request_special_accept_status']."', 
        request_special_accept_by = '".$data['request_special_accept_by']."', 
        request_special_accept_date = NOW(), 
        request_special_status = '".$data['request_special_status']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE request_special_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    

    function insertRequestSpecial($data = []){
        $sql = " INSERT INTO tb_request_special (
            request_special_rewrite_id,
            request_special_rewrite_no,
            request_special_code,  
            supplier_id,
            employee_id,
            request_special_date,
            request_special_remark,
            purchase_order_open,
            request_special_accept_status,
            addby,
            adddate) 
        VALUES ('".
        $data['request_special_rewrite_id']."','".
        $data['request_special_rewrite_no']."','".
        $data['request_special_code']."','".  
        $data['supplier_id']."','".
        $data['employee_id']."','".
        $data['request_special_date']."','".
        $data['request_special_remark']."','".
        $data['purchase_order_open']."','".
        $data['request_special_accept_status']."','".
        $data['addby']."',".
        "NOW()); 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }


    function deleteRequestSpecialByID($id){
        $sql = " DELETE FROM tb_request_special WHERE request_special_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        $sql = " DELETE FROM tb_request_special_list WHERE request_special_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>