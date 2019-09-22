<?php

require_once("BaseModel.php");
class DeliveryNoteSupplierSendModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getDeliveryNoteSupplierSendBy($date_start = "",$date_end = "",$supplier_id = "",$keyword = "",$user_id = ""){

        $str_supplier = "";
        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(delivery_note_supplier_send_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(delivery_note_supplier_send_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(delivery_note_supplier_send_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(delivery_note_supplier_send_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND tb_delivery_note_supplier_send.employee_id = '$user_id' ";
        }

        if($supplier_id != ""){
            $str_supplier = "AND tb2.supplier_id = '$supplier_id' ";
        }


        $sql = " SELECT delivery_note_supplier_send_id, 
        tb_delivery_note_supplier_send.employee_id ,
        delivery_note_supplier_send_code, 
        delivery_note_supplier_send_date, 
        delivery_note_supplier_send_file,
        contact_name,
        delivery_note_supplier_send_remark,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        tb_delivery_note_supplier_send.supplier_id,
        supplier_name_en as supplier_name 
        FROM tb_delivery_note_supplier_send 
        LEFT JOIN tb_user as tb1 ON tb_delivery_note_supplier_send.employee_id = tb1.user_id 
        LEFT JOIN tb_supplier as tb2 ON tb_delivery_note_supplier_send.supplier_id = tb2.supplier_id 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%') 
            OR  contact_name LIKE ('%$keyword%') 
            OR  delivery_note_supplier_send_code LIKE ('%$keyword%') 
            OR  supplier_name_en LIKE ('%$keyword%') 
        ) 
        $str_supplier 
        $str_date 
        $str_user  
        ORDER BY STR_TO_DATE(delivery_note_supplier_send_date,'%d-%m-%Y %H:%i:%s'), delivery_note_supplier_send_code DESC 
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

    function getDeliveryNoteSupplierSendByID($id){
        $sql = " SELECT * 
        FROM tb_delivery_note_supplier_send 
        LEFT JOIN tb_supplier ON tb_delivery_note_supplier_send.supplier_id = tb_supplier.supplier_id 
        LEFT JOIN tb_user ON tb_delivery_note_supplier_send.employee_id = tb_user.user_id 
        WHERE delivery_note_supplier_send_id = '$id' 
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

    function getDeliveryNoteSupplierSendViewByID($id){
        $sql = " SELECT *   
        FROM tb_delivery_note_supplier_send 
        LEFT JOIN tb_user ON tb_delivery_note_supplier_send.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_supplier ON tb_delivery_note_supplier_send.supplier_id = tb_supplier.supplier_id 
        WHERE delivery_note_supplier_send_id = '$id' 
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

    function getDeliveryNoteSupplierSendLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(delivery_note_supplier_send_code,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  delivery_note_supplier_send_lastcode 
        FROM tb_delivery_note_supplier_send 
        WHERE delivery_note_supplier_send_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['delivery_note_supplier_send_lastcode'];
        }

    }

   
    function updateDeliveryNoteSupplierSendByID($id,$data = []){
        $sql = " UPDATE tb_delivery_note_supplier_send SET 
        supplier_id = '".static::$db->real_escape_string($data['supplier_id'])."', 
        employee_id = '".static::$db->real_escape_string($data['employee_id'])."', 
        delivery_note_supplier_send_date = '".static::$db->real_escape_string($data['delivery_note_supplier_send_date'])."', 
        delivery_note_supplier_send_remark = '".static::$db->real_escape_string($data['delivery_note_supplier_send_remark'])."', 
        delivery_note_supplier_send_file = '".static::$db->real_escape_string($data['delivery_note_supplier_send_file'])."', 
        employee_signature = '".static::$db->real_escape_string($data['employee_signature'])."', 
        contact_name = '".static::$db->real_escape_string($data['contact_name'])."', 
        contact_signature = '".static::$db->real_escape_string($data['contact_signature'])."', 
        updateby = '".static::$db->real_escape_string($data['updateby'])."', 
        lastupdate = NOW()
        WHERE delivery_note_supplier_send_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }



    function insertDeliveryNoteSupplierSend($data = []){
        $sql = " INSERT INTO tb_delivery_note_supplier_send (
            supplier_id,
            employee_id,
            delivery_note_supplier_send_code,
            delivery_note_supplier_send_date,
            delivery_note_supplier_send_remark,
            delivery_note_supplier_send_file,
            employee_signature,
            contact_name,
            contact_signature,
            addby,
            adddate
            ) 
        VALUES ('".
        static::$db->real_escape_string($data['supplier_id'])."','".
        static::$db->real_escape_string($data['employee_id'])."','".
        static::$db->real_escape_string($data['delivery_note_supplier_send_code'])."','".
        static::$db->real_escape_string($data['delivery_note_supplier_send_date'])."','".
        static::$db->real_escape_string($data['delivery_note_supplier_send_remark'])."','".
        static::$db->real_escape_string($data['delivery_note_supplier_send_file'])."','".
        static::$db->real_escape_string($data['employee_signature'])."','".
        static::$db->real_escape_string($data['contact_name'])."','".
        static::$db->real_escape_string($data['contact_signature'])."','".
        static::$db->real_escape_string($data['addby'])."',".
        " NOW()
            ); 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }



    function deleteDeliveryNoteSupplierSendByID($id){
        $sql = " DELETE FROM tb_delivery_note_supplier_send WHERE delivery_note_supplier_send_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        $sql = " DELETE FROM tb_delivery_note_supplier_send_list WHERE delivery_note_supplier_send_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }


}
?>