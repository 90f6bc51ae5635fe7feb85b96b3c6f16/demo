<?php

require_once("BaseModel.php");
class JournalCashPaymentModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db,"utf8");
    }

    function getJournalCashPaymentBy($date_start = "", $date_end = "",$keyword = "", $lock_1 = "0", $lock_2 = "0", $sort = "ASC"){


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
            $str_date = "AND STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }



        $sql = " SELECT tb_journal_cash_payment.journal_cash_payment_id, 
        IFNULL(tb_finance_credit.finance_credit_id,'0') as finance_credit_id, 
        IFNULL(tb_finance_credit.finance_credit_code,'-') as finance_credit_code,
        journal_cash_payment_code, 
        journal_cash_payment_date,
        journal_cash_payment_name, 
        IFNULL(SUM(journal_cash_payment_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_cash_payment_list_credit),0) as journal_credit
        FROM tb_journal_cash_payment 
        LEFT JOIN tb_journal_cash_payment_list ON tb_journal_cash_payment_list.journal_cash_payment_id = tb_journal_cash_payment.journal_cash_payment_id
        LEFT JOIN tb_finance_credit ON tb_journal_cash_payment.finance_credit_id = tb_finance_credit.finance_credit_id   
        LEFT JOIN tb_paper_lock ON SUBSTRING(tb_journal_cash_payment.journal_cash_payment_date,3,9)=SUBSTRING(tb_paper_lock.paper_lock_date,3,9) 
        WHERE ( 
                journal_cash_payment_code LIKE ('%$keyword%') 
            OR  journal_cash_payment_name LIKE ('%$keyword%') 
        ) 
        $str_lock 
        $str_date 
        GROUP BY journal_cash_payment_id 
        ORDER BY  journal_cash_payment_code $sort 
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

    function getJournalCashPaymentByKeyword($keyword = ""){
       
        $sql = " SELECT journal_cash_payment_id, 
        journal_cash_payment_code,  
        journal_cash_payment_name 
        FROM tb_journal_cash_payment  
        WHERE journal_cash_payment_code LIKE ('%$keyword%')  OR  journal_cash_payment_name LIKE ('%$keyword%') 
        ORDER BY journal_cash_payment_code DESC 
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


    function getJournalCashPaymentByFinanceCreditID($id){
        $sql = " SELECT * 
        FROM tb_journal_cash_payment 
        WHERE finance_credit_id = '$id' 
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


    function getJournalCashPaymentByID($id){
        $sql = " SELECT * 
        FROM tb_journal_cash_payment 
        WHERE journal_cash_payment_id = '$id' 
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

    function getJournalCashPaymentByCode($journal_cash_payment_code){
        $sql = " SELECT * 
        FROM tb_journal_cash_payment 
        WHERE journal_cash_payment_code = '$journal_cash_payment_code' 
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

    function getJournalCashPaymentViewByID($id){
        $sql = " SELECT journal_cash_payment_id, 
        journal_cash_payment_code, 
        journal_cash_payment_date,
        journal_cash_payment_name,
        addby,
        adddate,
        updateby,
        lastupdate,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as add_name, 
        IFNULL(CONCAT(tb2.user_name,' ',tb2.user_lastname),'-') as update_name 
        FROM tb_journal_cash_payment 
        LEFT JOIN tb_user as tb1 ON tb_journal_cash_payment.addby = tb1.user_id 
        LEFT JOIN tb_user as tb2 ON tb_journal_cash_payment.updateby = tb2.user_id 
        WHERE journal_cash_payment_id = '$id' 
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

    function getJournalCashPaymentLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(journal_cash_payment_code,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  journal_cash_payment_lastcode 
        FROM tb_journal_cash_payment 
        WHERE journal_cash_payment_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['journal_cash_payment_lastcode'];
        }

    }

   
    function updateJournalCashPaymentByID($id,$data = []){
        $sql = " UPDATE tb_journal_cash_payment SET 
        journal_cash_payment_code = '".$data['journal_cash_payment_code']."', 
        journal_cash_payment_date = '".$data['journal_cash_payment_date']."', 
        journal_cash_payment_name = '".static::$db->real_escape_string($data['journal_cash_payment_name']). "', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW()  
        WHERE journal_cash_payment_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }



    function insertJournalCashPayment($data = []){
        $sql = " INSERT INTO tb_journal_cash_payment (
            finance_credit_id,
            journal_cash_payment_code, 
            journal_cash_payment_date,
            journal_cash_payment_name,
            addby,
            adddate,
            updateby, 
            lastupdate) 
        VALUES ('".
        $data['finance_credit_id']."','".
        $data['journal_cash_payment_code']."','".
        $data['journal_cash_payment_date']."','".
        static::$db->real_escape_string($data['journal_cash_payment_name'])."','".
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


    function deleteJournalCashPaymentByFinanceCreditID($finance_credit_id){
        
        $sql = " DELETE FROM tb_journal_cash_payment_list WHERE journal_cash_payment_id IN (SELECT journal_cash_payment_id FROM tb_journal_cash_payment WHERE finance_credit_id = '$finance_credit_id') ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_journal_cash_payment WHERE finance_credit_id = '$finance_credit_id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        
    }

    function deleteJournalCashPaymentByID($id){
        $sql = " DELETE FROM tb_journal_cash_payment WHERE journal_cash_payment_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        $sql = " DELETE FROM tb_journal_cash_payment_list WHERE journal_cash_payment_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }


}
?>