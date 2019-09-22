<?php

require_once("BaseModel.php");
class BankAccountModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getBankAccountBy($bank_account_name = ''){
        $sql = " SELECT *   
        FROM tb_bank_account 
        LEFT JOIN tb_account ON tb_bank_account.account_id = tb_account.account_id
        WHERE bank_account_name LIKE ('%$bank_account_name%') 
        ORDER BY bank_account_name  
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

    function getBankAccountByID($id){
        $sql = " SELECT * 
        FROM tb_bank_account 
        LEFT JOIN tb_account ON tb_bank_account.account_id = tb_account.account_id
        WHERE bank_account_id = '$id' 
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

    function updateBankAccountByID($id,$data = []){
        $sql = " UPDATE tb_bank_account SET     
        bank_account_code = '".$data['bank_account_code']."', 
        bank_account_name = '".$data['bank_account_name']."', 
        bank_account_branch = '".$data['bank_account_branch']."', 
        bank_account_number = '".$data['bank_account_number']."', 
        bank_account_title = '".$data['bank_account_title']."',  
        account_id = '".$data['account_id']."' 
        WHERE bank_account_id = $id 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function insertBankAccount($data = []){
        $sql = " INSERT INTO tb_bank_account (
            bank_account_code,
            bank_account_name,
            bank_account_branch,
            bank_account_number,
            bank_account_title,
            account_id
        ) VALUES (
            '".$data['bank_account_code']."', 
            '".$data['bank_account_name']."', 
            '".$data['bank_account_branch']."', 
            '".$data['bank_account_number']."', 
            '".$data['bank_account_title']."', 
            '".$data['account_id']."' 
        ); 
        ";

        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }


    function deleteBankAccountByID($id){
        $sql = " DELETE FROM tb_bank_account WHERE bank_account_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>