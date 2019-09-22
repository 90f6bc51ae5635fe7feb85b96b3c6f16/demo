<?php

require_once("BaseModel.php");
class JournalReportModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }


    //#####################################################################################################################
    //
    //
    //------------------------------------------ ดึงรายสมุดรายวัน แบบย่อ เรียงตามวัน --------------------------------------------
    //
    //
    //#####################################################################################################################
    function getJournalReportBy($date_start = "", $date_end = "",$keyword = ""){


        //------------------------- General Journal -------------------------------------------------------------
        $str_general_date = "";

        if($date_start != "" && $date_end != ""){
            $str_general_date = "AND STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_general_date = "AND STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_general_date = "AND STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_general = " SELECT
        tb_journal_general.journal_general_id as journal_id, 
        'journal_general' as journal_type, 
        journal_general_code as journal_code, 
        journal_general_date as journal_date,
        journal_general_name  as journal_name,
        IFNULL(SUM(journal_general_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_general_list_credit),0) as journal_credit
        FROM tb_journal_general 
        LEFT JOIN tb_journal_general_list ON tb_journal_general_list.journal_general_id = tb_journal_general.journal_general_id  
        WHERE ( 
                journal_general_code LIKE ('%$keyword%') 
            OR  journal_general_name LIKE ('%$keyword%') 
        ) 
        $str_general_date 
        GROUP BY tb_journal_general.journal_general_id 
        ORDER BY STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s'), journal_general_code DESC 
        "; 
        //------------------------- End General Journal -------------------------------------------------------------



        //------------------------- Purchase Journal -------------------------------------------------------------
        $str_purchase_date = "";

        if($date_start != "" && $date_end != ""){
            $str_purchase_date = "AND STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_purchase_date = "AND STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_purchase_date = "AND STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_purchase = " SELECT
        tb_journal_purchase.journal_purchase_id as journal_id, 
        'journal_purchase' as journal_type, 
        journal_purchase_code as journal_code, 
        journal_purchase_date as journal_date,
        journal_purchase_name  as journal_name,
        IFNULL(SUM(journal_purchase_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_purchase_list_credit),0) as journal_credit
        FROM tb_journal_purchase 
        LEFT JOIN tb_journal_purchase_list ON tb_journal_purchase_list.journal_purchase_id = tb_journal_purchase.journal_purchase_id  
        WHERE ( 
                journal_purchase_code LIKE ('%$keyword%') 
            OR  journal_purchase_name LIKE ('%$keyword%') 
        ) 
        $str_purchase_date 
        GROUP BY tb_journal_purchase.journal_purchase_id 
        ORDER BY STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s'), journal_purchase_code DESC 
        "; 
        //------------------------- End Purchase Journal -------------------------------------------------------------



        //------------------------- Sale Journal -------------------------------------------------------------
        $str_sale_date = "";

        if($date_start != "" && $date_end != ""){
            $str_sale_date = "AND STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_sale_date = "AND STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_sale_date = "AND STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_sale = " SELECT 
        tb_journal_sale.journal_sale_id as journal_id, 
        'journal_sale' as journal_type, 
        journal_sale_code as journal_code, 
        journal_sale_date as journal_date,
        journal_sale_name  as journal_name,
        IFNULL(SUM(journal_sale_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_sale_list_credit),0) as journal_credit
        FROM tb_journal_sale 
        LEFT JOIN tb_journal_sale_list ON tb_journal_sale_list.journal_sale_id = tb_journal_sale.journal_sale_id  
        WHERE ( 
                journal_sale_code LIKE ('%$keyword%') 
            OR  journal_sale_name LIKE ('%$keyword%') 
        ) 
        $str_sale_date 
        GROUP BY tb_journal_sale.journal_sale_id 
        ORDER BY STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s'), journal_sale_code DESC 
        "; 
        //------------------------- End Sale Journal -------------------------------------------------------------



        //------------------------- Sale Return Journal -------------------------------------------------------------
        $str_sale_return_date = "";

        if($date_start != "" && $date_end != ""){
            $str_sale_return_date = "AND STR_TO_DATE(journal_sale_return_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_sale_return_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_sale_return_date = "AND STR_TO_DATE(journal_sale_return_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_sale_return_date = "AND STR_TO_DATE(journal_sale_return_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_sale_return = " SELECT
        tb_journal_sale_return.journal_sale_return_id as journal_id, 
        'journal_sale_return' as journal_type, 
        journal_sale_return_code as journal_code, 
        journal_sale_return_date as journal_date,
        journal_sale_return_name  as journal_name,
        IFNULL(SUM(journal_sale_return_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_sale_return_list_credit),0) as journal_credit
        FROM tb_journal_sale_return 
        LEFT JOIN tb_journal_sale_return_list ON tb_journal_sale_return_list.journal_sale_return_id = tb_journal_sale_return.journal_sale_return_id  
        WHERE ( 
                journal_sale_return_code LIKE ('%$keyword%') 
            OR  journal_sale_return_name LIKE ('%$keyword%') 
        ) 
        $str_sale_return_date 
        GROUP BY tb_journal_sale_return.journal_sale_return_id 
        ORDER BY STR_TO_DATE(journal_sale_return_date,'%d-%m-%Y %H:%i:%s'), journal_sale_return_code DESC 
        "; 
        //------------------------- End Sale Journal -------------------------------------------------------------



        //------------------------- Cash Payment Journal -------------------------------------------------------------
        $str_cash_payment_date = "";

        if($date_start != "" && $date_end != ""){
            $str_cash_payment_date = "AND STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_cash_payment_date = "AND STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_cash_payment_date = "AND STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_cash_payment = " SELECT
        tb_journal_cash_payment.journal_cash_payment_id as journal_id, 
        'journal_cash_payment' as journal_type, 
        journal_cash_payment_code as journal_code, 
        journal_cash_payment_date as journal_date,
        journal_cash_payment_name  as journal_name,
        IFNULL(SUM(journal_cash_payment_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_cash_payment_list_credit),0) as journal_credit
        FROM tb_journal_cash_payment 
        LEFT JOIN tb_journal_cash_payment_list ON tb_journal_cash_payment_list.journal_cash_payment_id = tb_journal_cash_payment.journal_cash_payment_id  
        WHERE ( 
                journal_cash_payment_code LIKE ('%$keyword%') 
            OR  journal_cash_payment_name LIKE ('%$keyword%') 
        ) 
        $str_cash_payment_date 
        GROUP BY tb_journal_cash_payment.journal_cash_payment_id 
        ORDER BY STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s'), journal_cash_payment_code DESC 
        "; 
        //------------------------- End Cash Payment Journal -------------------------------------------------------------



        //------------------------- Cash Receipt Journal -------------------------------------------------------------
        $str_cash_receipt_date = "";

        if($date_start != "" && $date_end != ""){
            $str_cash_receipt_date = "AND STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_cash_receipt_date = "AND STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_cash_receipt_date = "AND STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_cash_receipt = " SELECT
        tb_journal_cash_receipt.journal_cash_receipt_id as journal_id, 
        'journal_cash_receipt' as journal_type, 
        journal_cash_receipt_code as journal_code, 
        journal_cash_receipt_date as journal_date,
        journal_cash_receipt_name  as journal_name,
        IFNULL(SUM(journal_cash_receipt_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_cash_receipt_list_credit),0) as journal_credit
        FROM tb_journal_cash_receipt 
        LEFT JOIN tb_journal_cash_receipt_list ON tb_journal_cash_receipt_list.journal_cash_receipt_id = tb_journal_cash_receipt.journal_cash_receipt_id  
        WHERE ( 
                journal_cash_receipt_code LIKE ('%$keyword%') 
            OR  journal_cash_receipt_name LIKE ('%$keyword%') 
        ) 
        $str_cash_receipt_date 
        GROUP BY tb_journal_cash_receipt.journal_cash_receipt_id 
        ORDER BY STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s'), journal_cash_receipt_code DESC 
        "; 
        //------------------------- End Cash Receipt Journal -------------------------------------------------------------


        $sql =" SELECT * 
                FROM (($sql_general) 
                UNION  ($sql_purchase)
                UNION  ($sql_sale)
                UNION  ($sql_sale_return)
                UNION  ($sql_cash_payment)
                UNION  ($sql_cash_receipt)) as tb_journal
                ORDER BY journal_date, journal_code ASC
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
    //--------------------------------------------- ดึงรายสมุดรายวัน แบบเต็ม เรียงตามวัน -----------------------------------------
    //
    //
    //#####################################################################################################################
    function getJournalFullReportBy($date_start = "", $date_end = "",$keyword = ""){


        //------------------------- General Journal -------------------------------------------------------------
        $str_general_date = "";

        if($date_start != "" && $date_end != ""){
            $str_general_date = "AND STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_general_date = "AND STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_general_date = "AND STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_general = " SELECT 
        '0' as status_cancelled,
        CONCAT('general',journal_general_list_id) as journal_id,
        journal_general_code as journal_code, 
        journal_general_date as journal_date,
        journal_general_name  as journal_name,
        IFNULL(account_code,'N/A') as account_code ,
        account_name_th as account_name ,
        journal_general_list_name as journal_list_name,
        IFNULL(journal_general_list_debit,0) as journal_debit,
        IFNULL(journal_general_list_credit,0) as journal_credit
        FROM tb_journal_general 
        LEFT JOIN tb_journal_general_list ON tb_journal_general.journal_general_id = tb_journal_general_list.journal_general_id  
        LEFT JOIN tb_account ON tb_journal_general_list.account_id = tb_account.account_id 
        WHERE ( 
                journal_general_code LIKE ('%$keyword%') 
            OR  journal_general_name LIKE ('%$keyword%') 
            OR  account_code LIKE ('%$keyword%') 
        ) 
        $str_general_date 
        ORDER BY STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s'), journal_general_code , IFNULL(account_code,'N/A') DESC 
        "; 
        //------------------------- End General Journal -------------------------------------------------------------



        //------------------------- Purchase Journal -------------------------------------------------------------
        $str_purchase_date = "";

        if($date_start != "" && $date_end != ""){
            $str_purchase_date = "AND STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_purchase_date = "AND STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_purchase_date = "AND STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_purchase = " SELECT 
        '0' as status_cancelled,
        CONCAT('purchase',journal_purchase_list_id) as journal_id,
        journal_purchase_code as journal_code, 
        journal_purchase_date as journal_date,
        journal_purchase_name  as journal_name,
        IFNULL(account_code,'N/A') as account_code ,
        account_name_th as account_name ,
        journal_purchase_list_name as journal_list_name,
        IFNULL(journal_purchase_list_debit,0) as journal_debit,
        IFNULL(journal_purchase_list_credit,0) as journal_credit
        FROM tb_journal_purchase 
        LEFT JOIN tb_journal_purchase_list ON tb_journal_purchase.journal_purchase_id = tb_journal_purchase_list.journal_purchase_id  
        LEFT JOIN tb_account ON tb_journal_purchase_list.account_id = tb_account.account_id 
        WHERE ( 
                journal_purchase_code LIKE ('%$keyword%') 
            OR  journal_purchase_name LIKE ('%$keyword%') 
            OR  account_code LIKE ('%$keyword%') 
        ) 
        $str_purchase_date 
        ORDER BY STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s'), journal_purchase_code , IFNULL(account_code,'N/A') DESC 
        "; 
        //------------------------- End Purchase Journal -------------------------------------------------------------



        //------------------------- Sale Journal -------------------------------------------------------------
        $str_sale_date = "";

        if($date_start != "" && $date_end != ""){
            $str_sale_date = "AND STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_sale_date = "AND STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_sale_date = "AND STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_sale = " SELECT 
        IFNULL((
            SELECT invoice_customer_close FROM tb_invoice_customer WHERE invoice_customer_id = tb_journal_sale.invoice_customer_id LIMIT 0,1
        ),0) as status_cancelled, 
        CONCAT('sale',journal_sale_list_id) as journal_id,
        journal_sale_code as journal_code, 
        journal_sale_date as journal_date,
        journal_sale_name  as journal_name,
        IFNULL(account_code,'N/A') as account_code ,
        account_name_th as account_name ,
        journal_sale_list_name as journal_list_name,
        IFNULL(journal_sale_list_debit,0) as journal_debit,
        IFNULL(journal_sale_list_credit,0) as journal_credit
        FROM tb_journal_sale 
        LEFT JOIN tb_journal_sale_list ON tb_journal_sale.journal_sale_id = tb_journal_sale_list.journal_sale_id  
        LEFT JOIN tb_account ON tb_journal_sale_list.account_id = tb_account.account_id 
        WHERE ( 
                journal_sale_code LIKE ('%$keyword%') 
            OR  journal_sale_name LIKE ('%$keyword%') 
            OR  account_code LIKE ('%$keyword%') 
        ) 
        $str_sale_date 
        ORDER BY STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s'), journal_sale_code , IFNULL(account_code,'N/A') DESC 
        "; 
        //------------------------- End Sale Journal -------------------------------------------------------------


        //------------------------- Sale Return Journal -------------------------------------------------------------
        $str_sale_return_date = "";

        if($date_start != "" && $date_end != ""){
            $str_sale_return_date = "AND STR_TO_DATE(journal_sale_return_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_sale_return_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_sale_return_date = "AND STR_TO_DATE(journal_sale_return_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_sale_return_date = "AND STR_TO_DATE(journal_sale_return_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_sale_return = " SELECT 
        IFNULL((
            SELECT credit_note_close FROM tb_credit_note WHERE credit_note_id = tb_journal_sale_return.credit_note_id LIMIT 0,1
        ),0) as status_cancelled, 
        CONCAT('sale',journal_sale_return_list_id) as journal_id,
        journal_sale_return_code as journal_code, 
        journal_sale_return_date as journal_date,
        journal_sale_return_name  as journal_name,
        IFNULL(account_code,'N/A') as account_code ,
        account_name_th as account_name ,
        journal_sale_return_list_name as journal_list_name,
        IFNULL(journal_sale_return_list_debit,0) as journal_debit,
        IFNULL(journal_sale_return_list_credit,0) as journal_credit
        FROM tb_journal_sale_return 
        LEFT JOIN tb_journal_sale_return_list ON tb_journal_sale_return.journal_sale_return_id = tb_journal_sale_return_list.journal_sale_return_id  
        LEFT JOIN tb_account ON tb_journal_sale_return_list.account_id = tb_account.account_id 
        WHERE ( 
                journal_sale_return_code LIKE ('%$keyword%') 
            OR  journal_sale_return_name LIKE ('%$keyword%') 
            OR  account_code LIKE ('%$keyword%') 
        ) 
        $str_sale_return_date 
        ORDER BY STR_TO_DATE(journal_sale_return_date,'%d-%m-%Y %H:%i:%s'), journal_sale_return_code , IFNULL(account_code,'N/A') DESC 
        "; 
        //------------------------- End Sale Return Journal -------------------------------------------------------------



        //------------------------- Cash Payment Journal -------------------------------------------------------------
        $str_cash_payment_date = "";

        if($date_start != "" && $date_end != ""){
            $str_cash_payment_date = "AND STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_cash_payment_date = "AND STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_cash_payment_date = "AND STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_cash_payment = " SELECT 
        '0' as status_cancelled,
        CONCAT('payment',journal_cash_payment_list_id) as journal_id,
        journal_cash_payment_code as journal_code, 
        journal_cash_payment_date as journal_date,
        journal_cash_payment_name  as journal_name,
        IFNULL(account_code,'N/A') as account_code ,
        account_name_th as account_name ,
        journal_cash_payment_list_name as journal_list_name,
        IFNULL(journal_cash_payment_list_debit,0) as journal_debit,
        IFNULL(journal_cash_payment_list_credit,0) as journal_credit
        FROM tb_journal_cash_payment 
        LEFT JOIN tb_journal_cash_payment_list ON tb_journal_cash_payment.journal_cash_payment_id = tb_journal_cash_payment_list.journal_cash_payment_id  
        LEFT JOIN tb_account ON tb_journal_cash_payment_list.account_id = tb_account.account_id 
        WHERE ( 
                journal_cash_payment_code LIKE ('%$keyword%') 
            OR  journal_cash_payment_name LIKE ('%$keyword%') 
            OR  account_code LIKE ('%$keyword%') 
        ) 
        $str_cash_payment_date 
        ORDER BY STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s'), journal_cash_payment_code , IFNULL(account_code,'N/A') DESC 
        "; 
        //------------------------- End Cash Payment Journal -------------------------------------------------------------



        //------------------------- Cash Receipt Journal -------------------------------------------------------------
        $str_cash_receipt_date = "";

        if($date_start != "" && $date_end != ""){
            $str_cash_receipt_date = "AND STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_cash_receipt_date = "AND STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_cash_receipt_date = "AND STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_cash_receipt = " SELECT 
        '0' as status_cancelled,
        CONCAT('receipt',journal_cash_receipt_list_id) as journal_id,
        journal_cash_receipt_code as journal_code, 
        journal_cash_receipt_date as journal_date,
        journal_cash_receipt_name  as journal_name,
        IFNULL(account_code,'N/A') as account_code ,
        account_name_th as account_name ,
        journal_cash_receipt_list_name as journal_list_name,
        IFNULL(journal_cash_receipt_list_debit,0) as journal_debit,
        IFNULL(journal_cash_receipt_list_credit,0) as journal_credit
        FROM tb_journal_cash_receipt 
        LEFT JOIN tb_journal_cash_receipt_list ON tb_journal_cash_receipt.journal_cash_receipt_id = tb_journal_cash_receipt_list.journal_cash_receipt_id  
        LEFT JOIN tb_account ON tb_journal_cash_receipt_list.account_id = tb_account.account_id 
        WHERE ( 
                journal_cash_receipt_code LIKE ('%$keyword%') 
            OR  journal_cash_receipt_name LIKE ('%$keyword%') 
            OR  account_code LIKE ('%$keyword%') 
        ) 
        $str_cash_receipt_date 
        ORDER BY STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s'), journal_cash_receipt_code , IFNULL(account_code,'N/A') DESC
        "; 
        //------------------------- End Cash Receipt Journal -------------------------------------------------------------


        $sql =" SELECT * 
                FROM (($sql_general) 
                UNION  ($sql_purchase)
                UNION  ($sql_sale)
                UNION  ($sql_sale)
                UNION  ($sql_cash_payment)
                UNION  ($sql_cash_receipt)) as tb_journal 
                ORDER BY journal_date, journal_code, account_code ASC
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
    //-------------------------------- ดึงรายสมุดรายวัน แบบย่อ รวมตามบัญชี เรียงตามบัญชี ------------------------------------------
    //
    //
    //#####################################################################################################################
    function getJournalAcountReportBy($date_end = "", $code_start = "", $code_end = "" ,$keyword = ""){


        //------------------------- General Journal -------------------------------------------------------------
        $str_general_date = "";

        if ($date_end != ""){
            $str_general_date = "AND STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_general = " SELECT
        '0' as status_cancelled,
        journal_general_code as journal_code, 
        journal_general_date as journal_date,
        journal_general_name  as journal_name,
        account_id,
        IFNULL(SUM(journal_general_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_general_list_credit),0) as journal_credit
        FROM tb_journal_general 
        LEFT JOIN tb_journal_general_list ON tb_journal_general_list.journal_general_id = tb_journal_general.journal_general_id  
        WHERE ( 
                journal_general_code LIKE ('%$keyword%') 
            OR  journal_general_name LIKE ('%$keyword%') 
        ) 
        $str_general_date 
        GROUP BY tb_journal_general_list.journal_general_list_id 
        ORDER BY STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s'), journal_general_code DESC 
        "; 
        //------------------------- End General Journal -------------------------------------------------------------



        //------------------------- Purchase Journal -------------------------------------------------------------
        $str_purchase_date = "";

        if ($date_end != ""){
            $str_purchase_date = "AND STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_purchase = " SELECT
        '0' as status_cancelled,
        journal_purchase_code as journal_code, 
        journal_purchase_date as journal_date,
        journal_purchase_name  as journal_name,
        account_id,
        IFNULL(SUM(journal_purchase_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_purchase_list_credit),0) as journal_credit
        FROM tb_journal_purchase 
        LEFT JOIN tb_journal_purchase_list ON tb_journal_purchase_list.journal_purchase_id = tb_journal_purchase.journal_purchase_id  
        WHERE ( 
                journal_purchase_code LIKE ('%$keyword%') 
            OR  journal_purchase_name LIKE ('%$keyword%') 
        ) 
        $str_purchase_date 
        GROUP BY tb_journal_purchase_list.journal_purchase_list_id 
        ORDER BY STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s'), journal_purchase_code DESC 
        "; 
        //------------------------- End Purchase Journal -------------------------------------------------------------



        //------------------------- Sale Journal -------------------------------------------------------------
        $str_sale_date = "";

        if ($date_end != ""){
            $str_sale_date = "AND STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_sale = " SELECT
        IFNULL((
            SELECT invoice_customer_close FROM tb_invoice_customer WHERE invoice_customer_id = tb_journal_sale.invoice_customer_id LIMIT 0,1
        ),0) as status_cancelled, 
        journal_sale_code as journal_code, 
        journal_sale_date as journal_date,
        journal_sale_name  as journal_name,
        account_id,
        IFNULL(SUM(journal_sale_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_sale_list_credit),0) as journal_credit
        FROM tb_journal_sale 
        LEFT JOIN tb_journal_sale_list ON tb_journal_sale_list.journal_sale_id = tb_journal_sale.journal_sale_id  
        WHERE ( 
                journal_sale_code LIKE ('%$keyword%') 
            OR  journal_sale_name LIKE ('%$keyword%') 
        ) 
        $str_sale_date 
        GROUP BY tb_journal_sale_list.journal_sale_list_id 
        ORDER BY STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s'), journal_sale_code DESC 
        "; 
        //------------------------- End Sale Journal -------------------------------------------------------------


        
        //------------------------- Sale Return Journal -------------------------------------------------------------
        $str_sale_return_date = "";

        if ($date_end != ""){
            $str_sale_return_date = "AND STR_TO_DATE(journal_sale_return_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_sale_return = " SELECT
        IFNULL((
            SELECT credit_note_close FROM tb_credit_note WHERE credit_note_id = tb_journal_sale_return.credit_note_id LIMIT 0,1
        ),0) as status_cancelled, 
        journal_sale_return_code as journal_code, 
        journal_sale_return_date as journal_date,
        journal_sale_return_name  as journal_name,
        account_id,
        IFNULL(SUM(journal_sale_return_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_sale_return_list_credit),0) as journal_credit
        FROM tb_journal_sale_return 
        LEFT JOIN tb_journal_sale_return_list ON tb_journal_sale_return_list.journal_sale_return_id = tb_journal_sale_return.journal_sale_return_id  
        WHERE ( 
                journal_sale_return_code LIKE ('%$keyword%') 
            OR  journal_sale_return_name LIKE ('%$keyword%') 
        ) 
        $str_sale_return_date 
        GROUP BY tb_journal_sale_return_list.journal_sale_return_list_id 
        ORDER BY STR_TO_DATE(journal_sale_return_date,'%d-%m-%Y %H:%i:%s'), journal_sale_return_code DESC 
        "; 
        //------------------------- End Sale Return Journal -------------------------------------------------------------



        //------------------------- Cash Payment Journal -------------------------------------------------------------
        $str_cash_payment_date = "";

        if ($date_end != ""){
            $str_cash_payment_date = "AND STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_cash_payment = " SELECT
        '0' as status_cancelled,
        journal_cash_payment_code as journal_code, 
        journal_cash_payment_date as journal_date,
        journal_cash_payment_name  as journal_name,
        account_id,
        IFNULL(SUM(journal_cash_payment_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_cash_payment_list_credit),0) as journal_credit
        FROM tb_journal_cash_payment 
        LEFT JOIN tb_journal_cash_payment_list ON tb_journal_cash_payment_list.journal_cash_payment_id = tb_journal_cash_payment.journal_cash_payment_id  
        WHERE ( 
                journal_cash_payment_code LIKE ('%$keyword%') 
            OR  journal_cash_payment_name LIKE ('%$keyword%') 
        ) 
        $str_cash_payment_date 
        GROUP BY tb_journal_cash_payment_list.journal_cash_payment_list_id 
        ORDER BY STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s'), journal_cash_payment_code DESC 
        "; 
        //------------------------- End Cash Payment Journal -------------------------------------------------------------



        //------------------------- Cash Receipt Journal -------------------------------------------------------------
        $str_cash_receipt_date = "";

        if ($date_end != ""){
            $str_cash_receipt_date = "AND STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_cash_receipt = " SELECT
        '0' as status_cancelled,
        journal_cash_receipt_code as journal_code, 
        journal_cash_receipt_date as journal_date,
        journal_cash_receipt_name  as journal_name,
        account_id,
        IFNULL(SUM(journal_cash_receipt_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_cash_receipt_list_credit),0) as journal_credit
        FROM tb_journal_cash_receipt 
        LEFT JOIN tb_journal_cash_receipt_list ON tb_journal_cash_receipt_list.journal_cash_receipt_id = tb_journal_cash_receipt.journal_cash_receipt_id  
        WHERE ( 
                journal_cash_receipt_code LIKE ('%$keyword%') 
            OR  journal_cash_receipt_name LIKE ('%$keyword%') 
        ) 
        $str_cash_receipt_date 
        GROUP BY tb_journal_cash_receipt_list.journal_cash_receipt_list_id 
        ORDER BY STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s'), journal_cash_receipt_code DESC 
        "; 
        //------------------------- End Cash Receipt Journal -------------------------------------------------------------


        $sql =" SELECT account_code , account_name_th ,  MAX(IFNULL(account_debit_begin,0)) ,SUM(IFNULL(tb_journal.journal_debit,0)), MAX(IFNULL(account_credit_begin,0)) ,SUM(IFNULL(tb_journal.journal_credit,0)) , ( MAX(IFNULL(account_debit_begin,0)) + SUM(IFNULL(tb_journal.journal_debit,0)) ) - ( MAX(IFNULL(account_credit_begin,0)) + SUM(IFNULL(tb_journal.journal_credit,0)) ) as account_value
                FROM tb_account 
                LEFT JOIN  (($sql_general)  
                UNION   ALL  ($sql_purchase) 
                UNION   ALL  ($sql_sale) 
                UNION   ALL  ($sql_sale_return) 
                UNION   ALL  ($sql_cash_payment) 
                UNION   ALL  ($sql_cash_receipt)) as tb_journal  
                ON tb_account.account_id = tb_journal.account_id  
                WHERE IFNULL(status_cancelled,0) = 0 
                GROUP BY account_code 
                HAVING  round(account_value,2)  != 0
                ORDER BY account_code ASC
        ";  
        //echo '<pre>'.$sql.'</pre>';
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
    //-------------------------------- ดึงรายสมุดรายวัน แบบย่อ รวมตามบัญชี เรียงตามบัญชี (เเสดงทั้งหมด)------------------------------
    //
    //
    //#####################################################################################################################

    function getJournalAcountReportShowAllBy($date_end = "", $code_start = "", $code_end = "" ,$keyword = ""){
  //------------------------- General Journal -------------------------------------------------------------
        $str_general_date = "";

        if ($date_end != ""){
            $str_general_date = "AND STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_general = " SELECT
        journal_general_code as journal_code, 
        journal_general_date as journal_date,
        journal_general_name  as journal_name,
        account_id,
        IFNULL(SUM(journal_general_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_general_list_credit),0) as journal_credit
        FROM tb_journal_general 
        LEFT JOIN tb_journal_general_list ON tb_journal_general_list.journal_general_id = tb_journal_general.journal_general_id  
        WHERE ( 
                journal_general_code LIKE ('%$keyword%') 
            OR  journal_general_name LIKE ('%$keyword%') 
        ) 
        $str_general_date 
        GROUP BY tb_journal_general_list.journal_general_list_id 
        ORDER BY STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s'), journal_general_code DESC 
        "; 
        //------------------------- End General Journal -------------------------------------------------------------



        //------------------------- Purchase Journal -------------------------------------------------------------
        $str_purchase_date = "";

        if ($date_end != ""){
            $str_purchase_date = "AND STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_purchase = " SELECT
        journal_purchase_code as journal_code, 
        journal_purchase_date as journal_date,
        journal_purchase_name  as journal_name,
        account_id,
        IFNULL(SUM(journal_purchase_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_purchase_list_credit),0) as journal_credit
        FROM tb_journal_purchase 
        LEFT JOIN tb_journal_purchase_list ON tb_journal_purchase_list.journal_purchase_id = tb_journal_purchase.journal_purchase_id  
        WHERE ( 
                journal_purchase_code LIKE ('%$keyword%') 
            OR  journal_purchase_name LIKE ('%$keyword%') 
        ) 
        $str_purchase_date 
        GROUP BY tb_journal_purchase_list.journal_purchase_list_id 
        ORDER BY STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s'), journal_purchase_code DESC 
        "; 
        //------------------------- End Purchase Journal -------------------------------------------------------------



        //------------------------- Sale Journal -------------------------------------------------------------
        $str_sale_date = "";

        if ($date_end != ""){
            $str_sale_date = "AND STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_sale = " SELECT
        journal_sale_code as journal_code, 
        journal_sale_date as journal_date,
        journal_sale_name  as journal_name,
        account_id,
        IFNULL(SUM(journal_sale_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_sale_list_credit),0) as journal_credit
        FROM tb_journal_sale 
        LEFT JOIN tb_journal_sale_list ON tb_journal_sale_list.journal_sale_id = tb_journal_sale.journal_sale_id  
        WHERE ( 
                journal_sale_code LIKE ('%$keyword%') 
            OR  journal_sale_name LIKE ('%$keyword%') 
        ) 
        $str_sale_date 
        GROUP BY tb_journal_sale_list.journal_sale_list_id 
        ORDER BY STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s'), journal_sale_code DESC 
        "; 
        //------------------------- End Sale Journal -------------------------------------------------------------



        //------------------------- Sale Return Journal -------------------------------------------------------------
        $str_sale_return_date = "";

        if ($date_end != ""){
            $str_sale_return_date = "AND STR_TO_DATE(journal_sale_return_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_sale_return = " SELECT
        journal_sale_return_code as journal_code, 
        journal_sale_return_date as journal_date,
        journal_sale_return_name  as journal_name,
        account_id,
        IFNULL(SUM(journal_sale_return_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_sale_return_list_credit),0) as journal_credit
        FROM tb_journal_sale_return 
        LEFT JOIN tb_journal_sale_return_list ON tb_journal_sale_return_list.journal_sale_return_id = tb_journal_sale_return.journal_sale_return_id  
        WHERE ( 
                journal_sale_return_code LIKE ('%$keyword%') 
            OR  journal_sale_return_name LIKE ('%$keyword%') 
        ) 
        $str_sale_return_date 
        GROUP BY tb_journal_sale_return_list.journal_sale_return_list_id 
        ORDER BY STR_TO_DATE(journal_sale_return_date,'%d-%m-%Y %H:%i:%s'), journal_sale_return_code DESC 
        "; 
        //------------------------- End Sale Return Journal -------------------------------------------------------------



        //------------------------- Cash Payment Journal -------------------------------------------------------------
        $str_cash_payment_date = "";

        if ($date_end != ""){
            $str_cash_payment_date = "AND STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_cash_payment = " SELECT
        journal_cash_payment_code as journal_code, 
        journal_cash_payment_date as journal_date,
        journal_cash_payment_name  as journal_name,
        account_id,
        IFNULL(SUM(journal_cash_payment_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_cash_payment_list_credit),0) as journal_credit
        FROM tb_journal_cash_payment 
        LEFT JOIN tb_journal_cash_payment_list ON tb_journal_cash_payment_list.journal_cash_payment_id = tb_journal_cash_payment.journal_cash_payment_id  
        WHERE ( 
                journal_cash_payment_code LIKE ('%$keyword%') 
            OR  journal_cash_payment_name LIKE ('%$keyword%') 
        ) 
        $str_cash_payment_date 
        GROUP BY tb_journal_cash_payment_list.journal_cash_payment_list_id 
        ORDER BY STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s'), journal_cash_payment_code DESC 
        "; 
        //------------------------- End Cash Payment Journal -------------------------------------------------------------



        //------------------------- Cash Receipt Journal -------------------------------------------------------------
        $str_cash_receipt_date = "";

        if ($date_end != ""){
            $str_cash_receipt_date = "AND STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_cash_receipt = " SELECT
        journal_cash_receipt_code as journal_code, 
        journal_cash_receipt_date as journal_date,
        journal_cash_receipt_name  as journal_name,
        account_id,
        IFNULL(SUM(journal_cash_receipt_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_cash_receipt_list_credit),0) as journal_credit
        FROM tb_journal_cash_receipt 
        LEFT JOIN tb_journal_cash_receipt_list ON tb_journal_cash_receipt_list.journal_cash_receipt_id = tb_journal_cash_receipt.journal_cash_receipt_id  

        WHERE ( 
                journal_cash_receipt_code LIKE ('%$keyword%') 
            OR  journal_cash_receipt_name LIKE ('%$keyword%') 
        ) 
        $str_cash_receipt_date 
        GROUP BY tb_journal_cash_receipt_list.journal_cash_receipt_list_id 
        ORDER BY STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s'), journal_cash_receipt_code DESC 
        "; 
        //------------------------- End Cash Receipt Journal -------------------------------------------------------------


        $sql =" SELECT *
                FROM tb_account 
                JOIN tb_account_group
                ON tb_account.account_group = tb_account_group.account_group_id 
                ORDER BY account_code ASC
        ";  
      /*
      LEFT JOIN  (($sql_general)  
      UNION   ALL  ($sql_purchase) 
      UNION   ALL  ($sql_sale) 
      UNION   ALL  ($sql_sale_return) 
      UNION   ALL  ($sql_cash_payment) 
      UNION   ALL  ($sql_cash_receipt)) as tb_journal   echo '<pre>'.$sql.'</pre>'*/;
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
    //-------------------------------- ดึงยอดขายทั้งหมด (เเสดงทั้งหมด)------------------------------
    //
    //
    //#####################################################################################################################

    function getJournalSalesReportShowAllBy($date_start="", $date_end = "",$product_category_id = "",$employee_id = ''){
        
        $str_date = ""; 
        $str_employee = ""; 

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        if($product_category_id != ""){
            $str_category = " AND tb_product.product_category_id = '$product_category_id' ";
        }
        if($employee_id != ""){
            $str_employee = " AND tb_data.employee_id = '$employee_id' ";
        }


        $sql =" SELECT * , SUM(invoice_customer_list_total) as invoice_customer_total_price 
                FROM
                (
                    (
                        SELECT 
                            '1_invoice' as paper_type,
                            tb_invoice_customer.invoice_customer_id,
                            invoice_customer_code,
                            invoice_customer_date,
                            employee_id,
                            customer_id,
                            invoice_customer_close,
                            product_id,
                            invoice_customer_list_id,
                            invoice_customer_list_no,
                            invoice_customer_list_qty,
                            invoice_customer_list_price,
                            invoice_customer_list_total   
                        FROM tb_invoice_customer 
                        LEFT JOIN tb_invoice_customer_list ON tb_invoice_customer.invoice_customer_id = tb_invoice_customer_list.invoice_customer_id 
                        WHERE invoice_customer_close = 0  
                        AND invoice_customer_begin = 0 
                    ) UNION ALL (
                        SELECT 
                            '2_credit_note' as paper_type,
                            tb_credit_note.credit_note_id as invoice_customer_id,
                            credit_note_code as invoice_customer_code,
                            credit_note_date as invoice_customer_date,
                            IFNULL((SELECT employee_id FROM tb_invoice_customer WHERE invoice_customer_id = tb_credit_note.invoice_customer_id),employee_id) as  employee_id,
                            customer_id,
                            credit_note_close as invoice_customer_close,
                            product_id,
                            credit_note_list_id as invoice_customer_list_id,
                            credit_note_list_no as invoice_customer_list_no,
                            credit_note_list_qty as invoice_customer_list_qty,
                             - credit_note_list_price as invoice_customer_list_price,
                             - credit_note_list_total as invoice_customer_list_total  
                        FROM tb_credit_note 
                        LEFT JOIN tb_credit_note_list ON tb_credit_note.credit_note_id = tb_credit_note_list.credit_note_id 
                        WHERE credit_note_close = 0  
                        AND tb_credit_note_list.credit_note_id IS NOT NULL
                    ) UNION ALL (
                        SELECT 
                            '3_debit_note' as paper_type,
                            tb_debit_note.debit_note_id as invoice_customer_id,
                            debit_note_code as invoice_customer_code,
                            debit_note_date as invoice_customer_date,
                            IFNULL((SELECT employee_id FROM tb_invoice_customer WHERE invoice_customer_id = tb_debit_note.invoice_customer_id),employee_id) as employee_id,
                            customer_id,
                            debit_note_close as invoice_customer_close,
                            product_id,
                            debit_note_list_id as invoice_customer_list_id,
                            debit_note_list_no as invoice_customer_list_no,
                            debit_note_list_qty as invoice_customer_list_qty,
                            debit_note_list_price as invoice_customer_list_price,
                            debit_note_list_total as invoice_customer_list_total  
                        FROM tb_debit_note 
                        LEFT JOIN tb_debit_note_list ON tb_debit_note.debit_note_id = tb_debit_note_list.debit_note_id 
                        WHERE debit_note_close = 0  
                        AND tb_debit_note_list.debit_note_id IS NOT NULL
                    )
                ) AS tb_data 
                LEFT JOIN tb_product ON tb_data.product_id = tb_product.product_id 
                LEFT JOIN tb_customer  ON (tb_data.customer_id = tb_customer.customer_id) 
                LEFT JOIN tb_user ON (tb_data.employee_id = tb_user.user_id) 
                WHERE invoice_customer_close = 0   
                $str_employee
                $str_category
                $str_date 
                GROUP BY tb_data.paper_type, tb_data.invoice_customer_id
                ORDER BY employee_id , invoice_customer_code ASC
        ";  
    //    echo '<pre>'.$sql.'</pre>';
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';
            return $data;
        }

    } 
      

    //#####################################################################################################################
    //
    //
    //-------------------------------- ดึงยอดขายทั้งหมด (เเสดงตามรายการ)------------------------------
    //
    //
    //#####################################################################################################################

    function getJournalSalesReportShowAllListBy($date_start="", $date_end = "",$product_category_id = "",$order_by_type = '',$employee_id = ''){
        
        $str_date = ""; 
        $str_category = '';
        $str_order_by = "";
        $str_employee = "";



        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        if($product_category_id != ""){
            $str_category = " AND tb_product.product_category_id = '$product_category_id' ";
        }

        if($employee_id != ""){
            $str_employee = " AND tb_data.employee_id = '$employee_id' ";
        }


        if($order_by_type == "sale_customer"){
            $str_order_by = " ORDER BY employee_id ,customer_code, invoice_customer_code , invoice_customer_list_no ASC ";

        }else{
            $str_order_by = " ORDER BY employee_id, customer_code , invoice_customer_code , invoice_customer_list_no ASC ";
        }
        $sql =" SELECT * 
        
                FROM
                (
                    (
                        SELECT 
                            '1_invoice' as paper_type,
                            tb_invoice_customer.invoice_customer_id,
                            invoice_customer_code,
                            invoice_customer_date,
                            employee_id,
                            customer_id,
                            invoice_customer_close,
                            product_id,
                            invoice_customer_list_id,
                            invoice_customer_list_no,
                            invoice_customer_list_qty,
                            invoice_customer_list_price,
                            invoice_customer_list_total   
                        FROM tb_invoice_customer 
                        LEFT JOIN tb_invoice_customer_list ON tb_invoice_customer.invoice_customer_id = tb_invoice_customer_list.invoice_customer_id 
                        WHERE invoice_customer_close = 0  
                        AND invoice_customer_begin = 0
                    ) UNION ALL (
                        SELECT 
                            '2_credit_note' as paper_type,
                            tb_credit_note.credit_note_id as invoice_customer_id,
                            credit_note_code as invoice_customer_code,
                            credit_note_date as invoice_customer_date,
                            IFNULL((SELECT employee_id FROM tb_invoice_customer WHERE invoice_customer_id = tb_credit_note.invoice_customer_id),employee_id) as employee_id,
                            customer_id,
                            credit_note_close as invoice_customer_close,
                            product_id,
                            credit_note_list_id as invoice_customer_list_id,
                            credit_note_list_no as invoice_customer_list_no,
                            credit_note_list_qty as invoice_customer_list_qty,
                             - credit_note_list_price as invoice_customer_list_price,
                             - credit_note_list_total as invoice_customer_list_total  
                        FROM tb_credit_note 
                        LEFT JOIN tb_credit_note_list ON tb_credit_note.credit_note_id = tb_credit_note_list.credit_note_id 
                        WHERE credit_note_close = 0  
                        AND tb_credit_note_list.credit_note_id IS NOT NULL
                    ) UNION ALL (
                        SELECT 
                            '3_debit_note' as paper_type,
                            tb_debit_note.debit_note_id as invoice_customer_id,
                            debit_note_code as invoice_customer_code,
                            debit_note_date as invoice_customer_date,
                            IFNULL((SELECT employee_id FROM tb_invoice_customer WHERE invoice_customer_id = tb_debit_note.invoice_customer_id),employee_id) as employee_id,
                            customer_id,
                            debit_note_close as invoice_customer_close,
                            product_id,
                            debit_note_list_id as invoice_customer_list_id,
                            debit_note_list_no as invoice_customer_list_no,
                            debit_note_list_qty as invoice_customer_list_qty,
                            debit_note_list_price as invoice_customer_list_price,
                            debit_note_list_total as invoice_customer_list_total  
                        FROM tb_debit_note 
                        LEFT JOIN tb_debit_note_list ON tb_debit_note.debit_note_id = tb_debit_note_list.debit_note_id 
                        WHERE debit_note_close = 0
                        AND tb_debit_note_list.debit_note_id IS NOT NULL  
                    )
                ) AS tb_data 
                LEFT JOIN tb_product ON tb_data.product_id = tb_product.product_id 
                LEFT JOIN tb_customer  ON (tb_data.customer_id = tb_customer.customer_id) 
                LEFT JOIN tb_user ON (tb_data.employee_id = tb_user.user_id) 
                WHERE invoice_customer_close = 0  
                $str_employee
                $str_category
                $str_date 
                $str_order_by 
        ";  

    //    echo '<pre>'.$sql.'</pre>';
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
    //-------------------------------- ดึงยอดขายทั้งหมด (เเสดงรายการพนักงานขาย)------------------------------
    //
    //
    //#####################################################################################################################

    function getJournalSalesReportShowEmployeeBy($date_start="", $date_end = "",$employee_id = ''){
        
        $str_date = ""; 
        $str_employee = ""; 

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        if($employee_id != "" ){
            $str_employee = " AND tb_invoice_customer.employee_id = '$employee_id' "; 
        }

        $sql =" SELECT *
                FROM tb_invoice_customer 
                LEFT JOIN tb_invoice_customer_list ON tb_invoice_customer.invoice_customer_id = tb_invoice_customer_list.invoice_customer_id 
                LEFT JOIN tb_user ON (tb_invoice_customer.employee_id = tb_user.user_id) 
                WHERE 1 
                $str_date 
                $str_employee
                ORDER BY user_name ASC 
        ";  
       //echo '<pre>'.$sql.'</pre>';
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
    //-------------------------------- ดึงรายสมุดรายวัน แบบเต็ม รวมตามบัญชี เรียงตามบัญชี ------------------------------------------
    //
    //
    //#####################################################################################################################
    function getJournalAcountFullReportBy($date_start="", $date_end = "", $code_start = "", $code_end = "" ,$keyword = ""){
        //------------------------- General Journal -------------------------------------------------------------
        $str_general_date = ""; 
        $str_code_acc ="";
        if($date_start != "" && $date_end != ""){
            $str_general_date = "AND STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_general_date = "AND STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_general_date = "AND STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        if( $code_start != "" && $code_end==""){

            $str_code_acc ="AND account_code >= '$code_start'";

        }elseif($code_start != "" && $code_end !=""){
            $str_code_acc ="AND account_code >= '$code_start' AND account_code<= '$code_end'";

        }elseif($code_start == "" && $code_end !=""){
            $str_code_acc ="AND account_code<= '$code_end'";
        }



        $sql_general = " SELECT 
        '0' as status_cancelled,
        journal_general_code as journal_code, 
        journal_general_date as journal_date,
        journal_general_name  as journal_name,
        account_id,
        journal_general_list_name as journal_list_name,
        IFNULL(SUM(journal_general_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_general_list_credit),0) as journal_credit
        FROM tb_journal_general 
        LEFT JOIN tb_journal_general_list ON tb_journal_general_list.journal_general_id = tb_journal_general.journal_general_id  
        WHERE ( 
                journal_general_code LIKE ('%$keyword%') 
            OR  journal_general_name LIKE ('%$keyword%') 
        )  
        $str_general_date 
        GROUP BY tb_journal_general_list.journal_general_list_id 
        ORDER BY STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s'), journal_general_code DESC 
        "; 
        //------------------------- End General Journal -------------------------------------------------------------



        //------------------------- Purchase Journal -------------------------------------------------------------
        $str_purchase_date = "";

        if($date_start != "" && $date_end != ""){
            $str_purchase_date = "AND STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_purchase_date = "AND STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_purchase_date = "AND STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_purchase = " SELECT 
        IFNULL((
            SELECT invoice_supplier_close FROM tb_invoice_supplier WHERE invoice_supplier_id = tb_journal_purchase.invoice_supplier_id LIMIT 0,1
        ),0) as status_cancelled, 
        journal_purchase_code as journal_code, 
        journal_purchase_date as journal_date,
        journal_purchase_name  as journal_name,
        account_id,
        journal_purchase_list_name as journal_list_name,
        IFNULL(SUM(journal_purchase_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_purchase_list_credit),0) as journal_credit
        FROM tb_journal_purchase 
        LEFT JOIN tb_journal_purchase_list ON tb_journal_purchase_list.journal_purchase_id = tb_journal_purchase.journal_purchase_id  
        WHERE ( 
                journal_purchase_code LIKE ('%$keyword%') 
            OR  journal_purchase_name LIKE ('%$keyword%') 
        )  
        $str_purchase_date 
        GROUP BY tb_journal_purchase_list.journal_purchase_list_id 
        ORDER BY STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s'), journal_purchase_code DESC 
        "; 
        //------------------------- End Purchase Journal -------------------------------------------------------------



        //------------------------- Sale Journal -------------------------------------------------------------
        $str_sale_date = "";

        if($date_start != "" && $date_end != ""){
            $str_sale_date = "AND STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_sale_date = "AND STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_sale_date = "AND STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_sale = " SELECT
        IFNULL((
            SELECT invoice_customer_close FROM tb_invoice_customer WHERE invoice_customer_id = tb_journal_sale.invoice_customer_id LIMIT 0,1
        ),0) as status_cancelled, 
        journal_sale_code as journal_code, 
        journal_sale_date as journal_date,
        journal_sale_name  as journal_name,
        account_id,
        journal_sale_list_name as journal_list_name,
        IFNULL(SUM(journal_sale_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_sale_list_credit),0) as journal_credit
        FROM tb_journal_sale 
        LEFT JOIN tb_journal_sale_list ON tb_journal_sale_list.journal_sale_id = tb_journal_sale.journal_sale_id  
        WHERE ( 
                journal_sale_code LIKE ('%$keyword%') 
            OR  journal_sale_name LIKE ('%$keyword%') 
        )  
        $str_sale_date 
        GROUP BY tb_journal_sale_list.journal_sale_list_id 
        ORDER BY STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s'), journal_sale_code DESC 
        "; 
        //------------------------- End Sale Journal -------------------------------------------------------------



        //------------------------- Sale Return Journal -------------------------------------------------------------
        $str_sale_return_date = "";

        if($date_start != "" && $date_end != ""){
            $str_sale_return_date = "AND STR_TO_DATE(journal_sale_return_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_sale_return_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_sale_return_date = "AND STR_TO_DATE(journal_sale_return_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_sale_return_date = "AND STR_TO_DATE(journal_sale_return_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_sale_return = " SELECT
        IFNULL((
            SELECT credit_note_close FROM tb_credit_note WHERE credit_note_id = tb_journal_sale_return.credit_note_id LIMIT 0,1
        ),0) as status_cancelled, 
        journal_sale_return_code as journal_code, 
        journal_sale_return_date as journal_date,
        journal_sale_return_name  as journal_name,
        account_id,
        journal_sale_return_list_name as journal_list_name,
        IFNULL(SUM(journal_sale_return_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_sale_return_list_credit),0) as journal_credit
        FROM tb_journal_sale_return 
        LEFT JOIN tb_journal_sale_return_list ON tb_journal_sale_return_list.journal_sale_return_id = tb_journal_sale_return.journal_sale_return_id  
        WHERE ( 
                journal_sale_return_code LIKE ('%$keyword%') 
            OR  journal_sale_return_name LIKE ('%$keyword%') 
        )  
        $str_sale_return_date 
        GROUP BY tb_journal_sale_return_list.journal_sale_return_list_id 
        ORDER BY STR_TO_DATE(journal_sale_return_date,'%d-%m-%Y %H:%i:%s'), journal_sale_return_code DESC 
        "; 
        //------------------------- End Sale Return Journal -------------------------------------------------------------



        //------------------------- Cash Payment Journal -------------------------------------------------------------
        $str_cash_payment_date = "";

        if($date_start != "" && $date_end != ""){
            $str_cash_payment_date = "AND STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_cash_payment_date = "AND STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_cash_payment_date = "AND STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_cash_payment = " SELECT 
        '0' as status_cancelled,
        journal_cash_payment_code as journal_code, 
        journal_cash_payment_date as journal_date,
        journal_cash_payment_name  as journal_name,
        account_id,
        journal_cash_payment_list_name as journal_list_name,
        IFNULL(SUM(journal_cash_payment_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_cash_payment_list_credit),0) as journal_credit
        FROM tb_journal_cash_payment 
        LEFT JOIN tb_journal_cash_payment_list ON tb_journal_cash_payment_list.journal_cash_payment_id = tb_journal_cash_payment.journal_cash_payment_id  
        WHERE ( 
                journal_cash_payment_code LIKE ('%$keyword%') 
            OR  journal_cash_payment_name LIKE ('%$keyword%') 
        )  
        $str_cash_payment_date 
        GROUP BY tb_journal_cash_payment_list.journal_cash_payment_list_id 
        ORDER BY STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s'), journal_cash_payment_code DESC 
        "; 
        //------------------------- End Cash Payment Journal -------------------------------------------------------------



        //------------------------- Cash Receipt Journal -------------------------------------------------------------
        $str_cash_receipt_date = "";

        if($date_start != "" && $date_end != ""){
            $str_cash_receipt_date = "AND STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_cash_receipt_date = "AND STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_cash_receipt_date = "AND STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        $sql_cash_receipt = " SELECT 
        '0' as status_cancelled,
        journal_cash_receipt_code as journal_code, 
        journal_cash_receipt_date as journal_date,
        journal_cash_receipt_name  as journal_name,
        account_id,
        journal_cash_receipt_list_name as journal_list_name,
        IFNULL(SUM(journal_cash_receipt_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_cash_receipt_list_credit),0) as journal_credit
        FROM tb_journal_cash_receipt 
        LEFT JOIN tb_journal_cash_receipt_list ON tb_journal_cash_receipt_list.journal_cash_receipt_id = tb_journal_cash_receipt.journal_cash_receipt_id  
        WHERE ( 
                journal_cash_receipt_code LIKE ('%$keyword%') 
            OR  journal_cash_receipt_name LIKE ('%$keyword%') 
        )  
        $str_cash_receipt_date 
        GROUP BY tb_journal_cash_receipt_list.journal_cash_receipt_list_id 
        ORDER BY STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s'), journal_cash_receipt_code DESC 
        "; 
        //------------------------- End Cash Receipt Journal -------------------------------------------------------------


            $sql =" SELECT tb_account.* , status_cancelled , journal_code , journal_date , journal_name , journal_list_name , journal_debit, journal_credit 
                FROM tb_account 
                LEFT JOIN  (($sql_general)  
                UNION   ALL  ($sql_purchase) 
                UNION   ALL  ($sql_sale) 
                UNION   ALL  ($sql_sale_return) 
                UNION   ALL  ($sql_cash_payment) 
                UNION   ALL  ($sql_cash_receipt)) as tb_journal   
                ON tb_account.account_id = tb_journal.account_id 
                WHERE 1 
                $str_code_acc 
                ORDER BY account_code ASC , STR_TO_DATE(journal_date,'%d-%m-%Y %H:%i:%s') ASC ,journal_code ASC, journal_debit DESC 
        ";  
 
            //echo "<pre>".$sql."</pre>";

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
    //-------------------------------- ดึงรายสมุดรายวัน แบบเต็ม รวมตามบัญชี เรียงตามบัญชี ------------------------------------------
    //
    //
    //#####################################################################################################################
    function getJournalAcountBalanceBy($date_start = "", $account_id = ""){


        //------------------------- General Journal -------------------------------------------------------------
        $sql_general = " SELECT
        '0' as status_cancelled,
        journal_general_code as journal_code, 
        journal_general_date as journal_date,
        journal_general_name  as journal_name,
        account_id,
        journal_general_list_name as journal_list_name,
        IFNULL(SUM(journal_general_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_general_list_credit),0) as journal_credit
        FROM tb_journal_general 
        LEFT JOIN tb_journal_general_list ON tb_journal_general_list.journal_general_id = tb_journal_general.journal_general_id  
        WHERE  account_id = '$account_id' 
        AND STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') < STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s')
        GROUP BY tb_journal_general_list.journal_general_list_id 
        HAVING status_cancelled = 0 
        ORDER BY STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s'), journal_general_code DESC 
        "; 
        //------------------------- End General Journal -------------------------------------------------------------



        //------------------------- Purchase Journal -------------------------------------------------------------


        $sql_purchase = " SELECT
        IFNULL((
            SELECT invoice_supplier_close FROM tb_invoice_supplier WHERE invoice_supplier_id = tb_journal_purchase.invoice_supplier_id LIMIT 0,1
        ),0) as status_cancelled, 
        journal_purchase_code as journal_code, 
        journal_purchase_date as journal_date,
        journal_purchase_name  as journal_name,
        account_id,
        journal_purchase_list_name as journal_list_name,
        IFNULL(SUM(journal_purchase_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_purchase_list_credit),0) as journal_credit
        FROM tb_journal_purchase 
        LEFT JOIN tb_journal_purchase_list ON tb_journal_purchase_list.journal_purchase_id = tb_journal_purchase.journal_purchase_id  
        WHERE  account_id = '$account_id' 
        AND STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') < STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') 
        GROUP BY tb_journal_purchase_list.journal_purchase_list_id 
        HAVING status_cancelled = 0 
        ORDER BY STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s'), journal_purchase_code DESC 
        "; 
        //------------------------- End Purchase Journal -------------------------------------------------------------



        //------------------------- Sale Journal -------------------------------------------------------------

        $sql_sale = " SELECT 
        IFNULL((
            SELECT invoice_customer_close FROM tb_invoice_customer WHERE invoice_customer_id = tb_journal_sale.invoice_customer_id LIMIT 0,1
        ),0) as status_cancelled, 
        journal_sale_code as journal_code, 
        journal_sale_date as journal_date,
        journal_sale_name  as journal_name,
        account_id,
        journal_sale_list_name as journal_list_name,
        IFNULL(SUM(journal_sale_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_sale_list_credit),0) as journal_credit
        FROM tb_journal_sale 
        LEFT JOIN tb_journal_sale_list ON tb_journal_sale_list.journal_sale_id = tb_journal_sale.journal_sale_id  
        WHERE  account_id = '$account_id' 
        AND STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') < STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s')
        GROUP BY tb_journal_sale_list.journal_sale_list_id 
        HAVING status_cancelled = 0 
        ORDER BY STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s'), journal_sale_code DESC 
        "; 
        //------------------------- End Sale Journal -------------------------------------------------------------



        //------------------------- Sale Return Journal -------------------------------------------------------------

        $sql_sale_return = " SELECT 
        IFNULL((
            SELECT credit_note_close FROM tb_credit_note WHERE credit_note_id = tb_journal_sale_return.credit_note_id LIMIT 0,1
        ),0) as status_cancelled, 
        journal_sale_return_code as journal_code, 
        journal_sale_return_date as journal_date,
        journal_sale_return_name  as journal_name,
        account_id,
        journal_sale_return_list_name as journal_list_name,
        IFNULL(SUM(journal_sale_return_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_sale_return_list_credit),0) as journal_credit
        FROM tb_journal_sale_return 
        LEFT JOIN tb_journal_sale_return_list ON tb_journal_sale_return_list.journal_sale_return_id = tb_journal_sale_return.journal_sale_return_id  
        WHERE  account_id = '$account_id' 
        AND STR_TO_DATE(journal_sale_return_date,'%d-%m-%Y %H:%i:%s') < STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s')
        GROUP BY tb_journal_sale_return_list.journal_sale_return_list_id 
        HAVING status_cancelled = 0 
        ORDER BY STR_TO_DATE(journal_sale_return_date,'%d-%m-%Y %H:%i:%s'), journal_sale_return_code DESC 
        "; 
        //------------------------- End Sale Return Journal -------------------------------------------------------------



        //------------------------- Cash Payment Journal -------------------------------------------------------------
 
        $sql_cash_payment = " SELECT
        '0' as status_cancelled,
        journal_cash_payment_code as journal_code, 
        journal_cash_payment_date as journal_date,
        journal_cash_payment_name  as journal_name,
        account_id,
        journal_cash_payment_list_name as journal_list_name,
        IFNULL(SUM(journal_cash_payment_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_cash_payment_list_credit),0) as journal_credit
        FROM tb_journal_cash_payment 
        LEFT JOIN tb_journal_cash_payment_list ON tb_journal_cash_payment_list.journal_cash_payment_id = tb_journal_cash_payment.journal_cash_payment_id  
        WHERE  account_id = '$account_id' 
        AND STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') < STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s')
        GROUP BY tb_journal_cash_payment_list.journal_cash_payment_list_id 
        HAVING status_cancelled = 0 
        ORDER BY STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s'), journal_cash_payment_code DESC 
        "; 
        //------------------------- End Cash Payment Journal -------------------------------------------------------------



        //------------------------- Cash Receipt Journal -------------------------------------------------------------
  
        $sql_cash_receipt = " SELECT
        '0' as status_cancelled,
        journal_cash_receipt_code as journal_code, 
        journal_cash_receipt_date as journal_date,
        journal_cash_receipt_name  as journal_name,
        account_id,
        journal_cash_receipt_list_name as journal_list_name,
        IFNULL(SUM(journal_cash_receipt_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_cash_receipt_list_credit),0) as journal_credit
        FROM tb_journal_cash_receipt 
        LEFT JOIN tb_journal_cash_receipt_list ON tb_journal_cash_receipt_list.journal_cash_receipt_id = tb_journal_cash_receipt.journal_cash_receipt_id  
        WHERE account_id = '$account_id'  
        AND STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') < STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s')
        GROUP BY tb_journal_cash_receipt_list.journal_cash_receipt_list_id 
        HAVING status_cancelled = 0 
        ORDER BY STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s'), journal_cash_receipt_code DESC 
        "; 
        //------------------------- End Cash Receipt Journal -------------------------------------------------------------

            $sql =" SELECT SUM(IFNULL(journal_debit,'0')) - SUM(IFNULL(journal_credit,'0')) as journal_begin
                FROM  (($sql_general)  
                UNION   ALL  ($sql_purchase) 
                UNION   ALL  ($sql_sale) 
                UNION   ALL  ($sql_sale_return) 
                UNION   ALL  ($sql_cash_payment) 
                UNION   ALL  ($sql_cash_receipt)) as tb_journal    
                GROUP BY account_id
        ";   

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
  

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data['journal_begin'];
        }
    }

     //#####################################################################################################################
    //
    //
    //-------------------------------- ดึงรายงานเช็คจ่ายคงเหลือ ตามวันที่ เเละ บัญชี ------------------------------
    //
    //
    //#####################################################################################################################

    function getJournalAcountReportShowpayAllBy($date_end = "", $account_id ){
        //------------------------- General Journal -------------------------------------------------------------
              $str_general_date = "";
              
              if ($date_end != ""){
                  $str_general_date = " WHERE STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
              }else{
                  $date_  = date('t-m-Y');
                  $str_general_date = " WHERE STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_','%d-%m-%Y %H:%i:%s') ";
              } 
              
              $sql_general = " SELECT
              journal_general_code as journal_code, 
              journal_general_date as journal_date,
              journal_general_name  as journal_name, 
              check_pay_code  as cheque_code,
              check_pay_total  as cheque_total,
              check_pay_date_write,
              check_pay_date,
              account_id,
              IFNULL(SUM(journal_general_list_debit),0) as journal_debit,
              IFNULL(SUM(journal_general_list_credit),0) as journal_credit
              FROM tb_journal_general 
              LEFT JOIN tb_journal_general_list ON tb_journal_general_list.journal_general_id = tb_journal_general.journal_general_id  
              LEFT JOIN tb_check_pay ON tb_journal_general_list.journal_cheque_pay_id = tb_check_pay.check_pay_id  
              $str_general_date 
              GROUP BY tb_journal_general_list.journal_general_list_id 
              ORDER BY STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s'), journal_general_code DESC 
              "; 
              //------------------------- End General Journal -------------------------------------------------------------
      
      
      
              //------------------------- Purchase Journal -------------------------------------------------------------
              $str_purchase_date = "";
      
              if ($date_end != ""){
                  $str_purchase_date = "WHERE STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
              }else{
                $date_  = date('t-m-Y');
                $str_purchase_date = " WHERE STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_','%d-%m-%Y %H:%i:%s') ";
            }  
      
              $sql_purchase = " SELECT
              journal_purchase_code as journal_code, 
              journal_purchase_date as journal_date,
              journal_purchase_name  as journal_name, 
              check_pay_code  as cheque_code,
              check_pay_total  as cheque_total,
              check_pay_date_write,check_pay_date,
              account_id,
              IFNULL(SUM(journal_purchase_list_debit),0) as journal_debit,
              IFNULL(SUM(journal_purchase_list_credit),0) as journal_credit
              FROM tb_journal_purchase 
              LEFT JOIN tb_journal_purchase_list ON tb_journal_purchase_list.journal_purchase_id = tb_journal_purchase.journal_purchase_id 
              LEFT JOIN tb_check_pay ON tb_journal_purchase_list.journal_cheque_pay_id = tb_check_pay.check_pay_id  
              $str_purchase_date 
              GROUP BY tb_journal_purchase_list.journal_purchase_list_id 
              ORDER BY STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s'), journal_purchase_code DESC 
              "; 
              //------------------------- End Purchase Journal -------------------------------------------------------------
      
      
      
              //------------------------- Sale Journal -------------------------------------------------------------
              $str_sale_date = "";
            
              if ($date_end != ""){
                  $str_sale_date = "WHERE STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
              } else{
                $date_  = date('t-m-Y');
                $str_sale_date = " WHERE STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_','%d-%m-%Y %H:%i:%s') ";
            } 
            
              $sql_sale = " SELECT
              journal_sale_code as journal_code, 
              journal_sale_date as journal_date,
              journal_sale_name  as journal_name, 
              check_pay_code  as cheque_code,
              check_pay_total  as cheque_total,
              check_pay_date_write,check_pay_date,
              account_id,
              IFNULL(SUM(journal_sale_list_debit),0) as journal_debit,
              IFNULL(SUM(journal_sale_list_credit),0) as journal_credit
              FROM tb_journal_sale 
              LEFT JOIN tb_journal_sale_list ON tb_journal_sale_list.journal_sale_id = tb_journal_sale.journal_sale_id  
              LEFT JOIN tb_check_pay ON tb_journal_sale_list.journal_cheque_pay_id = tb_check_pay.check_pay_id  
              $str_sale_date 
              GROUP BY tb_journal_sale_list.journal_sale_list_id 
              ORDER BY STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s'), journal_sale_code DESC 
              "; 
              //------------------------- End Sale Journal -------------------------------------------------------------
      
      
              //------------------------- Sale Return Journal -------------------------------------------------------------
              $str_sale_return_date = "";
            
              if ($date_end != ""){
                  $str_sale_return_date = "WHERE STR_TO_DATE(journal_sale_return_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
              } else{
                $date_  = date('t-m-Y');
                $str_sale_return_date = " WHERE STR_TO_DATE(journal_sale_return_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_','%d-%m-%Y %H:%i:%s') ";
            } 
            
              $sql_sale_return = " SELECT
              journal_sale_return_code as journal_code, 
              journal_sale_return_date as journal_date,
              journal_sale_return_name  as journal_name, 
              check_pay_code  as cheque_code,
              check_pay_total  as cheque_total,
              check_pay_date_write,check_pay_date,
              account_id,
              IFNULL(SUM(journal_sale_return_list_debit),0) as journal_debit,
              IFNULL(SUM(journal_sale_return_list_credit),0) as journal_credit
              FROM tb_journal_sale_return 
              LEFT JOIN tb_journal_sale_return_list ON tb_journal_sale_return_list.journal_sale_return_id = tb_journal_sale_return.journal_sale_return_id  
              LEFT JOIN tb_check_pay ON tb_journal_sale_return_list.journal_cheque_pay_id = tb_check_pay.check_pay_id  
              $str_sale_return_date 
              GROUP BY tb_journal_sale_return_list.journal_sale_return_list_id 
              ORDER BY STR_TO_DATE(journal_sale_return_date,'%d-%m-%Y %H:%i:%s'), journal_sale_return_code DESC 
              "; 
              //------------------------- End Sale Return Journal -------------------------------------------------------------
      
      
      
              //------------------------- Cash Payment Journal -------------------------------------------------------------
              $str_cash_payment_date = "";
      
              if ($date_end != ""){
                  $str_cash_payment_date = "WHERE STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
              }else{
                $date_  = date('t-m-Y');
                $str_cash_payment_date = " WHERE STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_','%d-%m-%Y %H:%i:%s') ";
            }  
      
              $sql_cash_payment = " SELECT
              journal_cash_payment_code as journal_code, 
              journal_cash_payment_date as journal_date,
              journal_cash_payment_name  as journal_name,
              check_pay_code  as cheque_code,
              check_pay_total  as cheque_total,
              check_pay_date_write,check_pay_date,
              account_id,
              IFNULL(SUM(journal_cash_payment_list_debit),0) as journal_debit,
              IFNULL(SUM(journal_cash_payment_list_credit),0) as journal_credit
              FROM tb_journal_cash_payment 
              LEFT JOIN tb_journal_cash_payment_list ON tb_journal_cash_payment_list.journal_cash_payment_id = tb_journal_cash_payment.journal_cash_payment_id  
              LEFT JOIN tb_check_pay ON tb_journal_cash_payment_list.journal_cheque_pay_id = tb_check_pay.check_pay_id  
              $str_cash_payment_date 
              GROUP BY tb_journal_cash_payment_list.journal_cash_payment_list_id 
              ORDER BY STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s'), journal_cash_payment_code DESC 
              "; 
              //------------------------- End Cash Payment Journal -------------------------------------------------------------
      
      
      
              //------------------------- Cash Receipt Journal -------------------------------------------------------------
              $str_cash_receipt_date = "";
      
              if ($date_end != ""){
                  $str_cash_receipt_date = "WHERE STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
              }else{
                $date_  = date('t-m-Y');
                $str_cash_receipt_date = " WHERE STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_','%d-%m-%Y %H:%i:%s') ";
            }  
      
              $sql_cash_receipt = " SELECT
              journal_cash_receipt_code as journal_code, 
              journal_cash_receipt_date as journal_date,
              journal_cash_receipt_name  as journal_name,
              check_pay_code  as cheque_code,
              check_pay_total  as cheque_total,
              check_pay_date_write,check_pay_date,
              account_id,
              IFNULL(SUM(journal_cash_receipt_list_debit),0) as journal_debit,
              IFNULL(SUM(journal_cash_receipt_list_credit),0) as journal_credit
              FROM tb_journal_cash_receipt 
              LEFT JOIN tb_journal_cash_receipt_list ON tb_journal_cash_receipt_list.journal_cash_receipt_id = tb_journal_cash_receipt.journal_cash_receipt_id  
              LEFT JOIN tb_check_pay ON tb_journal_cash_receipt_list.journal_cheque_pay_id = tb_check_pay.check_pay_id 
              $str_cash_receipt_date 
              GROUP BY tb_journal_cash_receipt_list.journal_cash_receipt_list_id 
              ORDER BY STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s'), journal_cash_receipt_code DESC 
              "; 
              //------------------------- End Cash Receipt Journal -------------------------------------------------------------
      
      
              $sql =" SELECT account_code , account_name_th , SUM(IFNULL(tb_journal.journal_debit,0)), SUM(IFNULL(tb_journal.journal_credit,0)) , (SUM(IFNULL(tb_journal.journal_debit,0)) - SUM(IFNULL(tb_journal.journal_credit,0)))  as account_value,
                      cheque_code , cheque_total,journal_name, check_pay_date_write,journal_code,check_pay_date
                      FROM tb_account 
                      LEFT JOIN  (($sql_general)  
                      UNION   ALL  ($sql_purchase) 
                      UNION   ALL  ($sql_sale) 
                      UNION   ALL  ($sql_sale_return) 
                      UNION   ALL  ($sql_cash_payment) 
                      UNION   ALL  ($sql_cash_receipt)) as tb_journal  
                      ON tb_account.account_id = tb_journal.account_id  
                      WHERE tb_account.account_id = '$account_id'  AND cheque_code IS NOT NULL
                      GROUP BY cheque_code  
                      HAVING account_value < 0    
                      ORDER BY cheque_code ASC
              ";  

              //echo "<pre>".$sql."</pre>";
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
    //-------------------------------- ดึงรายงานเช็ครับคงเหลือ ตามวันที่ เเละ บัญชี ------------------------------
    //
    //
    //#####################################################################################################################

    function getJournalAcountReportShowRceiptsAllBy($date_end = "", $account_id ){
        //------------------------- General Journal -------------------------------------------------------------
              $str_general_date = "";
              
              if ($date_end != ""){
                  $str_general_date = " WHERE STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
              }else{
                  $date_  = date('t-m-Y');
                  $str_general_date = " WHERE STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_','%d-%m-%Y %H:%i:%s') ";
              } 
              
              $sql_general = " SELECT
              journal_general_code as journal_code, 
              journal_general_date as journal_date,
              check_remark  as journal_name,
            
              check_code  as cheque_code,
              check_total  as cheque_total,
              check_date_write,check_date_recieve,
              account_id,
              IFNULL(SUM(journal_general_list_debit),0) as journal_debit,
              IFNULL(SUM(journal_general_list_credit),0) as journal_credit
              FROM tb_journal_general 
              LEFT JOIN tb_journal_general_list ON tb_journal_general_list.journal_general_id = tb_journal_general.journal_general_id  
              LEFT JOIN tb_check ON tb_journal_general_list.journal_cheque_id= tb_check.check_id  
              $str_general_date 
              GROUP BY tb_journal_general_list.journal_general_list_id 
              ORDER BY STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s'), journal_general_code DESC 
              "; 
              //------------------------- End General Journal -------------------------------------------------------------
      
      
      
              //------------------------- Purchase Journal -------------------------------------------------------------
              $str_purchase_date = "";
      
              if ($date_end != ""){
                  $str_purchase_date = "WHERE STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
              }else{
                $date_  = date('t-m-Y');
                $str_purchase_date = " WHERE STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_','%d-%m-%Y %H:%i:%s') ";
            }  
      
              $sql_purchase = " SELECT
              journal_purchase_code as journal_code, 
              journal_purchase_date as journal_date,
              check_remark  as journal_name,
             
              check_code  as cheque_code,
              check_total  as cheque_total,
              check_date_write,check_date_recieve,
              account_id,
              IFNULL(SUM(journal_purchase_list_debit),0) as journal_debit,
              IFNULL(SUM(journal_purchase_list_credit),0) as journal_credit
              FROM tb_journal_purchase 
              LEFT JOIN tb_journal_purchase_list ON tb_journal_purchase_list.journal_purchase_id = tb_journal_purchase.journal_purchase_id 
              LEFT JOIN tb_check ON tb_journal_purchase_list.journal_cheque_id = tb_check.check_id  
             
              $str_purchase_date 
              GROUP BY tb_journal_purchase_list.journal_purchase_list_id 
              ORDER BY STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s'), journal_purchase_code DESC 
              "; 
              //------------------------- End Purchase Journal -------------------------------------------------------------
      
      
      
              //------------------------- Sale Journal -------------------------------------------------------------
              $str_sale_date = "";
            
              if ($date_end != ""){
                  $str_sale_date = "WHERE STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
              } else{
                $date_  = date('t-m-Y');
                $str_sale_date = " WHERE STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_','%d-%m-%Y %H:%i:%s') ";
              } 
            
              $sql_sale = " SELECT
              check_code as journal_code, 
              journal_sale_date as journal_date,
              check_remark  as journal_name,
             
              check_code  as cheque_code,
              check_total  as cheque_total,
              check_date_write,check_date_recieve,
              account_id,
              IFNULL(SUM(journal_sale_list_debit),0) as journal_debit,
              IFNULL(SUM(journal_sale_list_credit),0) as journal_credit
              FROM tb_journal_sale 
              LEFT JOIN tb_journal_sale_list ON tb_journal_sale_list.journal_sale_id = tb_journal_sale.journal_sale_id  
              LEFT JOIN tb_check ON tb_journal_sale_list.journal_cheque_id = tb_check.check_id  
              $str_sale_date 
              GROUP BY tb_journal_sale_list.journal_sale_list_id 
              ORDER BY STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s'), journal_sale_code DESC 
              "; 
              //------------------------- End Sale Journal -------------------------------------------------------------
      
      
      
              //------------------------- Sale Return Journal -------------------------------------------------------------
              $str_sale_return_date = "";
            
              if ($date_end != ""){
                  $str_sale_return_date = "WHERE STR_TO_DATE(journal_sale_return_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
              } else{
                $date_  = date('t-m-Y');
                $str_sale_return_date = " WHERE STR_TO_DATE(journal_sale_return_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_','%d-%m-%Y %H:%i:%s') ";
              } 
            
              $sql_sale_return = " SELECT
              check_code as journal_code, 
              journal_sale_return_date as journal_date,
              check_remark  as journal_name,
             
              check_code  as cheque_code,
              check_total  as cheque_total,
              check_date_write,check_date_recieve,
              account_id,
              IFNULL(SUM(journal_sale_return_list_debit),0) as journal_debit,
              IFNULL(SUM(journal_sale_return_list_credit),0) as journal_credit
              FROM tb_journal_sale_return 
              LEFT JOIN tb_journal_sale_return_list ON tb_journal_sale_return_list.journal_sale_return_id = tb_journal_sale_return.journal_sale_return_id  
              LEFT JOIN tb_check ON tb_journal_sale_return_list.journal_cheque_id = tb_check.check_id  
              $str_sale_return_date 
              GROUP BY tb_journal_sale_return_list.journal_sale_return_list_id 
              ORDER BY STR_TO_DATE(journal_sale_return_date,'%d-%m-%Y %H:%i:%s'), journal_sale_return_code DESC 
              "; 
              //------------------------- End Sale Return Journal -------------------------------------------------------------
      
      
      
              //------------------------- Cash Payment Journal -------------------------------------------------------------
              $str_cash_payment_date = "";
      
              if ($date_end != ""){
                  $str_cash_payment_date = "WHERE STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
              }else{
                $date_  = date('t-m-Y');
                $str_cash_payment_date = " WHERE STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_','%d-%m-%Y %H:%i:%s') ";
            }  
      
              $sql_cash_payment = " SELECT
              journal_cash_payment_code as journal_code, 
              journal_cash_payment_date as journal_date,
              check_remark  as journal_name,
              check_code  as cheque_code,
              check_total  as cheque_total,
              check_date_write,check_date_recieve,
              account_id,
              IFNULL(SUM(journal_cash_payment_list_debit),0) as journal_debit,
              IFNULL(SUM(journal_cash_payment_list_credit),0) as journal_credit
              FROM tb_journal_cash_payment 
              LEFT JOIN tb_journal_cash_payment_list ON tb_journal_cash_payment_list.journal_cash_payment_id = tb_journal_cash_payment.journal_cash_payment_id  
              LEFT JOIN tb_check ON tb_journal_cash_payment_list.journal_cheque_id = tb_check.check_id   
              
              $str_cash_payment_date 
              GROUP BY tb_journal_cash_payment_list.journal_cash_payment_list_id 
              ORDER BY STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s'), journal_cash_payment_code DESC 
              "; 
              //------------------------- End Cash Payment Journal -------------------------------------------------------------
      
      
      
              //------------------------- Cash Receipt Journal -------------------------------------------------------------
              $str_cash_receipt_date = "";
      
              if ($date_end != ""){
                  $str_cash_receipt_date = "WHERE STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
              }else{
                $date_  = date('t-m-Y');
                $str_cash_receipt_date = " WHERE STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_','%d-%m-%Y %H:%i:%s') ";
            }  
      
              $sql_cash_receipt = " SELECT
              journal_cash_receipt_code as journal_code, 
              journal_cash_receipt_date as journal_date,
              check_remark  as journal_name,
              check_code  as cheque_code,
              check_total  as cheque_total,
              check_date_write,check_date_recieve,
              account_id,
              IFNULL(SUM(journal_cash_receipt_list_debit),0) as journal_debit,
              IFNULL(SUM(journal_cash_receipt_list_credit),0) as journal_credit
              FROM tb_journal_cash_receipt 
              LEFT JOIN tb_journal_cash_receipt_list ON tb_journal_cash_receipt_list.journal_cash_receipt_id = tb_journal_cash_receipt.journal_cash_receipt_id  
              LEFT JOIN tb_check ON tb_journal_cash_receipt_list.journal_cheque_id = tb_check.check_id   
              
             
              $str_cash_receipt_date 
              GROUP BY tb_journal_cash_receipt_list.journal_cash_receipt_list_id 
              ORDER BY STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s'), journal_cash_receipt_code DESC 
              "; 
              //------------------------- End Cash Receipt Journal -------------------------------------------------------------
      
      
              $sql =" SELECT account_code , account_name_th ,  SUM(IFNULL(tb_journal.journal_debit,0)), SUM(IFNULL(tb_journal.journal_credit,0)) , ( SUM(IFNULL(tb_journal.journal_debit,0)) - SUM(IFNULL(tb_journal.journal_credit,0)) ) as account_value,
                      cheque_code , cheque_total,journal_name, check_date_write,journal_code,check_date_recieve
                      FROM tb_account 
                      LEFT JOIN  (($sql_general)  
                      UNION   ALL  ($sql_purchase) 
                      UNION   ALL  ($sql_sale) 
                      UNION   ALL  ($sql_sale_return) 
                      UNION   ALL  ($sql_cash_payment) 
                      UNION   ALL  ($sql_cash_receipt)) as tb_journal  
                      ON tb_account.account_id = tb_journal.account_id  
                      WHERE tb_account.account_id = '$account_id'  AND cheque_code IS NOT NULL
                      GROUP BY cheque_code     
                      HAVING account_value > 0 
                      ORDER BY cheque_code ASC
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


    //#####################################################################################################################//
    //
    //
    //-------------------------------- ดึงรายการสินทรัพย์  ตามวันที่ ------------------------------------------
    //
    //
    //#####################################################################################################################//
    function getJournalAssetsReportBy($date_end = "", $date_start = "", $code_end = "" ,$keyword = ""){
        //echo $keyword ;

        if($keyword == 1){

            $account_group = "account_group = '1'";

        }elseif($keyword == 2){
            $account_group = "account_group = '2' OR account_group = '3'";
        }elseif($keyword == 4){
            $account_group = "account_group = '4' ";
        }elseif($keyword == 5){
            $account_group = " account_group = '5'";
        }
        //------------------------- General Journal -------------------------------------------------------------
        $str_general_date = "";

        if ($date_end != ""   ){
            $str_general_date .=" STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";        
        } 

        if(( $keyword == 5 or $keyword == 4 )){
            $str_general_date ="";
            $str_general_date ="STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND ";
            $str_general_date .="STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }
       // echo $str_general_date ;

        $sql_general = " SELECT
        journal_general_code as journal_code, 
        journal_general_date as journal_date,
        journal_general_name  as journal_name,
        account_id,
        IFNULL(SUM(journal_general_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_general_list_credit),0) as journal_credit
        FROM tb_journal_general 
        LEFT JOIN tb_journal_general_list ON tb_journal_general_list.journal_general_id = tb_journal_general.journal_general_id  
        WHERE 
        $str_general_date 
        GROUP BY tb_journal_general_list.journal_general_list_id 
        ORDER BY STR_TO_DATE(journal_general_date,'%d-%m-%Y %H:%i:%s'), journal_general_code DESC 
        "; 
        //------------------------- End General Journal -------------------------------------------------------------
 
        //echo '<pre>'.$sql_general.'</pre>';

        //------------------------- Purchase Journal -------------------------------------------------------------
        $str_purchase_date = "";

        if ($date_end != ""){
            $str_purchase_date = " STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        if(( $keyword == 5 or $keyword == 4 )){
            $str_purchase_date = "";
            $str_purchase_date = " STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND ";
            $str_purchase_date .= " STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }

        $sql_purchase = " SELECT
        journal_purchase_code as journal_code, 
        journal_purchase_date as journal_date,
        journal_purchase_name  as journal_name,
        account_id,
        IFNULL(SUM(journal_purchase_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_purchase_list_credit),0) as journal_credit
        FROM tb_journal_purchase 
        LEFT JOIN tb_journal_purchase_list ON tb_journal_purchase_list.journal_purchase_id = tb_journal_purchase.journal_purchase_id  
        WHERE 
        $str_purchase_date 
        GROUP BY tb_journal_purchase_list.journal_purchase_list_id 
        ORDER BY STR_TO_DATE(journal_purchase_date,'%d-%m-%Y %H:%i:%s'), journal_purchase_code DESC 
        "; 
        //------------------------- End Purchase Journal -------------------------------------------------------------



        //------------------------- Sale Journal -------------------------------------------------------------
        $str_sale_date = "";

        if ($date_end != ""){
            $str_sale_date = "STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 
        if(( $keyword == 5 or $keyword == 4 )){
            $str_sale_date = "";
            $str_sale_date = "STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND ";  
            $str_sale_date .= "STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        $sql_sale = " SELECT
        journal_sale_code as journal_code, 
        journal_sale_date as journal_date,
        journal_sale_name  as journal_name,
        account_id,
        IFNULL(SUM(journal_sale_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_sale_list_credit),0) as journal_credit
        FROM tb_journal_sale 
        LEFT JOIN tb_journal_sale_list ON tb_journal_sale_list.journal_sale_id = tb_journal_sale.journal_sale_id  
        WHERE 
        $str_sale_date 
        GROUP BY tb_journal_sale_list.journal_sale_list_id 
        ORDER BY STR_TO_DATE(journal_sale_date,'%d-%m-%Y %H:%i:%s'), journal_sale_code DESC 
        "; 
        //------------------------- End Sale Journal -------------------------------------------------------------


        //------------------------- Sale Return Journal -------------------------------------------------------------
        $str_sale_return_date = "";

        if ($date_end != ""){
            $str_sale_return_date = "STR_TO_DATE(journal_sale_return_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 
        if(( $keyword == 5 or $keyword == 4 )){
            $str_sale_return_date = "";
            $str_sale_return_date = "STR_TO_DATE(journal_sale_return_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND ";  
            $str_sale_return_date .= "STR_TO_DATE(journal_sale_return_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        $sql_sale_return = " SELECT
        journal_sale_return_code as journal_code, 
        journal_sale_return_date as journal_date,
        journal_sale_return_name  as journal_name,
        account_id,
        IFNULL(SUM(journal_sale_return_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_sale_return_list_credit),0) as journal_credit
        FROM tb_journal_sale_return 
        LEFT JOIN tb_journal_sale_return_list ON tb_journal_sale_return_list.journal_sale_return_id = tb_journal_sale_return.journal_sale_return_id  
        WHERE 
        $str_sale_return_date 
        GROUP BY tb_journal_sale_return_list.journal_sale_return_list_id 
        ORDER BY STR_TO_DATE(journal_sale_return_date,'%d-%m-%Y %H:%i:%s'), journal_sale_return_code DESC 
        "; 
        //------------------------- End Sale Return Journal -------------------------------------------------------------



        //------------------------- Cash Payment Journal -------------------------------------------------------------
        $str_cash_payment_date = "";

        if ($date_end != ""){
            $str_cash_payment_date = " STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 
        if(( $keyword == 5 or $keyword == 4 )){
            $str_cash_payment_date = "";
            $str_cash_payment_date = " STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND ";  
            $str_cash_payment_date .= " STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        $sql_cash_payment = " SELECT
        journal_cash_payment_code as journal_code, 
        journal_cash_payment_date as journal_date,
        journal_cash_payment_name  as journal_name,
        account_id,
        IFNULL(SUM(journal_cash_payment_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_cash_payment_list_credit),0) as journal_credit
        FROM tb_journal_cash_payment 
        LEFT JOIN tb_journal_cash_payment_list ON tb_journal_cash_payment_list.journal_cash_payment_id = tb_journal_cash_payment.journal_cash_payment_id  
        WHERE 
        $str_cash_payment_date 
        GROUP BY tb_journal_cash_payment_list.journal_cash_payment_list_id 
        ORDER BY STR_TO_DATE(journal_cash_payment_date,'%d-%m-%Y %H:%i:%s'), journal_cash_payment_code DESC 
        "; 
        //------------------------- End Cash Payment Journal -------------------------------------------------------------



        //------------------------- Cash Receipt Journal -------------------------------------------------------------
        $str_cash_receipt_date = "";

        if ($date_end != ""){
            $str_cash_receipt_date = " STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        if(( $keyword == 5 or $keyword == 4 )){
            $str_cash_receipt_date = "";
            $str_cash_receipt_date = " STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND ";
            $str_cash_receipt_date .= " STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";

        }

        $sql_cash_receipt = " SELECT
        journal_cash_receipt_code as journal_code, 
        journal_cash_receipt_date as journal_date,
        journal_cash_receipt_name  as journal_name,
        account_id,
        IFNULL(SUM(journal_cash_receipt_list_debit),0) as journal_debit,
        IFNULL(SUM(journal_cash_receipt_list_credit),0) as journal_credit
        FROM tb_journal_cash_receipt 
        LEFT JOIN tb_journal_cash_receipt_list ON tb_journal_cash_receipt_list.journal_cash_receipt_id = tb_journal_cash_receipt.journal_cash_receipt_id  
        WHERE 
        $str_cash_receipt_date 
        GROUP BY tb_journal_cash_receipt_list.journal_cash_receipt_list_id 
        ORDER BY STR_TO_DATE(journal_cash_receipt_date,'%d-%m-%Y %H:%i:%s'), journal_cash_receipt_code DESC 
        "; 
        //------------------------- End Cash Receipt Journal -------------------------------------------------------------


        $sql =" SELECT account_code , account_name_th ,  MAX(IFNULL(account_debit_begin,0)) ,SUM(IFNULL(tb_journal.journal_debit,0)) As journal_debit, MAX(IFNULL(account_credit_begin,0)) ,SUM(IFNULL(tb_journal.journal_credit,0)) As journal_credit, ( MAX(IFNULL(account_debit_begin,0)) + SUM(IFNULL(tb_journal.journal_debit,0)) ) - ( MAX(IFNULL(account_credit_begin,0)) + SUM(IFNULL(tb_journal.journal_credit,0)) ) as account_value , account_level,account_group
                FROM tb_account 
                LEFT JOIN  (($sql_general)  
                UNION   ALL  ($sql_purchase) 
                UNION   ALL  ($sql_sale) 
                UNION   ALL  ($sql_sale_return) 
                UNION   ALL  ($sql_cash_payment) 
                UNION   ALL  ($sql_cash_receipt)) as tb_journal  
                ON tb_account.account_id = tb_journal.account_id  
                WHERE   $account_group
                GROUP BY account_code 
                HAVING  round(account_value,2)  != 0 OR   account_level != '1'
                ORDER BY account_code ASC
        ";    

       //echo '<pre>'.$sql.'</pre>';
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