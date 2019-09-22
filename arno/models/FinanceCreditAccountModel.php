<?php

require_once("BaseModel.php");
class FinanceCreditAccountModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getFinanceCreditAccountBy($keyword = ''){
        $sql = " SELECT *   
        FROM tb_finance_credit_account 
        LEFT JOIN tb_account ON tb_finance_credit_account.account_id = tb_account.account_id
        LEFT JOIN tb_bank_account ON tb_finance_credit_account.bank_account_id = tb_bank_account.bank_account_id
        WHERE finance_credit_account_name LIKE ('%$keyword%') OR finance_credit_account_code LIKE ('%$keyword%')
        ORDER BY finance_credit_account_code  
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

    function getFinanceCreditAccountNoJoinBy($keyword = ''){
        $sql = " SELECT *   
        FROM tb_finance_credit_account  
        WHERE finance_credit_account_name LIKE ('%$keyword%') OR finance_credit_account_code LIKE ('%$keyword%')
        ORDER BY finance_credit_account_code  
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

    function getFinanceCreditAccountByID($id){
        $sql = " SELECT * 
        FROM tb_finance_credit_account  
        WHERE finance_credit_account_id = '$id' 
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

    function updateFinanceCreditAccountByID($id,$data = []){
        $sql = " UPDATE tb_finance_credit_account SET     
        finance_credit_account_code = '".$data['finance_credit_account_code']."', 
        finance_credit_account_name = '".$data['finance_credit_account_name']."', 
        finance_credit_account_cheque = '".$data['finance_credit_account_cheque']."', 
        bank_account_id = '".$data['bank_account_id']."',  
        account_id = '".$data['account_id']."' 
        WHERE finance_credit_account_id = $id 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function insertFinanceCreditAccount($data = []){
        $sql = " INSERT INTO tb_finance_credit_account (
            finance_credit_account_code,
            finance_credit_account_name,
            finance_credit_account_cheque,
            bank_account_id, 
            account_id
        ) VALUES (
            '".$data['finance_credit_account_code']."', 
            '".$data['finance_credit_account_name']."', 
            '".$data['finance_credit_account_cheque']."', 
            '".$data['bank_account_id']."',   
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


    function deleteFinanceCreditAccountByID($id){
        $sql = " DELETE FROM tb_finance_credit_account WHERE finance_credit_account_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>