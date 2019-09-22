<?php

require_once("BaseModel.php");
class RequestSpecialListModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getRequestSpecialListBy($request_special_id){
        $sql = " SELECT tb_request_special_list.product_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,   
        request_special_list_id, 
        request_special_list_qty,
        request_special_list_delivery,
        tb_request_special_list.customer_id,
        request_special_list_remark, 
        tool_test_result,
        customer_name_en,
        request_test_list_id
        FROM tb_request_special_list LEFT JOIN tb_product ON tb_request_special_list.product_id = tb_product.product_id 
        LEFT JOIN tb_customer ON tb_request_special_list.customer_id = tb_customer.customer_id 
        WHERE request_special_id = '$request_special_id' 
        ORDER BY request_special_list_id 
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


    function insertRequestSpecialList($data = []){
        $sql = " INSERT INTO tb_request_special_list (
            request_special_id,
            product_id,
            customer_id,
            request_special_list_qty,
            request_special_list_delivery,
            request_special_list_remark, 
            tool_test_result,
            request_test_list_id,
            addby,
            adddate
        ) VALUES (
            '".$data['request_special_id']."', 
            '".$data['product_id']."', 
            '".$data['customer_id']."', 
            '".$data['request_special_list_qty']."', 
            '".$data['request_special_list_delivery']."', 
            '".$data['request_special_list_remark']."', 
            '".$data['tool_test_result']."',
            '".$data['request_test_list_id']."',
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

    function updateRequestSpecialListById($data,$id){

        $sql = " UPDATE tb_request_special_list 
            SET product_id = '".$data['product_id']."', 
            customer_id = '".$data['customer_id']."', 
            request_special_list_qty = '".$data['request_special_list_qty']."', 
            request_special_list_delivery = '".$data['request_special_list_delivery']."', 
            request_special_list_remark = '".$data['request_special_list_remark']."', 
            tool_test_result = '".$data['tool_test_result']."' , 
            request_test_list_id = '".$data['request_test_list_id']."'  
            WHERE request_special_list_id = '$id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function updateRequestTestListId($request_special_list_id,$request_test_list_id){
        $sql = " UPDATE tb_request_special_list 
            SET request_test_list_id = '$request_test_list_id' 
            WHERE request_special_list_id = '$request_special_list_id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function updatePurchaseOrderListId($request_special_list_id,$purchase_order_list_id){
        $sql = " UPDATE tb_request_special_list 
            SET purchase_order_list_id = '$purchase_order_list_id' 
            WHERE request_special_list_id = '$request_special_list_id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function deleteRequestSpecialListByID($id){
        $sql = "DELETE FROM tb_request_special_list WHERE request_special_list_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteRequestSpecialListByRequestSpecialID($id){
        $sql = "DELETE FROM tb_request_special_list WHERE request_special_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteRequestSpecialListByRequestSpecialIDNotIN($id,$data){
        $str ='';
        if(is_array($data)){ 
            for($i=0; $i < count($data) ;$i++){
                $str .= $data[$i];
                if($i + 1 < count($data)){
                    $str .= ',';
                }
            }
        }else if ($data != ''){
            $str = $data;
        }else{
            $str='0';
        }

        $sql = "DELETE FROM tb_request_special_list WHERE request_special_id = '$id' AND request_special_list_id NOT IN ($str) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>