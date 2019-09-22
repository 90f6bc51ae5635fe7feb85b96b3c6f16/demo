<?php

require_once("BaseModel.php");
class PurchaseRequestListModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db,"utf8");
    }

    function getPurchaseRequestListBy($purchase_request_id){
        $sql = " SELECT tb_purchase_request_list.product_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,   
        product_description,   
        purchase_request_list_id, 
        purchase_request_list_qty, 
        purchase_request_list_price, 
        purchase_request_list_total, 
        stock_group_id,
        tb_supplier.supplier_id,
        supplier_name_en,
        purchase_request_list_delivery,
        purchase_request_list_remark 
        FROM tb_purchase_request_list 
        LEFT JOIN tb_product ON tb_purchase_request_list.product_id = tb_product.product_id
        LEFT JOIN tb_supplier ON  tb_purchase_request_list.supplier_id = tb_supplier.supplier_id
        WHERE purchase_request_id = '$purchase_request_id' 
        ORDER BY purchase_request_list_no, purchase_request_list_id 
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
    function getPurchaseRequestListByMobile($purchase_request_id){
        $sql = " SELECT tb_purchase_request_list.product_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,   
        product_description,   
        purchase_request_list_id, 
        purchase_request_list_qty, 
        purchase_request_list_price, 
        purchase_request_list_total, 
        tb_purchase_request_list.stock_group_id,
        tb_purchase_request_list.supplier_id,
        purchase_request_list_delivery,
        purchase_request_list_remark ,
        stock_group_name,
        supplier_name_en as supplier_name
        FROM tb_purchase_request_list
        LEFT JOIN tb_product ON tb_purchase_request_list.product_id = tb_product.product_id 
        LEFT JOIN tb_supplier ON tb_purchase_request_list.supplier_id = tb_supplier.supplier_id 
        LEFT JOIN tb_stock_group ON tb_purchase_request_list.stock_group_id = tb_stock_group.stock_group_id 
        WHERE purchase_request_id = '$purchase_request_id' 
        ORDER BY purchase_request_list_no, purchase_request_list_id 
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


    function insertPurchaseRequestList($data = []){
        $sql = " INSERT INTO tb_purchase_request_list ( 
            purchase_request_id,
            purchase_request_list_no,
            stock_group_id,
            supplier_id,
            product_id,
            purchase_request_list_qty,
            purchase_request_list_price,
            purchase_request_list_total,
            purchase_request_list_delivery,
            purchase_request_list_remark,
            addby,
            adddate
        ) VALUES ( 
            '".$data['purchase_request_id']."', 
            '".$data['purchase_request_list_no']."', 
            '".$data['stock_group_id']."', 
            '".$data['supplier_id']."', 
            '".$data['product_id']."', 
            '".$data['purchase_request_list_qty']."', 
            '".$data['purchase_request_list_price']."', 
            '".$data['purchase_request_list_total']."', 
            '".$data['purchase_request_list_delivery']."', 
            '".static::$db->real_escape_string($data['purchase_request_list_remark'])."',
            '".$data['addby']."', 
            NOW() 
        ); 
        ";

        //echo $sql."<br>";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }

    function updatePurchaseRquestListById($data,$id){

        $sql = " UPDATE tb_purchase_request_list 
            SET product_id = '".$data['product_id']."', 
            purchase_request_list_no = '".$data['purchase_request_list_no']."',
            stock_group_id = '".$data['stock_group_id']."',
            supplier_id = '".$data['supplier_id']."',
            purchase_request_list_qty = '".$data['purchase_request_list_qty']."',
            purchase_request_list_price = '".$data['purchase_request_list_price']."',
            purchase_request_list_total = '".$data['purchase_request_list_total']."',
            purchase_request_list_delivery = '".$data['purchase_request_list_delivery']."', 
            purchase_request_list_remark = '".static::$db->real_escape_string($data['purchase_request_list_remark'])."' , 
            updateby =  '".$data['updateby']."',
            lastupdate = NOW() 
            WHERE purchase_request_list_id = '$id'
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function updatePurchaseOrderId($purchase_request_list_id,$purchase_order_list_id){
        $sql = " UPDATE tb_purchase_request_list 
            SET purchase_order_list_id = '$purchase_order_list_id' 
            WHERE purchase_request_list_id = '$purchase_request_list_id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function deletePurchaseRequestListByID($id){
        $sql = "DELETE FROM tb_purchase_request_list WHERE purchase_request_list_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deletePurchaseRequestListByPurchaseRequestID($id){
        $sql = "DELETE FROM tb_purchase_request_list WHERE purchase_request_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deletePurchaseRequestListByPurchaseRequestIDNotIN($id,$data){
        $str ='';
        if(is_array($data)){ 
            for($i=0; $i < count($data) ;$i++){
                $str .= " '".$data[$i]."' ";
                if($i + 1 < count($data)){
                    $str .= ",";
                }
            }
        }else if ($data != ''){
            $str = $data;
        }else{
            $str='0';
        }

        $sql = "DELETE FROM tb_purchase_request_list WHERE purchase_request_id = '$id' AND purchase_request_list_id NOT IN ($str) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>