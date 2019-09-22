<?php

require_once("BaseModel.php");
class PurchaseOrderListModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getPurchaseOrderListBy($purchase_order_id){
        $sql = " SELECT tb.product_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,   
        tb.purchase_order_list_id,  purchase_order_list_code,
        IFNULL(purchase_request_list_id,0) as purchase_request_list_id,
        IFNULL(customer_purchase_order_list_detail_id,0) as customer_purchase_order_list_detail_id,
        IFNULL(delivery_note_supplier_list_id,0) as delivery_note_supplier_list_id,
        IFNULL(regrind_supplier_receive_list_id,0) as regrind_supplier_receive_list_id,
        IFNULL(request_standard_list_id,0) as request_standard_list_id,
        IFNULL(request_special_list_id,0) as request_special_list_id,
        IFNULL(request_regrind_list_id,0) as request_regrind_list_id,
        IFNULL(( 
            SELECT SUM(IFNULL(invoice_supplier_list_qty,0)) 
            FROM tb_invoice_supplier_list 
            LEFT JOIN tb_invoice_supplier ON  tb_invoice_supplier_list.invoice_supplier_id = tb_invoice_supplier.invoice_supplier_id 
            WHERE invoice_supplier_close = '0' 
            AND  purchase_order_list_id = tb.purchase_order_list_id

        ),0) as purchase_order_list_qty_recieve , 
        tb.stock_group_id, 
        purchase_order_list_qty, 
        purchase_order_list_price, 
        purchase_order_list_price_sum, 
        purchase_order_list_delivery_min,  
        purchase_order_list_delivery_max, 
        purchase_order_list_remark, 
        purchase_order_list_supplier_qty, 
        purchase_order_list_supplier_delivery_min,  
        purchase_order_list_supplier_delivery_max, 
        purchase_order_list_supplier_remark ,
        purchase_order_list_remark_for_supplier, 
        product_drawing
        FROM tb_purchase_order_list as tb 
        LEFT JOIN tb_product ON tb.product_id = tb_product.product_id 
        LEFT JOIN tb_purchase_request_list ON tb.purchase_order_list_id = tb_purchase_request_list.purchase_order_list_id
        LEFT JOIN tb_customer_purchase_order_list_detail ON tb.purchase_order_list_id = tb_customer_purchase_order_list_detail.purchase_order_list_id
        LEFT JOIN tb_delivery_note_supplier_list ON tb.purchase_order_list_id = tb_delivery_note_supplier_list.purchase_order_list_id
        LEFT JOIN tb_regrind_supplier_receive_list ON tb.purchase_order_list_id = tb_regrind_supplier_receive_list.purchase_order_list_id
        LEFT JOIN tb_request_standard_list ON tb.purchase_order_list_id = tb_request_standard_list.purchase_order_list_id
        LEFT JOIN tb_request_special_list ON tb.purchase_order_list_id = tb_request_special_list.purchase_order_list_id
        LEFT JOIN tb_request_regrind_list ON tb.purchase_order_list_id = tb_request_regrind_list.purchase_order_list_id
        WHERE purchase_order_id = '$purchase_order_id' 
        ORDER BY purchase_order_list_no, tb.purchase_order_list_id 
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

    function getPurchaseOrderListIDByOther($purchase_order_code,$purchase_order_list_no){
        $sql ="SELECT * 
        FROM tb_purchase_order_list 
        LEFT JOIN tb_purchase_order ON tb_purchase_order_list.purchase_order_id = tb_purchase_order.purchase_order_id 
        WHERE purchase_order_code = '$purchase_order_code'"; 
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data=[];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data[$purchase_order_list_no-1]['purchase_order_list_id'];
        }
    }


    function insertPurchaseOrderList($data = []){
        $sql = " INSERT INTO tb_purchase_order_list ( 
            purchase_order_id,
            purchase_order_list_no,
            product_id,
            stock_group_id,
            purchase_order_list_qty,
            purchase_order_list_price, 
            purchase_order_list_price_sum,
            purchase_order_list_delivery_min, 
            purchase_order_list_delivery_max,
            purchase_order_list_remark,
            purchase_order_list_remark_for_supplier,
            purchase_order_list_code,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES ( 
            '".$data['purchase_order_id']."', 
            '".$data['purchase_order_list_no']."', 
            '".$data['product_id']."', 
            '".$data['stock_group_id']."', 
            '".$data['purchase_order_list_qty']."', 
            '".$data['purchase_order_list_price']."', 
            '".$data['purchase_order_list_price_sum']."', 
            '".$data['purchase_order_list_delivery_min']."', 
            '".$data['purchase_order_list_delivery_max']."', 
            '".static::$db->real_escape_string($data['purchase_order_list_remark'])."',
            '".static::$db->real_escape_string($data['purchase_order_list_remark_for_supplier'])."',
            '".static::$db->real_escape_string($data['purchase_order_list_code'])."',
            '".$data['addby']."', 
            NOW(), 
            '".$data['updateby']."', 
            NOW() 
        ); 
        ";

 
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return '';
        }

    }

    function updatePurchaseOrderListById($data,$id){

        $sql = " UPDATE tb_purchase_order_list 
            SET purchase_order_list_supplier_qty = '".$data['purchase_order_list_supplier_qty']."',
            purchase_order_list_supplier_delivery_min = '".$data['purchase_order_list_supplier_delivery_min']."', 
            purchase_order_list_supplier_delivery_max = '".$data['purchase_order_list_supplier_delivery_max']."',
            purchase_order_list_supplier_remark = '".static::$db->real_escape_string($data['purchase_order_list_supplier_remark'])."', 
            purchase_order_list_code = '".static::$db->real_escape_string($data['purchase_order_list_code'])."'
            WHERE purchase_order_list_id = '$id'
        "; 
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }
    function updatePurchaseOrderListByDetail($data,$id){

        $sql = " UPDATE tb_purchase_order_list 
            SET 
            purchase_order_list_price = '".static::$db->real_escape_string($data['purchase_order_list_price'])."', 
            purchase_order_list_price_sum = '".static::$db->real_escape_string($data['purchase_order_list_price_sum'])."'
            WHERE purchase_order_list_id = '$id'
        ";  
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function updatePurchaseOrderListByIdAdmin($data,$id){

        $sql = " UPDATE tb_purchase_order_list 
            SET product_id = '".$data['product_id']."', 
            stock_group_id = '".$data['stock_group_id']."',
            purchase_order_list_no = '".$data['purchase_order_list_no']."',
            purchase_order_list_qty = '".$data['purchase_order_list_qty']."',
            purchase_order_list_price = '".$data['purchase_order_list_price']."', 
            purchase_order_list_price_sum = '".$data['purchase_order_list_price_sum']."',
            purchase_order_list_delivery_min = '".$data['purchase_order_list_delivery_min']."', 
            purchase_order_list_delivery_max = '".$data['purchase_order_list_delivery_max']."',
            purchase_order_list_remark = '".static::$db->real_escape_string($data['purchase_order_list_remark'])."',
            purchase_order_list_remark_for_supplier = '".static::$db->real_escape_string($data['purchase_order_list_remark_for_supplier'])."',
            updateby = '".$data['updateby']."',
            lastupdate =  NOW(),
            purchase_order_list_code = '".static::$db->real_escape_string($data['purchase_order_list_code'])."'
            WHERE purchase_order_list_id = '$id'
        ";

        // echo $sql."<br><br>";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function updateInvoiceSupplierListID($purchase_order_list_id,$invoice_supplier_list_id){
        $sql = " UPDATE tb_purchase_request_list 
            SET invoice_supplier_list_id = '$invoice_supplier_list_id' 
            WHERE purchase_order_list_id = '$purchase_order_list_id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    
    function updateInvoiceCustomerListID($purchase_order_list_id,$invoice_customer_list_id){
        $sql = " UPDATE tb_purchase_request_list 
            SET invoice_customer_list_id = '$invoice_customer_list_id' 
            WHERE purchase_order_list_id = '$purchase_order_list_id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    } 
    function deletePurchaseOrderListByID($id){
        $sql = "DELETE FROM tb_purchase_order_list WHERE purchase_order_list_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    } 
    function deletePurchaseOrderListByPurchaseOrderID($id){

        $sql = "UPDATE  tb_purchase_request_list SET purchase_order_list_id = '0'  WHERE purchase_order_list_id IN (SELECT purchase_order_list_id FROM tb_purchase_order_list WHERE purchase_order_id = '$id') ";
     
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = "UPDATE  tb_customer_purchase_order_list_detail SET purchase_order_list_id = '0'  WHERE purchase_order_list_id IN (SELECT purchase_order_list_id FROM tb_purchase_order_list WHERE purchase_order_id = '$id') ";
     
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = "UPDATE  tb_delivery_note_supplier_list SET purchase_order_list_id = '0'  WHERE purchase_order_list_id IN (SELECT purchase_order_list_id FROM tb_purchase_order_list WHERE purchase_order_id = '$id') ";
     
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = "UPDATE  tb_regrind_supplier_receive_list SET purchase_order_list_id = '0'  WHERE purchase_order_list_id IN (SELECT purchase_order_list_id FROM tb_purchase_order_list WHERE purchase_order_id = '$id') ";
     
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = "UPDATE  tb_request_standard_list SET purchase_order_list_id = '0'  WHERE purchase_order_list_id IN (SELECT purchase_order_list_id FROM tb_purchase_order_list WHERE purchase_order_id = '$id') ";
     
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = "UPDATE  tb_request_special_list SET purchase_order_list_id = '0'  WHERE purchase_order_list_id IN (SELECT purchase_order_list_id FROM tb_purchase_order_list WHERE purchase_order_id = '$id') ";
     
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = "UPDATE  tb_request_regrind_list SET purchase_order_list_id = '0'  WHERE purchase_order_list_id IN (SELECT purchase_order_list_id FROM tb_purchase_order_list WHERE purchase_order_id = '$id') ";
     
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);




        $sql = "DELETE FROM tb_purchase_order_list WHERE purchase_order_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        

    }

    function deletePurchaseOrderListByPurchaseOrderIDNotIN($id,$data){
        $str ='';
        if(is_array($data)){ 
            for($i=0; $i < count($data) ;$i++){
                $str .= " '".$data[$i]."' ";
                if($i + 1 < count($data)){
                    $str .= ',';
                }
            }
        }else if ($data != ''){
            $str = $data;
        }else{
            $str='0';
        }
        $sql = "UPDATE  tb_purchase_request_list SET purchase_order_list_id = '0'  WHERE purchase_order_list_id IN (SELECT purchase_order_list_id FROM tb_purchase_order_list WHERE purchase_order_id = '$id' AND purchase_order_list_id NOT IN ($str)) ";
        //echo $sql."<br><br>";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = "UPDATE  tb_customer_purchase_order_list_detail SET purchase_order_list_id = '0'  WHERE purchase_order_list_id IN (SELECT purchase_order_list_id FROM tb_purchase_order_list WHERE purchase_order_id = '$id' AND purchase_order_list_id NOT IN ($str)) ";
     
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 

        $sql = "UPDATE  tb_delivery_note_supplier_list SET purchase_order_list_id = '0'  WHERE purchase_order_list_id IN (SELECT purchase_order_list_id FROM tb_purchase_order_list WHERE purchase_order_id = '$id' AND purchase_order_list_id NOT IN ($str)) ";
     
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 

        $sql = "UPDATE  tb_regrind_supplier_receive_list SET purchase_order_list_id = '0'  WHERE purchase_order_list_id IN (SELECT purchase_order_list_id FROM tb_purchase_order_list WHERE purchase_order_id = '$id' AND purchase_order_list_id NOT IN ($str)) ";
     
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 

        $sql = "UPDATE  tb_request_standard_list SET purchase_order_list_id = '0'  WHERE purchase_order_list_id IN (SELECT purchase_order_list_id FROM tb_purchase_order_list WHERE purchase_order_id = '$id' AND purchase_order_list_id NOT IN ($str)) ";
     
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 

        $sql = "UPDATE  tb_request_special_list SET purchase_order_list_id = '0'  WHERE purchase_order_list_id IN (SELECT purchase_order_list_id FROM tb_purchase_order_list WHERE purchase_order_id = '$id' AND purchase_order_list_id NOT IN ($str)) ";
     
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 

        $sql = "UPDATE  tb_request_regrind_list SET purchase_order_list_id = '0'  WHERE purchase_order_list_id IN (SELECT purchase_order_list_id FROM tb_purchase_order_list WHERE purchase_order_id = '$id' AND purchase_order_list_id NOT IN ($str)) ";
     
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 

        $sql = "DELETE FROM tb_purchase_order_list WHERE purchase_order_id = '$id' AND purchase_order_list_id NOT IN ($str) ";
     
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);


    }
}
?>