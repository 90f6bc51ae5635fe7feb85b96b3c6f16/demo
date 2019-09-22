<?php

require_once("BaseModel.php");
class CustomerAccountModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getCustomerAccountBy($customer_id, $customer_account_no = '', $customer_account_bank = '', $customer_account_branch = ''){
        $sql = " SELECT customer_account_id, customer_account_no, customer_account_bank , customer_account_branch, customer_account_detail   
        FROM tb_customer_account 
        WHERE customer_id = $customer_id
        AND (customer_account_no LIKE ('%$customer_account_no%') 
        OR customer_account_bank LIKE ('%$customer_account_bank%') 
        OR customer_account_branch LIKE ('%$customer_account_branch%') 
        )
        ORDER BY customer_account_bank , customer_account_no
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

    function getCustomerAccountByID($id){
        $sql = " SELECT * 
        FROM tb_customer_account 
        WHERE customer_account_id = '$id' 
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

    function updateCustomerAccountByID($id,$data = []){
        $sql = " UPDATE tb_customer_account SET 
        customer_id = '".$data['customer_id']."', 
        customer_account_no = '".$data['customer_account_no']."', 
        customer_account_name = '".$data['customer_account_name']."', 
        customer_account_bank = '".$data['customer_account_bank']."', 
        customer_account_branch = '".$data['customer_account_branch']."', 
        customer_account_detail = '".$data['customer_account_detail']."', 
        updateby = '".$data['updateby']."',  
        lastupdate = NOW() 
        WHERE customer_account_id = $id 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function insertCustomerAccount($data = []){
        $sql = " INSERT INTO tb_customer_account (
            customer_id,
            customer_account_no, 
            customer_account_name, 
            customer_account_bank, 
            customer_account_branch, 
            customer_account_detail, 
            addby,
            adddate
        ) VALUES (
            '".$data['customer_id']."', 
            '".$data['customer_account_no']."', 
            '".$data['customer_account_name']."', 
            '".$data['customer_account_bank']."', 
            '".$data['customer_account_branch']."', 
            '".$data['customer_account_detail']."', 
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


    function deleteCustomerAccountByID($id){
        $sql = "DELETE FROM tb_customer_account WHERE customer_account_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }
    
}
?>