<?php

require_once("BaseModel.php");
class SupplierAccountModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getSupplierAccountBy($supplier_id, $supplier_account_no = '', $supplier_account_bank = '', $supplier_account_branch = ''){
        $sql = " SELECT supplier_account_id, supplier_account_no, supplier_account_bank , supplier_account_branch, supplier_account_detail   
        FROM tb_supplier_account 
        WHERE supplier_id = $supplier_id
        AND (supplier_account_no LIKE ('%$supplier_account_no%') 
        OR supplier_account_bank LIKE ('%$supplier_account_bank%') 
        OR supplier_account_branch LIKE ('%$supplier_account_branch%') 
        )
        ORDER BY supplier_account_bank , supplier_account_no
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

    function getSupplierAccountByID($id){
        $sql = " SELECT * 
        FROM tb_supplier_account 
        WHERE supplier_account_id = '$id' 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }

    }

    function updateSupplierAccountByID($id,$data = []){
        $sql = " UPDATE tb_supplier_account SET 
        supplier_id = '".$data['supplier_id']."', 
        supplier_account_no = '".$data['supplier_account_no']."', 
        supplier_account_name = '".$data['supplier_account_name']."', 
        supplier_account_bank = '".$data['supplier_account_bank']."', 
        supplier_account_branch = '".$data['supplier_account_branch']."', 
        supplier_account_detail = '".$data['supplier_account_detail']."', 
        updateby = '".$data['updateby']."',  
        lastupdate = NOW() 
        WHERE supplier_account_id = $id 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function insertSupplierAccount($data = []){
        $sql = " INSERT INTO tb_supplier_account (
            supplier_id,
            supplier_account_no, 
            supplier_account_name, 
            supplier_account_bank, 
            supplier_account_branch, 
            supplier_account_detail, 
            addby,
            adddate
        ) VALUES (
            '".$data['supplier_id']."', 
            '".$data['supplier_account_no']."', 
            '".$data['supplier_account_name']."', 
            '".$data['supplier_account_bank']."', 
            '".$data['supplier_account_branch']."', 
            '".$data['supplier_account_detail']."', 
            '".$data['addby']."', 
            NOW()  
        ); 
        ";

        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }


    function deleteSupplierAccountByID($id){
        $sql = "DELETE FROM tb_supplier_account WHERE supplier_account_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }
    
}
?>