<?php

require_once("BaseModel.php"); 
class InvoiceSupplierFreightInListModel extends BaseModel{
 

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        } 
        mysqli_set_charset(static::$db,"utf8");
    }

    function getInvoiceSupplierFreightInListBy($invoice_supplier_id){
        $sql = " SELECT *
        FROM tb_invoice_supplier_freight_in_list  
        WHERE invoice_supplier_id = '$invoice_supplier_id' 
        ORDER BY invoice_supplier_freight_in_list_id 
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

    function getInvoiceSupplierFreightInListByID($id){
        $sql = " SELECT * 
        FROM tb_invoice_supplier_freight_in_list 
        WHERE invoice_supplier_freight_in_list_id = '$id'  
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


    function insertInvoiceSupplierFreightInList($data = []){
        $sql = " INSERT INTO tb_invoice_supplier_freight_in_list ( 
            invoice_supplier_id,
            invoice_supplier_freight_in_list_name,
            invoice_supplier_freight_in_list_total 
        ) VALUES ( 
            '".$data['invoice_supplier_id']."', 
            '".$data['invoice_supplier_freight_in_list_name']."', 
            '".$data['invoice_supplier_freight_in_list_total']."' 
        ); 
        ";

        //echo $sql . "<br><br>";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {

            $purchase_order_list_id = mysqli_insert_id(static::$db);
            return $purchase_order_list_id; 
        }else {
            return 0;
        }

    }

    

    function updateInvoiceSupplierFreightInListById($data,$id){
 

        $sql = " UPDATE tb_invoice_supplier_freight_in_list 
            SET invoice_supplier_freight_in_list_name = '".$data['invoice_supplier_freight_in_list_name']."',  
            invoice_supplier_freight_in_list_total = '".$data['invoice_supplier_freight_in_list_total']."' 
            WHERE invoice_supplier_freight_in_list_id = '$id' 
        ";

        //echo $sql . "<br><br>";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {  
           return true;
        }else {
            return false;
        }
    } 




    function deleteInvoiceSupplierFreightInListByID($id){
        $sql = "DELETE FROM tb_invoice_supplier_freight_in_list WHERE invoice_supplier_freight_in_list_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteInvoiceSupplierFreightInListByInvoiceSupplierID($id){


        $sql = "DELETE FROM tb_invoice_supplier_freight_in_list WHERE invoice_supplier_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteInvoiceSupplierFreightInListByIDNotIN($id,$data){
        $str ='';
        if(is_array($data)){ 
            for($i=0; $i < count($data) ;$i++){
                $str .= "'".$data[$i]."'";
                if($i + 1 < count($data)){
                    $str .= ',';
                }
            }
        }else if ($data != ''){
            $str = "'".$data."'";
        }else{
            $str='0';
        } 

        $sql = "DELETE FROM tb_invoice_supplier_freight_in_list WHERE invoice_supplier_id = '$id' AND invoice_supplier_freight_in_list_id NOT IN ($str) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        

    }
}
?>