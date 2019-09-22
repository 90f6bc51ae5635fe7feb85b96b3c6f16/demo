<?php

require_once("BaseModel.php");
class FinanceDebitModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getFinanceDebitBy($date_start = "",$date_end = "",$customer_id = "",$keyword = "",$user_id = "", $lock_1 = "0", $lock_2 = "0" ){

        $str_customer = "";
        $str_date = "";
        $str_user = "";
        $str_lock = "";

        if($lock_1 == "1" && $lock_2 == "1"){
            $str_lock = "AND (paper_lock_1 = '0' OR paper_lock_2 = '0')";
        }else if ($lock_1 == "1") {
            $str_lock = "AND paper_lock_1 = '0' ";
        }else if($lock_2 == "1"){
            $str_lock = "AND paper_lock_2 = '0' ";
        }


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
        finance_debit_code, 
        finance_debit_date, 
        finance_debit_date_pay, 
        finance_debit_total,
        finance_debit_pay, 
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name,  
        IFNULL( tb2.customer_name_en ,'-') as customer_name  
        FROM tb_finance_debit as tb
        LEFT JOIN tb_user as tb1 ON tb.employee_id = tb1.user_id 
        LEFT JOIN tb_customer as tb2 ON tb.customer_id = tb2.customer_id 
        LEFT JOIN tb_journal_cash_receipt ON tb_journal_cash_receipt.finance_debit_id = tb.finance_debit_id 
        LEFT JOIN tb_paper_lock ON SUBSTRING(tb.finance_debit_date_pay,3,9)=SUBSTRING(tb_paper_lock.paper_lock_date,3,9) 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%')  
            OR  finance_debit_code LIKE ('%$keyword%') 
        ) 
        $str_lock 
        $str_customer 
        $str_date 
        $str_user  
        GROUP BY tb.finance_debit_id
        ORDER BY finance_debit_code ASC 
         ";

         //echo $sql;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getFinanceDebitByID($id){
        $sql = " SELECT * 
        FROM tb_finance_debit 
        LEFT JOIN tb_customer ON tb_finance_debit.customer_id = tb_customer.customer_id 
        LEFT JOIN tb_user ON tb_finance_debit.employee_id = tb_user.user_id 
        WHERE finance_debit_id = '$id' 
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


    function getFinanceDebitByCode($code){
        $sql = " SELECT * 
        FROM tb_finance_debit 
        LEFT JOIN tb_customer ON tb_finance_debit.customer_id = tb_customer.customer_id 
        LEFT JOIN tb_user ON tb_finance_debit.employee_id = tb_user.user_id 
        WHERE finance_debit_code = '$code' 
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

    function getFinanceDebitViewByID($id){
        $sql = " SELECT *   
        FROM tb_finance_debit 
        LEFT JOIN tb_user ON tb_finance_debit.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user.user_position_id 
        LEFT JOIN tb_customer ON tb_finance_debit.customer_id = tb_customer.customer_id 
        WHERE finance_debit_id = '$id' 
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

    function getFinanceDebitLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(finance_debit_code,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  finance_debit_lastcode 
        FROM tb_finance_debit
        WHERE finance_debit_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['finance_debit_lastcode'];
        }

    }

    function getCustomerOrder(){

        $sql = "SELECT tb_customer.customer_id, customer_name_en , customer_name_th 
                FROM tb_customer 
                WHERE customer_id IN ( 
                    SELECT customer_id FROM (
                        (
                            SELECT DISTINCT customer_id 
                            FROM tb_invoice_customer 
                            LEFT JOIN tb_finance_debit_list ON tb_invoice_customer.invoice_customer_id = tb_finance_debit_list.invoice_customer_id 
                            GROUP BY tb_invoice_customer.invoice_customer_id 
                            HAVING MAX(IFNULL(tb_invoice_customer.invoice_customer_net_price,0)) > SUM(IFNULL(finance_debit_list_balance,0))

                        ) UNION ALL (
                            SELECT DISTINCT customer_id 
                            FROM tb_credit_note 
                            LEFT JOIN tb_finance_debit_list ON tb_credit_note.credit_note_id = tb_finance_debit_list.credit_note_id 
                            GROUP BY tb_credit_note.credit_note_id 
                            HAVING MAX(IFNULL(tb_credit_note.credit_note_net_price,0)) > SUM(IFNULL(finance_debit_list_balance,0))

                        ) UNION ALL (
                            SELECT DISTINCT customer_id 
                            FROM tb_debit_note 
                            LEFT JOIN tb_finance_debit_list ON tb_debit_note.debit_note_id = tb_finance_debit_list.debit_note_id 
                            GROUP BY tb_debit_note.debit_note_id 
                            HAVING MAX(IFNULL(tb_debit_note.debit_note_net_price,0)) > SUM(IFNULL(finance_debit_list_balance,0))

                        )
                    ) AS tb
                    
                ) 
                ORDER BY customer_name_en
        "; 

        //echo $sql;
        $data = [];
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }

            $result->close();
            
        }
        return $data;
    }


    function generateFinanceDebitListByCustomerId($customer_id, $data_invoice = [], $data_credit = [], $data_debit = [], $search=""){

        $str_invoice ='0';
        $str_credit ='0';
        $str_debit ='0';

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

        $sql_customer = "SELECT * 
        FROM 
        (
            (
                SELECT 
                tb_1.invoice_customer_id,
                '0' as credit_note_id,
                '0' as debit_note_id,
                IFNULL( 
                    (
                        SELECT billing_note_code
                        FROM tb_billing_note_list 
                        LEFT JOIN tb_billing_note ON tb_billing_note_list.billing_note_id = tb_billing_note.billing_note_id 
                        WHERE tb_billing_note_list.invoice_customer_id = tb_1.invoice_customer_id 
                        GROUP BY tb_billing_note_list.billing_note_id 
                        LIMIT 0 ,1 
                    )
                 ,'-') as billing_note_code,  
                invoice_customer_code as finance_debit_list_code,  
                '' as finance_debit_list_billing, 
                '' as finance_debit_list_receipt, 
                MAX(IFNULL(tb_1.invoice_customer_net_price,0)) as finance_debit_list_amount, 
                SUM(IFNULL(finance_debit_list_balance,0)) as finance_debit_list_paid, 
                invoice_customer_date as finance_debit_list_date, 
                invoice_customer_due as finance_debit_list_due 
                FROM tb_invoice_customer as tb_1
                LEFT JOIN tb_finance_debit_list ON tb_1.invoice_customer_id = tb_finance_debit_list.invoice_customer_id 
                WHERE tb_1.invoice_customer_id NOT IN ($str_invoice)  
                AND tb_1.invoice_customer_close = '0' 
                AND tb_1.customer_id = '$customer_id' 
                AND (
                    invoice_customer_date LIKE ('%$search%') OR
                    invoice_customer_due LIKE ('%$search%') OR 
                    invoice_customer_code LIKE ('%$search%') 
                ) 
                GROUP BY tb_1.invoice_customer_id 
                HAVING MAX(IFNULL(tb_1.invoice_customer_net_price,0)) > SUM(IFNULL(finance_debit_list_balance,0))
            ) UNION ALL (
                SELECT 
                '0' as invoice_customer_id,
                tb_1.credit_note_id,
                '0' as debit_note_id,
                IFNULL(
                    (
                        SELECT billing_note_code
                        FROM tb_billing_note_list 
                        LEFT JOIN tb_billing_note ON tb_billing_note_list.billing_note_id = tb_billing_note.billing_note_id 
                        WHERE tb_billing_note_list.credit_note_id = tb_1.credit_note_id 
                        GROUP BY tb_billing_note_list.billing_note_id 
                        LIMIT 0 ,1 
                    )
                 ,'-') as billing_note_code, 
                credit_note_code as finance_debit_list_code,  
                '' as finance_debit_list_billing, 
                '' as finance_debit_list_receipt, 
                (- MAX(IFNULL(tb_1.credit_note_net_price,0))) as finance_debit_list_amount, 
                SUM(IFNULL(finance_debit_list_balance,0)) as finance_debit_list_paid, 
                credit_note_date as finance_debit_list_date, 
                credit_note_due as finance_debit_list_due 
                FROM tb_credit_note as tb_1
                LEFT JOIN tb_finance_debit_list ON tb_1.credit_note_id = tb_finance_debit_list.credit_note_id 
                WHERE tb_1.credit_note_id NOT IN ($str_credit)  
                AND tb_1.credit_note_close = '0' 
                AND tb_1.customer_id = '$customer_id' 
                AND (
                    credit_note_date LIKE ('%$search%') OR
                    credit_note_due LIKE ('%$search%') OR 
                    credit_note_code LIKE ('%$search%') 
                ) 
                GROUP BY tb_1.credit_note_id 
                HAVING (MAX(IFNULL(tb_1.credit_note_net_price,0))) > -SUM(IFNULL(finance_debit_list_balance,0))

            ) UNION ALL (
                SELECT 
                '0' as invoice_customer_id,
                '0' as credit_note_id,
                tb_1.debit_note_id,
                IFNULL(
                    (
                        SELECT billing_note_code
                        FROM tb_billing_note_list 
                        LEFT JOIN tb_billing_note ON tb_billing_note_list.billing_note_id = tb_billing_note.billing_note_id 
                        WHERE tb_billing_note_list.debit_note_id = tb_1.debit_note_id 
                        GROUP BY tb_billing_note_list.billing_note_id 
                        LIMIT 0 ,1 
                    )
                 ,'-') as billing_note_code,  
                debit_note_code as finance_debit_list_code,  
                '' as finance_debit_list_billing, 
                '' as finance_debit_list_receipt, 
                MAX(IFNULL(tb_1.debit_note_net_price,0)) as finance_debit_list_amount, 
                SUM(IFNULL(finance_debit_list_balance,0)) as finance_debit_list_paid, 
                debit_note_date as finance_debit_list_date, 
                debit_note_due as finance_debit_list_due 
                FROM tb_debit_note as tb_1 
                LEFT JOIN tb_finance_debit_list ON tb_1.debit_note_id = tb_finance_debit_list.debit_note_id 
                WHERE tb_1.debit_note_id NOT IN ($str_debit)  
                AND tb_1.debit_note_close = '0' 
                AND tb_1.customer_id = '$customer_id' 
                AND (
                    debit_note_date LIKE ('%$search%') OR
                    debit_note_due LIKE ('%$search%') OR 
                    debit_note_code LIKE ('%$search%') 
                ) 
                GROUP BY tb_1.debit_note_id 
                HAVING MAX(IFNULL(tb_1.debit_note_net_price,0)) > SUM(IFNULL(finance_debit_list_balance,0))
            )
            
        ) as tb 
        ORDER BY finance_debit_list_code 
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

   
    function updateFinanceDebitByID($id,$data = []){
        $sql = " UPDATE tb_finance_debit SET 
        customer_id = '".$data['customer_id']."', 
        employee_id = '".$data['employee_id']."', 
        finance_debit_code = '".$data['finance_debit_code']."',
        finance_debit_date = '".$data['finance_debit_date']."', 
        finance_debit_date_pay = '".$data['finance_debit_date_pay']."', 
        finance_debit_name = '".$data['finance_debit_name']."', 
        finance_debit_address = '".$data['finance_debit_address']."', 
        finance_debit_tax = '".$data['finance_debit_tax']."', 
        finance_debit_branch = '".$data['finance_debit_branch']."', 
        finance_debit_remark = '".$data['finance_debit_remark']."', 
        finance_debit_sent_name = '".$data['finance_debit_sent_name']."', 
        finance_debit_recieve_name = '".$data['finance_debit_recieve_name']."', 
        finance_debit_total = '".$data['finance_debit_total']."', 
        finance_debit_interest = '".$data['finance_debit_interest']."', 
        finance_debit_cash = '".$data['finance_debit_cash']."', 
        finance_debit_other_pay = '".$data['finance_debit_other_pay']."', 
        finance_debit_tax_pay = '".$data['finance_debit_tax_pay']."', 
        finance_debit_discount_cash = '".$data['finance_debit_discount_cash']."', 
        finance_debit_pay = '".$data['finance_debit_pay']."', 
        finance_debit_total_text = '".$data['finance_debit_total_text']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE finance_debit_id = $id 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }


    function insertFinanceDebit($data = []){
        $sql = " INSERT INTO tb_finance_debit (
            customer_id,
            employee_id,
            finance_debit_code,
            finance_debit_date,
            finance_debit_date_pay,
            finance_debit_name,
            finance_debit_address,
            finance_debit_tax,
            finance_debit_branch,
            finance_debit_remark,
            finance_debit_sent_name,
            finance_debit_recieve_name,
            finance_debit_total,
            finance_debit_interest,
            finance_debit_cash,
            finance_debit_other_pay,
            finance_debit_tax_pay,
            finance_debit_discount_cash,
            finance_debit_pay,
            finance_debit_total_text,
            addby,
            adddate,
            updateby,
            lastupdate) 
        VALUES ('".
        $data['customer_id']."','".
        $data['employee_id']."','".
        $data['finance_debit_code']."','".
        $data['finance_debit_date']."','".
        $data['finance_debit_date_pay']."','".
        $data['finance_debit_name']."','".
        $data['finance_debit_address']."','".
        $data['finance_debit_tax']."','".
        $data['finance_debit_branch']."','".
        $data['finance_debit_remark']."','".
        $data['finance_debit_sent_name']."','".
        $data['finance_debit_recieve_name']."','".
        $data['finance_debit_total']."','".
        $data['finance_debit_interest']."','".
        $data['finance_debit_cash']."','".
        $data['finance_debit_other_pay']."','".
        $data['finance_debit_tax_pay']."','".
        $data['finance_debit_discount_cash']."','".
        $data['finance_debit_pay']."','".
        $data['finance_debit_total_text']."','".
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


    function deleteFinanceDebitByID($id){

        $sql = " DELETE FROM tb_finance_debit WHERE finance_debit_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_finance_debit_list WHERE finance_debit_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_finance_debit_pay WHERE finance_debit_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }


}
?>