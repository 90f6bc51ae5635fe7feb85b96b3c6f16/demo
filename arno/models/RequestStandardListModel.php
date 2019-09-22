<?php

require_once("BaseModel.php");
class RequestStandardListModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getRequestStandardListBy($request_standard_id){
        $sql = " SELECT tb_request_standard_list.product_id, 
        tb_request_standard_list.customer_id,
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,   
        request_standard_list_id, 
        request_standard_list_qty,
        request_standard_list_delivery,
        request_standard_list_remark, 
        tool_test_result,
        customer_name_en,
        customer_code,
        request_test_list_id
        FROM tb_request_standard_list LEFT JOIN tb_product ON tb_request_standard_list.product_id = tb_product.product_id 
        LEFT JOIN tb_customer ON tb_request_standard_list.customer_id = tb_customer.customer_id 
        WHERE request_standard_id = '$request_standard_id' 
        ORDER BY request_standard_list_id 
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


    function insertRequestStandardList($data = []){
        $sql = " INSERT INTO tb_request_standard_list (
            request_standard_id,
            product_id,
            customer_id,
            request_standard_list_qty,
            request_standard_list_delivery,
            request_standard_list_remark, 
            tool_test_result,
            request_test_list_id,
            addby,
            adddate 
        ) VALUES (
            '".$data['request_standard_id']."', 
            '".$data['product_id']."', 
            '".$data['customer_id']."', 
            '".$data['request_standard_list_qty']."', 
            '".$data['request_standard_list_delivery']."', 
            '".$data['request_standard_list_remark']."', 
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

    function updateRequestStandardListById($data,$id){

        $sql = " UPDATE tb_request_standard_list 
            SET product_id = '".$data['product_id']."', 
            customer_id = '".$data['customer_id']."', 
            request_standard_list_qty = '".$data['request_standard_list_qty']."', 
            request_standard_list_delivery = '".$data['request_standard_list_delivery']."', 
            request_standard_list_remark = '".$data['request_standard_list_remark']."', 
            tool_test_result = '".$data['tool_test_result']."' , 
            request_test_list_id = '".$data['request_test_list_id']."'  ,
            updateby = '".$data['updateby']."'  ,
            lastupdate = NOW()  
            WHERE request_standard_list_id = '$id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function updateRequestTestListId($request_standard_list_id,$request_test_list_id){
        $sql = " UPDATE tb_request_standard_list 
            SET request_test_list_id = '$request_test_list_id' 
            WHERE request_standard_list_id = '$request_standard_list_id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    
    function updatePurchaseOrderListId($request_standard_list_id,$purchase_order_list_id){
        $sql = " UPDATE tb_request_standard_list 
            SET purchase_order_list_id = '$purchase_order_list_id' 
            WHERE request_standard_list_id = '$request_standard_list_id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function deleteRequestStandardListByID($id){
        $sql = "DELETE FROM tb_request_standard_list WHERE request_standard_list_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteRequestStandardListByRequestStandardID($id){
        $sql = "DELETE FROM tb_request_standard_list WHERE request_standard_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteRequestStandardListByRequestStandardIDNotIN($id,$data){
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

        $sql = "DELETE FROM tb_request_standard_list WHERE request_standard_id = '$id' AND request_standard_list_id NOT IN ($str) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>