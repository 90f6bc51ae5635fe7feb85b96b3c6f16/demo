<?php

require_once("BaseModel.php");
class CustomerTypeModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getCustomerTypeBy(){
        $sql = "SELECT * FROM tb_customer_type ";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }

    function getCustomerTypeByID($id){
        $sql = " SELECT * 
        FROM tb_customer_type 
        WHERE customer_type_id = '$id' 
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

    function updateCustomerTypeByID($id,$data = []){
        $sql = " UPDATE tb_customer_type SET  
        customer_type_name = '".$data['customer_type_name']."' 
        ";

        //echo $sql;

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function insertCustomerType($data = []){
        $sql = " INSERT INTO tb_customer_type ( 
            customer_type_name  
        ) VALUES (
            '".$data['customer_type_name']."' 
        ); 
        ";

        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }


    function deleteCustomerTypeByID($id){
        $sql = " DELETE FROM tb_customer_type WHERE customer_type_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>