<?php

require_once("BaseModel.php"); 
class InvoiceCustomerCostModel extends BaseModel{
 

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        } 
        mysqli_set_charset(static::$db,"utf8");
    }

    function getInvoiceCustomerCostByInvoiceSupplierID($invoice_supplier_id){
        $sql = " SELECT *
        FROM tb_invoice_customer_cost  
        LEFT JOIN tb_invoice_customer ON tb_invoice_customer_cost.invoice_customer_id = tb_invoice_customer.invoice_customer_id
        LEFT JOIN tb_customer ON tb_invoice_customer.customer_id = tb_customer.customer_id
        WHERE invoice_supplier_id = '$invoice_supplier_id' 
        ORDER BY invoice_customer_code 
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

    function getInvoiceSupplierCostByInvoiceCustomerID($invoice_customer_id){
        $sql = " SELECT *
        FROM tb_invoice_customer_cost  
        LEFT JOIN tb_invoice_supplier ON tb_invoice_customer_cost.invoice_supplier_id = tb_invoice_supplier.invoice_supplier_id
        LEFT JOIN tb_supplier ON tb_invoice_supplier.supplier_id = tb_supplier.supplier_id
        WHERE invoice_customer_id = '$invoice_customer_id' 
        ORDER BY invoice_supplier_code 
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

    function getInvoiceCustomerCostByID($invoice_customer_id, $invoice_supplier_id){
        $sql = " SELECT * 
        FROM tb_invoice_customer_cost 
        LEFT JOIN tb_invoice_customer ON tb_invoice_customer_cost.invoice_customer_id = tb_invoice_customer.invoice_customer_id
        LEFT JOIN tb_invoice_supplier ON tb_invoice_customer_cost.invoice_supplier_id = tb_invoice_supplier.invoice_supplier_id
        WHERE invoice_customer_id = '$invoice_customer_id'  
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


    function insertInvoiceCustomerCost($data = []){
        $sql = " INSERT INTO tb_invoice_customer_cost ( 
            invoice_customer_id,
            invoice_supplier_id,
            invoice_customer_cost_value,
            addby,
            adddate,
            updateby,
            lastupdate 
        ) VALUES ( 
            '".$data['invoice_customer_id']."', 
            '".$data['invoice_supplier_id']."', 
            '".$data['invoice_customer_cost_value']."', 
            '".$data['addby']."', 
            '".$data['adddate']."', 
            '".$data['updateby']."', 
            '".$data['lastupdate']."' 
        ); 
        "; 

        //echo $sql."<br>";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {

            $purchase_order_list_id = mysqli_insert_id(static::$db);
            return $purchase_order_list_id; 
        }else {
            return 0;
        }

    }

    

    function updateInvoiceCustomerCostById($data,$invoice_customer_id,$invoice_supplier_id){
 

        $sql = " UPDATE tb_invoice_customer_cost 
            SET invoice_customer_cost_value = '".$data['invoice_customer_cost_value']."' 
            WHERE invoice_customer_id = '$invoice_customer_id' 
            AND invoice_supplier_id = '$invoice_supplier_id' 
        ";
 
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {  
           return true;
        }else {
            return false;
        }
    } 




    function deleteInvoiceCustomerCostByID($invoice_customer_id,$invoice_supplier_id){
        $sql = "DELETE FROM tb_invoice_customer_cost WHERE invoice_customer_id = '$invoice_customer_id' AND invoice_supplier_id = '$invoice_supplier_id' ";
        //echo $sql."<br>";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteInvoiceCustomerCostByInvoiceSupplierID($invoice_supplier_id){


        $sql = "DELETE FROM tb_invoice_customer_cost WHERE invoice_supplier_id = '$invoice_supplier_id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteInvoiceCustomerCostByInvoiceCustomerID($invoice_customer_id){


        $sql = "DELETE FROM tb_invoice_customer_cost WHERE invoice_customer_id = '$invoice_customer_id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteInvoiceCustomerCostByIDNotIN($invoice_customer_id,$data){
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

        $sql = "DELETE FROM tb_invoice_customer_cost WHERE invoice_customer_id = '$invoice_customer_id' AND invoice_supplier_id NOT IN ($str) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        

    }
}
?>