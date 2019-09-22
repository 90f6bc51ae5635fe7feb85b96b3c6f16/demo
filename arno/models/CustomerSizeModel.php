<?php

require_once("BaseModel.php");
class CustomerSizeModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getCustomerSizeBy(){
        $sql = "SELECT * FROM tb_customer_size ";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }

    function getCustomerSizeByID($id){
        $sql = " SELECT * 
        FROM tb_customer_size 
        WHERE customer_size_id = '$id' 
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

    function updateCustomerSizeByID($id,$data = []){
        $sql = " UPDATE tb_customer_size SET  
        customer_size_name = '".$data['customer_size_name']."' 
        ";

        //echo $sql;

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function insertCustomerSize($data = []){
        $sql = " INSERT INTO tb_customer_size ( 
            customer_size_name  
        ) VALUES (
            '".$data['customer_size_name']."' 
        ); 
        ";

        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }


    function deleteCustomerSizeByID($id){
        $sql = " DELETE FROM tb_customer_size WHERE customer_size_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>