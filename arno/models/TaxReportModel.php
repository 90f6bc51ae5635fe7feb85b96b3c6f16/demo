<?php

require_once("BaseModel.php");
class TaxReportModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getPaperDontUse(){
        $sql ="
                SELECT * ,
                IFNULL(
                            (
                                (
                                    SELECT COUNT(journal_invoice_supplier_id) 
                                    FROM tb_journal_general_list 
                                    WHERE journal_invoice_supplier_id = tb.invoice_supplier_id
                                ) 
                                +
                                (
                                    SELECT COUNT(journal_invoice_supplier_id) 
                                    FROM tb_journal_sale_list 
                                    WHERE journal_invoice_supplier_id = tb.invoice_supplier_id
                                ) 
                                +
                                (
                                    SELECT COUNT(journal_invoice_supplier_id) 
                                    FROM tb_journal_purchase_list 
                                    WHERE journal_invoice_supplier_id = tb.invoice_supplier_id
                                ) 
                                +
                                (
                                    SELECT COUNT(journal_invoice_supplier_id) 
                                    FROM tb_journal_cash_payment_list 
                                    WHERE journal_invoice_supplier_id = tb.invoice_supplier_id
                                ) 
                                +
                                (
                                    SELECT COUNT(journal_invoice_supplier_id) 
                                    FROM tb_journal_cash_receipt_list 
                                    WHERE journal_invoice_supplier_id = tb.invoice_supplier_id
                                ) 
                                
                            )
                        ,0) as use_number 
                FROM `tb_invoice_supplier` as tb  
                WHERE tb.invoice_supplier_begin = 2
                HAVING use_number < 1
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }else{
            return [];
        }

    }

    function getPurchaseTaxReportBy($date_start = "",$date_end = "",$supplier_id = "",$keyword = "",$user_id = ""){

        $str_supplier = "";
        $str_date = "";
        $str_user = "";

        $str_credit_supplier = "";
        $str_credit_date = "";
        $str_credit_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(invoice_supplier_date_recieve,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(invoice_supplier_date_recieve,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
            $str_credit_date = "AND STR_TO_DATE(credit_note_supplier_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(credit_note_supplier_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(invoice_supplier_date_recieve,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
            $str_credit_date = "AND STR_TO_DATE(credit_note_supplier_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(invoice_supplier_date_recieve,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
            $str_credit_date = "AND STR_TO_DATE(credit_note_supplier_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND employee_id = '$user_id' ";
            $str_credit_user = "AND employee_id = '$user_id' ";
        }

        if($supplier_id != ""){
            $str_supplier = "AND tb2.supplier_id = '$supplier_id' ";
            $str_credit_supplier = "AND tb2.supplier_id = '$supplier_id' ";
        }


        $sql = " SELECT * FROM 
        (
            (
                SELECT 
                'invoice' as data_type,
                0 as credit_note_supplier_id,
                invoice_supplier_id, 
                invoice_supplier_code, 
                invoice_supplier_code_gen, 
                invoice_supplier_date, 
                invoice_supplier_vat_price,
                invoice_supplier_total_price,
                IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name,  
                invoice_supplier_name,
                invoice_supplier_tax,
                invoice_supplier_branch,
                IFNULL(
                    (
                        (
                            SELECT COUNT(journal_invoice_supplier_id) 
                            FROM tb_journal_general_list 
                            WHERE journal_invoice_supplier_id = tb.invoice_supplier_id
                        ) 
                        +
                        (
                            SELECT COUNT(journal_invoice_supplier_id) 
                            FROM tb_journal_sale_list 
                            WHERE journal_invoice_supplier_id = tb.invoice_supplier_id
                        ) 
                        +
                        (
                            SELECT COUNT(journal_invoice_supplier_id) 
                            FROM tb_journal_purchase_list 
                            WHERE journal_invoice_supplier_id = tb.invoice_supplier_id
                        ) 
                        +
                        (
                            SELECT COUNT(journal_invoice_supplier_id) 
                            FROM tb_journal_cash_payment_list 
                            WHERE journal_invoice_supplier_id = tb.invoice_supplier_id
                        ) 
                        +
                        (
                            SELECT COUNT(journal_invoice_supplier_id) 
                            FROM tb_journal_cash_receipt_list 
                            WHERE journal_invoice_supplier_id = tb.invoice_supplier_id
                        ) 
                        
                    )
                ,0) as use_number 
                FROM tb_invoice_supplier as tb
                LEFT JOIN tb_user as tb1 ON tb.employee_id = tb1.user_id 
                LEFT JOIN tb_supplier as tb2 ON tb.supplier_id = tb2.supplier_id 
                WHERE ( 
                    CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%')  
                    OR  invoice_supplier_code_gen LIKE ('%$keyword%') 
                ) 
                AND invoice_supplier_vat_price != 0
                $str_supplier 
                $str_date 
                $str_user  
                HAVING use_number > 0
                ORDER BY STR_TO_DATE(invoice_supplier_date,'%d-%m-%Y %H:%i:%s'),invoice_supplier_code_gen ASC 
            ) UNION ALL (
                SELECT 
                'credit_note' as data_type,
                credit_note_supplier_id,
                0 as invoice_supplier_id, 
                credit_note_supplier_invoice_code as invoice_supplier_code, 
                credit_note_supplier_code as invoice_supplier_code_gen, 
                credit_note_supplier_date as invoice_supplier_date, 
                - credit_note_supplier_vat_price as invoice_supplier_vat_price,
                - credit_note_supplier_total_price as invoice_supplier_total_price,
                IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name,  
                credit_note_supplier_name as invoice_supplier_name,
                credit_note_supplier_tax as invoice_supplier_tax,
                credit_note_supplier_branch as invoice_supplier_branch,
                1 as use_number 
                FROM tb_credit_note_supplier as tb
                LEFT JOIN tb_user as tb1 ON tb.employee_id = tb1.user_id 
                LEFT JOIN tb_supplier as tb2 ON tb.supplier_id = tb2.supplier_id 
                WHERE ( 
                    CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%')  
                    OR  credit_note_supplier_code LIKE ('%$keyword%') 
                ) 
                AND credit_note_supplier_vat_price != 0
                $str_credit_supplier 
                $str_credit_date 
                $str_credit_user  
                HAVING use_number > 0
                ORDER BY STR_TO_DATE(credit_note_supplier_date,'%d-%m-%Y %H:%i:%s'),invoice_supplier_code_gen ASC 
            )
        ) AS tb_data 
        ORDER BY STR_TO_DATE(invoice_supplier_date,'%d-%m-%Y %H:%i:%s'),invoice_supplier_code_gen ASC 
         ";

        // echo '<pre>'.$sql.'</pre>';
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getSaleTaxReportBy($date_start = "",$date_end = "",$customer_id = "",$keyword = "",$user_id = ""){

        $str_invoice_customer = "";
        $str_invoice_date = "";
        $str_invoice_user = "";

        $str_credit_customer = "";
        $str_credit_date = "";
        $str_credit_user = "";

        $str_debit_customer = "";
        $str_debit_date = "";
        $str_debit_user = "";

        if($date_start != "" && $date_end != ""){
            $str_invoice_date = "AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
            $str_credit_date = "AND STR_TO_DATE(credit_note_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(credit_note_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
            $str_debit_date = "AND STR_TO_DATE(debit_note_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(debit_note_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_invoice_date = "AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
            $str_credit_date = "AND STR_TO_DATE(credit_note_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
            $str_debit_date = "AND STR_TO_DATE(debit_note_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_invoice_date = "AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
            $str_credit_date = "AND STR_TO_DATE(credit_note_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
            $str_debit_date = "AND STR_TO_DATE(debit_note_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_invoice_user = "AND employee_id = '$user_id' ";
            $str_credit_user = "AND employee_id = '$user_id' ";
            $str_debit_user = "AND employee_id = '$user_id' ";
        }

        if($customer_id != ""){
            $str_invoice_customer = "AND tb_invoice_customer.customer_id = '$customer_id' ";
            $str_credit_customer = "AND tb_credit_note.customer_id = '$customer_id' ";
            $str_debit_customer = "AND tb_debit_note.customer_id = '$customer_id' ";
        }

        $sql = " SELECT * 
        FROM (
            (
                SELECT invoice_customer_id, 
                '0' as credit_note_id, 
                '0' as debit_note_id, 
                invoice_customer_code,   
                invoice_customer_name,   
                invoice_customer_tax,   
                invoice_customer_branch,   
                invoice_customer_date, 
                IF(invoice_customer_close != 0,0,invoice_customer_vat_price) as invoice_customer_vat_price,
                IF(invoice_customer_close != 0,0,invoice_customer_total_price) as invoice_customer_total_price,
                invoice_customer_close,
                IFNULL(CONCAT(tb_user.user_name,' ',tb_user.user_lastname),'-') as employee_name,  
                IFNULL(tb_customer.customer_name_th,tb_customer.customer_name_en) as customer_name   
                FROM tb_invoice_customer 
                LEFT JOIN tb_user  ON tb_invoice_customer.employee_id = tb_user.user_id 
                LEFT JOIN tb_customer  ON tb_invoice_customer.customer_id = tb_customer.customer_id 
                WHERE invoice_customer_code LIKE ('%$keyword%')   
                $str_invoice_customer 
                $str_invoice_date 
                $str_invoice_user  
            ) UNION ALL (
                SELECT '0' as invoice_customer_id, 
                credit_note_id, 
                '0' as debit_note_id, 
                credit_note_code as invoice_customer_code,   
                credit_note_name as invoice_customer_name,   
                credit_note_tax as invoice_customer_tax,   
                credit_note_branch as invoice_customer_branch,   
                credit_note_date as invoice_customer_date, 
                IF(credit_note_close != 0,0,-credit_note_vat_price) as invoice_customer_vat_price,
                IF(credit_note_close != 0,0,-credit_note_total_price) as invoice_customer_total_price,
                credit_note_close as invoice_customer_close,
                IFNULL(CONCAT(tb_user.user_name,' ',tb_user.user_lastname),'-') as employee_name,  
                IFNULL(tb_customer.customer_name_th,tb_customer.customer_name_en) as customer_name  
                FROM tb_credit_note 
                LEFT JOIN tb_user  ON tb_credit_note.employee_id = tb_user.user_id 
                LEFT JOIN tb_customer  ON tb_credit_note.customer_id = tb_customer.customer_id 
                WHERE credit_note_code LIKE ('%$keyword%')   
                $str_credit_customer 
                $str_credit_date 
                $str_credit_user  
            ) UNION ALL (
                SELECT '0' as invoice_customer_id, 
                '0' as credit_note_id, 
                debit_note_id, 
                debit_note_code as invoice_customer_code,   
                debit_note_name as invoice_customer_name,   
                debit_note_tax as invoice_customer_tax,   
                debit_note_branch as invoice_customer_branch,   
                debit_note_date as invoice_customer_date, 
                IF(debit_note_close != 0,0,debit_note_vat_price) as invoice_customer_vat_price,
                IF(debit_note_close != 0,0,debit_note_total_price) as invoice_customer_total_price,
                debit_note_close as invoice_customer_close,
                IFNULL(CONCAT(tb_user.user_name,' ',tb_user.user_lastname),'-') as employee_name,  
                IFNULL(tb_customer.customer_name_th,tb_customer.customer_name_en) as customer_name  
                FROM tb_debit_note 
                LEFT JOIN tb_user  ON tb_debit_note.employee_id = tb_user.user_id 
                LEFT JOIN tb_customer  ON tb_debit_note.customer_id = tb_customer.customer_id 
                WHERE debit_note_code LIKE ('%$keyword%')   
                $str_debit_customer 
                $str_debit_date 
                $str_debit_user  
            )
        ) AS tb
        ORDER BY STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s'), invoice_customer_code  
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
}
?>