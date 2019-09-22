<?php

require_once("BaseModel.php");
class DebitNoteModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db,"utf8");
    }

    function getDebitNoteBy($date_start = "",$date_end = "",$customer_id = "",$keyword = "",$user_id = "", $lock_1 = "0", $lock_2 = "0", $sort = "ASC"){
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
            $str_date = "AND STR_TO_DATE(debit_note_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(debit_note_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(debit_note_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(debit_note_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND employee_id = '$user_id' ";
        }

        if($customer_id != ""){
            $str_customer = "AND tb2.customer_id = '$customer_id' ";
        }


        $sql = " SELECT debit_note_id,  
        debit_note_code, 
        debit_note_date, 
        debit_note_total_old,
        debit_note_total,
        debit_note_total_price,
        debit_note_vat,
        debit_note_vat_price,
        debit_note_net_price,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        debit_note_term, 
        debit_note_due, 
        debit_note_due_day, 
        IFNULL( tb2.customer_name_en ,'-') as customer_name  
        FROM tb_debit_note 
        LEFT JOIN tb_user as tb1 ON tb_debit_note.employee_id = tb1.user_id 
        LEFT JOIN tb_customer as tb2 ON tb_debit_note.customer_id = tb2.customer_id 
        LEFT JOIN tb_paper_lock ON SUBSTRING(debit_note_date,3,9)=SUBSTRING(tb_paper_lock.paper_lock_date,3,9) 
        WHERE debit_note_code LIKE ('%$keyword%')  
        $str_lock 
        $str_customer 
        $str_date 
        $str_user  
        ORDER BY STR_TO_DATE(debit_note_date,'%d-%m-%Y %H:%i:%s'), debit_note_code $sort 
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

    function generateDebitNoteByInvoiceCustomerID($invoice_customer_id){
        $sql = " SELECT 
            tb_invoice_customer.customer_id, 
            customer_code, 
            credit_day as debit_note_due_day,
            invoice_customer_code,
            invoice_customer_id,  
            '' as debit_note_date,
            '' as debit_note_remark,
            invoice_customer_name as debit_note_name,
            invoice_customer_address as debit_note_address,
            invoice_customer_tax as debit_note_tax,
            invoice_customer_branch as debit_note_branch 
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

    function getDebitNoteByID($id){
        $sql = " SELECT * 
        FROM tb_debit_note 
        LEFT JOIN tb_customer ON tb_debit_note.customer_id = tb_customer.customer_id 
        LEFT JOIN tb_user ON tb_debit_note.employee_id = tb_user.user_id 
        WHERE debit_note_id = '$id' 
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

    function getDebitNoteByCode($debit_note_code){
        $sql = " SELECT * 
        FROM tb_debit_note 
        LEFT JOIN tb_customer ON tb_debit_note.customer_id = tb_customer.customer_id 
        LEFT JOIN tb_user ON tb_debit_note.employee_id = tb_user.user_id 
        WHERE debit_note_code = '$debit_note_code' 
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

    function getDebitNoteViewByID($id){
        $sql = " SELECT tb_debit_note.* , 
        tb_invoice_customer.*,
        tb_customer.*, 
        tb1.user_name ,
        tb1.user_lastname ,
        tb1.user_signature,
        tb2.user_name as sale_name ,
        tb2.user_lastname as sale_lastname
        FROM tb_debit_note  
        LEFT JOIN tb_invoice_customer ON tb_debit_note.invoice_customer_id = tb_invoice_customer.invoice_customer_id 
        LEFT JOIN tb_user as tb1 ON tb_debit_note.employee_id = tb1.user_id 
        LEFT JOIN tb_user as tb2 ON tb_invoice_customer.employee_id = tb2.user_id 
        LEFT JOIN tb_customer ON tb_debit_note.customer_id = tb_customer.customer_id 
        WHERE debit_note_id = '$id' 
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

    function getDebitNoteLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(debit_note_code,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  debit_note_lastcode 
        FROM tb_debit_note
        WHERE debit_note_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['debit_note_lastcode'];
        }

    }


    function generateDebitNoteListByInvoiceCustomerId($invoice_customer_id, $data = [],$search=""){

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
            SELECT SUM(debit_note_list_qty) 
            FROM tb_debit_note_list 
            WHERE invoice_customer_list_id = tb2.invoice_customer_list_id 
        ),0) ,0) as debit_note_list_qty,  
        invoice_customer_list_price as debit_note_list_price, 
        invoice_customer_list_total as debit_note_list_total, 
        invoice_customer_list_product_name as debit_note_list_product_name, 
        invoice_customer_list_product_detail as debit_note_list_product_detail, 
        invoice_customer_list_remark as debit_note_list_remark 
        FROM tb_invoice_customer 
        LEFT JOIN tb_invoice_customer_list as tb2 ON tb_invoice_customer.invoice_customer_id = tb2.invoice_customer_id 
        LEFT JOIN tb_product ON tb2.product_id = tb_product.product_id 
        WHERE tb_invoice_customer.invoice_customer_id = '$invoice_customer_id' 
        AND tb2.invoice_customer_list_id NOT IN ($str) 
        AND tb2.invoice_customer_list_id IN (
            SELECT tb_invoice_customer_list.invoice_customer_list_id 
            FROM tb_invoice_customer_list  
            LEFT JOIN tb_debit_note_list ON  tb_invoice_customer_list.invoice_customer_list_id = tb_debit_note_list.invoice_customer_list_id 
            GROUP BY tb_invoice_customer_list.invoice_customer_list_id 
            HAVING IFNULL(SUM(debit_note_list_qty),0) < AVG(invoice_customer_list_qty)  
        ) 
        AND (product_name LIKE ('%$search%') OR invoice_customer_code LIKE ('%$search%')) ";

        //echo "<pre>".$sql_customer."</pre>";

        $data = [];
        if ($result = mysqli_query(static::$db,$sql_customer, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }

        return $data;
    }

   
    function updateDebitNoteByID($id,$data = []){
        $sql = " UPDATE tb_debit_note SET 
        customer_id = '".$data['customer_id']."', 
        employee_id = '".$data['employee_id']."', 
        invoice_customer_id = '".$data['invoice_customer_id']."', 
        debit_note_invoice_code = '".$data['debit_note_invoice_code']."',  
        debit_note_code = '".$data['debit_note_code']."', 
        debit_note_total_old = '".$data['debit_note_total_old']."', 
        debit_note_total = '".$data['debit_note_total']."', 
        debit_note_total_price = '".$data['debit_note_total_price']."', 
        debit_note_vat = '".$data['debit_note_vat']."', 
        debit_note_vat_price = '".$data['debit_note_vat_price']."', 
        debit_note_net_price = '".$data['debit_note_net_price']."', 
        debit_note_date = '".$data['debit_note_date']."', 
        debit_note_remark = '".$data['debit_note_remark']."', 
        debit_note_name = '".$data['debit_note_name']."', 
        debit_note_address = '".$data['debit_note_address']."', 
        debit_note_tax = '".$data['debit_note_tax']."', 
        debit_note_branch = '".$data['debit_note_branch']."', 
        debit_note_term = '".$data['debit_note_term']."', 
        debit_note_due = '".$data['debit_note_due']."', 
        debit_note_due_day = '".$data['debit_note_due_day']."', 
        debit_note_close = '".$data['debit_note_close']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE debit_note_id = $id 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }


    function insertDebitNote($data = []){
        $sql = " INSERT INTO tb_debit_note (
            customer_id,
            employee_id,
            invoice_customer_id,
            debit_note_invoice_code, 
            debit_note_code,
            debit_note_total_old,
            debit_note_total,
            debit_note_total_price,
            debit_note_vat,
            debit_note_vat_price,
            debit_note_net_price,
            debit_note_date,
            debit_note_remark,
            debit_note_name,
            debit_note_address,
            debit_note_tax,
            debit_note_branch,
            debit_note_term,
            debit_note_due,
            debit_note_due_day,
            debit_note_close,
            addby,
            adddate,
            updateby,
            lastupdate) 
        VALUES ('".
        $data['customer_id']."','".
        $data['employee_id']."','".
        $data['invoice_customer_id']."','".
        $data['debit_note_invoice_code']."','". 
        $data['debit_note_code']."','".
        $data['debit_note_total_old']."','".
        $data['debit_note_total']."','".
        $data['debit_note_total_price']."','".
        $data['debit_note_vat']."','".
        $data['debit_note_vat_price']."','".
        $data['debit_note_net_price']."','".
        $data['debit_note_date']."','".
        $data['debit_note_remark']."','".
        $data['debit_note_name']."','".
        $data['debit_note_address']."','".
        $data['debit_note_tax']."','".
        $data['debit_note_branch']."','".
        $data['debit_note_term']."','".
        $data['debit_note_due']."','".
        $data['debit_note_due_day']."','".
        $data['debit_note_close']."','".
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

    function cancelDebitNoteById($id){
        $sql = " UPDATE tb_debit_note SET 
        debit_note_close = '1', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE debit_note_id = '$id' 
        ";

        //echo $sql;

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function uncancelDebitNoteById($id){
        $sql = " UPDATE tb_debit_note SET 
        debit_note_close = '0', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE debit_note_id = '$id' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }



    function deleteDebitNoteByID($id){


        $sql = " DELETE FROM tb_debit_note WHERE debit_note_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_debit_note_list WHERE debit_note_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }


}
?>