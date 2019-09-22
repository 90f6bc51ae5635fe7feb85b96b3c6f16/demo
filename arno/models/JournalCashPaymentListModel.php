<?php

require_once("BaseModel.php");
class JournalCashPaymentListModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getJournalCashPaymentListBy($journal_cash_payment_id){
        $sql = " SELECT 
        IF(journal_cash_payment_list_debit > 0,1,0) as sort_debit,
        journal_cash_payment_list_id, 
        journal_cash_payment_list_no,
        journal_cash_payment_list_name,
        journal_cash_payment_list_debit,
        journal_cash_payment_list_credit, 
        tb_journal_cash_payment_list.account_id, 
        tb_journal_cash_payment_list.journal_cheque_id, 
        tb_journal_cash_payment_list.journal_cheque_pay_id, 
        tb_journal_cash_payment_list.journal_invoice_customer_id, 
        tb_journal_cash_payment_list.journal_invoice_supplier_id, 
        account_code,  
        account_name_th,  
        account_name_en 
        FROM tb_journal_cash_payment_list LEFT JOIN tb_account ON tb_journal_cash_payment_list.account_id = tb_account.account_id 
        WHERE journal_cash_payment_id = '$journal_cash_payment_id' 
        ORDER BY sort_debit DESC , journal_cash_payment_list_no ASC
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

    function getJournalCashPaymentListByFinanceCreditPayId($journal_cash_payment_id,$finance_credit_pay_id){
        $sql = " SELECT 
        journal_cash_payment_list_id, 
        journal_cash_payment_list_no,
        journal_cash_payment_list_name,
        journal_cash_payment_list_debit,
        journal_cash_payment_list_credit, 
        tb_journal_cash_payment_list.account_id, 
        tb_journal_cash_payment_list.journal_cheque_id, 
        tb_journal_cash_payment_list.journal_cheque_pay_id, 
        tb_journal_cash_payment_list.journal_invoice_customer_id, 
        tb_journal_cash_payment_list.journal_invoice_supplier_id, 
        finance_credit_pay_id,
        account_name_th,  
        account_name_en 
        FROM tb_journal_cash_payment_list LEFT JOIN tb_account ON tb_journal_cash_payment_list.account_id = tb_account.account_id 
        WHERE journal_cash_payment_id = '$journal_cash_payment_id' AND tb_journal_cash_payment_list.finance_credit_pay_id = '$finance_credit_pay_id' 
        ORDER BY journal_cash_payment_list_no 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data ;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data  = $row;
            }
            $result->close();
            return $data;
        }

    }

    function insertJournalCashPaymentList($data = []){

        $sql = " INSERT INTO tb_journal_cash_payment_list (
            journal_cash_payment_id,
            journal_cash_payment_list_no,
            finance_credit_pay_id,
            journal_cheque_id,
            journal_cheque_pay_id,
            journal_invoice_customer_id,
            journal_invoice_supplier_id,
            account_id,
            journal_cash_payment_list_name,
            journal_cash_payment_list_debit,
            journal_cash_payment_list_credit,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['journal_cash_payment_id']."', 
            '".$data['journal_cash_payment_list_no']."', 
            '".$data['finance_credit_pay_id']."', 
            '".$data['journal_cheque_id']."', 
            '".$data['journal_cheque_pay_id']."', 
            '".$data['journal_invoice_customer_id']."', 
            '".$data['journal_invoice_supplier_id']."', 
            '".$data['account_id']."', 
            '".static::$db->real_escape_string($data['journal_cash_payment_list_name'])."', 
            '".$data['journal_cash_payment_list_debit']."',
            '".$data['journal_cash_payment_list_credit']."',
            '".$data['addby']."', 
            NOW(), 
            '".$data['updateby']."', 
            NOW() 
        ); 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }

    function updateJournalCashPaymentListById($data,$id){

        $sql = " UPDATE tb_journal_cash_payment_list 
            SET account_id = '".$data['account_id']."',  
            journal_cash_payment_list_no = '".$data['journal_cash_payment_list_no']."',
            journal_cheque_id = '".$data['journal_cheque_id']."',
            journal_cheque_pay_id = '".$data['journal_cheque_pay_id']."',
            journal_invoice_customer_id = '".$data['journal_invoice_customer_id']."',
            journal_invoice_supplier_id = '".$data['journal_invoice_supplier_id']."',
            journal_cash_payment_list_name = '".static::$db->real_escape_string($data['journal_cash_payment_list_name'])."',
            journal_cash_payment_list_debit = '".$data['journal_cash_payment_list_debit']."',
            journal_cash_payment_list_credit = '".$data['journal_cash_payment_list_credit']."' 
            WHERE journal_cash_payment_list_id = '$id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }



    function deleteJournalCashPaymentListByID($id){
        $sql = "DELETE FROM tb_journal_cash_payment_list WHERE journal_cash_payment_list_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteJournalCashPaymentListByJournalCashPaymentID($id){
        $sql = "DELETE FROM tb_journal_cash_payment_list WHERE journal_cash_payment_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteJournalCashPaymentListByFinanceCreditListIDNotIn($journal_cash_payment_id,$data){
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

        $sql = "DELETE FROM tb_journal_cash_payment_list 
                WHERE journal_cash_payment_id = '$journal_cash_payment_id'   
                AND finance_credit_pay_id NOT IN ( $str ) 
                AND finance_credit_pay_id NOT IN ('-1','-2') ";
                // echo $sql."<br>";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }

    function deleteJournalCashPaymentListByJournalCashPaymentIDNotIN($id,$data){
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

        $sql = "DELETE FROM tb_journal_cash_payment_list WHERE journal_cash_payment_id = '$id' AND journal_cash_payment_list_id NOT IN ($str) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>