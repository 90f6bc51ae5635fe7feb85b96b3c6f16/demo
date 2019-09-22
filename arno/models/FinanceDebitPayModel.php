<?php

require_once("BaseModel.php");
class FinanceDebitPayModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getFinanceDebitPayBy($finance_debit_id){
        $sql = " SELECT 
        check_id,
        finance_debit_pay_id, 
        tb_finance_debit_pay.finance_debit_account_id,
        finance_debit_pay_by,
        finance_debit_pay_date,
        tb_finance_debit_pay.bank_account_id,
        finance_debit_pay_bank,
        tb_finance_debit_pay.account_id,
        finance_debit_pay_value,
        finance_debit_pay_balance,
        finance_debit_pay_total,
        tb_finance_debit_account.finance_debit_account_cheque
        FROM tb_finance_debit_pay 
        LEFT JOIN tb_finance_debit_account ON tb_finance_debit_pay.finance_debit_account_id = tb_finance_debit_account.finance_debit_account_id
        WHERE finance_debit_id = '$finance_debit_id' 
        ORDER BY finance_debit_pay_id 
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

    function getFinanceDebitPayByAccount($finance_debit_id){
        $sql = " SELECT 
        check_id,
        finance_debit_pay_id, 
        tb_finance_debit_account.finance_debit_account_id,
        finance_debit_account_code,
        finance_debit_account_name,
        finance_debit_pay_by,
        finance_debit_pay_date,
        tb_finance_debit_pay.bank_account_id,
        finance_debit_pay_bank,
        tb_finance_debit_pay.account_id,
        finance_debit_pay_value,
        finance_debit_pay_balance,
        finance_debit_pay_total
        FROM tb_finance_debit_pay 
        LEFT JOIN tb_finance_debit_account ON tb_finance_debit_pay.finance_debit_account_id = tb_finance_debit_account.finance_debit_account_id
        WHERE finance_debit_id = '$finance_debit_id' 
        ORDER BY finance_debit_pay_id 
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


    function insertFinanceDebitPay($data = []){
        $sql = " INSERT INTO tb_finance_debit_pay (
            finance_debit_id, 
            finance_debit_account_id,
            finance_debit_pay_by,
            finance_debit_pay_date,
            bank_account_id,
            finance_debit_pay_bank,
            account_id,
            check_id,
            finance_debit_pay_value,
            finance_debit_pay_balance,
            finance_debit_pay_total,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['finance_debit_id']."', 
            '".$data['finance_debit_account_id']."', 
            '".$data['finance_debit_pay_by']."', 
            '".$data['finance_debit_pay_date']."', 
            '".$data['bank_account_id']."', 
            '".$data['finance_debit_pay_bank']."', 
            '".$data['account_id']."', 
            '".$data['check_id']."', 
            '".$data['finance_debit_pay_value']."', 
            '".$data['finance_debit_pay_balance']."', 
            '".$data['finance_debit_pay_total']."', 
            '".$data['addby']."', 
            NOW(), 
            '".$data['updateby']."', 
            NOW() 
        ); 
        ";

        //echo $sql."<br><br>";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }

    function updateFinanceDebitPayById($data,$id){

        $sql = " UPDATE tb_finance_debit_pay 
            SET finance_debit_account_id = '".$data['finance_debit_account_id']."',
            finance_debit_pay_by = '".$data['finance_debit_pay_by']."', 
            finance_debit_pay_date = '".$data['finance_debit_pay_date']."',
            bank_account_id = '".$data['bank_account_id']."',
            finance_debit_pay_bank = '".$data['finance_debit_pay_bank']."',
            account_id = '".$data['account_id']."',
            check_id = '".$data['check_id']."',
            finance_debit_pay_value = '".$data['finance_debit_pay_value']."',
            finance_debit_pay_balance = '".$data['finance_debit_pay_balance']."',
            finance_debit_pay_total = '".$data['finance_debit_pay_total']."'     
            WHERE finance_debit_pay_id = '$id' 
        ";

        //echo $sql."<br><br>";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function deleteFinanceDebitPayByID($id){
        $sql = "DELETE FROM tb_finance_debit_pay WHERE finance_debit_pay_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteFinanceDebitPayByFinanceDebitID($id){
        $sql = "DELETE FROM tb_finance_debit_pay WHERE finance_debit_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteFinanceDebitPayByFinanceDebitPayIDNotIN($id,$data){
        $str ='';
        if(is_array($data)){ 
            for($i=0; $i < count($data) ;$i++){
                if($data[$i] != ""){
                    $str .= $data[$i];
                    if($i + 1 < count($data)){
                        $str .= ',';
                    }
                }
            }
        }else if ($data != ''){
            $str = $data;
        }else{
            $str='0';
        }

        if( $str==''){
            $str='0';
        }

        $sql = "DELETE FROM tb_finance_debit_pay WHERE finance_debit_id = '$id' AND finance_debit_pay_id NOT IN ($str) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>