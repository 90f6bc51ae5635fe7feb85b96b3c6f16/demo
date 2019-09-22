<?php

require_once("BaseModel.php"); 
class InvoiceSupplierShortListModel extends BaseModel{
 

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        } 
        mysqli_set_charset(static::$db,"utf8");
    }

    function getInvoiceSupplierShortListBy($invoice_supplier_id){
        $sql = " SELECT *
        FROM tb_invoice_supplier_short_list  
        LEFT JOIN tb_account ON tb_invoice_supplier_short_list.account_id = tb_account.account_id
        WHERE invoice_supplier_id = '$invoice_supplier_id' 
        ORDER BY account_code 
        "; 

// echo $sql . "<br><br>";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getInvoiceSupplierShortListByID($account_id,$invoice_supplier_id){
        $sql = " SELECT * 
        FROM tb_invoice_supplier_short_list 
        WHERE account_id = '$account_id'  
        AND invoice_supplier_id = '$invoice_supplier_id'
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


    function insertInvoiceSupplierShortList($data = []){
        $sql = " INSERT INTO tb_invoice_supplier_short_list ( 
            account_id,
            invoice_supplier_id,
            invoice_supplier_short_list_name,
            invoice_supplier_short_list_total_currency, 
            invoice_supplier_short_list_exchange_rate, 
            invoice_supplier_short_list_total 
        ) VALUES ( 
            '".$data['account_id']."', 
            '".$data['invoice_supplier_id']."', 
            '".$data['invoice_supplier_short_list_name']."', 
            '".$data['invoice_supplier_short_list_total_currency']."',
            '".$data['invoice_supplier_short_list_exchange_rate']."',
            '".$data['invoice_supplier_short_list_total']."' 
        ); 
        ";

        // echo $sql . "<br><br>";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {

            $purchase_order_list_id = mysqli_insert_id(static::$db);
            return $purchase_order_list_id; 
        }else {
            return 0;
        }

    }

    

    function updateInvoiceSupplierShortListById($data,$account_id,$invoice_supplier_id){
 

        $sql = " UPDATE tb_invoice_supplier_short_list 
            SET invoice_supplier_short_list_name = '".$data['invoice_supplier_short_list_name']."',  
            invoice_supplier_short_list_total_currency = '".$data['invoice_supplier_short_list_total_currency']."', 
            invoice_supplier_short_list_exchange_rate = '".$data['invoice_supplier_short_list_exchange_rate']."', 
            invoice_supplier_short_list_total = '".$data['invoice_supplier_short_list_total']."' 
            WHERE account_id = '$account_id' AND invoice_supplier_id ='$invoice_supplier_id'
        ";

        // echo $sql . "<br><br>";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {  
           return true;
        }else {
            return false;
        }
    } 




    function deleteInvoiceSupplierShortListByID($account_id,$invoice_supplier_id){
        $sql = "DELETE FROM tb_invoice_supplier_short_list WHERE account_id = '$account_id' AND invoice_supplier_id = '$invoice_supplier_id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteInvoiceSupplierShortListByInvoiceSupplierID($invoice_supplier_id){


        $sql = "DELETE FROM tb_invoice_supplier_short_list WHERE invoice_supplier_id = '$invoice_supplier_id' ";
        //echo $sql . "<br><br>";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

}
?>