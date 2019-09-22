<?php

require_once("BaseModel.php");
class CustomerContactModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getcustomerContactBy($customer_id, $customer_contact_name = '', $customer_contact_position = '', $customer_contact_tel = '', $customer_contact_email = ''){
        $sql = " SELECT customer_contact_id, 
        customer_contact_name, 
        customer_contact_position , 
        customer_contact_tel, 
        customer_contact_email , 
        customer_contact_detail 
        FROM tb_customer_contact 
        WHERE customer_id = $customer_id
        AND (customer_contact_name LIKE ('%$customer_contact_name%') 
        OR customer_contact_position LIKE ('%$customer_contact_position%') 
        OR customer_contact_tel LIKE ('%$customer_contact_tel%') 
        OR customer_contact_email LIKE ('%$customer_contact_email%') 
        )
        ORDER BY customer_contact_name
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

    function getCustomerContactByID($id){
        $sql = " SELECT * 
        FROM tb_customer_contact 
        WHERE customer_contact_id = '$id' 
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

    function updateCustomerContactByID($id,$data = []){
        $sql = " UPDATE tb_customer_contact SET 
        customer_id = '".$data['customer_id']."', 
        customer_contact_name = '".$data['customer_contact_name']."', 
        customer_contact_position = '".$data['customer_contact_position']."', 
        customer_contact_tel = '".$data['customer_contact_tel']."', 
        customer_contact_email = '".$data['customer_contact_email']."', 
        customer_contact_detail = '".$data['customer_contact_detail']."', 
        updateby = '".$data['updateby']."',  
        lastupdate = NOW() 
        WHERE customer_contact_id = $id 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function insertCustomerContact($data = []){
        $sql = " INSERT INTO tb_customer_contact (
            customer_id,
            customer_contact_name, 
            customer_contact_position, 
            customer_contact_tel, 
            customer_contact_email, 
            customer_contact_detail, 
            addby, 
            adddate 
        ) VALUES (
            '".$data['customer_id']."', 
            '".$data['customer_contact_name']."', 
            '".$data['customer_contact_position']."', 
            '".$data['customer_contact_tel']."', 
            '".$data['customer_contact_email']."', 
            '".$data['customer_contact_detail']."', 
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


    function deleteCustomerContactByID($id){
        $sql = "DELETE FROM tb_customer_contact WHERE customer_contact_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }
    
}
?>