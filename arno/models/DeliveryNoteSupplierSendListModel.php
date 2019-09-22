<?php

require_once("BaseModel.php");
class DeliveryNoteSupplierSendListModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getDeliveryNoteSupplierSendListBy($delivery_note_supplier_send_id){
        $sql = " SELECT tb_delivery_note_supplier_send_list.product_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,   
        delivery_note_supplier_send_list_id, 
        delivery_note_supplier_send_list_qty,
        delivery_note_supplier_send_list_remark,
        tb_delivery_note_supplier_send_list.stock_group_id,
        stock_group_name,
        stock_group_move_id
        FROM tb_delivery_note_supplier_send_list 
        LEFT JOIN tb_product ON tb_delivery_note_supplier_send_list.product_id = tb_product.product_id 
        LEFT JOIN tb_stock_group ON tb_delivery_note_supplier_send_list.stock_group_id = tb_stock_group.stock_group_id 
        WHERE delivery_note_supplier_send_id = '$delivery_note_supplier_send_id' 
        ORDER BY delivery_note_supplier_send_list_id 
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


    function insertDeliveryNoteSupplierSendList($data = []){
        $sql = " INSERT INTO tb_delivery_note_supplier_send_list (
            delivery_note_supplier_send_id,
            product_id,
            delivery_note_supplier_send_list_qty,
            delivery_note_supplier_send_list_remark,
            stock_group_id,
            stock_group_move_id,
            addby,
            adddate
        ) VALUES (
            '".$data['delivery_note_supplier_send_id']."', 
            '".$data['product_id']."', 
            '".$data['delivery_note_supplier_send_list_qty']."', 
            '".static::$db->real_escape_string($data['delivery_note_supplier_send_list_remark'])."',
            '".$data['stock_group_id']."',
            '".$data['stock_group_move_id']."',
            '".$data['addby']."', 
            NOW()
        ); 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }

    function updateDeliveryNoteSupplierSendListById($data,$id){

        $sql = " UPDATE tb_delivery_note_supplier_send_list 
            SET product_id = '".$data['product_id']."', 
            delivery_note_supplier_send_list_qty = '".$data['delivery_note_supplier_send_list_qty']."',
            delivery_note_supplier_send_list_remark = '".static::$db->real_escape_string($data['delivery_note_supplier_send_list_remark'])."' ,
            stock_group_id = '".$data['stock_group_id']."',
            stock_group_move_id = '".$data['stock_group_move_id']."',
            updateby = '".$data['updateby']."'  ,
            lastupdate = NOW() 
            WHERE delivery_note_supplier_send_list_id = '$id'
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function updatePurchaseOrderId($delivery_note_supplier_send_list_id,$purchase_order_list_id){
        $sql = " UPDATE tb_delivery_note_supplier_send_list 
            SET purchase_order_list_id = '$purchase_order_list_id' 
            WHERE delivery_note_supplier_send_list_id = '$delivery_note_supplier_send_list_id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function deleteDeliveryNoteSupplierSendListByID($id){
        $sql = "DELETE FROM tb_delivery_note_supplier_send_list WHERE delivery_note_supplier_send_list_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteDeliveryNoteSupplierSendListByDeliveryNoteSupplierSendID($id){
        $sql = "DELETE FROM tb_delivery_note_supplier_send_list WHERE delivery_note_supplier_send_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteDeliveryNoteSupplierSendListByDeliveryNoteSupplierSendIDNotIN($id,$data){
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

        $sql = "DELETE FROM tb_delivery_note_supplier_send_list WHERE delivery_note_supplier_send_id = '$id' AND delivery_note_supplier_send_list_id NOT IN ($str) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>