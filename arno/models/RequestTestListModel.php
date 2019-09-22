<?php

require_once("BaseModel.php");
class RequestTestListModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getRequestTestListBy($request_test_id){
        $sql = " SELECT tb_request_test_list.product_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,   
        tb_request_test_list.request_test_list_id,  
        IFNULL(request_standard_list_id,0) as request_standard_list_id,
        IFNULL(request_special_list_id,0) as request_special_list_id,
        IFNULL(request_regrind_list_id,0) as request_regrind_list_id,
        request_test_list_qty, 
        request_test_list_delivery, 
        request_test_list_remark, 
        request_test_list_supplier_qty, 
        request_test_list_supplier_delivery, 
        request_test_list_supplier_remark,
        stock_group_id
        FROM tb_request_test_list 
        LEFT JOIN tb_product ON tb_request_test_list.product_id = tb_product.product_id 
        LEFT JOIN tb_request_standard_list ON tb_request_test_list.request_test_list_id = tb_request_standard_list.request_test_list_id
        LEFT JOIN tb_request_special_list ON tb_request_test_list.request_test_list_id = tb_request_special_list.request_test_list_id
        LEFT JOIN tb_request_regrind_list ON tb_request_test_list.request_test_list_id = tb_request_regrind_list.request_test_list_id
        WHERE request_test_id = '$request_test_id' 
        ORDER BY tb_request_test_list.request_test_list_id 
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


    function insertRequestTestList($data = []){
        $sql = " INSERT INTO tb_request_test_list (
            request_test_id, 
            product_id, 
            request_test_list_qty, 
            request_test_list_delivery, 
            request_test_list_remark, 
            stock_group_id, 
            addby, 
            adddate, 
            updateby, 
            lastupdate
        ) VALUES (
            '".$data['request_test_id']."', 
            '".$data['product_id']."', 
            '".$data['request_test_list_qty']."', 
            '".$data['request_test_list_delivery']."',  
            '".$data['request_test_list_remark']."', 
            '".$data['stock_group_id']."',
            '".$data['addby']."', 
            NOW(), 
            '".$data['updateby']."', 
            NOW() 
        ); 
        ";

        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }

    
    function updateRequestTestListById($data,$id){

        $sql = " UPDATE tb_request_test_list 
            SET request_test_list_supplier_qty = '".$data['request_test_list_supplier_qty']."',
            request_test_list_supplier_delivery = '".$data['request_test_list_supplier_delivery']."', 
            request_test_list_supplier_remark = '".$data['request_test_list_supplier_remark']."'
            WHERE request_test_list_id = '$id'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function updateRequestTestListByIdAdmin($data,$id){

        $sql = " UPDATE tb_request_test_list 
            SET product_id = '".$data['product_id']."', 
            request_test_list_qty = '".$data['request_test_list_qty']."', 
            request_test_list_delivery = '".$data['request_test_list_delivery']."', 
            request_test_list_remark = '".$data['request_test_list_remark']."', 
            stock_group_id = '".$data['stock_group_id']."'
            WHERE request_test_list_id = '$id'
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    
    function updateDeliveryNoteListID($request_test_list_id,$delivery_note_customer_list_id){
        $sql = " UPDATE tb_purchase_request_list 
            SET delivery_note_customer_list_id = '$delivery_note_customer_list_id' 
            WHERE request_test_list_id = '$request_test_list_id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }



    function deleteRequestTestListByID($id){
        $sql = "DELETE FROM tb_request_test_list WHERE request_test_list_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }


    function deleteRequestTestListByRequestTestID($id){

        $sql = " UPDATE tb_request_standard_list SET request_test_list_id = '0' WHERE request_test_list_id (SELECT request_test_list_id FROM tb_request_test_list WHERE request_test_id = '$id') ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " UPDATE tb_request_special_list SET request_test_list_id = '0' WHERE request_test_list_id (SELECT request_test_list_id FROM tb_request_test_list WHERE request_test_id = '$id') ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " UPDATE tb_request_regrind_list SET request_test_list_id = '0' WHERE request_test_list_id (SELECT request_test_list_id FROM tb_request_test_list WHERE request_test_id = '$id') ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);


        $sql = "DELETE FROM tb_request_test_list WHERE request_test_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        

    }

    function deleteRequestTestListByRequestTestIDNotIN($id,$data){
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

        $sql = "UPDATE  tb_request_standard_list SET request_test_list_id = '0'  WHERE request_test_list_id IN (SELECT request_test_list_id FROM tb_request_test_list WHERE request_test_id = '$id' AND request_test_list_id NOT IN ($str)) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = "UPDATE  tb_request_special_list SET request_test_list_id = '0'  WHERE request_test_list_id IN (SELECT request_test_list_id FROM tb_request_test_list WHERE request_test_id = '$id' AND request_test_list_id NOT IN ($str)) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 

        $sql = "UPDATE  tb_request_regrind_list SET request_test_list_id = '0'  WHERE request_test_list_id IN (SELECT request_test_list_id FROM tb_request_test_list WHERE request_test_id = '$id' AND request_test_list_id NOT IN ($str)) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 

        $sql = "DELETE FROM tb_request_test_list WHERE request_test_id = '$id' AND request_test_list_id NOT IN ($str) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);


    }
}
?>