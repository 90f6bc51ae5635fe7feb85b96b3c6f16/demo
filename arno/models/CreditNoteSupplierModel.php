<?php

require_once("BaseModel.php");
class CreditNoteSupplierModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db,"utf8");
    }

    function getCreditNoteSupplierBy($date_start = "",$date_end = "",$supplier_id = "",$keyword = "",$user_id = "", $lock_1 = "0", $lock_2 = "0", $sort = "ASC"){
        $str_supplier = "";
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
            $str_date = "AND STR_TO_DATE(credit_note_supplier_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(credit_note_supplier_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(credit_note_supplier_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(credit_note_supplier_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND employee_id = '$user_id' ";
        }

        if($supplier_id != ""){
            $str_supplier = "AND tb2.supplier_id = '$supplier_id' ";
        }


        $sql = " SELECT credit_note_supplier_id,  
        credit_note_supplier_code, 
        credit_note_supplier_type_id, 
        credit_note_supplier_date, 
        credit_note_supplier_total_old,
        credit_note_supplier_total,
        credit_note_supplier_total_price,
        credit_note_supplier_vat,
        credit_note_supplier_vat_price,
        credit_note_supplier_net_price,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        credit_note_supplier_term, 
        credit_note_supplier_due, 
        credit_note_supplier_due_day, 
        IFNULL( tb2.supplier_name_en ,'-') as supplier_name  
        FROM tb_credit_note_supplier  
        LEFT JOIN tb_user as tb1 ON tb_credit_note_supplier.employee_id = tb1.user_id 
        LEFT JOIN tb_supplier as tb2 ON tb_credit_note_supplier.supplier_id = tb2.supplier_id 
        LEFT JOIN tb_paper_lock ON SUBSTRING(credit_note_supplier_date,3,9)=SUBSTRING(tb_paper_lock.paper_lock_date,3,9) 
        WHERE credit_note_supplier_code LIKE ('%$keyword%')  
        $str_lock 
        $str_supplier 
        $str_date 
        $str_user  
        ORDER BY STR_TO_DATE(credit_note_supplier_date,'%d-%m-%Y %H:%i:%s'), credit_note_supplier_code $sort 
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

    function generateCreditNoteSupplierByInvoiceSupplierID($invoice_supplier_id){
        $sql = " SELECT 
            tb_invoice_supplier.supplier_id, 
            supplier_code, 
            credit_day as credit_note_supplier_due_day,
            invoice_supplier_code,
            invoice_supplier_code_gen,
            invoice_supplier_id,
           '1' as  credit_note_supplier_type_id,  
            '' as credit_note_supplier_date,
            '' as credit_note_supplier_remark,
            invoice_supplier_name as credit_note_supplier_name,
            invoice_supplier_address as credit_note_supplier_address,
            invoice_supplier_tax as credit_note_supplier_tax,
            invoice_supplier_branch as credit_note_supplier_branch 
        FROM tb_invoice_supplier 
        LEFT JOIN tb_supplier ON tb_invoice_supplier.supplier_id = tb_supplier.supplier_id  
        WHERE invoice_supplier_id = '$invoice_supplier_id' 
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

    function getCreditNoteSupplierByID($id){
        $sql = " SELECT * 
        FROM tb_credit_note_supplier 
        LEFT JOIN tb_supplier ON tb_credit_note_supplier.supplier_id = tb_supplier.supplier_id 
        LEFT JOIN tb_user ON tb_credit_note_supplier.employee_id = tb_user.user_id 
        WHERE credit_note_supplier_id = '$id' 
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

    function getCreditNoteSupplierByCode($credit_note_supplier_code){
        $sql = " SELECT * 
        FROM tb_credit_note_supplier 
        LEFT JOIN tb_supplier ON tb_credit_note_supplier.supplier_id = tb_supplier.supplier_id 
        LEFT JOIN tb_user ON tb_credit_note_supplier.employee_id = tb_user.user_id 
        WHERE credit_note_supplier_code = '$credit_note_supplier_code' 
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

    function getCreditNoteSupplierViewByID($id){
        $sql = " SELECT *   
        FROM tb_credit_note_supplier  
        LEFT JOIN tb_invoice_supplier ON tb_credit_note_supplier.invoice_supplier_id = tb_invoice_supplier.invoice_supplier_id 
        LEFT JOIN tb_user ON tb_credit_note_supplier.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user.user_position_id 
        LEFT JOIN tb_supplier ON tb_credit_note_supplier.supplier_id = tb_supplier.supplier_id 
        WHERE credit_note_supplier_id = '$id' 
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

    function getCreditNoteSupplierLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(credit_note_supplier_code,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  credit_note_supplier_lastcode 
        FROM tb_credit_note_supplier
        WHERE credit_note_supplier_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['credit_note_supplier_lastcode'];
        }

    }


    function generateCreditNoteSupplierListByInvoiceSupplierId($invoice_supplier_id, $data = [],$search=""){

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

        $sql_supplier = "SELECT tb2.product_id, 
        tb2.invoice_supplier_list_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,  
        stock_group_id,
        IFNULL(invoice_supplier_list_qty 
        - IFNULL((
            SELECT SUM(credit_note_supplier_list_qty) 
            FROM tb_credit_note_supplier_list 
            WHERE invoice_supplier_list_id = tb2.invoice_supplier_list_id 
        ),0) ,0) as credit_note_supplier_list_qty,  
        invoice_supplier_list_price as credit_note_supplier_list_price, 
        invoice_supplier_list_total as credit_note_supplier_list_total, 
        invoice_supplier_list_product_name as credit_note_supplier_list_product_name, 
        invoice_supplier_list_product_detail as credit_note_supplier_list_product_detail, 
        invoice_supplier_list_remark as credit_note_supplier_list_remark 
        FROM tb_invoice_supplier 
        LEFT JOIN tb_invoice_supplier_list as tb2 ON tb_invoice_supplier.invoice_supplier_id = tb2.invoice_supplier_id 
        LEFT JOIN tb_product ON tb2.product_id = tb_product.product_id 
        WHERE tb_invoice_supplier.invoice_supplier_id = '$invoice_supplier_id' 
        AND tb2.invoice_supplier_list_id NOT IN ($str) 
        AND tb2.invoice_supplier_list_id IN (
            SELECT tb_invoice_supplier_list.invoice_supplier_list_id 
            FROM tb_invoice_supplier_list  
            LEFT JOIN tb_credit_note_supplier_list ON  tb_invoice_supplier_list.invoice_supplier_list_id = tb_credit_note_supplier_list.invoice_supplier_list_id 
            GROUP BY tb_invoice_supplier_list.invoice_supplier_list_id 
            HAVING IFNULL(SUM(credit_note_supplier_list_qty),0) < AVG(invoice_supplier_list_qty)  
        ) 
        AND (product_name LIKE ('%$search%') OR invoice_supplier_code LIKE ('%$search%')) ";

        //echo $sql_supplier;

        $data = [];
        if ($result = mysqli_query(static::$db,$sql_supplier, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }

        return $data; 

    }

   
    function updateCreditNoteSupplierByID($id,$data = []){
        $sql = " UPDATE tb_credit_note_supplier SET 
        supplier_id = '".$data['supplier_id']."', 
        employee_id = '".$data['employee_id']."', 
        invoice_supplier_id = '".$data['invoice_supplier_id']."', 
        credit_note_supplier_invoice_code = '".$data['credit_note_supplier_invoice_code']."', 
        credit_note_supplier_type_id = '".$data['credit_note_supplier_type_id']."', 
        credit_note_supplier_code = '".$data['credit_note_supplier_code']."', 
        credit_note_supplier_update_code = '".$data['credit_note_supplier_update_code']."', 
        credit_note_supplier_total_old = '".$data['credit_note_supplier_total_old']."', 
        credit_note_supplier_total = '".$data['credit_note_supplier_total']."', 
        credit_note_supplier_total_price = '".$data['credit_note_supplier_total_price']."', 
        credit_note_supplier_vat = '".$data['credit_note_supplier_vat']."', 
        credit_note_supplier_vat_price = '".$data['credit_note_supplier_vat_price']."', 
        credit_note_supplier_net_price = '".$data['credit_note_supplier_net_price']."', 
        credit_note_supplier_date = '".$data['credit_note_supplier_date']."', 
        credit_note_supplier_remark = '".$data['credit_note_supplier_remark']."', 
        credit_note_supplier_name = '".$data['credit_note_supplier_name']."', 
        credit_note_supplier_address = '".$data['credit_note_supplier_address']."', 
        credit_note_supplier_tax = '".$data['credit_note_supplier_tax']."', 
        credit_note_supplier_branch = '".$data['credit_note_supplier_branch']."', 
        credit_note_supplier_term = '".$data['credit_note_supplier_term']."', 
        credit_note_supplier_due = '".$data['credit_note_supplier_due']."', 
        credit_note_supplier_due_day = '".$data['credit_note_supplier_due_day']."', 
        credit_note_supplier_close = '".$data['credit_note_supplier_close']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW()
        WHERE credit_note_supplier_id = $id 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }


    function insertCreditNoteSupplier($data = []){
        $sql = " INSERT INTO tb_credit_note_supplier (
            supplier_id,
            employee_id,
            invoice_supplier_id,
            credit_note_supplier_invoice_code,
            credit_note_supplier_type_id,
            credit_note_supplier_code,
            credit_note_supplier_update_code,
            credit_note_supplier_total_old,
            credit_note_supplier_total,
            credit_note_supplier_total_price,
            credit_note_supplier_vat,
            credit_note_supplier_vat_price,
            credit_note_supplier_net_price,
            credit_note_supplier_date,
            credit_note_supplier_remark,
            credit_note_supplier_name,
            credit_note_supplier_address,
            credit_note_supplier_tax,
            credit_note_supplier_branch,
            credit_note_supplier_term,
            credit_note_supplier_due,
            credit_note_supplier_due_day,
            credit_note_supplier_close,
            addby,
            adddate,
            updateby,
            lastupdate) 
        VALUES ('".
        $data['supplier_id']."','".
        $data['employee_id']."','".
        $data['invoice_supplier_id']."','".
        $data['credit_note_supplier_invoice_code']."','".
        $data['credit_note_supplier_type_id']."','".
        $data['credit_note_supplier_code']."','".
        $data['credit_note_supplier_update_code']."','".
        $data['credit_note_supplier_total_old']."','".
        $data['credit_note_supplier_total']."','".
        $data['credit_note_supplier_total_price']."','".
        $data['credit_note_supplier_vat']."','".
        $data['credit_note_supplier_vat_price']."','".
        $data['credit_note_supplier_net_price']."','".
        $data['credit_note_supplier_date']."','".
        $data['credit_note_supplier_remark']."','".
        $data['credit_note_supplier_name']."','".
        $data['credit_note_supplier_address']."','".
        $data['credit_note_supplier_tax']."','".
        $data['credit_note_supplier_branch']."','".
        $data['credit_note_supplier_term']."','".
        $data['credit_note_supplier_due']."','".
        $data['credit_note_supplier_due_day']."','".
        $data['credit_note_supplier_close']."','".
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

    function cancelCreditNoteSupplierById($id){
        $sql = " UPDATE tb_credit_note_supplier SET 
        credit_note_supplier_close = '1', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE credit_note_supplier_id = '$id' 
        ";

        //echo $sql;

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function uncancelCreditNoteSupplierById($id){
        $sql = " UPDATE tb_credit_note_supplier SET 
        credit_note_supplier_close = '0', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE credit_note_supplier_id = '$id' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }



    function deleteCreditNoteSupplierByID($id){


        $sql = " DELETE FROM tb_credit_note_supplier WHERE credit_note_supplier_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_credit_note_supplier_list WHERE credit_note_supplier_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }


}
?>