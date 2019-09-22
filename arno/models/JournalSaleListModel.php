<?php

require_once("BaseModel.php");
class JournalSaleListModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getJournalSaleListBy($journal_sale_id){
        $sql = " SELECT 
        IF(journal_sale_list_debit > 0,1,0) as sort_debit,
        account_code,
        journal_sale_list_id, 
        journal_sale_list_no, 
        journal_sale_list_name,
        journal_sale_list_debit,
        journal_sale_list_credit, 
        journal_cheque_id,
        journal_cheque_pay_id,
        journal_invoice_customer_id,
        journal_invoice_supplier_id,
        tb_journal_sale_list.account_id, 
        account_name_th,  
        account_name_en 
        FROM tb_journal_sale_list LEFT JOIN tb_account ON tb_journal_sale_list.account_id = tb_account.account_id 
        WHERE journal_sale_id = '$journal_sale_id' 
        ORDER BY sort_debit DESC , journal_sale_list_no ASC
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

    function getJournalSaleListByAccountId($journal_sale_id,$account_id){
        $sql = " SELECT *
        FROM tb_journal_sale_list  
        WHERE journal_sale_id = '$journal_sale_id' AND account_id = '$account_id' 
        ORDER BY journal_sale_list_no 
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


    function insertJournalSaleList($data = []){
        $sql = " INSERT INTO tb_journal_sale_list (
            journal_sale_id,
            journal_sale_list_no,
            journal_cheque_id,
            journal_cheque_pay_id,
            journal_invoice_customer_id,
            journal_invoice_supplier_id,
            account_id,
            journal_sale_list_name,
            journal_sale_list_debit,
            journal_sale_list_credit,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['journal_sale_id']."',  
            '".$data['journal_sale_list_no']."',  
            '".$data['journal_cheque_id']."', 
            '".$data['journal_cheque_pay_id']."', 
            '".$data['journal_invoice_customer_id']."', 
            '".$data['journal_invoice_supplier_id']."', 
            '".$data['account_id']."', 
            '".$data['journal_sale_list_name']."', 
            '".$data['journal_sale_list_debit']."',
            '".$data['journal_sale_list_credit']."',
            '".$data['addby']."', 
            NOW(), 
            '".$data['updateby']."', 
            NOW() 
        ); 
        ";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }

    function updateJournalSaleListById($data,$id){

        $sql = " UPDATE tb_journal_sale_list 
            SET account_id = '".$data['account_id']."',  
            journal_sale_list_no = '".$data['journal_sale_list_no']."',
            journal_cheque_id = '".$data['journal_cheque_id']."',
            journal_cheque_pay_id = '".$data['journal_cheque_pay_id']."',
            journal_invoice_customer_id = '".$data['journal_invoice_customer_id']."',
            journal_invoice_supplier_id = '".$data['journal_invoice_supplier_id']."',
            journal_sale_list_name = '".$data['journal_sale_list_name']."',
            journal_sale_list_debit = '".$data['journal_sale_list_debit']."',
            journal_sale_list_credit = '".$data['journal_sale_list_credit']."' 
            WHERE journal_sale_list_id = '$id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function deleteJournalSaleListByID($id){
        $sql = "DELETE FROM tb_journal_sale_list WHERE journal_sale_list_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteJournalSaleListByJournalSaleID($id){
        $sql = "DELETE FROM tb_journal_sale_list WHERE journal_sale_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteJournalSaleListByJournalSaleIDNotIN($id,$data){
        $str ='';
        if(is_array($data)){ 
            for($i=0; $i < count($data) ;$i++){
                if($data[$i] != ""){
                    $str .= $data[$i];
                    if($i + 1 < count($data)){
                        $str .= ',';
                    }
                }
            }
        }else if ($data != ''){
            $str = $data;
        }else{
            $str='0';
        }

        if( $str==''){
            $str='0';
        }

        $sql = "DELETE FROM tb_journal_sale_list WHERE journal_sale_id = '$id' AND journal_sale_list_id NOT IN ($str) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>