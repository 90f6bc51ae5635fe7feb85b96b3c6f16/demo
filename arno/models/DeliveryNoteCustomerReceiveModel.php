<?php

require_once("BaseModel.php");
class DeliveryNoteCustomerReceiveModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getDeliveryNoteCustomerReceiveBy($date_start = "",$date_end = "",$customer_id = "",$keyword = "",$user_id = ""){

        $str_customer = "";
        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(delivery_note_customer_receive_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(delivery_note_customer_receive_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(delivery_note_customer_receive_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(delivery_note_customer_receive_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND tb_delivery_note_customer_receive.employee_id = '$user_id' ";
        }

        if($customer_id != ""){
            $str_customer = "AND tb2.customer_id = '$customer_id' ";
        }


        $sql = " SELECT delivery_note_customer_receive_id, 
        tb_delivery_note_customer_receive.employee_id,
        delivery_note_customer_receive_code, 
        delivery_note_customer_receive_date, 
        delivery_note_customer_receive_file,
        contact_name,
        delivery_note_customer_receive_remark,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        IFNULL(CONCAT(tb2.customer_name_en,' (',tb2.customer_name_th,')'),'-') as customer_name 
        FROM tb_delivery_note_customer_receive 
        LEFT JOIN tb_user as tb1 ON tb_delivery_note_customer_receive.employee_id = tb1.user_id 
        LEFT JOIN tb_customer as tb2 ON tb_delivery_note_customer_receive.customer_id = tb2.customer_id 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%') 
            OR  contact_name LIKE ('%$keyword%') 
            OR  delivery_note_customer_receive_code LIKE ('%$keyword%') 
        ) 
        $str_customer 
        $str_date 
        $str_user  
        ORDER BY STR_TO_DATE(delivery_note_customer_receive_date,'%d-%m-%Y %H:%i:%s') DESC 
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
    function getDeliveryNoteCustomerReceiveByMobile($date_start = "",$date_end = "",$customer_id = "",$keyword = "",$user_id = ""){

        $str_customer = "";
        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(delivery_note_customer_receive_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(delivery_note_customer_receive_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(delivery_note_customer_receive_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(delivery_note_customer_receive_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND tb_delivery_note_customer_receive.employee_id = '$user_id' ";
        }

        if($customer_id != ""){
            $str_customer = "AND tb2.customer_id = '$customer_id' ";
        }


        $sql = " SELECT delivery_note_customer_receive_id, 
        tb_delivery_note_customer_receive.employee_id,
        delivery_note_customer_receive_code, 
        delivery_note_customer_receive_date, 
        delivery_note_customer_receive_file,
        contact_name,
        delivery_note_customer_receive_remark,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        IFNULL(tb2.customer_name_en,'-') as customer_name 
        FROM tb_delivery_note_customer_receive 
        LEFT JOIN tb_user as tb1 ON tb_delivery_note_customer_receive.employee_id = tb1.user_id 
        LEFT JOIN tb_customer as tb2 ON tb_delivery_note_customer_receive.customer_id = tb2.customer_id 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%') 
            OR  contact_name LIKE ('%$keyword%') 
            OR  delivery_note_customer_receive_code LIKE ('%$keyword%') 
        ) 
        $str_customer 
        $str_date 
        $str_user  
        ORDER BY STR_TO_DATE(delivery_note_customer_receive_date,'%d-%m-%Y %H:%i:%s') DESC 
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

    function getDeliveryNoteCustomerReceiveByID($id){
        $sql = " SELECT * 
        FROM tb_delivery_note_customer_receive 
        LEFT JOIN tb_customer ON tb_delivery_note_customer_receive.customer_id = tb_customer.customer_id 
        LEFT JOIN tb_user ON tb_delivery_note_customer_receive.employee_id = tb_user.user_id 
        WHERE delivery_note_customer_receive_id = '$id' 
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

    function getDeliveryNoteCustomerReceiveViewByID($id){
        $sql = " SELECT *   
        FROM tb_delivery_note_customer_receive 
        LEFT JOIN tb_user ON tb_delivery_note_customer_receive.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_customer ON tb_delivery_note_customer_receive.customer_id = tb_customer.customer_id 
        WHERE delivery_note_customer_receive_id = '$id' 
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

    function getDeliveryNoteCustomerReceiveLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(delivery_note_customer_receive_code,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  delivery_note_customer_receive_lastcode 
        FROM tb_delivery_note_customer_receive 
        WHERE delivery_note_customer_receive_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['delivery_note_customer_receive_lastcode'];
        }

    }


    
    function getCustomerOrder(){

        $sql = "SELECT customer_id, customer_name_en , customer_name_th 
                FROM tb_customer 
                WHERE customer_id IN ( 
                    SELECT DISTINCT customer_id 
                    FROM  tb_request_test_list 
                    LEFT JOIN  tb_delivery_note_customer_receive_list ON tb_request_test_list.request_test_list_id = tb_delivery_note_customer_receive_list.request_test_list_id 
                    LEFT JOIN tb_request_test  ON tb_request_test_list.request_test_id = tb_request_test.request_test_id 
                    GROUP BY tb_request_test_list.request_test_list_id 
                    HAVING SUM(IFNULL(request_test_list_qty,0)) - SUM(IFNULL(delivery_note_customer_receive_list_qty,0)) > 0 
                ) 
                GROUP BY customer_id 
        ";

        $data = [];
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }
        return $data;
    }


    function generateDeliveryNoteCustomerReceiveListByCustomerId($customer_id, $data_rt = [], $search = ""){

        $str_rt ='0';

        if(is_array($data_rt)){ 
            for($i=0; $i < count($data_rt) ;$i++){
                $str_rt .= $data_rt[$i];
                if($i + 1 < count($data_rt)){
                    $str_rt .= ',';
                }
            }
        }else if ($data_rt != ''){
            $str_rt = $data_rt;
        }else{
            $str_rt='0';
        }



        $sql_request = "SELECT tb_request_test_list.product_id, 
        tb_request_test_list.request_test_list_id , 
        '0' as delivery_note_customer_receive_list_id,
        CONCAT(product_code_first,product_code) as product_code, 
        product_name, 
        request_test_list_qty as delivery_note_customer_receive_list_qty,  
        CONCAT('RT : ',request_test_code) as delivery_note_customer_receive_list_remark 
        FROM tb_request_test 
        LEFT JOIN tb_request_test_list ON tb_request_test.request_test_id = tb_request_test_list.request_test_id 
        LEFT JOIN tb_delivery_note_customer_receive_list ON tb_request_test_list.request_test_list_id = tb_delivery_note_customer_receive_list.request_test_list_id 
        LEFT JOIN tb_product ON tb_request_test_list.product_id = tb_product.product_id 
        WHERE customer_id = '$customer_id' 
        AND tb_request_test_list.request_test_list_id NOT IN ($str_rt) 
        AND request_test_code LIKE ('%$search%') 
        GROUP BY  tb_request_test_list.request_test_list_id 
        HAVING SUM(IFNULL(request_test_list_qty,0)) - SUM(IFNULL(delivery_note_customer_receive_list_qty,0)) > 0 ";

        $data = [];

        //echo $sql_request."<br><br>";

        if ($result = mysqli_query(static::$db,$sql_request, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }


        return $data;
    }

   
    function updateDeliveryNoteCustomerReceiveByID($id,$data = []){
        $sql = " UPDATE tb_delivery_note_customer_receive SET 
        customer_id = '".$data['customer_id']."', 
        employee_id = '".$data['employee_id']."', 
        
        delivery_note_customer_receive_date = '".$data['delivery_note_customer_receive_date']."', 
        delivery_note_customer_receive_remark = '".static::$db->real_escape_string($data['delivery_note_customer_receive_remark'])."', 
        delivery_note_customer_receive_file = '".static::$db->real_escape_string($data['delivery_note_customer_receive_file'])."', 
        employee_signature = '".$data['employee_signature']."', 
        contact_name = '".static::$db->real_escape_string($data['contact_name'])."', 
        contact_signature = '".$data['contact_signature']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE delivery_note_customer_receive_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }



    function insertDeliveryNoteCustomerReceive($data = []){
        $sql = " INSERT INTO tb_delivery_note_customer_receive (
            customer_id,
            employee_id,
            delivery_note_customer_receive_code,
            delivery_note_customer_receive_date,
            delivery_note_customer_receive_remark,
            delivery_note_customer_receive_file,
            employee_signature,
            contact_name,
            contact_signature,
            addby,
            adddate ) 
        VALUES ('".
        $data['customer_id']."','".
        $data['employee_id']."','".
        $data['delivery_note_customer_receive_code']."','".
        $data['delivery_note_customer_receive_date']."','".
        static::$db->real_escape_string($data['delivery_note_customer_receive_remark'])."','".
        static::$db->real_escape_string($data['delivery_note_customer_receive_file'])."','".
        $data['employee_signature']."','".
        static::$db->real_escape_string($data['contact_name'])."','".
        $data['contact_signature']."','".
        $data['addby']."',".
        " NOW() 
        );";

        // return $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }



    function deleteDeliveryNoteCustomerReceiveByID($id){

        $sql = " DELETE FROM tb_delivery_note_customer_receive WHERE delivery_note_customer_receive_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        $sql = " DELETE FROM tb_delivery_note_customer_receive_list WHERE delivery_note_customer_receive_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }


}
?>