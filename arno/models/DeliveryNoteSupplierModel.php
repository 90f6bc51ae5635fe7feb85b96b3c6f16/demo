<?php

require_once("BaseModel.php");
class DeliveryNoteSupplierModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getDeliveryNoteSupplierBy($date_start = "",$date_end = "",$supplier_id = "",$keyword = "",$user_id = ""){

        $str_supplier = "";
        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(delivery_note_supplier_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(delivery_note_supplier_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(delivery_note_supplier_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(delivery_note_supplier_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND tb_delivery_note_supplier.employee_id = '$user_id' ";
        }

        if($supplier_id != ""){
            $str_supplier = "AND tb2.supplier_id = '$supplier_id' ";
        }


        $sql = " SELECT delivery_note_supplier_id, 
        tb_delivery_note_supplier.employee_id,
        delivery_note_supplier_code, 
        delivery_note_supplier_date, 
        delivery_note_supplier_file,
        contact_name,
        delivery_note_supplier_remark,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        IFNULL(CONCAT(tb2.supplier_name_en,' (',tb2.supplier_name_th,')'),'-') as supplier_name 
        FROM tb_delivery_note_supplier 
        LEFT JOIN tb_user as tb1 ON tb_delivery_note_supplier.employee_id = tb1.user_id 
        LEFT JOIN tb_supplier as tb2 ON tb_delivery_note_supplier.supplier_id = tb2.supplier_id 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%') 
            OR  contact_name LIKE ('%$keyword%') 
            OR  delivery_note_supplier_code LIKE ('%$keyword%') 
        ) 
        $str_supplier 
        $str_date 
        $str_user  
        ORDER BY STR_TO_DATE(delivery_note_supplier_date,'%d-%m-%Y %H:%i:%s') DESC 
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
    function getDeliveryNoteSupplierByMobile($date_start = "",$date_end = "",$supplier_id = "",$keyword = "",$user_id = ""){

        $str_supplier = "";
        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(delivery_note_supplier_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(delivery_note_supplier_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(delivery_note_supplier_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(delivery_note_supplier_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND tb_delivery_note_supplier.employee_id = '$user_id' ";
        }

        if($supplier_id != ""){
            $str_supplier = "AND tb2.supplier_id = '$supplier_id' ";
        }


        $sql = " SELECT delivery_note_supplier_id, 
        tb_delivery_note_supplier.employee_id,
        delivery_note_supplier_code, 
        delivery_note_supplier_date, 
        delivery_note_supplier_file,
        contact_name,
        delivery_note_supplier_remark,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        IFNULL(tb2.supplier_name_en,'-') as supplier_name 
        FROM tb_delivery_note_supplier 
        LEFT JOIN tb_user as tb1 ON tb_delivery_note_supplier.employee_id = tb1.user_id 
        LEFT JOIN tb_supplier as tb2 ON tb_delivery_note_supplier.supplier_id = tb2.supplier_id 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%') 
            OR  contact_name LIKE ('%$keyword%') 
            OR  delivery_note_supplier_code LIKE ('%$keyword%') 
        ) 
        $str_supplier 
        $str_date 
        $str_user  
        ORDER BY STR_TO_DATE(delivery_note_supplier_date,'%d-%m-%Y %H:%i:%s') DESC 
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

    function getDeliveryNoteSupplierByID($id){
        $sql = " SELECT * 
        FROM tb_delivery_note_supplier 
        LEFT JOIN tb_supplier ON tb_delivery_note_supplier.supplier_id = tb_supplier.supplier_id 
        LEFT JOIN tb_user ON tb_delivery_note_supplier.employee_id = tb_user.user_id 
        WHERE delivery_note_supplier_id = '$id' 
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

    function getDeliveryNoteSupplierViewByID($id){
        $sql = " SELECT *   
        FROM tb_delivery_note_supplier 
        LEFT JOIN tb_user ON tb_delivery_note_supplier.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_supplier ON tb_delivery_note_supplier.supplier_id = tb_supplier.supplier_id 
        WHERE delivery_note_supplier_id = '$id' 
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

    function getDeliveryNoteSupplierLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(delivery_note_supplier_code,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  delivery_note_supplier_lastcode 
        FROM tb_delivery_note_supplier 
        WHERE delivery_note_supplier_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['delivery_note_supplier_lastcode'];
        }

    }


    
    function getSupplierOrder(){

        $sql = "SELECT supplier_id, supplier_name_en , supplier_name_th 
                FROM tb_supplier 
                WHERE supplier_id IN ( 
                    SELECT DISTINCT supplier_id 
                    FROM  tb_request_test_list 
                    LEFT JOIN  tb_delivery_note_supplier_list ON tb_request_test_list.request_test_list_id = tb_delivery_note_supplier_list.request_test_list_id 
                    LEFT JOIN tb_request_test  ON tb_request_test_list.request_test_id = tb_request_test.request_test_id 
                    GROUP BY tb_request_test_list.request_test_list_id 
                    HAVING SUM(IFNULL(request_test_list_qty,0)) - SUM(IFNULL(delivery_note_supplier_list_qty,0)) > 0 
                ) 
                GROUP BY supplier_id 
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


    function generateDeliveryNoteSupplierListBySupplierId($supplier_id, $data_rt = [], $search = ""){

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
        '0' as delivery_note_supplier_list_id,
        CONCAT(product_code_first,product_code) as product_code, 
        product_name, 
        request_test_list_qty as delivery_note_supplier_list_qty,  
        CONCAT('RT : ',request_test_code) as delivery_note_supplier_list_remark 
        FROM tb_request_test 
        LEFT JOIN tb_request_test_list ON tb_request_test.request_test_id = tb_request_test_list.request_test_id 
        LEFT JOIN tb_delivery_note_supplier_list ON tb_request_test_list.request_test_list_id = tb_delivery_note_supplier_list.request_test_list_id 
        LEFT JOIN tb_product ON tb_request_test_list.product_id = tb_product.product_id 
        WHERE supplier_id = '$supplier_id' 
        AND tb_request_test_list.request_test_list_id NOT IN ($str_rt) 
        AND request_test_code LIKE ('%$search%') 
        GROUP BY  tb_request_test_list.request_test_list_id 
        HAVING SUM(IFNULL(request_test_list_qty,0)) - SUM(IFNULL(delivery_note_supplier_list_qty,0)) > 0 ";

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

   
    function updateDeliveryNoteSupplierByID($id,$data = []){
        $sql = " UPDATE tb_delivery_note_supplier SET 
        supplier_id = '".$data['supplier_id']."', 
        employee_id = '".$data['employee_id']."', 
        
        delivery_note_supplier_date = '".$data['delivery_note_supplier_date']."', 
        delivery_note_supplier_remark = '".static::$db->real_escape_string($data['delivery_note_supplier_remark'])."', 
        delivery_note_supplier_file = '".static::$db->real_escape_string($data['delivery_note_supplier_file'])."', 
        employee_signature = '".$data['employee_signature']."', 
        contact_name = '".static::$db->real_escape_string($data['contact_name'])."', 
        contact_signature = '".$data['contact_signature']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE delivery_note_supplier_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }



    function insertDeliveryNoteSupplier($data = []){
        $sql = " INSERT INTO tb_delivery_note_supplier (
            supplier_id,
            employee_id,
            delivery_note_supplier_code,
            delivery_note_supplier_date,
            delivery_note_supplier_remark,
            delivery_note_supplier_file,
            employee_signature,
            contact_name,
            contact_signature,
            addby,
            adddate ) 
        VALUES ('".
        $data['supplier_id']."','".
        $data['employee_id']."','".
        $data['delivery_note_supplier_code']."','".
        $data['delivery_note_supplier_date']."','".
        static::$db->real_escape_string($data['delivery_note_supplier_remark'])."','".
        static::$db->real_escape_string($data['delivery_note_supplier_file'])."','".
        $data['employee_signature']."','".
        static::$db->real_escape_string($data['contact_name'])."','".
        $data['contact_signature']."','".
        $data['addby']."',".
        " NOW() 
        );";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }



    function deleteDeliveryNoteSupplierByID($id){

        $sql = " DELETE FROM tb_delivery_note_supplier WHERE delivery_note_supplier_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        $sql = " DELETE FROM tb_delivery_note_supplier_list WHERE delivery_note_supplier_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }


}
?>