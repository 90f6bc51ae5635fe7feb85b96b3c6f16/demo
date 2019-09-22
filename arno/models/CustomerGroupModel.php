<?php

require_once("BaseModel.php");
class CustomerGroupModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getCustomerGroupBy(){
        $sql = "SELECT * FROM tb_customer_group ";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }

    function getCustomerGroupByID($id){
        $sql = " SELECT * 
        FROM tb_customer_group 
        WHERE customer_group_id = '$id' 
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

    function updateCustomerGroupByID($id,$data = []){
        $sql = " UPDATE tb_customer_group SET  
        customer_group_name = '".$data['customer_group_name']."' 
        ";

        //echo $sql;

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function insertCustomerGroup($data = []){
        $sql = " INSERT INTO tb_customer_group ( 
            customer_group_name  
        ) VALUES (
            '".$data['customer_group_name']."' 
        ); 
        ";

        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }


    function deleteCustomerGroupByID($id){
        $sql = " DELETE FROM tb_customer_group WHERE customer_group_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>