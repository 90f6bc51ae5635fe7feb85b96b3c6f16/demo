<?php

require_once("BaseModel.php");
class OfficialReceiptModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getOfficialReceiptBy($date_start = "",$date_end = "",$customer_id = "",$keyword = "",$user_id = "", $lock_1 = "0", $lock_2 = "0"){

        $str_customer = "";
        $str_date = "";
        $str_user = "";

        $str_lock = "";

        if($lock_1 == "1" && $lock_2 == "1"){
            $str_lock = "AND (paper_lock_1 = '0' OR paper_lock_2 = '0') ";
        }else if ($lock_1 == "1") {
            $str_lock = "AND paper_lock_1 = '0' ";
        }else if($lock_2 == "1"){
            $str_lock = "AND paper_lock_2 = '0' ";
        }

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(official_receipt_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(official_receipt_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(official_receipt_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(official_receipt_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND employee_id = '$user_id' ";
        }

        if($customer_id != ""){
            $str_customer = "AND tb2.customer_id = '$customer_id' ";
        }


        $sql = " SELECT official_receipt_id, 
        official_receipt_code, 
        official_receipt_date, 
        official_receipt_total,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name,  
        IFNULL(CONCAT(tb2.customer_name_en,' (',tb2.customer_name_th,')'),'-') as customer_name  
        FROM tb_official_receipt 
        LEFT JOIN tb_user as tb1 ON tb_official_receipt.employee_id = tb1.user_id 
        LEFT JOIN tb_customer as tb2 ON tb_official_receipt.customer_id = tb2.customer_id 
        LEFT JOIN tb_paper_lock ON SUBSTRING(tb_official_receipt.official_receipt_date,3,9)=SUBSTRING(tb_paper_lock.paper_lock_date,3,9) 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%')  
            OR  official_receipt_code LIKE ('%$keyword%') 
        )  
        $str_lock 
        $str_customer 
        $str_date 
        $str_user  
        ORDER BY STR_TO_DATE(official_receipt_date,'%d-%m-%Y %H:%i:%s'), official_receipt_code DESC 
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

    function getOfficialReceiptByID($id){
        $sql = " SELECT * 
        FROM tb_official_receipt 
        LEFT JOIN tb_customer ON tb_official_receipt.customer_id = tb_customer.customer_id 
        LEFT JOIN tb_user ON tb_official_receipt.employee_id = tb_user.user_id 
        WHERE official_receipt_id = '$id' 
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

    function getOfficialReceiptViewByID($id){
        $sql = " SELECT *   
        FROM tb_official_receipt 
        LEFT JOIN tb_user ON tb_official_receipt.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user.user_position_id 
        LEFT JOIN tb_customer ON tb_official_receipt.customer_id = tb_customer.customer_id 
        WHERE official_receipt_id = '$id' 
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

    function getOfficialReceiptLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(official_receipt_code,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  official_receipt_lastcode 
        FROM tb_official_receipt
        WHERE official_receipt_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['official_receipt_lastcode'];
        }

    }

    function getCustomerOrder(){

        $sql = "SELECT tb_customer.customer_id, customer_name_en , customer_name_th 
                FROM tb_customer 
                WHERE customer_id IN ( 
                    SELECT DISTINCT customer_id 
                    FROM tb_billing_note  
                    LEFT JOIN tb_billing_note_list ON tb_billing_note.billing_note_id = tb_billing_note_list.billing_note_id 
                    WHERE billing_note_list_id NOT IN (
                        SELECT billing_note_list_id 
                        FROM tb_official_receipt_list
                        GROUP BY billing_note_list_id 
                    ) 
                    GROUP BY customer_id 
                ) 
                ORDER BY customer_name_en
        ";

        $data = [];
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }
        return $data;
    }


    function generateOfficialReceiptListByCustomerId($customer_id, $data = [] ,$search=""){

        $str ='0';

        if(is_array($data)){ 
            for($i=0; $i < count($data) ;$i++){
                $str .= $data[$i];
                if($i + 1 < count($data)){
                    $str .= ',';
                }
            }
        }else if ($data != ''){
            $str = $data;
        }else{
            $str='0';
        }

        $sql_customer = "SELECT billing_note_list_id, 
        invoice_customer_code,
        invoice_customer_date as official_receipt_list_date, 
        invoice_customer_due as official_receipt_list_due,
        billing_note_code,
        billing_note_list_balance as official_receipt_inv_amount,
        billing_note_list_balance as official_receipt_bal_amount 
        FROM tb_billing_note 
        LEFT JOIN tb_billing_note_list ON tb_billing_note.billing_note_id = tb_billing_note_list.billing_note_id 
        LEFT JOIN tb_invoice_customer ON tb_billing_note_list.invoice_customer_id = tb_invoice_customer.invoice_customer_id 
        WHERE tb_billing_note_list.billing_note_list_id NOT IN ($str) 
        AND tb_billing_note_list.billing_note_list_id NOT IN (
            SELECT billing_note_list_id 
            FROM tb_official_receipt_list 
            GROUP BY billing_note_list_id 
        ) 
        AND tb_billing_note.customer_id = '$customer_id' 
        ORDER BY  invoice_customer_code ";


        $data = [];
        if ($result = mysqli_query(static::$db,$sql_customer, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }

        return $data;
    }

   
    function updateOfficialReceiptByID($id,$data = []){
        $sql = " UPDATE tb_official_receipt SET 
        customer_id = '".$data['customer_id']."', 
        employee_id = '".$data['employee_id']."', 
        official_receipt_code = '".$data['official_receipt_code']."',
        official_receipt_date = '".$data['official_receipt_date']."', 
        official_receipt_name = '".$data['official_receipt_name']."', 
        official_receipt_address = '".$data['official_receipt_address']."', 
        official_receipt_tax = '".$data['official_receipt_tax']."', 
        official_receipt_remark = '".$data['official_receipt_remark']."', 
        official_receipt_sent_name = '".$data['official_receipt_sent_name']."', 
        official_receipt_recieve_name = '".$data['official_receipt_recieve_name']."', 
        official_receipt_total = '".$data['official_receipt_total']."', 
        official_receipt_total_text = '".$data['official_receipt_total_text']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE official_receipt_id = $id 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }


    function insertOfficialReceipt($data = []){
        $sql = " INSERT INTO tb_official_receipt (
            customer_id,
            employee_id,
            official_receipt_code,
            official_receipt_date,
            official_receipt_name,
            official_receipt_address,
            official_receipt_tax,
            official_receipt_remark,
            official_receipt_sent_name,
            official_receipt_recieve_name,
            official_receipt_total,
            official_receipt_total_text,
            addby,
            adddate,
            updateby,
            lastupdate) 
        VALUES ('".
        $data['customer_id']."','".
        $data['employee_id']."','".
        $data['official_receipt_code']."','".
        $data['official_receipt_date']."','".
        $data['official_receipt_name']."','".
        $data['official_receipt_address']."','".
        $data['official_receipt_tax']."','".
        $data['official_receipt_remark']."','".
        $data['official_receipt_sent_name']."','".
        $data['official_receipt_recieve_name']."','".
        $data['official_receipt_total']."','".
        $data['official_receipt_total_text']."','".
        $data['addby']."',".
        "NOW(),'".
        $data['addby'].
        "',NOW()); 
        ";


        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }


    function deleteOfficialReceiptByID($id){

        $sql = " DELETE FROM tb_official_receipt WHERE official_receipt_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_official_receipt_list WHERE official_receipt_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }


}
?>