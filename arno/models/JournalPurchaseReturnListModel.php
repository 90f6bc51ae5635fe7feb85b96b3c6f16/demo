<?php

require_once("BaseModel.php");
class JournalPurchaseReturnListModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getJournalPurchaseReturnListBy($journal_purchase_return_id){
        $sql = " SELECT 
        journal_purchase_return_list_id, 
        journal_purchase_return_list_name,
        journal_purchase_return_list_debit,
        journal_purchase_return_list_credit, 
        tb_journal_purchase_return_list.account_id, 
        account_name_th,  
        account_name_en 
        FROM tb_journal_purchase_return_list LEFT JOIN tb_account ON tb_journal_purchase_return_list.account_id = tb_account.account_id 
        WHERE journal_purchase_return_id = '$journal_purchase_return_id' 
        ORDER BY journal_purchase_return_list_id 
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


    function insertJournalPurchaseReturnList($data = []){
        $sql = " INSERT INTO tb_journal_purchase_return_list (
            journal_purchase_return_id,
            account_id,
            journal_purchase_return_list_name,
            journal_purchase_return_list_debit,
            journal_purchase_return_list_credit,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['journal_purchase_return_id']."', 
            '".$data['account_id']."', 
            '".static::$db->real_escape_string($data['journal_purchase_return_list_name'])."', 
            '".$data['journal_purchase_return_list_debit']."',
            '".$data['journal_purchase_return_list_credit']."',
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

    function updateJournalPurchaseReturnListById($data,$id){

        $sql = " UPDATE tb_journal_purchase_return_list 
            SET account_id = '".$data['account_id']."', 
            journal_purchase_return_list_name = '".static::$db->real_escape_string($data['journal_purchase_return_list_name'])."',
            journal_purchase_return_list_debit = '".$data['journal_purchase_return_list_debit']."',
            journal_purchase_return_list_credit = '".$data['journal_purchase_return_list_credit']."' 
            WHERE journal_purchase_return_list_id = '$id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function deleteJournalPurchaseReturnListByID($id){
        $sql = "DELETE FROM tb_journal_purchase_return_list WHERE journal_purchase_return_list_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteJournalPurchaseReturnListByJournalPurchaseReturnID($id){
        $sql = "DELETE FROM tb_journal_purchase_return_list WHERE journal_purchase_return_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteJournalPurchaseReturnListByJournalPurchaseReturnIDNotIN($id,$data){
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

        $sql = "DELETE FROM tb_journal_purchase_return_list WHERE journal_purchase_return_id = '$id' AND journal_purchase_return_list_id NOT IN ($str) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>