<?php

require_once("BaseModel.php");
class PurchaseOrderListDetailModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getPurchaseOrderListDetailBy($purchase_order_list_id = ""){
        $str = "1";
        if($purchase_order_list_id != ""){
            $str = "purchase_order_list_id = '$purchase_order_list_id'";
        }
        $sql = "    SELECT  purchase_order_list_detail_id,
                            purchase_order_list_id,
                            date_recieve,
                            qty_recieve,
                            remark_recieve
                    FROM tb_purchase_order_list_detail 
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

    function getPurchaseOrderListDetailByID($purchase_order_list_detail_id){
        $sql = "  SELECT * FROM tb_purchase_order_list_detail WHERE purchase_order_list_detail_id = $purchase_order_list_detail_id ";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $data;
        }

    }




    function updatePurchaseOrderListDetailByID($id,$data = []){
        $sql = " UPDATE tb_purchase_order_list_detail SET 
        purchase_order_list_id = '".$data['purchase_order_list_id']."' , 
        date_recieve = '".$data['date_recieve']."' , 
        qty_recieve = '".$data['qty_recieve']."' , 
        remark_recieve = '".static::$db->real_escape_string($data['remark_recieve'])."' 
        WHERE purchase_order_list_detail_id = $id 
        ";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function insertPurchaseOrderListDetail($data = []){
        $sql = " INSERT INTO tb_purchase_order_list_detail (
            purchase_order_list_id, 
            date_recieve, 
            qty_recieve, 
            remark_recieve 
        ) VALUES (  
            '".$data['purchase_order_list_id']."', 
            '".$data['date_recieve']."', 
            '".$data['qty_recieve']."', 
            '".static::$db->real_escape_string($data['remark_recieve'])."'  
        ); 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }

    function updatePurchaseOrderId($purchase_order_list_detail_id,$purchase_order_list_id){
        $sql = " UPDATE tb_purchase_order_list_detail 
            SET purchase_order_list_id = '$purchase_order_list_id' 
            WHERE purchase_order_list_detail_id = '$purchase_order_list_detail_id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function deletePurchaseOrderListDetailByID($id){
        $sql = " DELETE FROM tb_purchase_order_list_detail WHERE purchase_order_list_detail_id = '$id' ";
        if(mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)){
            return true;
        }else{
            return false;
        }
    }

    function deletePurchaseOrderListDetailByIDNotIN($id,$data){
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

            
        $sql = "DELETE FROM tb_purchase_order_list_detail WHERE purchase_order_list_id = '$id' AND purchase_order_list_detail_id NOT IN ($str) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

}
?>