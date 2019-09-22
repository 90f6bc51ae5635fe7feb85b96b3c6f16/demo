<?php

require_once("BaseModel.php");
class TestCaseModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }



    function getInvoiceCustomerValueNotMatch(){
        $sql ="SELECT tb.* , 
        IFNULL((
            SELECT SUM(invoice_customer_list_price * invoice_customer_list_qty) 
            FROM tb_invoice_customer_list 
            WHERE invoice_customer_id = tb.invoice_customer_id
        ),0) as invoice_customer_list_total_sum 
        FROM tb_invoice_customer as tb
        WHERE invoice_customer_begin = 0 
        HAVING  ROUND(invoice_customer_total_price,2) != ROUND(invoice_customer_list_total_sum,2)
        ";

        //echo $sql;

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



    function getInvoiceSupplierTotalDutyFreigthNotMatch(){
        $sql ="SELECT *
        FROM tb_invoice_supplier
        LEFT JOIN tb_supplier ON tb_invoice_supplier.supplier_id = tb_supplier.supplier_id 
        WHERE supplier_domestic = 'ภายนอกประเทศ' 
        AND (ROUND(invoice_supplier_total_price,2) + ROUND(freight_in,2) + ROUND(import_duty,2)) != ROUND(invoice_supplier_cost_total ,2) 
        AND invoice_supplier_begin = 0 

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



    function getInvoiceSupplierShortNotMatch(){
        $sql ="SELECT * , 
        IFNULL( 
            (SELECT SUM(invoice_supplier_short_list_total_currency) FROM tb_invoice_supplier_short_list WHERE invoice_supplier_id = tb.invoice_supplier_id ) 
        , 0) as invoice_supplier_short_total_currency , 
        IFNULL( 
            (SELECT SUM(invoice_supplier_short_list_total) FROM tb_invoice_supplier_short_list WHERE invoice_supplier_id = tb.invoice_supplier_id ) 
        , 0) as invoice_supplier_short_total 
        FROM tb_invoice_supplier as tb
        LEFT JOIN tb_supplier ON tb.supplier_id = tb_supplier.supplier_id 
        WHERE supplier_domestic = 'ภายนอกประเทศ' 
        AND invoice_supplier_begin = 0 
        HAVING ROUND(invoice_supplier_currency_total,2) != ROUND(invoice_supplier_short_total_currency,2) 
        OR ROUND(invoice_supplier_total_price,2) != ROUND(invoice_supplier_short_total,2) 
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




    function getInvoiceSupplierExchangeRateNotMatch(){
        $sql =" SELECT * , 
                    IFNULL((
                        SELECT 	exchange_rate_baht_value 
                        FROM tb_exchange_rate_baht 
                        WHERE currency_id = tb1.currency_id
                        AND STR_TO_DATE(exchange_rate_baht_date,'%d-%m-%Y %H:%i:%s') = STR_TO_DATE(tb.invoice_supplier_date_recieve,'%d-%m-%Y %H:%i:%s')
                        LIMIT 0 , 1
                    ),0) as exchange_rate 
                FROM tb_invoice_supplier_short_list 
                LEFT JOIN tb_invoice_supplier as tb 
                ON tb_invoice_supplier_short_list.invoice_supplier_id = tb.invoice_supplier_id
                LEFT JOIN tb_supplier as tb1 
                ON tb.supplier_id = tb1.supplier_id
                WHERE supplier_domestic = 'ภายนอกประเทศ' 
                AND invoice_supplier_begin = 0 

                HAVING ROUND(exchange_rate,5) != ROUND(invoice_supplier_short_list_exchange_rate,5)
        ";


        //echo $sql;


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



    function getBillingNoteInvoiceCustomerNotMatch(){
        $sql =" SELECT * 
                FROM tb_billing_note_list 
                LEFT JOIN tb_billing_note ON tb_billing_note_list.billing_note_id = tb_billing_note.billing_note_id 
                LEFT JOIN tb_invoice_customer ON tb_billing_note_list.invoice_customer_id = tb_invoice_customer.invoice_customer_id 
                HAVING ROUND(billing_note_list_amount,2) != ROUND(invoice_customer_net_price,2)  
        ";


        //echo $sql;


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



    function getBillingNoteCreditNoteNotMatch(){
        $sql =" SELECT * 
                FROM tb_billing_note_list 
                LEFT JOIN tb_billing_note ON tb_billing_note_list.billing_note_id = tb_billing_note.billing_note_id 
                LEFT JOIN tb_credit_note ON tb_billing_note_list.credit_note_id = tb_credit_note.credit_note_id 
                HAVING ROUND(billing_note_list_amount,2) != ROUND(credit_note_net_price,2)  


        ";


        //echo $sql;


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



    function getBillingNoteDebitNoteNotMatch(){
        $sql =" SELECT * 
                FROM tb_billing_note_list 
                LEFT JOIN tb_billing_note ON tb_billing_note_list.billing_note_id = tb_billing_note.billing_note_id 
                LEFT JOIN tb_debit_note ON tb_billing_note_list.debit_note_id = tb_debit_note.debit_note_id 
                HAVING ROUND(billing_note_list_amount,2) != -ROUND(debit_note_net_price,2)  


        ";


        //echo $sql;


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

    
    function getBillingNoteCodeDouble(){
        $sql =" SELECT * 
                FROM tb_billing_note
                LEFT JOIN
                WHERE billing_note_code IN 
                (
                    SELECT DISTINCT billing_note_code 
                    FROM tb_billing_note 
                    GROUP BY billing_note_code
                    HAVING COUNT(billing_note_code) > 1 
                ) 
        ";


        //echo $sql;


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

    function getChequeCodeDouble(){
        $sql =" SELECT * 
                FROM tb_check
                LEFT JOIN
                WHERE check_code IN 
                (
                    SELECT DISTINCT check_code 
                    FROM tb_check 
                    GROUP BY check_code
                    HAVING COUNT(check_code) > 1 
                ) 
        ";


        //echo $sql;


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

    function getChequePayCodeDouble(){
        $sql =" SELECT * 
                FROM tb_check_pay
                LEFT JOIN
                WHERE check_pay_code IN 
                (
                    SELECT DISTINCT check_pay_code 
                    FROM tb_check_pay 
                    GROUP BY check_pay_code
                    HAVING COUNT(check_pay_code) > 1 
                ) 
        ";


        //echo $sql;


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




}
?>