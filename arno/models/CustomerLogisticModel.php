<?php

require_once("BaseModel.php");
class CustomerLogisticModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getcustomerLogisticBy($customer_id, $customer_logistic_name = '', $customer_logistic_detail = '', $customer_logistic_lead_time = ''){
        $sql = " SELECT customer_logistic_id, 
        customer_logistic_name, 
        customer_logistic_detail , 
        customer_logistic_lead_time 
        FROM tb_customer_logistic 
        WHERE customer_id = $customer_id
        AND (customer_logistic_name LIKE ('%$customer_logistic_name%') 
        OR customer_logistic_detail LIKE ('%$customer_logistic_detail%') 
        OR customer_logistic_lead_time LIKE ('%$customer_logistic_lead_time%') 
        )
        ORDER BY customer_logistic_name
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

    function getCustomerLogisticByID($id){
        $sql = " SELECT * 
        FROM tb_customer_logistic 
        WHERE customer_logistic_id = '$id' 
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

    function updateCustomerLogisticByID($id,$data = []){
        $sql = " UPDATE tb_customer_logistic SET 
        customer_id = '".$data['customer_id']."', 
        customer_logistic_name = '".$data['customer_logistic_name']."', 
        customer_logistic_detail = '".$data['customer_logistic_detail']."', 
        customer_logistic_lead_time = '".$data['customer_logistic_lead_time']."' 
        WHERE customer_logistic_id = $id 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function insertCustomerLogistic($data = []){
        $sql = " INSERT INTO tb_customer_logistic (
            customer_id,
            customer_logistic_name, 
            customer_logistic_detail, 
            customer_logistic_lead_time
        ) VALUES (
            '".$data['customer_id']."', 
            '".$data['customer_logistic_name']."', 
            '".$data['customer_logistic_detail']."', 
            '".$data['customer_logistic_lead_time']."'  
        ); 
        ";

        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }


    function deleteCustomerLogisticByID($id){
        $sql = "DELETE FROM tb_customer_logistic WHERE customer_logistic_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }
    
}
?>