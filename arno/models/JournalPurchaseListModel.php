<?php

require_once("BaseModel.php");
class JournalPurchaseListModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getJournalPurchaseListBy($journal_purchase_id){
        $sql = " SELECT 
        IF(journal_purchase_list_debit > 0,1,0) as sort_debit,
        account_code,
        journal_purchase_list_id, 
        journal_purchase_list_no, 
        journal_purchase_list_name,
        journal_purchase_list_debit,
        journal_purchase_list_credit, 
        journal_cheque_id,
        journal_cheque_pay_id,
        journal_invoice_customer_id,
        journal_invoice_supplier_id,
        tb_journal_purchase_list.account_id, 
        account_name_th,  
        account_name_en 
        FROM tb_journal_purchase_list LEFT JOIN tb_account ON tb_journal_purchase_list.account_id = tb_account.account_id 
        WHERE journal_purchase_id = '$journal_purchase_id' 
        ORDER BY sort_debit DESC , journal_purchase_list_no ASC
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

    function getJournalPurchaseListByAccountId($journal_purchase_id,$account_id){
        $sql = " SELECT *
        FROM tb_journal_purchase_list  
        WHERE journal_purchase_id = '$journal_purchase_id' AND account_id = '$account_id' 
        ORDER BY journal_purchase_list_no 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data ;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }

    } 


    function insertJournalPurchaseList($data = []){
        $sql = " INSERT INTO tb_journal_purchase_list (
            journal_purchase_id, 
            journal_purchase_list_no, 
            journal_cheque_id,
            journal_cheque_pay_id,
            journal_invoice_customer_id,
            journal_invoice_supplier_id,
            account_id,
            journal_purchase_list_name,
            journal_purchase_list_debit,
            journal_purchase_list_credit,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['journal_purchase_id']."',   
            '".$data['journal_purchase_list_no']."',   
            '".$data['journal_cheque_id']."', 
            '".$data['journal_cheque_pay_id']."', 
            '".$data['journal_invoice_customer_id']."', 
            '".$data['journal_invoice_supplier_id']."', 
            '".$data['account_id']."', 
            '".static::$db->real_escape_string($data['journal_purchase_list_name'])."', 
            '".$data['journal_purchase_list_debit']."',
            '".$data['journal_purchase_list_credit']."',
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

    function updateJournalPurchaseListById($data,$id){



        $sql = " UPDATE tb_journal_purchase_list 
            SET account_id = '".$data['account_id']."',  
            journal_purchase_list_no = '".$data['journal_purchase_list_no']."',
            journal_cheque_id = '".$data['journal_cheque_id']."',
            journal_cheque_pay_id = '".$data['journal_cheque_pay_id']."',
            journal_invoice_customer_id = '".$data['journal_invoice_customer_id']."',
            journal_invoice_supplier_id = '".$data['journal_invoice_supplier_id']."',
            journal_purchase_list_name = '".static::$db->real_escape_string($data['journal_purchase_list_name'])."',
            journal_purchase_list_debit = '".$data['journal_purchase_list_debit']."',
            journal_purchase_list_credit = '".$data['journal_purchase_list_credit']."' 
            WHERE journal_purchase_list_id = '$id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function deleteJournalPurchaseListByID($id){
        $sql = "DELETE FROM tb_journal_purchase_list WHERE journal_purchase_list_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteJournalPurchaseListByJournalPurchaseID($id){
        $sql = "DELETE FROM tb_journal_purchase_list WHERE journal_purchase_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteJournalPurchaseListByJournalPurchaseIDNotIN($id,$data){
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

        $sql = "DELETE FROM tb_journal_purchase_list WHERE journal_purchase_id = '$id' AND journal_purchase_list_id NOT IN ($str) ";
 
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>