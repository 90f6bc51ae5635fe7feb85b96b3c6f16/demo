<?php

require_once("BaseModel.php");
class CreditNoteListModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getCreditNoteListBy($credit_note_id){
        $sql = " SELECT tb_credit_note_list.product_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,   
        credit_note_list_id,  
        credit_note_list_no,  
        credit_note_list_product_name, 
        credit_note_list_product_detail, 
        credit_note_list_qty, 
        credit_note_list_price, 
        credit_note_list_total, 
        credit_note_list_remark,
        invoice_customer_list_id,
        stock_group_id
        FROM tb_credit_note_list LEFT JOIN tb_product ON tb_credit_note_list.product_id = tb_product.product_id 
        WHERE credit_note_id = '$credit_note_id' 
        ORDER BY credit_note_list_no 
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

    function generateCreditNoteListByInvoiceCustomerID($invoice_customer_id){
        $sql = " SELECT tb_invoice_customer_list.product_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,   
        '0' as credit_note_list_id,  
        product_name as credit_note_list_product_name, 
        '' as credit_note_list_product_detail, 
        invoice_customer_list_qty as credit_note_list_qty, 
        invoice_customer_list_price as credit_note_list_price, 
        invoice_customer_list_total as credit_note_list_total, 
        '' as credit_note_list_remark,
        invoice_customer_list_id,
        stock_group_id
        FROM tb_invoice_customer_list LEFT JOIN tb_product ON tb_invoice_customer_list.product_id = tb_product.product_id 
        WHERE invoice_customer_id = '$invoice_customer_id' 
        ORDER BY invoice_customer_list_id 
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


    function insertCreditNoteList($data = []){
        $sql = " INSERT INTO tb_credit_note_list (
            credit_note_id,
            credit_note_list_no,
            product_id,
            credit_note_list_product_name,
            credit_note_list_product_detail,
            credit_note_list_qty,
            credit_note_list_price, 
            credit_note_list_total,
            credit_note_list_remark,
            invoice_customer_list_id,
            stock_group_id,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['credit_note_id']."', 
            '".$data['credit_note_list_no']."', 
            '".$data['product_id']."', 
            '".$data['credit_note_list_product_name']."', 
            '".$data['credit_note_list_product_detail']."', 
            '".$data['credit_note_list_qty']."', 
            '".$data['credit_note_list_price']."', 
            '".$data['credit_note_list_total']."', 
            '".$data['credit_note_list_remark']."',
            '".$data['invoice_customer_list_id']."',
            '".$data['stock_group_id']."',
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

    

    function updateCreditNoteListById($data,$id){

        $sql = " UPDATE tb_credit_note_list 
            SET product_id = '".$data['product_id']."', 
            credit_note_list_no = '".$data['credit_note_list_no']."', 
            credit_note_list_product_name = '".$data['credit_note_list_product_name']."', 
            credit_note_list_product_detail = '".$data['credit_note_list_product_detail']."',
            credit_note_list_qty = '".$data['credit_note_list_qty']."',
            credit_note_list_price = '".$data['credit_note_list_price']."', 
            credit_note_list_total = '".$data['credit_note_list_total']."',
            credit_note_list_remark = '".$data['credit_note_list_remark']."', 
            invoice_customer_list_id = '".$data['invoice_customer_list_id']."', 
            stock_group_id = '".$data['stock_group_id']."'
            WHERE credit_note_list_id = '$id'
        ";
      // echo $sql . "<br><br>";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            
           return true;
        }else {
            return false;
        }
    }




    function deleteCreditNoteListByID($id){
        $sql = "DELETE FROM tb_credit_note_list WHERE credit_note_list_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteCreditNoteListByCreditNoteID($id){

        $sql = "DELETE FROM tb_credit_note_list WHERE credit_note_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteCreditNoteListByCreditNoteIDNotIN($id,$data){
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


        $sql = "DELETE FROM tb_credit_note_list WHERE credit_note_id = '$id' AND credit_note_list_id NOT IN ($str) ";
     
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        

    }
}
?>