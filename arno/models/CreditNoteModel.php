<?php

require_once("BaseModel.php");
class CreditNoteModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db,"utf8");
    }

    function getCreditNoteBy($date_start = "",$date_end = "",$customer_id = "",$keyword = "",$user_id = "", $lock_1 = "0", $lock_2 = "0", $sort = "ASC"){
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
            $str_date = "AND STR_TO_DATE(credit_note_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(credit_note_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(credit_note_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(credit_note_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND employee_id = '$user_id' ";
        }

        if($customer_id != ""){
            $str_customer = "AND tb2.customer_id = '$customer_id' ";
        }


        $sql = " SELECT credit_note_id, 
        credit_note_type_name,
        tb_credit_note.credit_note_type_id,
        credit_note_code, 
        credit_note_date, 
        credit_note_total_old,
        credit_note_total,
        credit_note_total_price,
        credit_note_vat,
        credit_note_vat_price,
        credit_note_net_price,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        credit_note_term, 
        credit_note_due, 
        credit_note_due_day, 
        IFNULL( tb2.customer_name_en ,'-') as customer_name  
        FROM tb_credit_note 
        LEFT JOIN tb_credit_note_type ON tb_credit_note.credit_note_type_id = tb_credit_note_type.credit_note_type_id 
        LEFT JOIN tb_user as tb1 ON tb_credit_note.employee_id = tb1.user_id 
        LEFT JOIN tb_customer as tb2 ON tb_credit_note.customer_id = tb2.customer_id 
        LEFT JOIN tb_paper_lock ON SUBSTRING(credit_note_date,3,9)=SUBSTRING(tb_paper_lock.paper_lock_date,3,9) 
        WHERE credit_note_code LIKE ('%$keyword%')  
        $str_lock 
        $str_customer 
        $str_date 
        $str_user  
        ORDER BY STR_TO_DATE(credit_note_date,'%d-%m-%Y %H:%i:%s'), credit_note_code $sort 
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

    function generateCreditNoteByInvoiceCustomerID($invoice_customer_id){
        $sql = " SELECT 
            tb_invoice_customer.customer_id, 
            customer_code, 
            credit_day as credit_note_due_day,
            invoice_customer_code,
            invoice_customer_id,
           '1' as  credit_note_type_id,  
            '' as credit_note_date,
            '' as credit_note_remark,
            invoice_customer_name as credit_note_name,
            invoice_customer_address as credit_note_address,
            invoice_customer_tax as credit_note_tax,
            invoice_customer_branch as credit_note_branch 
        FROM tb_invoice_customer 
        LEFT JOIN tb_customer ON tb_invoice_customer.customer_id = tb_customer.customer_id  
        WHERE invoice_customer_id = '$invoice_customer_id' 
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

    function getCreditNoteByID($id){
        $sql = " SELECT * 
        FROM tb_credit_note 
        LEFT JOIN tb_customer ON tb_credit_note.customer_id = tb_customer.customer_id 
        LEFT JOIN tb_user ON tb_credit_note.employee_id = tb_user.user_id 
        WHERE credit_note_id = '$id' 
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

    function getCreditNoteByCode($credit_note_code){
        $sql = " SELECT * 
        FROM tb_credit_note 
        LEFT JOIN tb_customer ON tb_credit_note.customer_id = tb_customer.customer_id 
        LEFT JOIN tb_user ON tb_credit_note.employee_id = tb_user.user_id 
        WHERE credit_note_code = '$credit_note_code' 
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

    function getCreditNoteViewByID($id){
        $sql = " SELECT tb_credit_note.* ,
        tb_credit_note_type.*,
        tb_invoice_customer.*,
        tb_customer.*,
        tb_credit_note.credit_note_type_id as 'credit_note_type',
        tb1.user_name ,
        tb1.user_lastname ,
        tb1.user_signature,
        tb2.user_name as sale_name ,
        tb2.user_lastname as sale_lastname
        FROM tb_credit_note 
        LEFT JOIN tb_credit_note_type ON tb_credit_note.credit_note_type_id = tb_credit_note_type.credit_note_type_id 
        LEFT JOIN tb_invoice_customer ON tb_credit_note.invoice_customer_id = tb_invoice_customer.invoice_customer_id 
        LEFT JOIN tb_user as tb1 ON tb_credit_note.employee_id = tb1.user_id 
        LEFT JOIN tb_user as tb2 ON tb_invoice_customer.employee_id = tb2.user_id 
        LEFT JOIN tb_customer ON tb_credit_note.customer_id = tb_customer.customer_id 
        WHERE credit_note_id = '$id' 
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

    function getCreditNoteLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(credit_note_code,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  credit_note_lastcode 
        FROM tb_credit_note
        WHERE credit_note_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['credit_note_lastcode'];
        }

    }


    function generateCreditNoteListByInvoiceCustomerId($invoice_customer_id, $data = [],$search=""){

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

        $sql_customer = "SELECT tb2.product_id, 
        tb2.invoice_customer_list_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,  
        stock_group_id,
        IFNULL(invoice_customer_list_qty 
        - IFNULL((
            SELECT SUM(credit_note_list_qty) 
            FROM tb_credit_note_list 
            WHERE invoice_customer_list_id = tb2.invoice_customer_list_id 
        ),0) ,0) as credit_note_list_qty,  
        invoice_customer_list_price as credit_note_list_price, 
        invoice_customer_list_total as credit_note_list_total, 
        invoice_customer_list_product_name as credit_note_list_product_name, 
        invoice_customer_list_product_detail as credit_note_list_product_detail, 
        invoice_customer_list_remark as credit_note_list_remark 
        FROM tb_invoice_customer 
        LEFT JOIN tb_invoice_customer_list as tb2 ON tb_invoice_customer.invoice_customer_id = tb2.invoice_customer_id 
        LEFT JOIN tb_product ON tb2.product_id = tb_product.product_id 
        WHERE tb_invoice_customer.invoice_customer_id = '$invoice_customer_id' 
        AND tb2.invoice_customer_list_id NOT IN ($str) 
        AND tb2.invoice_customer_list_id IN (
            SELECT tb_invoice_customer_list.invoice_customer_list_id 
            FROM tb_invoice_customer_list  
            LEFT JOIN tb_credit_note_list ON  tb_invoice_customer_list.invoice_customer_list_id = tb_credit_note_list.invoice_customer_list_id 
            GROUP BY tb_invoice_customer_list.invoice_customer_list_id 
            HAVING IFNULL(SUM(credit_note_list_qty),0) < AVG(invoice_customer_list_qty)  
        ) 
        AND (product_name LIKE ('%$search%') OR invoice_customer_code LIKE ('%$search%')) ";

        //echo $sql_customer;

        $data = [];
        if ($result = mysqli_query(static::$db,$sql_customer, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }

        return $data;
    }

   
    function updateCreditNoteByID($id,$data = []){
        $sql = " UPDATE tb_credit_note SET 
        customer_id = '".$data['customer_id']."', 
        employee_id = '".$data['employee_id']."', 
        invoice_customer_id = '".$data['invoice_customer_id']."', 
        credit_note_invoice_code = '".$data['credit_note_invoice_code']."', 
        credit_note_type_id = '".$data['credit_note_type_id']."', 
        credit_note_code = '".$data['credit_note_code']."', 
        credit_note_total_old = '".$data['credit_note_total_old']."', 
        credit_note_total = '".$data['credit_note_total']."', 
        credit_note_total_price = '".$data['credit_note_total_price']."', 
        credit_note_vat = '".$data['credit_note_vat']."', 
        credit_note_vat_price = '".$data['credit_note_vat_price']."', 
        credit_note_net_price = '".$data['credit_note_net_price']."', 
        credit_note_date = '".$data['credit_note_date']."', 
        credit_note_remark = '".$data['credit_note_remark']."', 
        credit_note_name = '".$data['credit_note_name']."', 
        credit_note_address = '".$data['credit_note_address']."', 
        credit_note_tax = '".$data['credit_note_tax']."', 
        credit_note_branch = '".$data['credit_note_branch']."', 
        credit_note_term = '".$data['credit_note_term']."', 
        credit_note_due = '".$data['credit_note_due']."', 
        credit_note_due_day = '".$data['credit_note_due_day']."', 
        credit_note_close = '".$data['credit_note_close']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE credit_note_id = $id 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }


    function insertCreditNote($data = []){
        $sql = " INSERT INTO tb_credit_note (
            customer_id,
            employee_id,
            invoice_customer_id,
            credit_note_invoice_code,
            credit_note_type_id,
            credit_note_code,
            credit_note_total_old,
            credit_note_total,
            credit_note_total_price,
            credit_note_vat,
            credit_note_vat_price,
            credit_note_net_price,
            credit_note_date,
            credit_note_remark,
            credit_note_name,
            credit_note_address,
            credit_note_tax,
            credit_note_branch,
            credit_note_term,
            credit_note_due,
            credit_note_due_day,
            credit_note_close,
            addby,
            adddate,
            updateby,
            lastupdate) 
        VALUES ('".
        $data['customer_id']."','".
        $data['employee_id']."','".
        $data['invoice_customer_id']."','".
        $data['credit_note_invoice_code']."','".
        $data['credit_note_type_id']."','".
        $data['credit_note_code']."','".
        $data['credit_note_total_old']."','".
        $data['credit_note_total']."','".
        $data['credit_note_total_price']."','".
        $data['credit_note_vat']."','".
        $data['credit_note_vat_price']."','".
        $data['credit_note_net_price']."','".
        $data['credit_note_date']."','".
        $data['credit_note_remark']."','".
        $data['credit_note_name']."','".
        $data['credit_note_address']."','".
        $data['credit_note_tax']."','".
        $data['credit_note_branch']."','".
        $data['credit_note_term']."','".
        $data['credit_note_due']."','".
        $data['credit_note_due_day']."','".
        $data['credit_note_close']."','".
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

    function cancelCreditNoteById($id){
        $sql = " UPDATE tb_credit_note SET 
        credit_note_close = '1', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE credit_note_id = '$id' 
        ";

        //echo $sql;

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function uncancelCreditNoteById($id){
        $sql = " UPDATE tb_credit_note SET 
        credit_note_close = '0', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE credit_note_id = '$id' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }



    function deleteCreditNoteByID($id){


        $sql = " DELETE FROM tb_credit_note WHERE credit_note_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_credit_note_list WHERE credit_note_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }


}
?>