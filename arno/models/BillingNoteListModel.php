<?php

require_once("BaseModel.php");
class BillingNoteListModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getBillingNoteListBy($billing_note_id){
        $sql = " SELECT billing_note_id,
        billing_note_list_id,  
        tb_billing_note_list.invoice_customer_id,
        tb_billing_note_list.credit_note_id,
        tb_billing_note_list.debit_note_id,
        IFNULL(invoice_customer_code,IFNULL(credit_note_code,debit_note_code)) as billing_note_list_code, 
        '0' as billing_note_list_paid, 
        IFNULL(invoice_customer_net_price,IFNULL(-credit_note_net_price,debit_note_net_price)) as billing_note_list_amount, 
        IFNULL(invoice_customer_date,IFNULL(credit_note_date,debit_note_date)) as billing_note_list_date, 
        IFNULL(invoice_customer_due,IFNULL(credit_note_due,debit_note_due)) as billing_note_list_due, 
        billing_note_list_remark 
        FROM tb_billing_note_list 
        LEFT JOIN tb_invoice_customer ON tb_billing_note_list.invoice_customer_id = tb_invoice_customer.invoice_customer_id 
        LEFT JOIN tb_credit_note ON tb_billing_note_list.credit_note_id = tb_credit_note.credit_note_id 
        LEFT JOIN tb_debit_note ON tb_billing_note_list.debit_note_id = tb_debit_note.debit_note_id 
        WHERE billing_note_id = '$billing_note_id' 
        ORDER BY billing_note_list_id 
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


    function insertBillingNoteList($data = []){
        $sql = " INSERT INTO tb_billing_note_list (
            billing_note_id,
            invoice_customer_id,
            credit_note_id,
            debit_note_id,
            billing_note_list_amount,
            billing_note_list_paid,
            billing_note_list_balance,
            billing_note_list_remark,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['billing_note_id']."', 
            '".$data['invoice_customer_id']."', 
            '".$data['credit_note_id']."', 
            '".$data['debit_note_id']."', 
            '".$data['billing_note_list_amount']."',
            '".$data['billing_note_list_paid']."',
            '".$data['billing_note_list_balance']."',
            '".$data['billing_note_list_remark']."', 
            '".$data['addby']."', 
            NOW(), 
            '".$data['updateby']."', 
            NOW() 
        ); 
        ";

       // echo $sql . "<br><br>";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $id = mysqli_insert_id(static::$db);
            return $id; 
        }else {
            return 0;
        }

    }

    

    function updateBillingNoteListById($data,$id){

        $sql = " UPDATE tb_billing_note_list 
            SET invoice_customer_id = '".$data['invoice_customer_id']."', 
            credit_note_id = '".$data['credit_note_id']."', 
            debit_note_id = '".$data['debit_note_id']."', 
            billing_note_list_amount = '".$data['billing_note_list_amount']."', 
            billing_note_list_paid = '".$data['billing_note_list_paid']."',
            billing_note_list_balance = '".$data['billing_note_list_balance']."',  
            billing_note_list_remark = '".$data['billing_note_list_remark']."' 
            WHERE billing_note_list_id = '$id'
        ";
      // echo $sql . "<br><br>";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }




    function deleteBillingNoteListByID($id){
        $sql = "DELETE FROM tb_billing_note_list WHERE billing_note_list_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteBillingNoteListByBillingNoteID($id){

        $sql = "DELETE FROM tb_billing_note_list WHERE billing_note_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteBillingNoteListByBillingNoteIDNotIN($id,$data){
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


        $sql = "DELETE FROM tb_billing_note_list WHERE billing_note_id = '$id' AND billing_note_list_id NOT IN ($str) ";
     
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        

    }
}
?>