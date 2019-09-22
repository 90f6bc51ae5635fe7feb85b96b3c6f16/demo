<?php

require_once("BaseModel.php");
class JournalCashReceiptModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db,"utf8");
    }

    function getJournalCashReceiptBy($date_start = "", $date_end = "",$keyword = "", $lock_1 = "0", $lock_2 = "0", $sort = "ASC"){


        $str_date = "";

        $str_lock = "";

        if($lock_1 == "1" && $lock_2 == "1"){
            $str_lock = "AND (paper_lock_1 = '0' OR paper_lock_2 = '0') ";
        }else if ($lock_1 == "1") {
            $str_lock = "AND paper_lock_1 = '0' ";
        }else if($lock_2 == "1"){
            $str_lock = "AND paper_lock_2 = '0' ";
        }

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }



        $sql = " SELECT tb_journal_cash_receipt.journal_cash_receipt_id, 
        IFNULL(tb_finance_debit.finance_debit_id,'0') as finance_debit_id,
        IFNULL(tb_finance_debit.finance_debit_code,'-') as finance_debit_code,
        journal_cash_receipt_code, 
        journal_cash_receipt_date,
        journal_cash_receipt_name,
        IFNULL(SUM(journal_cash_receipt_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_cash_receipt_list_credit),0) as journal_credit 
        FROM tb_journal_cash_receipt 
        LEFT JOIN tb_journal_cash_receipt_list ON tb_journal_cash_receipt_list.journal_cash_receipt_id = tb_journal_cash_receipt.journal_cash_receipt_id
        LEFT JOIN tb_finance_debit ON tb_journal_cash_receipt.finance_debit_id = tb_finance_debit.finance_debit_id  
        LEFT JOIN tb_paper_lock ON SUBSTRING(tb_journal_cash_receipt.journal_cash_receipt_date,3,9)=SUBSTRING(tb_paper_lock.paper_lock_date,3,9) 
        WHERE ( 
                journal_cash_receipt_code LIKE ('%$keyword%') 
            OR  journal_cash_receipt_name LIKE ('%$keyword%') 
        ) 
        $str_lock 
        $str_date 
        GROUP BY tb_journal_cash_receipt.journal_cash_receipt_id 
        ORDER BY journal_cash_receipt_code $sort 
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

    function getJournalCashReceiptByKeyword($keyword = ""){
       
        $sql = " SELECT journal_cash_receipt_id, 
        journal_cash_receipt_code,  
        journal_cash_receipt_name 
        FROM tb_journal_cash_receipt  
        WHERE journal_cash_receipt_code LIKE ('%$keyword%')  OR  journal_cash_receipt_name LIKE ('%$keyword%') 
        ORDER BY journal_cash_receipt_code DESC 
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


    function getJournalCashReceiptByFinanceDebitID($id){
        $sql = " SELECT * 
        FROM tb_journal_cash_receipt 
        WHERE finance_debit_id = '$id' 
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

    function getJournalCashReceiptByID($id){
        $sql = " SELECT * 
        FROM tb_journal_cash_receipt 
        WHERE journal_cash_receipt_id = '$id' 
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
    
    function getJournalCashReceiptByCode($journal_cash_receipt_code){
        $sql = " SELECT * 
        FROM tb_journal_cash_receipt 
        WHERE journal_cash_receipt_code = '$journal_cash_receipt_code' 
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
    
    function getJournalCashReceiptViewByID($id){
        $sql = " SELECT journal_cash_receipt_id, 
        journal_cash_receipt_code, 
        journal_cash_receipt_date,
        journal_cash_receipt_name,
        addby,
        adddate,
        updateby,
        lastupdate,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as add_name, 
        IFNULL(CONCAT(tb2.user_name,' ',tb2.user_lastname),'-') as update_name 
        FROM tb_journal_cash_receipt 
        LEFT JOIN tb_user as tb1 ON tb_journal_cash_receipt.addby = tb1.user_id 
        LEFT JOIN tb_user as tb2 ON tb_journal_cash_receipt.updateby = tb2.user_id 
        WHERE journal_cash_receipt_id = '$id' 
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

    function getJournalCashReceiptLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(journal_cash_receipt_code,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  journal_cash_receipt_lastcode 
        FROM tb_journal_cash_receipt 
        WHERE journal_cash_receipt_code LIKE ('$id%') 
        ";

        //echo $sql;

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['journal_cash_receipt_lastcode'];
        }

    }

   
    function updateJournalCashReceiptByID($id,$data = []){
        $sql = " UPDATE tb_journal_cash_receipt SET 
        journal_cash_receipt_code = '".$data['journal_cash_receipt_code']."', 
        journal_cash_receipt_date = '".$data['journal_cash_receipt_date']."', 
        journal_cash_receipt_name = '".static::$db->real_escape_string($data['journal_cash_receipt_name'])."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW()  
        WHERE journal_cash_receipt_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }



    function insertJournalCashReceipt($data = []){
        $sql = " INSERT INTO tb_journal_cash_receipt (
            finance_debit_id,
            journal_cash_receipt_code, 
            journal_cash_receipt_date,
            journal_cash_receipt_name,
            addby,
            adddate,
            updateby, 
            lastupdate) 
        VALUES ('".
        $data['finance_debit_id']."','".
        $data['journal_cash_receipt_code']."','".
        $data['journal_cash_receipt_date']."','".
        static::$db->real_escape_string($data['journal_cash_receipt_name'])."','".
        $data['addby']."',".
        "NOW(),'".
        $data['addby'].
        "',NOW()); 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }

    function deleteJournalCashReceiptByFinanceDebitID($finance_debit_id){
        
        $sql = " DELETE FROM tb_journal_cash_receipt_list WHERE journal_cash_receipt_id IN (SELECT journal_cash_receipt_id FROM tb_journal_cash_receipt WHERE finance_debit_id = '$finance_debit_id') ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_journal_cash_receipt WHERE finance_debit_id = '$finance_debit_id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        
    }



    function deleteJournalCashReceiptByID($id){
        $sql = " DELETE FROM tb_journal_cash_receipt WHERE journal_cash_receipt_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        $sql = " DELETE FROM tb_journal_cash_receipt_list WHERE journal_cash_receipt_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }


}
?>