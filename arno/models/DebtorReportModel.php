<?php

require_once("BaseModel.php");
class DebtorReportModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    } 



    //#####################################################################################################################
    //
    //
    //----------------------------------------------- ดึงรายงานใบกำกับภาษี ---------------------------------------------------
    //
    //
    //#####################################################################################################################

    function getInvoiceDebtorReportBy($date_start = "",$date_end = "",$customer_id = "",$keyword = "",$user_id = ""){

        $str_customer = "";
        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND employee_id = '$user_id' ";
        }

        if($customer_id != ""){
            $str_customer = "AND tb_invoice_customer.customer_id = '$customer_id' ";
        }

        $sql = " SELECT invoice_customer_id, 
        customer_code,
        invoice_customer_code,   
        invoice_customer_name,   
        invoice_customer_tax,   
        invoice_customer_branch, 
        invoice_customer_date, 
        IF(invoice_customer_close != 0,0,invoice_customer_vat_price) as invoice_customer_vat_price,
        IF(invoice_customer_close != 0,0,invoice_customer_total_price) as invoice_customer_total_price,
        IF(invoice_customer_close != 0,0,invoice_customer_net_price) as invoice_customer_net_price,
        invoice_customer_close, 
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name,  
        IFNULL(tb2.customer_name_th,tb2.customer_name_en) as customer_name  
        FROM tb_invoice_customer 
        LEFT JOIN tb_user as tb1 ON tb_invoice_customer.employee_id = tb1.user_id 
        LEFT JOIN tb_customer as tb2 ON tb_invoice_customer.customer_id = tb2.customer_id 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%')  
            OR  invoice_customer_code LIKE ('%$keyword%') 
        ) 
        $str_customer 
        $str_date 
        $str_user  
        ORDER BY invoice_customer_code  
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

    //#####################################################################################################################
    //
    //
    //----------------------------------------------- ดึงรายงานใบกำกับภาษี แบบเต็ม---------------------------------------------------
    //
    //
    //#####################################################################################################################

    function getInvoiceDebtorFullReportBy($date_start = "",$date_end = "",$customer_id = "",$keyword = "",$user_id = ""){

        $str_customer = "";
        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND employee_id = '$user_id' ";
        }

        if($customer_id != ""){
            $str_customer = "AND tb_invoice_customer.customer_id = '$customer_id' ";
        }

        $sql = " SELECT tb_invoice_customer.invoice_customer_id, 
        customer_code,
        product_code,
        product_name,
        invoice_customer_code,   
        invoice_customer_name,   
        invoice_customer_tax,   
        invoice_customer_branch, 
        invoice_customer_date, 
        invoice_customer_list_qty,
        invoice_customer_list_price,
        invoice_customer_list_total,
        invoice_customer_vat_price,
        invoice_customer_total_price,
        invoice_customer_net_price,
        stock_group_code, 
        IFNULL(CONCAT(tb_user.user_name,' ',tb_user.user_lastname),'-') as employee_name,  
        IFNULL(tb_customer.customer_name_th,tb_customer.customer_name_en) as customer_name  
        FROM tb_invoice_customer 
        LEFT JOIN tb_user ON tb_invoice_customer.employee_id = tb_user.user_id 
        LEFT JOIN tb_invoice_customer_list ON tb_invoice_customer.invoice_customer_id = tb_invoice_customer_list.invoice_customer_id 
        LEFT JOIN tb_product ON tb_invoice_customer_list.product_id = tb_product.product_id 
        LEFT JOIN tb_stock_group ON tb_invoice_customer_list.stock_group_id = tb_stock_group.stock_group_id 
        LEFT JOIN tb_customer ON tb_invoice_customer.customer_id = tb_customer.customer_id 
        WHERE ( 
            CONCAT(tb_user.user_name,' ',tb_user.user_lastname) LIKE ('%$keyword%')  
            OR  invoice_customer_code LIKE ('%$keyword%') 
        ) 
        AND invoice_customer_vat_price != 0
        $str_customer 
        $str_date 
        $str_user  
        ORDER BY invoice_customer_code  
         "; 
 
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }


    

    //#####################################################################################################################
    //
    //
    //----------------------------------------------- ดึงรายงานใบวางบิล ---------------------------------------------------
    //
    //
    //#####################################################################################################################

    function getBillingNoteDebtorReportBy($date_start = "",$date_end = "",$customer_id = "",$keyword = "",$user_id = ""){

        $str_customer = "";
        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(billing_note_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(billing_note_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(billing_note_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(billing_note_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND employee_id = '$user_id' ";
        }

        if($customer_id != ""){
            $str_customer = "AND tb2.customer_id = '$customer_id' ";
        }

        $sql = " SELECT billing_note_id, 
        customer_code,
        billing_note_code, 
        billing_note_date, 
        billing_note_name,   
        billing_note_tax,   
        billing_note_branch, 
        billing_note_total,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name,  
        IFNULL(tb2.customer_name_en,'-') as customer_name  
        FROM tb_billing_note 
        LEFT JOIN tb_user as tb1 ON tb_billing_note.employee_id = tb1.user_id 
        LEFT JOIN tb_customer as tb2 ON tb_billing_note.customer_id = tb2.customer_id 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%')  
            OR  billing_note_code LIKE ('%$keyword%') 
        )  
        $str_customer 
        $str_date 
        $str_user  
        ORDER BY billing_note_code  
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

    


    //#####################################################################################################################
    //
    //
    //----------------------------------------- ดึงรายงานใบลดหนี้/ใบรับคืนสินค้า ------------------------------------------------
    //
    //
    //#####################################################################################################################

    function getCreditNoteDebtorReportBy($date_start = "",$date_end = "",$customer_id = "",$keyword = "",$user_id = ""){

        $str_customer = "";
        $str_date = "";
        $str_user = "";

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
        customer_code,
        credit_note_code, 
        credit_note_date, 
        credit_note_name,   
        credit_note_tax,   
        credit_note_branch, 
        credit_note_total_old,
        credit_note_total,
        credit_note_total_price,
        credit_note_vat,
        credit_note_vat_price,
        credit_note_net_price,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        credit_note_term, 
        credit_note_due, 
        IFNULL( tb2.customer_name_en ,'-') as customer_name  
        FROM tb_credit_note 
        LEFT JOIN tb_credit_note_type ON tb_credit_note.credit_note_type_id = tb_credit_note_type.credit_note_type_id 
        LEFT JOIN tb_user as tb1 ON tb_credit_note.employee_id = tb1.user_id 
        LEFT JOIN tb_customer as tb2 ON tb_credit_note.customer_id = tb2.customer_id 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%')  
            OR  credit_note_code LIKE ('%$keyword%') 
        )  
        $str_customer 
        $str_date 
        $str_user  
        ORDER BY credit_note_code  
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


    


    //#####################################################################################################################
    //
    //
    //------------------------------------------------ ดึงรายงานใบเพิ่มหนี้ ----------------------------------------------------
    //
    //
    //#####################################################################################################################

    function getDebitNoteDebtorReportBy($date_start = "",$date_end = "",$customer_id = "",$keyword = "",$user_id = ""){

        $str_customer = "";
        $str_date = "";
        $str_user = "";

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
        customer_code,
        debit_note_code, 
        debit_note_date, 
        debit_note_name,   
        debit_note_tax,   
        debit_note_branch, 
        debit_note_total_old,
        debit_note_total,
        debit_note_total_price,
        debit_note_vat,
        debit_note_vat_price,
        debit_note_net_price,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        debit_note_term, 
        debit_note_due, 
        IFNULL( tb2.customer_name_en ,'-') as customer_name  
        FROM tb_debit_note  
        LEFT JOIN tb_user as tb1 ON tb_debit_note.employee_id = tb1.user_id 
        LEFT JOIN tb_customer as tb2 ON tb_debit_note.customer_id = tb2.customer_id 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%')  
            OR  debit_note_code LIKE ('%$keyword%') 
        )  
        $str_customer 
        $str_date 
        $str_user  
        ORDER BY debit_note_code  
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



    


    //#####################################################################################################################
    //
    //
    //----------------------------------------------- ดึงรายงานใบรับชำระเงิน --------------------------------------------------
    //
    //
    //#####################################################################################################################

    function getFinanceDebitDebtorReportBy($date_start = "",$date_end = "",$customer_id = "",$keyword = "",$user_id = ""){

        $str_customer = "";
        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(finance_debit_date_pay,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(finance_debit_date_pay,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(finance_debit_date_pay,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(finance_debit_date_pay,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND employee_id = '$user_id' ";
        }

        if($customer_id != ""){
            $str_customer = "AND tb2.customer_id = '$customer_id' ";
        }

        $sql = " SELECT tb.finance_debit_id, 
        IFNULL (journal_cash_receipt_code, '-') as journal_cash_receipt_code,
        IFNULL (journal_cash_receipt_id, '0') as journal_cash_receipt_id,
        customer_code,
        finance_debit_code, 
        finance_debit_date, 
        finance_debit_name,   
        finance_debit_tax,   
        finance_debit_branch, 
        finance_debit_date_pay, 
        finance_debit_total,
        finance_debit_pay, 
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name,  
        IFNULL( tb2.customer_name_en ,'-') as customer_name  
        FROM tb_finance_debit as tb
        LEFT JOIN tb_user as tb1 ON tb.employee_id = tb1.user_id 
        LEFT JOIN tb_customer as tb2 ON tb.customer_id = tb2.customer_id 
        LEFT JOIN tb_journal_cash_receipt ON tb_journal_cash_receipt.finance_debit_id = tb.finance_debit_id 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%')  
            OR  finance_debit_code LIKE ('%$keyword%') 
        )  
        $str_customer 
        $str_date 
        $str_user  
        ORDER BY finance_debit_code  
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
  //#####################################################################################################################
    //
    //
    //----------------------------------------------- ดึงรายงานใบรับชำระเงินแบบเต็ม --------------------------------------------------
    //
    //
    //#####################################################################################################################

    function getFinanceDebitDebtorFullReportBy($date_start = "",$date_end = "",$customer_id = "",$keyword = "",$user_id = ""){

        $str_customer = "";
        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(finance_debit_date_pay,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(finance_debit_date_pay,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(finance_debit_date_pay,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(finance_debit_date_pay,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND employee_id = '$user_id' ";
        }

        if($customer_id != ""){
            $str_customer = " AND tb2.customer_id = '$customer_id' ";
        }

        $sql = " SELECT tb5.finance_debit_id, 
       
        customer_code,
        finance_debit_code, 
        finance_debit_date, 
        finance_debit_name,   
        finance_debit_tax,   
        finance_debit_branch, 
        finance_debit_date_pay, 
        finance_debit_total,
        finance_debit_pay,
        finance_debit_list_id,
        tb_finance_debit_pay.finance_debit_id ,
        finance_debit_pay_by,
        finance_debit_pay_bank,
        finance_debit_pay_value,
        finance_debit_pay_total,
        finance_debit_pay_balance,
        tb_finance_debit_pay.finance_debit_account_id,
        finance_debit_account_code,
        finance_debit_account_name,
        if (invoice_customer_code is not null,invoice_customer_code,credit_note_code) as invoice_customer_code,
        if (invoice_customer_date is not null,invoice_customer_date,credit_note_date) as invoice_customer_date,
        finance_debit_list_amount,
        finance_debit_list_balance,
        IFNULL( tb2.customer_name_en ,'-') as customer_name
       
        FROM tb_finance_debit_list as tb5
        
        
        
        LEFT JOIN tb_finance_debit ON tb5.finance_debit_id = tb_finance_debit.finance_debit_id 
        LEFT JOIN tb_finance_debit_pay ON tb_finance_debit.finance_debit_id = tb_finance_debit_pay.finance_debit_id 
        LEFT JOIN tb_finance_debit_account ON tb_finance_debit_pay.finance_debit_account_id = tb_finance_debit_account.finance_debit_account_id 
        LEFT JOIN tb_invoice_customer ON tb5.invoice_customer_id = tb_invoice_customer.invoice_customer_id  
        LEFT JOIN tb_credit_note ON tb5.credit_note_id = tb_credit_note.credit_note_id  
        LEFT JOIN tb_customer as tb2 ON tb_invoice_customer.customer_id = tb2.customer_id 
        
        WHERE  ( 
            
            finance_debit_code LIKE ('%$keyword%') 
        )  
        $str_customer 
        $str_date 
        $str_user
         


        GROUP BY invoice_customer_code
        ORDER BY finance_debit_code  
         ";  
 
    //  echo $sql;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }



    


    //#####################################################################################################################
    //
    //
    //----------------------------------------------- ดึงรายงานสถานะลูกหนี้ --------------------------------------------------
    //
    //
    //#####################################################################################################################

    function getDebtorListReportBy($customer_id="",$code_start="",$code_end=""){

        $str_customer ='';
        if($customer_id != ""){
            $str_customer =" AND customer_id = '$customer_id' ";
        }
        
        $str_code="";
        if($code_start != "" && $code_end != ""){
            $str_code = "AND customer_code >=  '$code_start' AND customer_code <=  '$code_end' ";
        }else if ($code_start != ""){
            $str_code = "AND customer_code >=  '$code_start' ";    
        }else if ($code_end != ""){
            $str_code = "AND customer_code <= '$code_end' ";  
        }

        $sql_customer = "SELECT 
        tb_customer.customer_id,
        customer_code,
        customer_name_en  
        FROM tb_customer  
        WHERE 1  
        $str_customer
        $str_customer
        ORDER BY customer_code
        "; 

        $data = [];
        if ($result = mysqli_query(static::$db,$sql_customer, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }

        return $data;
    }

    function getBeforeDebitReportBy($customer_id,$date_start){
        $sql ="SELECT    
        (
            IFNULL( 
                ( 
                    SELECT SUM(invoice_customer_net_price) 
                    FROM tb_invoice_customer 
                    WHERE customer_id = '$customer_id' 
                    AND invoice_customer_close = '0'
                    AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') < STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') 
                    
                ) 
            , 0) 

        -  IFNULL( 
                (
                    SELECT SUM(credit_note_net_price) 
                    FROM tb_credit_note 
                    WHERE customer_id = '$customer_id'  
                    AND credit_note_close = '0' 
                    AND STR_TO_DATE(credit_note_date,'%d-%m-%Y %H:%i:%s') < STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') 
                )
            , 0) 

        -  IFNULL( 
                ( 
                    SELECT SUM(finance_debit_pay) 
                    FROM tb_finance_debit 
                    WHERE customer_id = '$customer_id' 
                    AND STR_TO_DATE(finance_debit_date_pay,'%d-%m-%Y %H:%i:%s') < STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s')  
                ) 
            , 0) 
        ) as debit_before "; 
 
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data['debit_before'];
        }else{
            return 0;
        }

    }

    function getInvoiceDebitReportBy($customer_id,$date_start,$date_end){
        $sql =" SELECT SUM(invoice_customer_net_price) as debit_invoice 
                FROM tb_invoice_customer 
                WHERE customer_id = '$customer_id' 
                AND invoice_customer_close = '0'
                AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') 
                AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') 
            "; 
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data['debit_invoice'];
        }else{
            return 0;
        }

    }

    function getDebitDebitReportBy($customer_id,$date_start,$date_end){
        $sql =" SELECT SUM(debit_note_net_price) as debit_debit 
                FROM tb_debit_note 
                WHERE customer_id = '$customer_id'  
                AND debit_note_close = '0'
                AND STR_TO_DATE(debit_note_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') 
                AND STR_TO_DATE(debit_note_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') 
            "; 
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data['debit_debit'];
        }else{
            return 0;
        }

    }
    function getCreditDebitReportBy($customer_id,$date_start,$date_end){
        $sql =" SELECT SUM(credit_note_net_price) as debit_credit 
                FROM tb_credit_note 
                WHERE customer_id = '$customer_id' 
                AND credit_note_close = '0'
                AND STR_TO_DATE(credit_note_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') 
                AND STR_TO_DATE(credit_note_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') 
            "; 
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data['debit_credit'];
        }else{
            return 0;
        }

    }

    function getRecieveDebitReportBy($customer_id,$date_start,$date_end){
        $sql =" SELECT SUM(finance_debit_pay) as debit_reciept 
                FROM tb_finance_debit 
                WHERE customer_id = '$customer_id'  
                AND STR_TO_DATE(finance_debit_date_pay,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') 
                AND STR_TO_DATE(finance_debit_date_pay,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') 
            "; 
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data['debit_reciept'];
        }else{
            return 0;
        }

    }



    //#####################################################################################################################
    //
    //
    //----------------------------------------------- ดึงรายงานวิเคราะห์อายุลูกหนี้ --------------------------------------------------
    //
    //
    //#####################################################################################################################

    function getAccountsReceivableDebtorListReportBy($date_end,$customer_id,$keyword=""){

        $str_customer ='';
        if($customer_id != ""){
            $str_customer =" AND tb_invoice_customer.customer_id = '$customer_id' ";
        }
        
        $str_date ='';
        if($date_end != ""){
            $str_date =" AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s')  ";
        }

        $sql_customer = "SELECT 
        tb1.customer_id, 
        customer_code, 
        customer_name_en, 
        customer_tax,
        customer_branch,
        '0' as paper_number, 
        '0' as balance, 
        '0' as due_comming_more_than_60, 
        '0' as due_comming_in_60, 
        '0' as due_comming_in_30, 
        '0' as over_due_1_to_30, 
        '0' as over_due_31_to_60, 
        '0' as over_due_61_to_90, 
        '0' as over_due_more_than_90 
        FROM tb_customer as tb1
        WHERE customer_id IN ( 
            SELECT customer_id FROM (
                (
                    SELECT DISTINCT tb_invoice_customer.customer_id 
                    FROM tb_invoice_customer 
                    LEFT JOIN tb_finance_debit_list ON tb_invoice_customer.invoice_customer_id = tb_finance_debit_list.invoice_customer_id  
                    LEFT JOIN tb_finance_debit ON tb_finance_debit_list.finance_debit_id = tb_finance_debit.finance_debit_id 
                    WHERE STR_TO_DATE(finance_debit_date_pay,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') 
                    AND finance_debit_pay = finance_debit_total
                    GROUP BY tb_invoice_customer.invoice_customer_id  

                ) UNION ALL (
                    SELECT DISTINCT tb_credit_note.customer_id 
                    FROM tb_credit_note 
                    LEFT JOIN tb_finance_debit_list ON tb_credit_note.credit_note_id = tb_finance_debit_list.credit_note_id  
                    LEFT JOIN tb_finance_debit ON tb_finance_debit_list.finance_debit_id = tb_finance_debit.finance_debit_id 
                    WHERE STR_TO_DATE(finance_debit_date_pay,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') 
                    AND finance_debit_pay = finance_debit_total
                    GROUP BY tb_credit_note.credit_note_id  

                ) UNION ALL (
                    SELECT DISTINCT tb_debit_note.customer_id 
                    FROM tb_debit_note 
                    LEFT JOIN tb_finance_debit_list ON tb_debit_note.debit_note_id = tb_finance_debit_list.debit_note_id  
                    LEFT JOIN tb_finance_debit ON tb_finance_debit_list.finance_debit_id = tb_finance_debit.finance_debit_id 
                    WHERE STR_TO_DATE(finance_debit_date_pay,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') 
                    AND finance_debit_pay = finance_debit_total
                    GROUP BY tb_debit_note.debit_note_id  

                )
            ) AS tb
        ) 
        ORDER BY customer_code
        ";

        // echo "<pre>";
        // echo $sql_customer;
        // echo "</pre>";

        $data = [];
        if ($result = mysqli_query(static::$db,$sql_customer, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }

        return $data;
    } 


    //#####################################################################################################################
    //
    //
    //----------------------------------------------- ดึงรายงานลูกหนี้คงค้าง --------------------------------------------------
    //
    //
    //#####################################################################################################################

    function getDebtorListDetailReportBy($date_end,$customer_id,$keyword=""){

        $str_customer ='';
        if($customer_id != ""){
            $str_invoice_customer =" AND tb1.customer_id = '$customer_id' ";
            $str_credit_customer =" AND tb1.customer_id = '$customer_id' ";
            $str_debit_customer =" AND tb1.customer_id = '$customer_id' ";
        }
        
        $str_date ='';
        if($date_end != ""){
            $str_invoice_date =" AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s')  ";
            $str_credit_date =" AND STR_TO_DATE(credit_note_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s')  ";
            $str_debit_date =" AND STR_TO_DATE(debit_note_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s')  ";
        }

        $sql_customer = "SELECT * 
        FROM (
            (
                SELECT * , invoice_customer_net_price - finance_debit_list_paid as invoice_customer_balance  
                FROM (
                    SELECT 
                        invoice_customer_id, 
                        '0' as credit_note_id,
                        '0' as debit_note_id,
                        customer_code,
                        invoice_customer_code, 
                        customer_name_en as invoice_customer_name,   
                        invoice_customer_tax,   
                        invoice_customer_branch, 
                        invoice_customer_date, 
                        invoice_customer_due, 
                        invoice_customer_net_price,
                        IFNULL((
                            SELECT SUM(IFNULL(finance_debit_list_balance,0))
                            FROM  tb_finance_debit_list 
                            LEFT JOIN tb_finance_debit ON tb_finance_debit_list.finance_debit_id = tb_finance_debit.finance_debit_id 
                            WHERE invoice_customer_id = tb1.invoice_customer_id 
                            AND finance_debit_pay = finance_debit_total
                            AND STR_TO_DATE(finance_debit_date_pay,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s')
                            GROUP BY invoice_customer_id 
                        ),0) as finance_debit_list_paid 
                    FROM tb_invoice_customer as tb1
                    LEFT JOIN tb_customer ON tb1.customer_id = tb_customer.customer_id 
                    WHERE invoice_customer_close = 0  
                    $str_invoice_customer 
                    $str_invoice_date 
                    AND (
                        invoice_customer_code LIKE ('%$keyword%') 
                    ) 

                    GROUP BY tb1.invoice_customer_id  
                    ORDER BY customer_code, invoice_customer_code 
                ) as tb2
                HAVING invoice_customer_balance != 0 
            )UNION ALL(

                SELECT * , invoice_customer_net_price - finance_debit_list_paid as invoice_customer_balance  
                FROM (
                    SELECT 
                        '0' as invoice_customer_id, 
                        credit_note_id,
                        '0' as debit_note_id,
                        customer_code,
                        credit_note_code as invoice_customer_code, 
                        customer_name_en as invoice_customer_name,   
                        credit_note_tax as invoice_customer_tax,   
                        credit_note_branch as invoice_customer_branch, 
                        credit_note_date as invoice_customer_date, 
                        credit_note_due as invoice_customer_due, 
                        - credit_note_net_price as invoice_customer_net_price,
                        IFNULL((
                            SELECT SUM(IFNULL(finance_debit_list_balance,0))
                            FROM  tb_finance_debit_list 
                            LEFT JOIN tb_finance_debit ON tb_finance_debit_list.finance_debit_id = tb_finance_debit.finance_debit_id 
                            WHERE credit_note_id = tb1.credit_note_id 
                            AND finance_debit_pay = finance_debit_total
                            AND STR_TO_DATE(finance_debit_date_pay,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s')
                            GROUP BY credit_note_id 
                        ),0) as finance_debit_list_paid 
                    FROM tb_credit_note as tb1
                    LEFT JOIN tb_customer ON tb1.customer_id = tb_customer.customer_id 
                    WHERE credit_note_close = 0   
                    $str_credit_customer 
                    $str_credit_date 
                    AND (
                        credit_note_code LIKE ('%$keyword%') 
                    ) 

                    GROUP BY tb1.credit_note_id  
                    ORDER BY customer_code, credit_note_code 
                ) as tb2
                HAVING invoice_customer_balance != 0 
            )UNION ALL(

                SELECT * , invoice_customer_net_price - finance_debit_list_paid as invoice_customer_balance  
                FROM (
                    SELECT 
                        '0' as invoice_customer_id, 
                        '0' as credit_note_id,
                        debit_note_id,
                        customer_code,
                        debit_note_code as invoice_customer_code, 
                        customer_name_en as invoice_customer_name,   
                        debit_note_tax as invoice_customer_tax,   
                        debit_note_branch as invoice_customer_branch, 
                        debit_note_date as invoice_customer_date, 
                        debit_note_due as invoice_customer_due, 
                        debit_note_net_price as invoice_customer_net_price,
                        IFNULL((
                            SELECT SUM(IFNULL(finance_debit_list_balance,0))
                            FROM  tb_finance_debit_list 
                            LEFT JOIN tb_finance_debit ON tb_finance_debit_list.finance_debit_id = tb_finance_debit.finance_debit_id 
                            WHERE debit_note_id = tb1.debit_note_id 
                            AND finance_debit_pay = finance_debit_total
                            AND STR_TO_DATE(finance_debit_date_pay,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s')
                            GROUP BY debit_note_id 
                        ),0) as finance_debit_list_paid 
                    FROM tb_debit_note as tb1
                    LEFT JOIN tb_customer ON tb1.customer_id = tb_customer.customer_id 
                    WHERE debit_note_close = 0   
                    $str_debit_customer 
                    $str_debit_date 
                    AND (
                        debit_note_code LIKE ('%$keyword%') 
                    ) 

                    GROUP BY tb1.debit_note_id  
                    ORDER BY customer_code, debit_note_code 
                ) as tb2
                HAVING invoice_customer_balance != 0 
            )
        ) as tb 
        ORDER BY customer_code , invoice_customer_code
        
        ";
 
        $data = [];
        if ($result = mysqli_query(static::$db,$sql_customer, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }

        return $data;
    }



    //#####################################################################################################################
    //
    //
    //----------------------------------------------- ดึงรายงานลูกค้า ---------------------------------------------------------
    //
    //
    //#####################################################################################################################

    function getCustomerListReportBy($code_start = '',$code_end = ''){

        $str_code="";
        if($code_start != "" && $code_end != ""){
            $str_code = "AND customer_code >=  '$code_start' AND customer_code <=  '$code_end' ";
        }else if ($code_start != ""){
            $str_code = "AND customer_code >=  '$code_start' ";    
        }else if ($code_end != ""){
            $str_code = "AND customer_code <= '$code_end' ";  
        }

        $sql = " SELECT * 
        FROM tb_customer as tb1  
        LEFT JOIN tb_customer_type ON tb1.customer_type_id = tb_customer_type.customer_type_id 
        WHERE 1 
        $str_code
        ORDER BY tb1.customer_code  
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



    //#####################################################################################################################
    //
    //
    //----------------------------------------------- ดึงรายงานใบเสนอราคา ---------------------------------------------------
    //
    //
    //#####################################################################################################################

    function getQuotationDebtorReportBy($date_start = "",$date_end = "",$customer_id = "",$keyword = "",$user_id = ""){

        $str_customer = "";
        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(quotation_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(quotation_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(quotation_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(quotation_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND tb.employee_id = '$user_id' ";
        }

        if($customer_id != ""){
            $str_customer = "AND tb2.customer_id = '$customer_id' ";
        }


        
        $sql = " SELECT tb.quotation_id,  
        tb.employee_id,
        quotation_date,  
        quotation_rewrite_id, 
        IFNULL(( 
            SELECT COUNT(*) FROM tb_quotation WHERE quotation_rewrite_id = tb.quotation_id 
        ),0) as count_rewrite, 
        quotation_rewrite_no, 
        quotation_code,  
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        quotation_total, 
        customer_code, 
        customer_name_en, 
        customer_branch,
        customer_tax,
        quotation_contact_name, 
        quotation_cancelled, 
        quotation_total,
        quotation_vat,
        quotation_vat_price,
        quotation_vat_net,
        quotation_remark  
        FROM tb_quotation as tb  
        LEFT JOIN tb_user as tb1 ON tb.employee_id = tb1.user_id  
        LEFT JOIN tb_customer as tb2 ON tb.customer_id = tb2.customer_id  
        LEFT JOIN tb_quotation_list ON tb.quotation_id =  tb_quotation_list.quotation_id 
        LEFT JOIN tb_product ON tb_quotation_list.product_id =  tb_product.product_id 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%') 
            OR  quotation_contact_name LIKE ('%$keyword%') 
            OR  quotation_code LIKE ('%$keyword%') 
            OR  product_name LIKE ('%$keyword%') 
            OR  product_code LIKE ('%$keyword%') 
        ) 
        $str_customer 
        $str_date 
        $str_user   
        GROUP BY tb.quotation_id 
        ORDER BY STR_TO_DATE(quotation_date,'%d-%m-%Y %H:%i:%s') , quotation_code DESC  
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

    //#####################################################################################################################
    //
    //
    //----------------------------------------------- ดึงเอกสารที่ยังไม่วางบิล ---------------------------------------------------
    //
    //
    //#####################################################################################################################
    function getCustomerOrder(){

        $sql = "SELECT tb_customer.customer_id, customer_name_en , customer_name_th 
                FROM tb_customer 
                WHERE customer_id IN ( 
                    SELECT customer_id FROM (
                        (
                            SELECT DISTINCT customer_id 
                            FROM tb_invoice_customer
                            WHERE invoice_customer_id NOT IN (
                                SELECT invoice_customer_id 
                                FROM tb_billing_note_list
                                GROUP BY invoice_customer_id 
                            ) 
                            AND tb_invoice_customer.invoice_customer_close = '0' 
                            GROUP BY customer_id 
                        ) UNION ALL (
                            SELECT DISTINCT customer_id 
                            FROM tb_credit_note
                            WHERE credit_note_id NOT IN (
                                SELECT credit_note_id 
                                FROM tb_billing_note_list
                                GROUP BY credit_note_id 
                            ) 
                            AND tb_credit_note.credit_note_close = '0' 
                            GROUP BY customer_id 
                        ) UNION ALL (
                            SELECT DISTINCT customer_id 
                            FROM tb_debit_note
                            WHERE debit_note_id NOT IN (
                                SELECT debit_note_id 
                                FROM tb_billing_note_list
                                GROUP BY debit_note_id 
                            ) 
                            AND tb_debit_note.debit_note_close = '0' 
                            GROUP BY customer_id 
                        )
                    )AS tb  
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

    function generateBillingNoteListByCustomerId( $data_invoice = [], $data_credit = [], $data_debit = [], $search=""){

        $str_invoice ='0';
        $str_credit ='0';
        $str_debit ='0';
        $str_customer ='0';
        $str_search_invoice ='';
        $str_search_credit ='';
        $str_search_debit ='';

        if(is_array($data_invoice)){ 
            for($i=0; $i < count($data_invoice) ;$i++){
                $str_invoice .= $data_invoice[$i];
                if($i + 1 < count($data_invoice)){
                    $str_invoice .= ',';
                }
            }
        }else if ($data_invoice != ''){
            $str_invoice = $data_invoice;
        }else{
            $str_invoice='0';
        }
        
        
        if(is_array($data_credit)){ 
            for($i=0; $i < count($data_credit) ;$i++){
                $str_credit .= $data_credit[$i];
                if($i + 1 < count($data_credit)){
                    $str_credit .= ',';
                }
            }
        }else if ($data_credit != ''){
            $str_credit = $data_credit;
        }else{
            $str_credit='0';
        }
        
        
        if(is_array($data_debit)){ 
            for($i=0; $i < count($data_debit) ;$i++){
                $str_debit .= $data_debit[$i];
                if($i + 1 < count($data_debit)){
                    $str_debit .= ',';
                }
            }
        }else if ($data_debit != ''){
            $str_debit = $data_debit;
        }else{
            $str_debit='0';
        }

        if(is_array($customer_id)){ 
            for($i=0; $i < count($customer_id) ;$i++){
                $str_customer .= $customer_id[$i];
                if($i + 1 < count($customer_id)){
                    $str_customer .= ',';
                }
            }
        }else if ($customer_id != ''){
            $str_customer = $customer_id;
        }else{
            $str_customer='0';
        }

        if($search != ''){
            $str_search_invoice = " AND invoice_customer_code LIKE ('%$search%')";
            $str_search_credit = " AND credit_note_code LIKE ('%$search%')";
            $str_search_debit = " AND debit_note_code LIKE ('%$search%')";
        }
        $sql_customer = "SELECT * FROM 
            (
                (
                    SELECT invoice_customer_id, 
                    '0' as credit_note_id, 
                    '0' as debit_note_id, 
                    invoice_customer_code as billing_note_list_code,
                    '0' as billing_note_list_paid, 
                    invoice_customer_net_price as billing_note_list_amount, 
                    invoice_customer_date as billing_note_list_date, 
                    invoice_customer_due as billing_note_list_due ,
                    customer_code,
                    customer_name_en ,
                    invoice_customer_total_price as total_price,
                    invoice_customer_vat_price as vat_price,
                    invoice_customer_net_price as net_price,
                    CONCAT(tb_user.user_name,' ',tb_user.user_lastname) as name 
                    FROM tb_invoice_customer 
                    LEFT JOIN tb_customer ON tb_invoice_customer.customer_id = tb_customer.customer_id 
                    LEFT JOIN tb_user ON tb_invoice_customer.addby = tb_user.user_id 
                    WHERE tb_invoice_customer.invoice_customer_id NOT IN ($str_invoice) 
                    AND tb_invoice_customer.invoice_customer_id NOT IN (
                        SELECT invoice_customer_id 
                        FROM tb_billing_note_list 
                        GROUP BY invoice_customer_id 
                    ) 
                    AND tb_invoice_customer.invoice_customer_close = '0' 
                    $str_search
                    ORDER BY  invoice_customer_code ASC 
                ) UNION ALL (
                    SELECT '0' as  invoice_customer_id, 
                    credit_note_id,
                    '0' as debit_note_id,
                    credit_note_code as billing_note_list_code,
                    '0' as billing_note_list_paid, 
                    -credit_note_net_price as billing_note_list_amount, 
                    credit_note_date as billing_note_list_date, 
                    credit_note_due as billing_note_list_due ,
                    customer_code,
                    customer_name_en ,
                    credit_note_total_price as total_price,
                    credit_note_vat_price as vat_price,
                    credit_note_net_price as net_price,
                    CONCAT(tb_user.user_name,' ',tb_user.user_lastname) as name 
                    FROM tb_credit_note 
                    LEFT JOIN tb_customer ON tb_credit_note.customer_id = tb_customer.customer_id 
                    LEFT JOIN tb_user ON tb_credit_note.addby = tb_user.user_id 
                    WHERE tb_credit_note.credit_note_id NOT IN ($str_credit) 
                    AND tb_credit_note.credit_note_id NOT IN (
                        SELECT credit_note_id 
                        FROM tb_billing_note_list 
                        GROUP BY credit_note_id 
                    ) 
                    AND tb_credit_note.credit_note_close = '0' 
                    $str_search_credit
                    ORDER BY credit_note_code ASC 
                ) UNION ALL (
                    SELECT '0' as  invoice_customer_id, 
                    '0' as credit_note_id,
                    debit_note_id,
                    debit_note_code as billing_note_list_code,
                    '0' as billing_note_list_paid, 
                    debit_note_net_price as billing_note_list_amount, 
                    debit_note_date as billing_note_list_date, 
                    debit_note_due as billing_note_list_due ,
                    customer_code,
                    customer_name_en ,
                    debit_note_total_price as total_price,
                    debit_note_vat_price as vat_price,
                    debit_note_net_price as net_price,
                    CONCAT(tb_user.user_name,' ',tb_user.user_lastname) as name
                    FROM tb_debit_note 
                    LEFT JOIN tb_customer ON tb_debit_note.customer_id = tb_customer.customer_id 
                    LEFT JOIN tb_user ON tb_debit_note.addby = tb_user.user_id 
                    WHERE tb_debit_note.debit_note_id NOT IN ($str_debit) 
                    AND tb_debit_note.debit_note_id NOT IN (
                        SELECT debit_note_id 
                        FROM tb_billing_note_list 
                        GROUP BY debit_note_id 
                    ) 
                    AND tb_debit_note.debit_note_close = '0' 
                    $str_search_debit
                    ORDER BY debit_note_code ASC 
                ) 
            )as tb ";
            // echo "<pre>";
            // print_r($sql_customer) ;
            // echo "</pre>";
        $data = [];
        if ($result = mysqli_query(static::$db,$sql_customer, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }

        return $data;
    }
}
?>