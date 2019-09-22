<?php

require_once("BaseModel.php");
class AccountModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getAccountBy($account = '0'){
        $sql = "SELECT * FROM tb_account  
        WHERE account_control = '$account' ORDER BY account_code 
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

    function getAccountAll(){
        $sql = "SELECT * FROM tb_account  ORDER BY account_code 
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


    function getAccountNode(){
        $sql = "SELECT * FROM tb_account WHERE tb_account.account_type = '0'  ORDER BY account_code ";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }


    function getAccountByID($id){
        $sql = " SELECT 
            tb_account.account_id , 
            tb_account.account_code , 
            tb_account.account_name_th , 
            tb_account.account_name_en , 
            tb_account.account_control , 
            tb_account.account_level , 
            tb_account.account_group , 
            tb_account.account_type , 
            tb1.account_code  as control_code 
        FROM tb_account 
        LEFT JOIN tb_account as tb1 ON tb_account.account_control = tb1.account_id  
        WHERE tb_account.account_id = '$id' 
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

    function updateAccountByID($id,$data = []){
        $sql = " UPDATE tb_account SET 
        account_code = '".$data['account_code']."', 
        account_name_th = '".$data['account_name_th']."', 
        account_name_en = '".$data['account_name_en']."', 
        account_control = '".$data['account_control']."', 
        account_level = '".$data['account_level']."', 
        account_group = '".$data['account_group']."', 
        account_type = '".$data['account_type']."'  
        WHERE account_id = $id 
        ";

        //echo $sql;

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function updateBeginByID($id,$data = []){
        $sql = " UPDATE tb_account SET 
        account_debit_begin = '".$data['account_debit_begin']."', 
        account_credit_begin = '".$data['account_credit_begin']."'  
        WHERE account_id = '$id' 
        ";

        //echo '<br><br>'.$sql;

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function insertAccount($data = []){
        $sql = " INSERT INTO tb_account (
            account_code , 
            account_name_th, 
            account_name_en,
            account_control,
            account_level,
            account_group,
            account_type 
        ) VALUES (
            '".$data['account_code']."', 
            '".$data['account_name_th']."', 
            '".$data['account_name_en']."', 
            '".$data['account_control']."', 
            '".$data['account_level']."', 
            '".$data['account_group']."', 
            '".$data['account_type']."' 
        ); 
        ";

        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }


    function deleteAccountByID($id){
        $sql = " DELETE FROM tb_account WHERE account_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>