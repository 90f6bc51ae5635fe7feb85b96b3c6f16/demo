<?php

require_once("BaseModel.php");
class MaintenanceFinanceModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db,"utf8");
    }

    function runMaintenance(){

        //ดึงข้อมูลเช็ค
        $sql = "    SELECT * 
                    FROM tb_check 
                    LEFT JOIN tb_bank_account ON tb_check_pay.bank_deposit_id = tb_bank_account.bank_account_id 
                    ORDER BY STR_TO_DATE(check_date_pass,'%d-%m-%Y %H:%i:%s') 
        ";
        $cheque = [];
        
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            while( $row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $cheque[] = $row;
            }
            $result->close(); 

            for($i = 0 ; $i < count($cheque); $i++){
                $this->unpassChequeReceipt($cheque[$i]['check_code']); 
                if($cheque[$i]['check_status'] == '1'){
                    $this->passChequeReceipt($cheque[$i]);
                } 
            }
        }

        //ดึงข้อมูลเช็ค
        $sql = "    SELECT * 
                    FROM tb_check_pay 
                    LEFT JOIN tb_bank_account ON tb_check_pay.bank_account_id = tb_bank_account.bank_account_id 
                    ORDER BY STR_TO_DATE(check_pay_date_pass,'%d-%m-%Y %H:%i:%s') 
        ";
        $cheque = [];
        
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            while( $row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $cheque[] = $row;
            }
            $result->close(); 

            for($i = 0 ; $i < count($cheque); $i++){
                $this->unpassChequePayment($cheque[$i]['check_pay_code']);
                if($cheque[$i]['check_pay_status'] == '1'){
                    $this->passChequePayment($cheque[$i]);
                } 
            }
        }

    } 


    function passChequeReceipt($data){ 

        $journal_name = "ผ่านเช็ครับ ".$data['check_remark'];

        $sql = " INSERT INTO tb_journal_cash_receipt (
            finance_debit_id,
            journal_cash_receipt_code, 
            journal_cash_receipt_date,
            journal_cash_receipt_name,
            addby,
            adddate,
            updateby, 
            lastupdate) 
        VALUES ('".
        "0"."','".
        $data['check_code']."','".
        $data['check_date_pass']."','".
        static::$db->real_escape_string($journal_name)."','".
        $data['addby']."',".
        "NOW(),'".
        $data['addby'].
        "',NOW()); 
        ";

        //echo $sql."<br><br>";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $journal_id = mysqli_insert_id(static::$db);

            $sql = " INSERT INTO tb_journal_cash_receipt_list (
                journal_cash_receipt_id,
                journal_cheque_id,
                journal_cheque_pay_id,
                journal_invoice_customer_id,
                journal_invoice_supplier_id,
                account_id,
                journal_cash_receipt_list_name,
                journal_cash_receipt_list_debit,
                journal_cash_receipt_list_credit,
                addby,
                adddate,
                updateby,
                lastupdate
            ) VALUES (
                '".$journal_id."',  
                '".$data['check_id']."', 
                '0', 
                '0', 
                '0', 
                (SELECT account_id FROM tb_account_setting WHERE tb_account_setting.account_setting_id = '6' LIMIT 0 , 1), 
                '".static::$db->real_escape_string($journal_name)."', 
                '0',
                '".($data['check_total'])."',
                '".$data['addby']."', 
                NOW(), 
                '".$data['updateby']."', 
                NOW() 
            ); 
            ";

            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

            if($data['check_fee'] != 0){
                $sql = " INSERT INTO tb_journal_cash_receipt_list (
                    journal_cash_receipt_id,
                    journal_cheque_id,
                    journal_cheque_pay_id,
                    journal_invoice_customer_id,
                    journal_invoice_supplier_id,
                    account_id,
                    journal_cash_receipt_list_name,
                    journal_cash_receipt_list_debit,
                    journal_cash_receipt_list_credit,
                    addby,
                    adddate,
                    updateby,
                    lastupdate
                ) VALUES (
                    '".$journal_id."',  
                    '".$data['check_id']."', 
                    '0', 
                    '0', 
                    '0', 
                    (SELECT account_id FROM tb_account_setting WHERE tb_account_setting.account_setting_id = '30' LIMIT 0 , 1), 
                    '".static::$db->real_escape_string($journal_name)."', 
                    '".($data['check_fee'])."',
                    '0',
                    '".$data['addby']."', 
                    NOW(), 
                    '".$data['updateby']."', 
                    NOW() 
                ); 
                ";
    
                mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
            }
            //echo $sql."<br><br>";

            $sql = " INSERT INTO tb_journal_cash_receipt_list (
                journal_cash_receipt_id,
                journal_cheque_id,
                journal_cheque_pay_id,
                journal_invoice_customer_id,
                journal_invoice_supplier_id,
                account_id,
                journal_cash_receipt_list_name,
                journal_cash_receipt_list_debit,
                journal_cash_receipt_list_credit,
                addby,
                adddate,
                updateby,
                lastupdate
            ) VALUES (
                '".$journal_id."',  
                '".$data['check_id']."', 
                '0', 
                '0', 
                '0', 
                '".$data['account_id']."', 
                '".static::$db->real_escape_string($journal_name)."', 
                '".($data['check_total']-$data['check_fee'])."',
                '0',
                '".$data['addby']."', 
                NOW(), 
                '".$data['updateby']."', 
                NOW() 
            ); 
            ";

            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 

            //echo $sql."<br><br><br><br>";
        }
    }


    function unpassChequeReceipt($check_code){  

        $sql = " DELETE FROM tb_journal_cash_receipt_list WHERE journal_cash_receipt_id IN ( SELECT DISTINCT journal_cash_receipt_id FROM tb_journal_cash_receipt WHERE journal_cash_receipt_code = '".$check_code."' ) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        //echo $sql."<br><br>";
        $sql = " DELETE FROM tb_journal_cash_receipt WHERE journal_cash_receipt_code = '".$check_code."' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        //echo $sql."<br><br>";

       
 
    }


    function passChequePayment($data){ 

        $journal_name = "ผานเช็คจ่าย ".$data['check_pay_remark'];

        $sql = " INSERT INTO tb_journal_cash_payment (
            finance_credit_id,
            journal_cash_payment_code, 
            journal_cash_payment_date,
            journal_cash_payment_name,
            addby,
            adddate,
            updateby, 
            lastupdate) 
        VALUES ('".
        "0"."','".
        $data['check_pay_code']."','".
        $data['check_pay_date_pass']."','".
        static::$db->real_escape_string($journal_name)."','".
        $data['addby']."',".
        "NOW(),'".
        $data['addby'].
        "',NOW()); 
        "; 

        //echo $sql."<br><br>";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $journal_id = mysqli_insert_id(static::$db);

            $sql = " INSERT INTO tb_journal_cash_payment_list (
                journal_cash_payment_id,
                journal_cheque_id,
                journal_cheque_pay_id,
                journal_invoice_customer_id,
                journal_invoice_supplier_id,
                account_id,
                journal_cash_payment_list_name,
                journal_cash_payment_list_debit,
                journal_cash_payment_list_credit,
                addby,
                adddate,
                updateby,
                lastupdate
            ) VALUES (
                '".$journal_id."',   
                '0', 
                '".$data['check_pay_id']."',
                '0', 
                '0', 
                (SELECT account_id FROM tb_account_setting WHERE tb_account_setting.account_setting_id = '13' LIMIT 0 , 1), 
                '".static::$db->real_escape_string($journal_name)."', 
                '".$data['check_pay_total']."',
                '0',
                '".$data['addby']."', 
                NOW(), 
                '".$data['updateby']."', 
                NOW() 
            ); 
            "; 
            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
            //echo $sql."<br><br>";

            $sql = " INSERT INTO tb_journal_cash_payment_list (
                journal_cash_payment_id,
                journal_cheque_id,
                journal_cheque_pay_id,
                journal_invoice_customer_id,
                journal_invoice_supplier_id,
                account_id,
                journal_cash_payment_list_name,
                journal_cash_payment_list_debit,
                journal_cash_payment_list_credit,
                addby,
                adddate,
                updateby,
                lastupdate
            ) VALUES (
                '".$journal_id."',  
                '0', 
                '".$data['check_pay_id']."', 
                '0', 
                '0', 
                '".$data['account_id']."', 
                '".static::$db->real_escape_string($journal_name)."', 
                '0',
                '".$data['check_pay_total']."',
                '".$data['addby']."', 
                NOW(), 
                '".$data['updateby']."', 
                NOW() 
            ); 
            "; 
            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 

            //echo $sql."<br><br><br><br>";
        }
    }


    function unpassChequePayment($check_pay_code){  

        $sql = " DELETE FROM tb_journal_cash_payment_list WHERE journal_cash_payment_id IN ( SELECT DISTINCT journal_cash_payment_id FROM tb_journal_cash_payment WHERE journal_cash_payment_code = '".$check_pay_codes."' ) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        //echo $sql."<br><br>";

        $sql = " DELETE FROM tb_journal_cash_payment WHERE journal_cash_payment_code = '".$check_pay_code."' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        //echo $sql."<br><br>";
       
 
    }
}
?>