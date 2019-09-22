<?php

require_once("BaseModel.php");
class OfficialReceiptListModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getOfficialReceiptListBy($official_receipt_id){
        $sql = " SELECT official_receipt_list_id, 
        tb_official_receipt_list.billing_note_list_id,
        invoice_customer_code,
        invoice_customer_date as official_receipt_list_date, 
        invoice_customer_due as official_receipt_list_due,
        billing_note_code,
        official_receipt_inv_amount,
        official_receipt_bal_amount 
        FROM tb_official_receipt_list
        LEFT JOIN tb_billing_note_list ON tb_official_receipt_list.billing_note_list_id = tb_billing_note_list.billing_note_list_id 
        LEFT JOIN tb_billing_note ON tb_billing_note_list.billing_note_id = tb_billing_note.billing_note_id 
        LEFT JOIN tb_invoice_customer ON tb_billing_note_list.invoice_customer_id = tb_invoice_customer.invoice_customer_id 
        WHERE official_receipt_id = '$official_receipt_id' 
        ORDER BY official_receipt_list_id 
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


    function insertOfficialReceiptList($data = []){
        $sql = " INSERT INTO tb_official_receipt_list (
            official_receipt_id,
            official_receipt_inv_amount,
            official_receipt_bal_amount,
            official_receipt_list_remark,
            billing_note_list_id,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['official_receipt_id']."', 
            '".$data['official_receipt_inv_amount']."',
            '".$data['official_receipt_bal_amount']."',
            '".$data['official_receipt_list_remark']."', 
            '".$data['billing_note_list_id']."', 
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

    

    function updateOfficialReceiptListById($data,$id){

        $sql = " UPDATE tb_official_receipt_list 
            SET official_receipt_inv_amount = '".$data['official_receipt_inv_amount']."' 
            official_receipt_bal_amount = '".$data['official_receipt_bal_amount']."' 
            official_receipt_list_remark = '".$data['official_receipt_list_remark']."' 
            WHERE official_receipt_list_id = '$id'
        ";
      //echo $sql . "<br><br>";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }




    function deleteOfficialReceiptListByID($id){
        $sql = "DELETE FROM tb_official_receipt_list WHERE official_receipt_list_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteOfficialReceiptListByOfficialReceiptID($id){

        $sql = "DELETE FROM tb_official_receipt_list WHERE official_receipt_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteOfficialReceiptListByOfficialReceiptIDNotIN($id,$data){
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


        $sql = "DELETE FROM tb_official_receipt_list WHERE official_receipt_id = '$id' AND official_receipt_list_id NOT IN ($str) ";
     
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        

    }
}
?>