<?php

require_once("BaseModel.php");
require_once("MaintenanceStockModel.php"); 
class InvoiceSupplierListModel extends BaseModel{

    private $maintenance_stock;

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db,"utf8");
        $this->maintenance_stock =  new MaintenanceStockModel;
    }

    function getInvoiceSupplierListBy($invoice_supplier_id){
        $sql = " SELECT tb_invoice_supplier_list.product_id, 
        invoice_supplier_list_id,
        CONCAT(product_code_first,product_code) as product_code, 
        product_name, 
        stock_group_name, 
        stock_group_code, 
        tb_invoice_supplier_list.purchase_order_list_id,
        purchase_order_list_price,
        purchase_order_list_price_sum,
        tb_invoice_supplier_list.stock_group_id,
        invoice_supplier_list_product_name, 
        invoice_supplier_list_product_detail, 
        invoice_supplier_list_qty, 
        invoice_supplier_list_import_duty, 
        invoice_supplier_list_import_duty_total, 
        invoice_supplier_list_freight_in, 
        invoice_supplier_list_freight_in_total, 
        invoice_supplier_list_currency_price, 
        invoice_supplier_list_currency_total, 
        invoice_supplier_list_duty, 
        invoice_supplier_list_fix_type, 
        invoice_supplier_list_price, 
        invoice_supplier_list_total, 
        invoice_supplier_list_cost, 
        invoice_supplier_list_cost_total, 
        invoice_supplier_list_remark,
        stock_event  
        FROM tb_invoice_supplier_list 
        LEFT JOIN tb_product ON tb_invoice_supplier_list.product_id = tb_product.product_id 
        LEFT JOIN tb_product_category ON tb_product.product_category_id = tb_product_category.product_category_id
        LEFT JOIN tb_stock_group ON tb_invoice_supplier_list.stock_group_id = tb_stock_group.stock_group_id 
        LEFT JOIN tb_purchase_order_list ON tb_invoice_supplier_list.purchase_order_list_id = tb_purchase_order_list.purchase_order_list_id 
        WHERE invoice_supplier_id = '$invoice_supplier_id' 
        ORDER BY invoice_supplier_list_no ,invoice_supplier_list_id 
        "; 

        //echo $sql;

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getInvoiceSupplierListStockBy($invoice_supplier_id){
        $sql = " SELECT tb_invoice_supplier_list.product_id, 
        invoice_supplier_list_id,
        CONCAT(product_code_first,product_code) as product_code, 
        product_name, 
        stock_group_name, 
        stock_group_code, 
        tb_invoice_supplier_list.purchase_order_list_id,
        purchase_order_list_price,
        purchase_order_list_price_sum,
        tb_invoice_supplier_list.stock_group_id,
        invoice_supplier_list_product_name, 
        invoice_supplier_list_product_detail, 
        invoice_supplier_list_qty, 
        invoice_supplier_list_import_duty, 
        invoice_supplier_list_import_duty_total, 
        invoice_supplier_list_freight_in, 
        invoice_supplier_list_freight_in_total, 
        invoice_supplier_list_currency_price, 
        invoice_supplier_list_currency_total, 
        invoice_supplier_list_duty, 
        invoice_supplier_list_fix_type, 
        invoice_supplier_list_price, 
        invoice_supplier_list_total, 
        invoice_supplier_list_cost, 
        invoice_supplier_list_cost_total, 
        invoice_supplier_list_remark,
        stock_event  
        FROM tb_invoice_supplier_list 
        LEFT JOIN tb_product ON tb_invoice_supplier_list.product_id = tb_product.product_id 
        LEFT JOIN tb_product_category ON tb_product.product_category_id = tb_product_category.product_category_id
        LEFT JOIN tb_stock_group ON tb_invoice_supplier_list.stock_group_id = tb_stock_group.stock_group_id 
        LEFT JOIN tb_purchase_order_list ON tb_invoice_supplier_list.purchase_order_list_id = tb_purchase_order_list.purchase_order_list_id 
        WHERE invoice_supplier_id = '$invoice_supplier_id' 
        AND stock_event = '1' 
        ORDER BY invoice_supplier_list_no ,invoice_supplier_list_id 
        "; 

        //echo $sql;

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getInvoiceSupplierListByID($id){
        $sql = " SELECT * 
        FROM tb_invoice_supplier_list 
        WHERE invoice_supplier_list_id = '$id'  
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


    function insertInvoiceSupplierList($data = []){
        $sql = " INSERT INTO tb_invoice_supplier_list ( 
            invoice_supplier_id,
            invoice_supplier_list_no,
            product_id,
            invoice_supplier_list_product_name,
            invoice_supplier_list_product_detail,
            invoice_supplier_list_duty,
            invoice_supplier_list_import_duty, 
            invoice_supplier_list_import_duty_total, 
            invoice_supplier_list_freight_in, 
            invoice_supplier_list_freight_in_total, 
            invoice_supplier_list_currency_price, 
            invoice_supplier_list_currency_total, 
            invoice_supplier_list_qty,
            invoice_supplier_list_price, 
            invoice_supplier_list_total,
            invoice_supplier_list_remark,
            invoice_supplier_list_fix_type,
            stock_group_id,
            purchase_order_list_id,
            invoice_supplier_list_cost, 
            invoice_supplier_list_cost_total, 
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES ( 
            '".$data['invoice_supplier_id']."', 
            '".$data['invoice_supplier_list_no']."', 
            '".$data['product_id']."', 
            '".static::$db->real_escape_string($data['invoice_supplier_list_product_name'])."', 
            '".static::$db->real_escape_string($data['invoice_supplier_list_product_detail'])."', 
            '".$data['invoice_supplier_list_duty']."', 
            '".$data['invoice_supplier_list_import_duty']."', 
            '".$data['invoice_supplier_list_import_duty_total']."', 
            '".$data['invoice_supplier_list_freight_in']."', 
            '".$data['invoice_supplier_list_freight_in_total']."', 
            '".$data['invoice_supplier_list_currency_price']."', 
            '".$data['invoice_supplier_list_currency_total']."', 
            '".$data['invoice_supplier_list_qty']."', 
            '".$data['invoice_supplier_list_price']."', 
            '".$data['invoice_supplier_list_total']."', 
            '".static::$db->real_escape_string($data['invoice_supplier_list_remark'])."',
            '".static::$db->real_escape_string($data['invoice_supplier_list_fix_type'])."',
            '".$data['stock_group_id']."', 
            '".$data['purchase_order_list_id']."', 
            '".$data['invoice_supplier_list_cost']."', 
            '".$data['invoice_supplier_list_cost_total']."', 
            '".$data['addby']."', 
            NOW(), 
            '".$data['updateby']."', 
            NOW() 
        ); 
        ";

        //echo $sql . "<br><br>";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {

            $purchase_order_list_id = mysqli_insert_id(static::$db);
            /*
            $this->maintenance_stock->addPurchase($data['stock_date'], $data['stock_group_id'] , $purchase_order_list_id, $data['product_id'], $data['invoice_supplier_list_qty'], $data['invoice_supplier_list_cost']);
            */
            return $purchase_order_list_id; 
        }else {
            return 0;
        }

    }

    

    function updateInvoiceSupplierListById($data,$id){

        $data_old = $this->getInvoiceSupplierListByID($id);

        $sql = " UPDATE tb_invoice_supplier_list 
            SET product_id = '".$data['product_id']."', 
            invoice_supplier_list_no = '".$data['invoice_supplier_list_no']."',  
            invoice_supplier_list_product_name = '".static::$db->real_escape_string($data['invoice_supplier_list_product_name'])."',  
            invoice_supplier_list_product_detail = '".static::$db->real_escape_string($data['invoice_supplier_list_product_detail'])."', 
            invoice_supplier_list_qty = '".$data['invoice_supplier_list_qty']."', 
            invoice_supplier_list_import_duty = '".$data['invoice_supplier_list_import_duty']."', 
            invoice_supplier_list_import_duty_total = '".$data['invoice_supplier_list_import_duty_total']."', 
            invoice_supplier_list_freight_in = '".$data['invoice_supplier_list_freight_in']."', 
            invoice_supplier_list_freight_in_total = '".$data['invoice_supplier_list_freight_in_total']."', 
            invoice_supplier_list_currency_price = '".$data['invoice_supplier_list_currency_price']."', 
            invoice_supplier_list_currency_total = '".$data['invoice_supplier_list_currency_total']."', 
            invoice_supplier_list_price = '".$data['invoice_supplier_list_price']."', 
            invoice_supplier_list_total = '".$data['invoice_supplier_list_total']."', 
            invoice_supplier_list_remark = '".static::$db->real_escape_string($data['invoice_supplier_list_remark'])."', 
            invoice_supplier_list_fix_type = '".static::$db->real_escape_string($data['invoice_supplier_list_fix_type'])."', 
            stock_group_id = '".$data['stock_group_id']."', 
            invoice_supplier_list_cost = '".$data['invoice_supplier_list_cost']."', 
            invoice_supplier_list_cost_total = '".$data['invoice_supplier_list_cost_total']."', 
            purchase_order_list_id = '".$data['purchase_order_list_id']."' 
            WHERE invoice_supplier_list_id = '$id' 
        ";

        // echo $sql . "<br><br>";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) { 

            /*
            $this->maintenance_stock->removePurchase($data_old['stock_group_id'], $id, $data_old['product_id'], $data_old['invoice_supplier_list_qty'] , $data_old['invoice_supplier_list_cost']);
            $this->maintenance_stock->addPurchase($data['stock_date'], $data['stock_group_id'] , $id, $data['product_id'], $data['invoice_supplier_list_qty'], $data['invoice_supplier_list_cost']);
            */

           return true;
        }else {
            return false;
        }
    }


    function updateCostListById($data,$id){

        $sql = " UPDATE tb_invoice_supplier_list 
            SET invoice_supplier_list_duty = '".$data['invoice_supplier_list_duty']."', 
                invoice_supplier_list_fix_type = '".$data['invoice_supplier_list_fix_type']."' ,  
                invoice_supplier_list_currency_price = '".$data['invoice_supplier_list_currency_price']."' , 
                invoice_supplier_list_currency_total = '".$data['invoice_supplier_list_currency_total']."' , 
                invoice_supplier_list_price = '".$data['invoice_supplier_list_price']."' , 
                invoice_supplier_list_total = '".$data['invoice_supplier_list_total']."' , 
                invoice_supplier_list_import_duty = '".$data['invoice_supplier_list_import_duty']."' , 
                invoice_supplier_list_import_duty_total = '".$data['invoice_supplier_list_import_duty_total']."' , 
                invoice_supplier_list_freight_in = '".$data['invoice_supplier_list_freight_in']."' , 
                invoice_supplier_list_freight_in_total = '".$data['invoice_supplier_list_freight_in_total']."' , 
                invoice_supplier_list_cost = '".$data['invoice_supplier_list_cost']."' , 
                invoice_supplier_list_cost_total = '".$data['invoice_supplier_list_cost_total']."' 
            WHERE invoice_supplier_list_id = '$id' 
        ";
 
        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }




    function deleteInvoiceSupplierListByID($id){
        $sql = "DELETE FROM tb_invoice_supplier_list WHERE invoice_supplier_list_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteInvoiceSupplierListByInvoiceSupplierID($id){


        $sql = "DELETE FROM tb_invoice_supplier_list WHERE invoice_supplier_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteInvoiceSupplierListByInvoiceSupplierIDNotIN($id,$data){
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

        $sql = "DELETE FROM tb_invoice_supplier_list WHERE invoice_supplier_id = '$id' AND invoice_supplier_list_id NOT IN ($str) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        

    }
}
?>