<?php

require_once("BaseModel.php");
class FinanceDebitListModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getFinanceDebitListBy($finance_debit_id){
        $sql = " SELECT finance_debit_id,
        finance_debit_list_id,   
        tb_1.invoice_customer_id,
        tb_1.credit_note_id,
        tb_1.debit_note_id,
        IFNULL(invoice_customer_code,IFNULL(credit_note_code,debit_note_code)) as finance_debit_list_code, 
        finance_debit_list_billing, 
        finance_debit_list_receipt, 
        '0' as finance_debit_list_paid, 
        IFNULL(invoice_customer_net_price,IFNULL(-credit_note_net_price,debit_note_net_price)) as finance_debit_list_amount, 
        IFNULL(invoice_customer_date,IFNULL(credit_note_date,debit_note_date))  as finance_debit_list_date, 
        IFNULL(invoice_customer_due,IFNULL(credit_note_due,debit_note_due))  as finance_debit_list_due,  
        finance_debit_list_amount,
        finance_debit_list_paid,
        finance_debit_list_balance,
        IF( 
            tb_1.invoice_customer_id != 0 ,
            (
                SELECT billing_note_code
                FROM tb_billing_note_list 
                LEFT JOIN tb_billing_note ON tb_billing_note_list.billing_note_id = tb_billing_note.billing_note_id 
                WHERE tb_billing_note_list.invoice_customer_id = tb_1.invoice_customer_id 
                GROUP BY tb_billing_note_list.billing_note_id 
                LIMIT 0 ,1 
            ),
            IF(
                tb_1.credit_note_id != 0,
                (
                    SELECT billing_note_code
                    FROM tb_billing_note_list 
                    LEFT JOIN tb_billing_note ON tb_billing_note_list.billing_note_id = tb_billing_note.billing_note_id 
                    WHERE tb_billing_note_list.credit_note_id = tb_1.credit_note_id 
                    GROUP BY tb_billing_note_list.billing_note_id 
                    LIMIT 0 ,1 
                ) 
                ,
                (
                    SELECT billing_note_code
                    FROM tb_billing_note_list 
                    LEFT JOIN tb_billing_note ON tb_billing_note_list.billing_note_id = tb_billing_note.billing_note_id 
                    WHERE tb_billing_note_list.credit_note_id = tb_1.credit_note_id 
                    GROUP BY tb_billing_note_list.billing_note_id 
                    LIMIT 0 ,1 
                ) 
            )
        ) as billing_note_code,
        finance_debit_list_remark 
        FROM tb_finance_debit_list as tb_1
        LEFT JOIN tb_invoice_customer ON tb_1.invoice_customer_id = tb_invoice_customer.invoice_customer_id 
        LEFT JOIN tb_credit_note ON tb_1.credit_note_id = tb_credit_note.credit_note_id 
        LEFT JOIN tb_debit_note ON tb_1.debit_note_id = tb_debit_note.debit_note_id 
        WHERE finance_debit_id = '$finance_debit_id' 
        ORDER BY finance_debit_list_code 
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


    function insertFinanceDebitList($data = []){
        $sql = " INSERT INTO tb_finance_debit_list (
            finance_debit_id,
            invoice_customer_id,
            credit_note_id,
            debit_note_id,
            finance_debit_list_billing,
            finance_debit_list_receipt,
            finance_debit_list_amount,
            finance_debit_list_paid,
            finance_debit_list_balance,
            finance_debit_list_remark,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['finance_debit_id']."', 
            '".$data['invoice_customer_id']."', 
            '".$data['credit_note_id']."', 
            '".$data['debit_note_id']."', 
            '".$data['finance_debit_list_billing']."',
            '".$data['finance_debit_list_receipt']."',
            '".$data['finance_debit_list_amount']."',
            '".$data['finance_debit_list_paid']."',
            '".$data['finance_debit_list_balance']."',
            '".$data['finance_debit_list_remark']."', 
            '".$data['addby']."', 
            NOW(), 
            '".$data['updateby']."', 
            NOW() 
        ); 
        ";

        //echo $sql . "<br><br>";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $id = mysqli_insert_id(static::$db);
            return $id; 
        }else {
            return 0;
        }

    }

    

    function updateFinanceDebitListById($data,$id){

        $sql = " UPDATE tb_finance_debit_list 
            SET invoice_customer_id = '".$data['invoice_customer_id']."', 
            credit_note_id = '".$data['credit_note_id']."', 
            debit_note_id = '".$data['debit_note_id']."', 
            finance_debit_list_billing = '".$data['finance_debit_list_billing']."', 
            finance_debit_list_receipt = '".$data['finance_debit_list_receipt']."', 
            finance_debit_list_amount = '".$data['finance_debit_list_amount']."', 
            finance_debit_list_paid = '".$data['finance_debit_list_paid']."',
            finance_debit_list_balance = '".$data['finance_debit_list_balance']."',  
            finance_debit_list_remark = '".$data['finance_debit_list_remark']."' 
            WHERE finance_debit_list_id = '$id'
        ";
        //echo $sql . "<br><br>";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }




    function deleteFinanceDebitListByID($id){
        $sql = "DELETE FROM tb_finance_debit_list WHERE finance_debit_list_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteFinanceDebitListByFinanceDebitID($id){

        $sql = "DELETE FROM tb_finance_debit_list WHERE finance_debit_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteFinanceDebitListByFinanceDebitIDNotIN($id,$data){
        $str ='';
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


        $sql = "DELETE FROM tb_finance_debit_list WHERE finance_debit_id = '$id' AND finance_debit_list_id NOT IN ($str) ";
     
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        

    }
}
?>