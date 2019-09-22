<?php

require_once("BaseModel.php");
require_once("MaintenanceFinanceModel.php");
class CheckModel extends BaseModel{

    private $maintenance;

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }

        $this->maintenance = new MaintenanceFinanceModel;
    }

    function getCheckBy($check_type = "",$date_start = "",$date_end = "",$customer_id = "",$keyword = "",$check_status="",$check_date_deposit = "", $lock_1 = "0", $lock_2 = "0" ){

        $str_customer = "";
        $str_date = "";
        $str_type = "";
        $str_status = "";
        $str_deposit = "";

        $str_lock = "";

        if($lock_1 == "1" && $lock_2 == "1"){
            $str_lock = "AND (paper_lock_1 = '0' OR paper_lock_2 = '0') ";
        }else if ($lock_1 == "1") {
            $str_lock = "AND paper_lock_1 = '0' ";
        }else if($lock_2 == "1"){
            $str_lock = "AND paper_lock_2 = '0' ";
        }

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(check_date_recieve,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(check_date_recieve,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(check_date_recieve,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(check_date_recieve,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }


        if($customer_id != ""){
            $str_customer = "AND tb2.customer_id = '$customer_id' ";
        }

        if($check_type != ""){
            $str_type = "AND check_type = '$check_type' ";
        }

        if($check_status != ""){
            $str_status  = "AND check_status = '$check_status' ";
        }

        if($check_date_deposit == "0"){
            $str_deposit  = "AND check_date_deposit = '' ";
        } else if($check_date_deposit == "1"){
            $str_deposit  = "AND check_date_deposit != '' ";
        }

        $sql = " SELECT *
        FROM tb_check 
        LEFT JOIN tb_customer as tb2 ON tb_check.customer_id = tb2.customer_id 
        LEFT JOIN tb_paper_lock ON SUBSTRING(tb_check.check_date_recieve,3,9)=SUBSTRING(tb_paper_lock.paper_lock_date,3,9) 
        WHERE ( 
             check_code LIKE ('%$keyword%') 
        ) 
        $str_lock 
        $str_type
        $str_status
        $str_deposit 
        $str_customer 
        $str_date 
        ORDER BY STR_TO_DATE(check_date_recieve,'%d-%m-%Y %H:%i:%s'), check_code DESC 
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

    function getCheckByCode($check_code){
        $sql = " SELECT * 
        FROM tb_check 
        WHERE check_code = '$check_code' 
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




    function getCheckByID($id){
        $sql = " SELECT * 
        FROM tb_check 
        WHERE check_id = '$id' 
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


    function getCheckViewByID($id){
        $sql = " SELECT *   
        FROM tb_check 
        LEFT JOIN tb_bank ON tb_check.bank_id = tb_bank.bank_id
        WHERE check_id = '$id' 
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

    function getCheckPassViewByID($id){
        $sql = " SELECT *   
        FROM tb_check 
        LEFT JOIN tb_bank_account ON tb_check.bank_deposit_id = tb_bank_account.bank_account_id
        WHERE check_id = '$id' 
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

    function getCheckViewListByjournalGeneralID($id){
        $sql = " SELECT *   
        FROM tb_journal_general_list 
        LEFT JOIN tb_check ON tb_journal_general_list.journal_cheque_id = tb_check.check_id
        LEFT JOIN tb_bank ON tb_check.bank_id = tb_bank.bank_id
        WHERE journal_general_id = '$id' AND tb_journal_general_list.journal_cheque_id > '0' 
        GROUP BY check_id 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data [] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getCheckViewListByjournalPaymentID($id){
        $sql = " SELECT *   
        FROM tb_journal_cash_payment_list 
        LEFT JOIN tb_check ON tb_journal_cash_payment_list.journal_cheque_id = tb_check.check_id
        LEFT JOIN tb_bank ON tb_check.bank_id = tb_bank.bank_id
        WHERE journal_cash_payment_id = '$id' AND tb_journal_cash_payment_list.journal_cheque_id > '0' 
        GROUP BY check_id 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data [] = $row;
            }
            $result->close();
            return $data;
        }

    }
    
    function getCheckViewListByjournalReceiptID($id){
        $sql = " SELECT *   
        FROM tb_journal_cash_receipt_list 
        LEFT JOIN tb_check ON tb_journal_cash_receipt_list.journal_cheque_id = tb_check.check_id
        LEFT JOIN tb_bank ON tb_check.bank_id = tb_bank.bank_id
        WHERE journal_cash_receipt_id = '$id' AND tb_journal_cash_receipt_list.journal_cheque_id > '0' 
        GROUP BY check_id 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data [] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getCheckViewListByjournalPurchaseID($id){
        $sql = " SELECT *   
        FROM tb_journal_purchase_list 
        LEFT JOIN tb_check ON tb_journal_purchase_list.journal_cheque_id = tb_check.check_id
        LEFT JOIN tb_bank ON tb_check.bank_id = tb_bank.bank_id
        WHERE journal_purchase_id = '$id' AND tb_journal_purchase_list.journal_cheque_id > '0' 
        GROUP BY check_id 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data [] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getCheckViewListByjournalSaleID($id){
        $sql = " SELECT *   
        FROM tb_journal_sale_list 
        LEFT JOIN tb_check ON tb_journal_sale_list.journal_cheque_id = tb_check.check_id
        LEFT JOIN tb_bank ON tb_check.bank_id = tb_bank.bank_id
        WHERE journal_sale_id = '$id' AND tb_journal_sale_list.journal_cheque_id > '0' 
        GROUP BY check_id 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data [] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getCheckViewListByjournalSaleReturnID($id){
        $sql = " SELECT *   
        FROM tb_journal_sale_return_list 
        LEFT JOIN tb_check ON tb_journal_sale_return_list.journal_cheque_id = tb_check.check_id
        LEFT JOIN tb_bank ON tb_check.bank_id = tb_bank.bank_id
        WHERE journal_sale_return_id = '$id' AND tb_journal_sale_return_list.journal_cheque_id > '0' 
        GROUP BY check_id 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data [] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getCheckLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(check_code,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  check_lastcode 
        FROM tb_check 
        WHERE check_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['check_lastcode'];
        }

    }


    function updateCheckByID($id,$data = []){
        $sql = " UPDATE tb_check SET 
        check_code = '".$data['check_code']."', 
        check_date_write = '".$data['check_date_write']."', 
        check_date_recieve = '".$data['check_date_recieve']."', 
        bank_id = '".$data['bank_id']."', 
        bank_branch = '".static::$db->real_escape_string($data['bank_branch'])."', 
        customer_id = '".$data['customer_id']."', 
        check_remark = '".static::$db->real_escape_string($data['check_remark'])."', 
        check_total = '".$data['check_total']."', 
        check_type = '".$data['check_type']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE check_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }
    

    function updateCheckDepositByID($id,$data = []){
        $sql = " UPDATE tb_check SET 
        check_date_deposit = '".$data['check_date_deposit']."', 
        check_fee = '".$data['check_fee']."', 
        bank_deposit_id = '".$data['bank_deposit_id']."',
        updateby = '".$data['updateby']."', 
        lastupdate = NOW()  
        WHERE check_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function updateCheckPassByID($id,$data = []){
        $sql = " UPDATE tb_check SET 
        check_status = '".$data['check_status']."', 
        check_date_pass = '".$data['check_date_pass']."',
        updateby = '".$data['updateby']."', 
        lastupdate = NOW()  
        WHERE check_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) { 
            $cheque = $this->getCheckPassViewByID($id);
            if($data['check_status'] == '1'){ 
                $this->maintenance->passChequeReceipt($cheque);
            }else{
                $this->maintenance->unpassChequeReceipt($cheque['check_code']); 
            }
           return true;
        }else {
            return false;
        } 
    }



    function insertCheck($data = []){
        $sql = " INSERT INTO tb_check (
            check_code,
            check_date_write,
            check_date_recieve,
            bank_id,
            bank_branch,
            customer_id,
            check_remark,
            check_total,
            check_type,
            addby,
            adddate,
            updateby,
            lastupdate) 
        VALUES ('".
        $data['check_code']."','".
        $data['check_date_write']."','".
        $data['check_date_recieve']."','".
        $data['bank_id']."','".
        static::$db->real_escape_string($data['bank_branch'])."','".
        $data['customer_id']."','".
        static::$db->real_escape_string($data['check_remark'])."','".
        $data['check_total']."','".
        $data['check_type']."','".
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

    function getJournalByChequeID($id){
        //------------------------- General Journal ------------------------------------------------------------- 
        $sql_general = " SELECT 
        tb_journal_general.journal_general_id as journal_id, 
        journal_general_code as journal_code, 
        journal_general_date as journal_date,
        journal_general_name  as journal_name 
        FROM tb_journal_general  
        LEFT JOIN tb_journal_general_list ON tb_journal_general_list.journal_general_id = tb_journal_general.journal_general_id  
        WHERE  journal_cheque_id = '$id' 
        GROUP BY tb_journal_general.journal_general_id 
        ORDER BY journal_general_code DESC 
        "; 
        //------------------------- End General Journal ------------------------------------------------------------- 



        //------------------------- Purchase Journal -------------------------------------------------------------  
        $sql_purchase = " SELECT 
        tb_journal_purchase.journal_purchase_id as journal_id, 
        journal_purchase_code as journal_code, 
        journal_purchase_date as journal_date,
        journal_purchase_name  as journal_name  
        FROM tb_journal_purchase 
        LEFT JOIN tb_journal_purchase_list ON tb_journal_purchase_list.journal_purchase_id = tb_journal_purchase.journal_purchase_id  
        WHERE  journal_cheque_id = '$id' 
        GROUP BY tb_journal_purchase.journal_purchase_id 
        ORDER BY journal_purchase_code DESC 
        "; 
        //------------------------- End Purchase Journal -------------------------------------------------------------



        //------------------------- Sale Journal ------------------------------------------------------------- 
        $sql_sale = " SELECT 
        tb_journal_sale.journal_sale_id as journal_id, 
        journal_sale_code as journal_code, 
        journal_sale_date as journal_date,
        journal_sale_name  as journal_name 
        FROM tb_journal_sale 
        LEFT JOIN tb_journal_sale_list ON tb_journal_sale_list.journal_sale_id = tb_journal_sale.journal_sale_id  
        WHERE  journal_cheque_id = '$id' 
        GROUP BY tb_journal_sale.journal_sale_id  
        ORDER BY journal_sale_code DESC 
        "; 
        //------------------------- End Sale Journal -------------------------------------------------------------


        //------------------------- Sale Return Journal ------------------------------------------------------------- 
        $sql_sale_return = " SELECT 
        tb_journal_sale_return.journal_sale_return_id as journal_id, 
        journal_sale_return_code as journal_code, 
        journal_sale_return_date as journal_date,
        journal_sale_return_name  as journal_name 
        FROM tb_journal_sale_return 
        LEFT JOIN tb_journal_sale_return_list ON tb_journal_sale_return_list.journal_sale_return_id = tb_journal_sale_return.journal_sale_return_id  
        WHERE  journal_cheque_id = '$id' 
        GROUP BY tb_journal_sale_return.journal_sale_return_id  
        ORDER BY journal_sale_return_code DESC 
        "; 
        //------------------------- End Sale Return Journal -------------------------------------------------------------



        //------------------------- Cash Payment Journal ------------------------------------------------------------- 
        $sql_cash_payment = " SELECT 
        tb_journal_cash_payment.journal_cash_payment_id as journal_id, 
        journal_cash_payment_code as journal_code, 
        journal_cash_payment_date as journal_date,
        journal_cash_payment_name  as journal_name 
        FROM tb_journal_cash_payment 
        LEFT JOIN tb_journal_cash_payment_list ON tb_journal_cash_payment_list.journal_cash_payment_id = tb_journal_cash_payment.journal_cash_payment_id  
        WHERE  journal_cheque_id = '$id' 
        GROUP BY tb_journal_cash_payment.journal_cash_payment_id 
        ORDER BY journal_cash_payment_code DESC 
        "; 
        //------------------------- End Cash Payment Journal -------------------------------------------------------------



        //------------------------- Cash Receipt Journal ------------------------------------------------------------- 
        $sql_cash_receipt = " SELECT
        tb_journal_cash_receipt.journal_cash_receipt_id as journal_id, 
        journal_cash_receipt_code as journal_code, 
        journal_cash_receipt_date as journal_date,
        journal_cash_receipt_name  as journal_name 
        FROM tb_journal_cash_receipt 
        LEFT JOIN tb_journal_cash_receipt_list ON tb_journal_cash_receipt_list.journal_cash_receipt_id = tb_journal_cash_receipt.journal_cash_receipt_id  
        WHERE  journal_cheque_id = '$id' 
        GROUP BY tb_journal_cash_receipt.journal_cash_receipt_id 
        ORDER BY journal_cash_receipt_code DESC 
        "; 
        //------------------------- End Cash Receipt Journal -------------------------------------------------------------


       $sql =" SELECT *
               FROM   (($sql_general)  
               UNION   ALL  ($sql_purchase) 
               UNION   ALL  ($sql_sale) 
               UNION   ALL  ($sql_sale_return) 
               UNION   ALL  ($sql_cash_payment) 
               UNION   ALL  ($sql_cash_receipt)) as tb_journal    
               ORDER BY journal_code ASC
        ";  

        //echo $sql."<br><br>";

       if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           $data = [];
           while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
               $data [] = $row;
           }
           $result->close();
           return $data;
       }
   }



    function deleteCheckByID($id){
        $sql = " DELETE FROM tb_check WHERE check_id = '$id' ";
        if(mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)){ 
            return true;
        }else{
            return false;
        }
    }


}
?>