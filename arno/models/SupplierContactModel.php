<?php

require_once("BaseModel.php");
class SupplierContactModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getSupplierContactBy($supplier_id, $supplier_contact_name = '', $supplier_contact_position = '', $supplier_contact_tel = '', $supplier_contact_email = ''){
        $sql = " SELECT supplier_contact_id, 
        supplier_contact_name, 
        supplier_contact_position , 
        supplier_contact_tel, 
        supplier_contact_email , 
        supplier_contact_detail 
        FROM tb_supplier_contact 
        WHERE supplier_id = $supplier_id
        AND (supplier_contact_name LIKE ('%$supplier_contact_name%') 
        OR supplier_contact_position LIKE ('%$supplier_contact_position%') 
        OR supplier_contact_tel LIKE ('%$supplier_contact_tel%') 
        OR supplier_contact_email LIKE ('%$supplier_contact_email%') 
        )
        ORDER BY supplier_contact_name
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

    function getSupplierContactByID($id){
        $sql = " SELECT * 
        FROM tb_supplier_contact 
        WHERE supplier_contact_id = '$id' 
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

    function updateSupplierContactByID($id,$data = []){
        $sql = " UPDATE tb_supplier_contact SET 
        supplier_id = '".$data['supplier_id']."', 
        supplier_contact_name = '".$data['supplier_contact_name']."', 
        supplier_contact_position = '".$data['supplier_contact_position']."', 
        supplier_contact_tel = '".$data['supplier_contact_tel']."', 
        supplier_contact_email = '".$data['supplier_contact_email']."', 
        supplier_contact_detail = '".$data['supplier_contact_detail']."', 
        updateby = '".$data['updateby']."',  
        lastupdate = NOW() 
        WHERE supplier_contact_id = $id 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function insertSupplierContact($data = []){
        $sql = " INSERT INTO tb_supplier_contact (
            supplier_id,
            supplier_contact_name, 
            supplier_contact_position, 
            supplier_contact_tel, 
            supplier_contact_email, 
            supplier_contact_detail, 
            addby, 
            adddate 
        ) VALUES (
            '".$data['supplier_id']."', 
            '".$data['supplier_contact_name']."', 
            '".$data['supplier_contact_position']."', 
            '".$data['supplier_contact_tel']."', 
            '".$data['supplier_contact_email']."', 
            '".$data['supplier_contact_detail']."', 
            '".$data['addby']."', 
            NOW()  
        ); 
        ";

        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }


    function deleteSupplierContactByID($id){
        $sql = "DELETE FROM tb_supplier_contact WHERE supplier_contact_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }
    
}
?>