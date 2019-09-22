<?php

require_once("BaseModel.php");
class CustomerPurchaseOrderListDetailModel extends BaseModel{

    function __construct(){
 
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getCustomerPurchaseOrderListDetailBy($customer_purchase_order_list_id = ""){
        $str = "1";
        if($customer_purchase_order_list_id != ""){
            $str = "customer_purchase_order_list_id = '$customer_purchase_order_list_id'";
        }
        $sql = "    SELECT tb_1.stock_group_name,  
                    tb_2.stock_group_name as stock_hold_name, 
                    stock_hold_id, 
                    tb_customer_purchase_order_list_detail.stock_group_id, 
                    tb_customer_purchase_order_list_detail.supplier_id,
                    tb_supplier.supplier_name_th,
                    tb_supplier.supplier_name_en,
                    qty, 
                    customer_purchase_order_list_id ,
                    stock_type_code,
                    stock_type_name,
                    customer_purchase_order_list_detail_id 
                    FROM tb_customer_purchase_order_list_detail 
                    LEFT JOIN tb_supplier ON tb_customer_purchase_order_list_detail.supplier_id = tb_supplier.supplier_id 
                    LEFT JOIN tb_stock_group as tb_1 ON tb_customer_purchase_order_list_detail.stock_group_id = tb_1.stock_group_id  
                    LEFT JOIN tb_stock_group as tb_2 ON tb_customer_purchase_order_list_detail.stock_hold_id = tb_2.stock_group_id 
                    LEFT JOIN tb_stock_type ON tb_1.stock_type_id = tb_stock_type.stock_type_id 
                    WHERE $str ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getCustomerPurchaseOrderListDetailByID($customer_purchase_order_list_detail_id){
        $sql = "  SELECT * FROM tb_customer_purchase_order_list_detail WHERE customer_purchase_order_list_detail_id = $customer_purchase_order_list_detail_id ";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $data;
        }

    }
    function getIsOpenPurchaseOrderByCustomerPurchaseOrderListDetailID($customer_purchase_order_list_detail_id){
        $sql = "  SELECT COUNT(*) AS 'po_number' FROM tb_purchase_order_list_data WHERE customer_purchase_order_list_detail_id = $customer_purchase_order_list_detail_id ";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            if($data['po_number'] > 0){ 
                return true;
            }else{ 
                return false;
            }
        }

    } 

    function updateCustomerPurchaseOrderListDetailByID($id,$data = []){
        $sql = " UPDATE tb_customer_purchase_order_list_detail SET 
        customer_purchase_order_list_id = '".$data['customer_purchase_order_list_id']."' , 
        supplier_id = '".$data['supplier_id']."' , 
        stock_hold_id = '".$data['stock_hold_id']."' , 
        stock_group_id = '".$data['stock_group_id']."' , 
        qty = '".$data['qty']."' 
        WHERE customer_purchase_order_list_detail_id = $id 
        ";

        //echo $sql."<br><br>";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function insertCustomerPurchaseOrderListDetail($data = []){
        $sql = " INSERT INTO tb_customer_purchase_order_list_detail (
            customer_purchase_order_list_id, 
            supplier_id, 
            stock_hold_id, 
            stock_group_id, 
            qty 
        ) VALUES (  
            '".$data['customer_purchase_order_list_id']."', 
            '".$data['supplier_id']."', 
            '".$data['stock_hold_id']."', 
            '".$data['stock_group_id']."', 
            '".$data['qty']."' 
        ); 
        ";
        
        //echo $sql."<br><br>";

        $result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        echo '<br>Result : '.$result;
        if ($result) {
           return mysqli_insert_id(static::$db);
        }else {
            return 0;
        } 
    }

    function updatePurchaseOrderId($customer_purchase_order_list_detail_id,$purchase_order_list_id){
        $sql = " UPDATE tb_customer_purchase_order_list_detail 
            SET purchase_order_list_id = '$purchase_order_list_id' 
            WHERE customer_purchase_order_list_detail_id = '$customer_purchase_order_list_detail_id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function deleteCustomerPurchaseOrderListDetailByID($id){
        $sql = " DELETE FROM tb_customer_purchase_order_list_detail WHERE customer_purchase_order_list_detail_id = '$id' ";
        if(mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)){
            return true;
        }else{
            return false;
        }
    }

    function deleteCustomerPurchaseOrderListDetailByIDNotIN($id,$data){
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

            
        $sql = "DELETE FROM tb_customer_purchase_order_list_detail WHERE customer_purchase_order_list_id = '$id' AND customer_purchase_order_list_detail_id NOT IN ($str) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

}
?>