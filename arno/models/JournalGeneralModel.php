<?php

require_once("BaseModel.php");
class JournalGeneralModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db,"utf8");
    }

    function getJournalGeneralBy($date_start = "", $date_end = "",$keyword = "", $lock_1 = "0", $lock_2 = "0", $sort = "ASC"){


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
            $str_date = "AND STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }



        $sql = " SELECT tb_journal_general.journal_general_id, 
        journal_general_code, 
        journal_general_date,
        journal_general_name,
        IFNULL(SUM(journal_general_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_general_list_credit),0) as journal_credit
        FROM tb_journal_general 
        LEFT JOIN tb_journal_general_list ON tb_journal_general_list.journal_general_id = tb_journal_general.journal_general_id  
        LEFT JOIN tb_paper_lock ON SUBSTRING(tb_journal_general.journal_general_date,3,9)=SUBSTRING(tb_paper_lock.paper_lock_date,3,9) 
        WHERE ( 
                journal_general_code LIKE ('%$keyword%') 
            OR  journal_general_name LIKE ('%$keyword%') 
        ) 
        $str_lock 
        $str_date 
        GROUP BY tb_journal_general.journal_general_id 
        ORDER BY  journal_general_code $sort 
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





    function getJournalGeneralByID($id){
        $sql = " SELECT * 
        FROM tb_journal_general 
        WHERE journal_general_id = '$id' 
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

    function getJournalGeneralByKeyword(){
       
        $sql = " SELECT journal_general_id, 
        journal_general_code,  
        journal_general_name 
        FROM tb_journal_general  
        WHERE journal_general_code LIKE ('%$keyword%')  OR  journal_general_name LIKE ('%$keyword%') 
        ORDER BY journal_general_code DESC 
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

    function getJournalGeneralByCode($journal_general_code){
        $sql = " SELECT * 
        FROM tb_journal_general 
        WHERE journal_general_code = '$journal_general_code' 
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

    function getJournalGeneralViewByID($id){
        $sql = " SELECT journal_general_id, 
        journal_general_code, 
        journal_general_date,
        journal_general_name,
        addby,
        adddate,
        updateby,
        lastupdate,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as add_name, 
        IFNULL(CONCAT(tb2.user_name,' ',tb2.user_lastname),'-') as update_name 
        FROM tb_journal_general 
        LEFT JOIN tb_user as tb1 ON tb_journal_general.addby = tb1.user_id 
        LEFT JOIN tb_user as tb2 ON tb_journal_general.updateby = tb2.user_id 
        WHERE journal_general_id = '$id' 
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

    function getJournalGeneralLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(journal_general_code,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  journal_general_lastcode 
        FROM tb_journal_general 
        WHERE journal_general_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['journal_general_lastcode'];
        }

    }

   
    function updateJournalGeneralByID($id,$data = []){
        $sql = " UPDATE tb_journal_general SET 
        journal_general_code = '".$data['journal_general_code']."', 
        journal_general_date = '".$data['journal_general_date']."', 
        journal_general_name = '".static::$db->real_escape_string($data['journal_general_name'])."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW()  
        WHERE journal_general_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }



    function insertJournalGeneral($data = []){
        $sql = " INSERT INTO tb_journal_general (
            journal_general_code, 
            journal_general_date,
            journal_general_name,
            addby,
            adddate,
            updateby, 
            lastupdate) 
        VALUES ('".
        $data['journal_general_code']."','".
        $data['journal_general_date']."','".
        static::$db->real_escape_string($data['journal_general_name'])."','".
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



    function deleteJournalGeneralByID($id){
        $sql = " DELETE FROM tb_journal_general WHERE journal_general_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        $sql = " DELETE FROM tb_journal_general_list WHERE journal_general_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }


}
?>