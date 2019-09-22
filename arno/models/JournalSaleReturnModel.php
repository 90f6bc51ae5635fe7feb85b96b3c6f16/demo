<?php

require_once("BaseModel.php");
class JournalSaleReturnModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db,"utf8");
    }

    function getJournalSaleReturnBy($date_start = "", $date_end = "",$keyword = "", $lock_1 = "0", $lock_2 = "0", $sort = "ASC"){


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
            $str_date = "AND STR_TO_DATE(journal_sale_return_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_sale_return_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(journal_sale_return_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(journal_sale_return_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }



        $sql = " SELECT tb_journal_sale_return.journal_sale_return_id, 
        journal_sale_return_code, 
        journal_sale_return_date,
        journal_sale_return_name,
        tb_journal_sale_return.credit_note_id,
        tb_credit_note.credit_note_code, 
        IFNULL(SUM(journal_sale_return_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_sale_return_list_credit),0) as journal_credit
        FROM tb_journal_sale_return 
        LEFT JOIN tb_journal_sale_return_list ON tb_journal_sale_return_list.journal_sale_return_id = tb_journal_sale_return.journal_sale_return_id
        LEFT JOIN tb_credit_note ON tb_journal_sale_return.credit_note_id = tb_credit_note.credit_note_id 
        LEFT JOIN tb_paper_lock ON SUBSTRING(tb_journal_sale_return.journal_sale_return_date,3,9)=SUBSTRING(tb_paper_lock.paper_lock_date,3,9) 
        WHERE ( 
                journal_sale_return_code LIKE ('%$keyword%') 
            OR  journal_sale_return_name LIKE ('%$keyword%') 
        ) 
        $str_lock 
        $str_date 
        GROUP BY tb_journal_sale_return.journal_sale_return_id 
        ORDER BY journal_sale_return_code $sort 
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

    function getJournalSaleReturnByCreditNoteID($credit_note_id){
        $sql = " SELECT * 
        FROM tb_journal_sale_return 
        WHERE credit_note_id = '$credit_note_id' 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data ;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getJournalSaleReturnByKeyword(){
       
        $sql = " SELECT journal_sale_return_id, 
        journal_sale_return_code,  
        journal_sale_return_name 
        FROM tb_journal_sale_return  
        WHERE journal_sale_return_code LIKE ('%$keyword%')  OR  journal_sale_return_name LIKE ('%$keyword%') 
        ORDER BY journal_sale_return_code DESC 
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





    function getJournalSaleReturnByID($id){
        $sql = " SELECT * 
        FROM tb_journal_sale_return 
        WHERE journal_sale_return_id = '$id' 
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

    function getJournalSaleReturnByCode($journal_sale_return_code){
        $sql = " SELECT * 
        FROM tb_journal_sale_return 
        WHERE journal_sale_return_code = '$journal_sale_return_code' 
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

    function getJournalSaleReturnViewByID($id){
        $sql = " SELECT journal_sale_return_id, 
        credit_note_id, 
        journal_sale_return_code, 
        journal_sale_return_date,
        journal_sale_return_name,
        addby,
        adddate,
        updateby,
        lastupdate,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as add_name, 
        IFNULL(CONCAT(tb2.user_name,' ',tb2.user_lastname),'-') as update_name 
        FROM tb_journal_sale_return 
        LEFT JOIN tb_user as tb1 ON tb_journal_sale_return.addby = tb1.user_id 
        LEFT JOIN tb_user as tb2 ON tb_journal_sale_return.updateby = tb2.user_id 
        WHERE journal_sale_return_id = '$id' 
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

    function getJournalSaleReturnLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(journal_sale_return_code,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  journal_sale_return_lastcode 
        FROM tb_journal_sale_return 
        WHERE journal_sale_return_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['journal_sale_return_lastcode'];
        }

    }

   
    function updateJournalSaleReturnByID($id,$data = []){
        $sql = " UPDATE tb_journal_sale_return SET 
        journal_sale_return_code = '".$data['journal_sale_return_code']."', 
        journal_sale_return_date = '".$data['journal_sale_return_date']."', 
        journal_sale_return_name = '".$data['journal_sale_return_name']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW()  
        WHERE journal_sale_return_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }



    function insertJournalSaleReturn($data = []){
        $sql = " INSERT INTO tb_journal_sale_return (
            credit_note_id,
            journal_sale_return_code, 
            journal_sale_return_date,
            journal_sale_return_name,
            addby,
            adddate,
            updateby, 
            lastupdate) 
        VALUES ('".
        $data['credit_note_id']."','".
        $data['journal_sale_return_code']."','".
        $data['journal_sale_return_date']."','".
        $data['journal_sale_return_name']."','".
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



    function deleteJournalSaleReturnByID($id){
        $sql = " DELETE FROM tb_journal_sale_return WHERE journal_sale_return_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        $sql = " DELETE FROM tb_journal_sale_return_list WHERE journal_sale_return_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }

    function deleteJournalSaleReturnByInvoiceCustomerID($credit_note_id){

        $sql = " DELETE FROM tb_journal_sale_return_list WHERE journal_sale_return_id IN (SELECT journal_sale_return_id FROM tb_journal_sale_return WHERE credit_note_id = '$credit_note_id' ) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_journal_sale_return WHERE  credit_note_id = '$credit_note_id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }


}
?>