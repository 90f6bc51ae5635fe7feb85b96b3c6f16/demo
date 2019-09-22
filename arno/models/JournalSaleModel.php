<?php

require_once("BaseModel.php");
class JournalSaleModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db,"utf8");
    }

    function getJournalSaleBy($date_start = "", $date_end = "",$keyword = "", $lock_1 = "0", $lock_2 = "0", $sort = "ASC"){


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
            $str_date = "AND STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }



        $sql = " SELECT tb_journal_sale.journal_sale_id, 
        journal_sale_code, 
        journal_sale_date,
        journal_sale_name,
        tb_journal_sale.invoice_customer_id,
        tb_invoice_customer.invoice_customer_code, 
        IFNULL(SUM(journal_sale_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_sale_list_credit),0) as journal_credit
        FROM tb_journal_sale 
        LEFT JOIN tb_journal_sale_list ON tb_journal_sale_list.journal_sale_id = tb_journal_sale.journal_sale_id
        LEFT JOIN tb_invoice_customer ON tb_journal_sale.invoice_customer_id = tb_invoice_customer.invoice_customer_id 
        LEFT JOIN tb_paper_lock ON SUBSTRING(tb_journal_sale.journal_sale_date,3,9)=SUBSTRING(tb_paper_lock.paper_lock_date,3,9) 
        WHERE ( 
                journal_sale_code LIKE ('%$keyword%') 
            OR  journal_sale_name LIKE ('%$keyword%') 
        ) 
        $str_lock 
        $str_date 
        GROUP BY tb_journal_sale.journal_sale_id 
        ORDER BY journal_sale_code $sort 
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

    function getJournalSaleByInvoiceCustomerID($invoice_customer_id){
        $sql = " SELECT * 
        FROM tb_journal_sale 
        WHERE invoice_customer_id = '$invoice_customer_id' 
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

    function getJournalSaleByKeyword(){
       
        $sql = " SELECT journal_sale_id, 
        journal_sale_code,  
        journal_sale_name 
        FROM tb_journal_sale  
        WHERE journal_sale_code LIKE ('%$keyword%')  OR  journal_sale_name LIKE ('%$keyword%') 
        ORDER BY journal_sale_code DESC 
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





    function getJournalSaleByID($id){
        $sql = " SELECT * 
        FROM tb_journal_sale 
        WHERE journal_sale_id = '$id' 
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

    function getJournalSaleByCode($journal_sale_code){
        $sql = " SELECT * 
        FROM tb_journal_sale 
        WHERE journal_sale_code = '$journal_sale_code' 
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

    function getJournalSaleViewByID($id){
        $sql = " SELECT journal_sale_id, 
        invoice_customer_id, 
        journal_sale_code, 
        journal_sale_date,
        journal_sale_name,
        addby,
        adddate,
        updateby,
        lastupdate,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as add_name, 
        IFNULL(CONCAT(tb2.user_name,' ',tb2.user_lastname),'-') as update_name 
        FROM tb_journal_sale 
        LEFT JOIN tb_user as tb1 ON tb_journal_sale.addby = tb1.user_id 
        LEFT JOIN tb_user as tb2 ON tb_journal_sale.updateby = tb2.user_id 
        WHERE journal_sale_id = '$id' 
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

    function getJournalSaleLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(journal_sale_code,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  journal_sale_lastcode 
        FROM tb_journal_sale 
        WHERE journal_sale_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['journal_sale_lastcode'];
        }

    }

   
    function updateJournalSaleByID($id,$data = []){
        $sql = " UPDATE tb_journal_sale SET 
        journal_sale_code = '".$data['journal_sale_code']."', 
        journal_sale_date = '".$data['journal_sale_date']."', 
        journal_sale_name = '".$data['journal_sale_name']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW()  
        WHERE journal_sale_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }



    function insertJournalSale($data = []){
        $sql = " INSERT INTO tb_journal_sale (
            invoice_customer_id,
            journal_sale_code, 
            journal_sale_date,
            journal_sale_name,
            addby,
            adddate,
            updateby, 
            lastupdate) 
        VALUES ('".
        $data['invoice_customer_id']."','".
        $data['journal_sale_code']."','".
        $data['journal_sale_date']."','".
        $data['journal_sale_name']."','".
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



    function deleteJournalSaleByID($id){
        $sql = " DELETE FROM tb_journal_sale WHERE journal_sale_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        $sql = " DELETE FROM tb_journal_sale_list WHERE journal_sale_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }

    function deleteJournalSaleByInvoiceCustomerID($invoice_customer_id){

        $sql = " DELETE FROM tb_journal_sale_list WHERE journal_sale_id IN (SELECT journal_sale_id FROM tb_journal_sale WHERE invoice_customer_id = '$invoice_customer_id' ) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_journal_sale WHERE  invoice_customer_id = '$invoice_customer_id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }


}
?>