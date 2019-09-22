<?php

require_once("BaseModel.php");
class JournalCashReceiptListModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getJournalCashReceiptListBy($journal_cash_receipt_id){
        $sql = " SELECT 
        IF(journal_cash_receipt_list_debit > 0,1,0) as sort_debit,
        journal_cash_receipt_list_id, 
        journal_cash_receipt_list_no,
        journal_cash_receipt_list_name,
        journal_cash_receipt_list_debit,
        journal_cash_receipt_list_credit, 
        journal_cheque_id,
        journal_cheque_pay_id,
        journal_invoice_customer_id,
        journal_invoice_supplier_id,
        tb_journal_cash_receipt_list.account_id, 
        account_code,
        account_name_th,  
        account_name_en 
        FROM tb_journal_cash_receipt_list LEFT JOIN tb_account ON tb_journal_cash_receipt_list.account_id = tb_account.account_id 
        WHERE journal_cash_receipt_id = '$journal_cash_receipt_id' 
        ORDER BY sort_debit DESC , journal_cash_receipt_list_no ASC
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

    function getJournalCashReceiptListByFinanceDebitPayId($journal_cash_receipt_id,$finance_debit_pay_id){
        $sql = " SELECT 
        journal_cash_receipt_list_id, 
        journal_cash_receipt_list_no, 
        journal_cash_receipt_list_name,
        journal_cash_receipt_list_debit,
        journal_cash_receipt_list_credit, 
        tb_journal_cash_receipt_list.account_id, 
        finance_debit_pay_id,
        account_code,
        account_name_th,  
        account_name_en 
        FROM tb_journal_cash_receipt_list LEFT JOIN tb_account ON tb_journal_cash_receipt_list.account_id = tb_account.account_id 
        WHERE journal_cash_receipt_id = '$journal_cash_receipt_id' AND tb_journal_cash_receipt_list.finance_debit_pay_id = '$finance_debit_pay_id' 
        ORDER BY journal_cash_receipt_list_no 
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


    function insertJournalCashReceiptList($data = []){
        $sql = " INSERT INTO tb_journal_cash_receipt_list (
            journal_cash_receipt_id,
            journal_cash_receipt_list_no,
            journal_cheque_id,
            journal_cheque_pay_id,
            journal_invoice_customer_id,
            journal_invoice_supplier_id,
            account_id,
            journal_cash_receipt_list_name,
            journal_cash_receipt_list_debit,
            journal_cash_receipt_list_credit,
            finance_debit_pay_id,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['journal_cash_receipt_id']."', 
            '".$data['journal_cash_receipt_list_no']."', 
            '".$data['journal_cheque_id']."', 
            '".$data['journal_cheque_pay_id']."', 
            '".$data['journal_invoice_customer_id']."', 
            '".$data['journal_invoice_supplier_id']."', 
            '".$data['account_id']."', 
            '".static::$db->real_escape_string($data['journal_cash_receipt_list_name'])."', 
            '".$data['journal_cash_receipt_list_debit']."',
            '".$data['journal_cash_receipt_list_credit']."',
            '".$data['finance_debit_pay_id']."',
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

    function updateJournalCashReceiptListById($data,$id){

        $sql = " UPDATE tb_journal_cash_receipt_list 
            SET account_id = '".$data['account_id']."', 
            journal_cash_receipt_list_no = '".$data['journal_cash_receipt_list_no']."',
            journal_cheque_id = '".$data['journal_cheque_id']."',
            journal_cheque_pay_id = '".$data['journal_cheque_pay_id']."',
            journal_invoice_customer_id = '".$data['journal_invoice_customer_id']."',
            journal_invoice_supplier_id = '".$data['journal_invoice_supplier_id']."',
            journal_cash_receipt_list_name = '".static::$db->real_escape_string($data['journal_cash_receipt_list_name'])."',
            journal_cash_receipt_list_debit = '".$data['journal_cash_receipt_list_debit']."',
            journal_cash_receipt_list_credit = '".$data['journal_cash_receipt_list_credit']."' 
            WHERE journal_cash_receipt_list_id = '$id' 
        ";
        


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function deleteJournalCashReceiptListByID($id){
        $sql = "DELETE FROM tb_journal_cash_receipt_list WHERE journal_cash_receipt_list_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteJournalCashReceiptListByJournalCashReceiptID($id){
        $sql = "DELETE FROM tb_journal_cash_receipt_list WHERE journal_cash_receipt_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteJournalCashReceiptListByFinanceDebitListIDNotIn($journal_cash_receipt_id,$data){
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

        $sql = "DELETE FROM tb_journal_cash_receipt_list 
                WHERE journal_cash_receipt_id = '$journal_cash_receipt_id' AND finance_debit_pay_id NOT IN ( $str ) AND finance_debit_pay_id NOT IN ('-1','-2') ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }

    function deleteJournalCashReceiptListByJournalCashReceiptIDNotIN($id,$data){
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

        $sql = "DELETE FROM tb_journal_cash_receipt_list WHERE journal_cash_receipt_id = '$id' AND journal_cash_receipt_list_id NOT IN ($str) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>