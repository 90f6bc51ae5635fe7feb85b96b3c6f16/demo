<?php

require_once("BaseModel.php");
class FinanceCreditListModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getFinanceCreditListBy($finance_credit_id){
        $sql = " SELECT finance_credit_id,
        finance_credit_list_id,  
        tb_finance_credit_list.invoice_supplier_id,
        tb_finance_credit_list.credit_note_supplier_id,
        tb_finance_credit_list.debit_note_supplier_id,
        IFNULL(invoice_supplier_code,IFNULL(credit_note_supplier_code,debit_note_supplier_code)) as invoice_supplier_code, 
        finance_credit_list_recieve, 
        finance_credit_list_receipt, 
        '0' as finance_credit_list_paid, 
        finance_credit_list_amount, 
        finance_credit_list_paid,
        finance_credit_list_balance ,
        IFNULL(invoice_supplier_date,IFNULL(credit_note_supplier_date,debit_note_supplier_date)) as finance_credit_list_date, 
        IFNULL(invoice_supplier_due,IFNULL(credit_note_supplier_due,debit_note_supplier_due)) as finance_credit_list_due, 
        finance_credit_list_remark 
        FROM tb_finance_credit_list 
        LEFT JOIN tb_invoice_supplier ON tb_finance_credit_list.invoice_supplier_id = tb_invoice_supplier.invoice_supplier_id 
        LEFT JOIN tb_credit_note_supplier ON tb_finance_credit_list.credit_note_supplier_id = tb_credit_note_supplier.credit_note_supplier_id 
        LEFT JOIN tb_debit_note_supplier ON tb_finance_credit_list.debit_note_supplier_id = tb_debit_note_supplier.debit_note_supplier_id 
        WHERE finance_credit_id = '$finance_credit_id' 
        ORDER BY finance_credit_list_id 
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


    function insertFinanceCreditList($data = []){
        $sql = " INSERT INTO tb_finance_credit_list (
            finance_credit_id,
            invoice_supplier_id,
            credit_note_supplier_id,
            debit_note_supplier_id,
            finance_credit_list_recieve,
            finance_credit_list_receipt,
            finance_credit_list_amount,
            finance_credit_list_paid,
            finance_credit_list_balance,
            finance_credit_list_remark,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['finance_credit_id']."', 
            '".$data['invoice_supplier_id']."', 
            '".$data['credit_note_supplier_id']."', 
            '".$data['debit_note_supplier_id']."', 
            '".$data['finance_credit_list_recieve']."',
            '".$data['finance_credit_list_receipt']."',
            '".$data['finance_credit_list_amount']."',
            '".$data['finance_credit_list_paid']."',
            '".$data['finance_credit_list_balance']."',
            '".$data['finance_credit_list_remark']."', 
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

    

    function updateFinanceCreditListById($data,$id){

        $sql = " UPDATE tb_finance_credit_list 
            SET invoice_supplier_id = '".$data['invoice_supplier_id']."', 
            credit_note_supplier_id = '".$data['credit_note_supplier_id']."', 
            debit_note_supplier_id = '".$data['debit_note_supplier_id']."', 
            finance_credit_list_recieve = '".$data['finance_credit_list_recieve']."', 
            finance_credit_list_receipt = '".$data['finance_credit_list_receipt']."', 
            finance_credit_list_amount = '".$data['finance_credit_list_amount']."', 
            finance_credit_list_paid = '".$data['finance_credit_list_paid']."',
            finance_credit_list_balance = '".$data['finance_credit_list_balance']."',  
            finance_credit_list_remark = '".$data['finance_credit_list_remark']."' 
            WHERE finance_credit_list_id = '$id'
        ";
      // echo $sql . "<br><br>";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }




    function deleteFinanceCreditListByID($id){
        $sql = "DELETE FROM tb_finance_credit_list WHERE finance_credit_list_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteFinanceCreditListByFinanceCreditID($id){

        $sql = "DELETE FROM tb_finance_credit_list WHERE finance_credit_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteFinanceCreditListByFinanceCreditIDNotIN($id,$data){
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


        $sql = "DELETE FROM tb_finance_credit_list WHERE finance_credit_id = '$id' AND finance_credit_list_id NOT IN ($str) ";
     
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        

    }
}
?>