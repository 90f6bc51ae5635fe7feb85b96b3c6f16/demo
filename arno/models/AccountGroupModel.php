<?php

require_once("BaseModel.php");
class AccountGroupModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getAccountGroupBy(){
        $sql = "SELECT * FROM tb_account_group    
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


    function getAccountGroupByID($id){
        $sql = " SELECT *
        FROM tb_account_group   
        WHERE account_group_id = '$id' 
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

    function updateAccountGroupByID($id,$data = []){
        $sql = " UPDATE tb_account_group SET 
        account_group_name = '".$data['account_group_name']."'   
        WHERE account_group_id = $id 
        ";
 
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }



    function insertAccountGroup($data = []){
        $sql = " INSERT INTO tb_account_group (
            account_group_name 
        ) VALUES (
            '".$data['account_group_name']."'  
        ); 
        ";
 
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }


    function deleteAccountByID($id){
        $sql = " DELETE FROM tb_account_group WHERE account_group_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>