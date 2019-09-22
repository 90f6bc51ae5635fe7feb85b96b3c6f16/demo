<?php

require_once("BaseModel.php");
class DeliveryNoteCustomerReceiveListModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getDeliveryNoteCustomerReceiveListBy($delivery_note_customer_receive_id){
        $sql = " SELECT tb_delivery_note_customer_receive_list.product_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,   
        delivery_note_customer_receive_list_id, 
        delivery_note_customer_receive_list_qty,
        delivery_note_customer_receive_list_remark,
        tb_delivery_note_customer_receive_list.stock_group_id,
        stock_group_name
        FROM tb_delivery_note_customer_receive_list 
        LEFT JOIN tb_product ON tb_delivery_note_customer_receive_list.product_id = tb_product.product_id 
        LEFT JOIN tb_stock_group ON tb_delivery_note_customer_receive_list.stock_group_id = tb_stock_group.stock_group_id
        WHERE delivery_note_customer_receive_id = '$delivery_note_customer_receive_id' 
        ORDER BY delivery_note_customer_receive_list_id 
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


    function insertDeliveryNoteCustomerReceiveList($data = []){
        $sql = " INSERT INTO tb_delivery_note_customer_receive_list (
            delivery_note_customer_receive_id,
            product_id,
            delivery_note_customer_receive_list_qty,
            stock_group_id,
            delivery_note_customer_receive_list_remark,
            request_test_list_id,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['delivery_note_customer_receive_id']."', 
            '".$data['product_id']."', 
            '".$data['delivery_note_customer_receive_list_qty']."', 
            '".$data['stock_group_id']."', 
            '".static::$db->real_escape_string($data['delivery_note_customer_receive_list_remark'])."',
            '".$data['request_test_list_id']."',
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

    function updateDeliveryNoteCustomerReceiveListById($data,$id){

        $sql = " UPDATE tb_delivery_note_customer_receive_list 
            SET product_id = '".$data['product_id']."', 
            delivery_note_customer_receive_list_qty = '".$data['delivery_note_customer_receive_list_qty']."',
            updateby = '".$data['updateby']."',
            lastupdate = NOW(),
            delivery_note_customer_receive_list_remark = '".static::$db->real_escape_string($data['delivery_note_customer_receive_list_remark'])."' 
            WHERE delivery_note_customer_receive_list_id = '$id'
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function updatePurchaseOrderId($delivery_note_customer_receive_list_id,$purchase_order_list_id){
        $sql = " UPDATE tb_delivery_note_customer_receive_list 
            SET purchase_order_list_id = '$purchase_order_list_id' 
            WHERE delivery_note_customer_receive_list_id = '$delivery_note_customer_receive_list_id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function deleteDeliveryNoteCustomerReceiveListByID($id){
        $sql = "DELETE FROM tb_delivery_note_customer_receive_list WHERE delivery_note_customer_receive_list_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteDeliveryNoteCustomerReceiveListByDeliveryNoteCustomerReceiveID($id){
        $sql = "DELETE FROM tb_delivery_note_customer_receive_list WHERE delivery_note_customer_receive_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteDeliveryNoteCustomerReceiveListByDeliveryNoteCustomerReceiveIDNotIN($id,$data){
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

        $sql = "DELETE FROM tb_delivery_note_customer_receive_list WHERE delivery_note_customer_receive_id = '$id' AND delivery_note_customer_receive_list_id NOT IN ($str) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>