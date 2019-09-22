<?php

require_once("BaseModel.php");
class RequestStandardModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getRequestStandardBy($date_start = "",$date_end = "",$keyword = "",$user_id = ""){

        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(request_standard_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(request_standard_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(request_standard_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(request_standard_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND tb.employee_id = '$user_id' ";
        }


        $sql = " SELECT request_standard_id, 
        tb.employee_id , 
        request_standard_date, 
        request_standard_rewrite_id, 
        supplier_name_th, supplier_name_en, 
        IFNULL((
            SELECT COUNT(*) FROM tb_request_standard WHERE request_standard_rewrite_id = tb.request_standard_id 
        ),0) as count_rewrite,
        request_standard_rewrite_no,
        request_standard_code, 
        purchase_order_open, 
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as request_name, 
        request_standard_accept_status, 
        IFNULL(CONCAT(tb2.user_name,' ',tb2.user_lastname),'-') as accept_name, 
        request_standard_cancelled, 
        request_standard_remark 
        FROM tb_request_standard as tb 
        LEFT JOIN tb_user as tb1 ON tb.employee_id = tb1.user_id 
        LEFT JOIN tb_user as tb2 ON tb.request_standard_accept_by = tb2.user_id  
        LEFT JOIN tb_supplier as tb4 ON tb.supplier_id = tb4.supplier_id 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%') 
            OR  CONCAT(tb2.user_name,' ',tb2.user_lastname) LIKE ('%$keyword%') 
            OR  request_standard_code LIKE ('%$keyword%') 
            OR  supplier_name_en LIKE ('%$keyword%') 
        ) 
        $str_date 
        $str_user  
        ORDER BY STR_TO_DATE(request_standard_date,'%d-%m-%Y %H:%i:%s') DESC 
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

    function getRequestStandardByID($id){
        $sql = " SELECT * 
        FROM tb_request_standard 
        WHERE request_standard_id = '$id' 
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

    function getRequestStandardViewByID($id){
        $sql = " SELECT *   
        FROM tb_request_standard 
        LEFT JOIN tb_user ON tb_request_standard.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id  
        LEFT JOIN tb_supplier ON tb_request_standard.supplier_id = tb_supplier.supplier_id 
        WHERE request_standard_id = '$id' 
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

    function getRequestStandardLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(request_standard_code,".(strlen($id)+1).",'$digit') AS SIGNED)),0) + 1,'$digit','0' )) AS  request_standard_lastcode 
        FROM tb_request_standard 
        WHERE request_standard_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['request_standard_lastcode'];
        }

    }

    function cancelRequestStandardByID($id){
        $sql = " UPDATE tb_request_standard SET 
        request_standard_cancelled = '1', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE request_standard_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function uncancelRequestStandardByID($id){
        $sql = " UPDATE tb_request_standard SET 
        request_standard_cancelled = '0', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE request_standard_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

   
    function updateRequestStandardByID($id,$data = []){
        $sql = " UPDATE tb_request_standard SET 
        request_standard_code = '".$data['request_standard_code']."',   
        supplier_id = '".$data['supplier_id']."', 
        employee_id = '".$data['employee_id']."', 
        request_standard_date = '".$data['request_standard_date']."', 
        request_standard_accept_status = '".$data['request_standard_accept_status']."', 
        request_standard_remark = '".$data['request_standard_remark']."', 
        purchase_order_open = '".$data['purchase_order_open']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE request_standard_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function updateRequestStandardAcceptByID($id,$data = []){
        $sql = " UPDATE tb_request_standard SET 
        request_standard_accept_status = '".$data['request_standard_accept_status']."', 
        request_standard_accept_by = '".$data['request_standard_accept_by']."', 
        request_standard_accept_date = NOW(), 
        request_standard_status = '".$data['request_standard_status']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE request_standard_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }
    function insertRequestStandard($data = []){
        $sql = " INSERT INTO tb_request_standard (
            request_standard_rewrite_id,
            request_standard_rewrite_no,
            request_standard_code,  
            supplier_id,
            employee_id,
            request_standard_date,
            request_standard_remark,
            purchase_order_open,
            request_standard_accept_status,
            addby,
            adddate) 
        VALUES ('".
        $data['request_standard_rewrite_id']."','".
        $data['request_standard_rewrite_no']."','".
        $data['request_standard_code']."','".  
        $data['supplier_id']."','".
        $data['employee_id']."','".
        $data['request_standard_date']."','".
        $data['request_standard_remark']."','".
        $data['purchase_order_open']."','".
        $data['request_standard_accept_status']."','".
        $data['addby']."',".
        "NOW()); 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }


    function deleteRequestStandardByID($id){
        $sql = " DELETE FROM tb_request_standard WHERE request_standard_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        $sql = " DELETE FROM tb_request_standard_list WHERE request_standard_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>