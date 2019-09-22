<?php

require_once("BaseModel.php");
class RequestTestModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getRequestTestByKeyword($date_start = "",$date_end = "",$supplier_id = "",$keyword = "",$user_id = ""){

        $str_supplier = "";
        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(request_test_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(request_test_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(request_test_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(request_test_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND employee_id = '$user_id' ";
        }

        if($supplier_id != ""){
            $str_supplier = "AND tb2.supplier_id = '$supplier_id' ";
        }


        $sql = "  SELECT  request_test_id, 
        tb.employee_id , 
        request_test_date,  
        supplier_name_th, supplier_name_en, 
        IFNULL((
            SELECT COUNT(*) FROM tb_request_test WHERE request_test_rewrite_id = tb.request_test_id 
        ),0) as count_rewrite,
        request_test_rewrite_no,
        request_test_status,
        request_test_code,  
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as request_name,   
        request_test_cancelled 
        FROM tb_request_test  as tb
        LEFT JOIN tb_user as tb1 ON tb.employee_id = tb1.user_id  
        LEFT JOIN tb_supplier as tb4 ON tb.supplier_id = tb4.supplier_id 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%')  
            OR  request_test_code LIKE ('%$keyword%') 
            OR  supplier_name_en LIKE ('%$keyword%') 
        ) 
        $str_date 
        $str_user
        ORDER BY STR_TO_DATE(`request_test_date`,'%d-%m-%Y %H:%i:%s') DESC 

         ";  
        //  echo "<pre>";
        //  echo $sql;
        //  echo "</pre>";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getRequestTestBy($date_start = "",$date_end = "",$supplier_id = "",$keyword = "",$user_id = ""){

        $str_supplier = "";
        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(request_test_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(request_test_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(request_test_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(request_test_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND employee_id = '$user_id' ";
        }

        if($supplier_id != ""){
            $str_supplier = "AND tb2.supplier_id = '$supplier_id' ";
        }


        $sql = "  SELECT * FROM ( 
                    
                    (
                        SELECT 
                        '1_request_standard' as request_type,
                        request_standard_id as 'id',
                        request_standard_code as 'code',
                        request_standard_date as 'request_date',   
                        request_standard_rewrite_no as 'request_rewrite_no',   
                        request_standard_remark as 'request_remark',   
                        IFNULL(CONCAT(tb_user.user_name,' ',tb_user.user_lastname),'-') as request_name,
                        request_standard_accept_status as 'request_status' 
                        FROM tb_request_standard
                        LEFT JOIN tb_user ON tb_request_standard.employee_id = tb_user.user_id 
                        LEFT JOIN tb_supplier ON tb_request_standard.supplier_id = tb_supplier.supplier_id 
                        WHERE request_standard_accept_status = 'Waiting'
                    )
                UNION ALL
                    (
                        SELECT 
                        '2_request_special' as request_type,
                        request_special_id as 'id',
                        request_special_code as 'code',
                        request_special_date as 'request_date',  
                        request_special_rewrite_no as 'request_rewrite_no',   
                        request_special_remark as 'request_remark',  
                        IFNULL(CONCAT(tb_user.user_name,' ',tb_user.user_lastname),'-') as request_name,
                        request_special_accept_status as 'request_status' 
                        FROM tb_request_special
                        LEFT JOIN tb_user ON tb_request_special.employee_id = tb_user.user_id 
                        LEFT JOIN tb_supplier ON tb_request_special.supplier_id = tb_supplier.supplier_id 
                        WHERE request_special_accept_status = 'Waiting'
                    )
                UNION ALL
                    (
                        SELECT 
                        '3_request_regrind' as request_type,
                        request_regrind_id as 'id',
                        request_regrind_code as 'code',
                        request_regrind_date as 'request_date',  
                        request_regrind_rewrite_no as 'request_rewrite_no',   
                        request_regrind_remark as 'request_remark',  
                        IFNULL(CONCAT(tb_user.user_name,' ',tb_user.user_lastname),'-') as request_name,
                        request_regrind_accept_status as 'request_status' 
                        FROM tb_request_regrind
                        LEFT JOIN tb_user ON tb_request_regrind.employee_id = tb_user.user_id 
                        LEFT JOIN tb_supplier ON tb_request_regrind.supplier_id = tb_supplier.supplier_id 
                        WHERE request_regrind_accept_status = 'Waiting' 
                    )
                    
                ) AS tb_data
                ORDER BY STR_TO_DATE(`request_date`,'%d-%m-%Y %H:%i:%s') DESC,request_type,id DESC
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

    function getRequestTestByID($id){
        $sql = " SELECT * 
        FROM tb_request_test 
        LEFT JOIN tb_supplier ON tb_request_test.supplier_id = tb_supplier.supplier_id 
        LEFT JOIN tb_user ON tb_request_test.employee_id = tb_user.user_id 
        WHERE request_test_id = '$id' 
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

    function getRequestTestViewByID($id){
        $sql = " SELECT *   
        FROM tb_request_test 
        LEFT JOIN tb_user ON tb_request_test.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_supplier ON tb_request_test.supplier_id = tb_supplier.supplier_id 
        WHERE request_test_id = '$id' 
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
    function getRequestTestLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(request_test_code,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  request_test_lastcode 
        FROM tb_request_test 
        WHERE request_test_code LIKE ('$id%') 
        ";

        //echo $sql;

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['request_test_lastcode'];
        }

    }

   
    function cancelRequestTestByID($id){
        $sql = " UPDATE tb_request_test SET 
        request_test_cancelled = '1', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE request_test_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function uncancelRequestTestByID($id){
        $sql = " UPDATE tb_request_test SET 
        request_test_cancelled = '0', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE request_test_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function updateRequestTestByID($id,$data = []){
        $sql = " UPDATE tb_request_test SET 
        supplier_id = '".$data['supplier_id']."', 
        employee_id = '".$data['employee_id']."',  
        request_test_code = '".$data['request_test_code']."',  
        request_test_date = '".$data['request_test_date']."', 
        request_test_status = '".$data['request_test_status']."',  
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE request_test_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }


    function updateRequestTestStatusByID($id,$data = []){
        if ($data['updateby'] != ""){
            $str = "updateby = '".$data['updateby']."', ";
        }
        
        $sql = " UPDATE tb_request_test SET 
        request_test_status = '".$data['request_test_status']."', 
        $str 
        lastupdate = NOW() 
        WHERE request_test_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function getSupplierOrder(){

        $sql = "SELECT supplier_id, supplier_name_en , supplier_name_th 
                FROM tb_supplier 
                WHERE supplier_id IN ( 
                    SELECT DISTINCT supplier_id 
                    FROM tb_request_standard_list  LEFT JOIN tb_request_standard ON tb_request_standard_list.request_standard_id = tb_request_standard.request_standard_id
                    WHERE request_test_list_id = 0 
                    AND tb_request_standard.request_standard_cancelled = 0 
                    AND request_standard_accept_status = 'Approve' 
                    AND purchase_order_open = 0 
                    UNION 
                    SELECT DISTINCT supplier_id 
                    FROM tb_request_special_list  LEFT JOIN tb_request_special ON tb_request_special_list.request_special_id = tb_request_special.request_special_id
                    WHERE request_test_list_id = 0 
                    AND tb_request_special.request_special_cancelled = 0 
                    AND request_special_accept_status = 'Approve' 
                    AND purchase_order_open = 0 
                    UNION 
                    SELECT DISTINCT supplier_id 
                    FROM tb_request_regrind_list  LEFT JOIN tb_request_regrind ON tb_request_regrind_list.request_regrind_id = tb_request_regrind.request_regrind_id
                    WHERE request_test_list_id = 0 
                    AND tb_request_regrind.request_regrind_cancelled = 0 
                    AND request_regrind_accept_status = 'Approve'  
                    AND purchase_order_open = 0 
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

    function generateRequestTestListBySupplierId($supplier_id, $data_str = [], $data_sptr = [], $data_rtr = [], $search = ""){

        $str_str ='0';

        if(is_array($data_str)){ 
            for($i=0; $i < count($data_str) ;$i++){
                $str_str .= $data_str[$i];
                if($i + 1 < count($data_str)){
                    $str_str .= ',';
                }
            }
        }else if ($data_str != ''){
            $str_str = $data_str;
        }else{
            $str_str='0';
        }


        $str_sptr ='0';

        if(is_array($data_sptr)){ 
            for($i=0; $i < count($data_sptr) ;$i++){
                $str_sptr .= $data_sptr[$i];
                if($i + 1 < count($data_sptr)){
                    $str_sptr .= ',';
                }
            }
        }else if ($data_sptr != ''){
            $str_sptr = $data_sptr;
        }else{
            $str_sptr='0';
        }


        $str_rtr ='0';

        if(is_array($data_rtr)){ 
            for($i=0; $i < count($data_rtr) ;$i++){
                $str_rtr .= $data_rtr[$i];
                if($i + 1 < count($data_rtr)){
                    $str_rtr .= ',';
                }
            }
        }else if ($data_rtr != ''){
            $str_rtr = $data_rtr;
        }else{
            $str_rtr='0';
        }



        $sql_request = "SELECT tb_request_standard_list.product_id, 
        request_standard_list_id, 
        '0' as request_special_list_id,
        '0' as request_regrind_list_id,
        '0' as request_test_list_id,
        supplier_id,
        CONCAT(product_code_first,product_code) as product_code, 
        product_name, 
        request_standard_list_qty as request_test_list_qty,  
        CONCAT('STR : ',request_standard_code) as request_test_list_remark 
        FROM tb_request_standard 
        LEFT JOIN tb_request_standard_list ON tb_request_standard.request_standard_id = tb_request_standard_list.request_standard_id 
        LEFT JOIN tb_product ON tb_request_standard_list.product_id = tb_product.product_id 
        WHERE supplier_id = '$supplier_id' 
        AND request_standard_accept_status = 'Approve'
        AND request_test_list_id = 0 
        AND purchase_order_open = 0 
        AND request_standard_list_id NOT IN ($str_str) 
        AND request_standard_code LIKE ('%$search%')  ";

        $data = []; 
        if ($result = mysqli_query(static::$db,$sql_request, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }



        $sql_request = "SELECT tb_request_special_list.product_id, 
        '0' as request_standard_list_id,
        request_special_list_id, 
        '0' as request_regrind_list_id, 
        '0' as request_test_list_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name, 
        request_special_list_qty as request_test_list_qty,  
        CONCAT('STR : ',request_special_code) as request_test_list_remark 
        FROM tb_request_standard 
        LEFT JOIN tb_request_special_list ON tb_request_standard.request_special_id = tb_request_special_list.request_special_id 
        LEFT JOIN tb_product ON tb_request_special_list.product_id = tb_product.product_id 
        WHERE supplier_id = '$supplier_id' 
        AND request_special_accept_status = 'Approve'
        AND request_test_list_id = 0 
        AND purchase_order_open = 0 
        AND request_special_list_id NOT IN ($str_sptr) 
        AND request_special_code LIKE ('%$search%')  ";

        
        if ($result = mysqli_query(static::$db,$sql_request, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }


        
        $sql_request = "SELECT tb_request_regrind_list.product_id,  
        '0' as request_standard_list_id, 
        '0' as request_special_list_id, 
        request_regrind_list_id,  
        '0' as request_test_list_id,  
        CONCAT(product_code_first,product_code) as product_code, 
        product_name, 
        request_regrind_list_qty as request_test_list_qty,  
        CONCAT('STR : ',request_regrind_code) as request_test_list_remark 
        FROM tb_request_standard 
        LEFT JOIN tb_request_regrind_list ON tb_request_standard.request_regrind_id = tb_request_regrind_list.request_regrind_id 
        LEFT JOIN tb_product ON tb_request_regrind_list.product_id = tb_product.product_id 
        WHERE supplier_id = '$supplier_id'  
        AND request_regrind_accept_status = 'Approve'
        AND request_test_list_id = 0 
        AND purchase_order_open = 0 
        AND request_regrind_list_id NOT IN ($str_rtr) 
        AND request_regrind_code LIKE ('%$search%')  ";

        //echo $sql_request."<br><br>";
 
        if ($result = mysqli_query(static::$db,$sql_request, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }


        return $data;
    }

    function insertRequestTest($data = []){
        $sql = " INSERT INTO tb_request_test (
            supplier_id,
            employee_id,
            request_test_rewrite_id,
            request_test_rewrite_no,
            request_test_status,
            request_test_code, 
            request_test_date, 
            addby,
            adddate,
            updateby,
            lastupdate) 
        VALUES ('".
        $data['supplier_id']."','".
        $data['employee_id']."','".
        $data['request_test_rewrite_id']."','".
        $data['request_test_rewrite_no']."','". 
        $data['request_test_status']."','". 
        $data['request_test_code']."','". 
        $data['request_test_date']."','". 
        $data['addby']."',".
        "NOW(),'".
        $data['addby'].
        "',NOW()); 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }


    function deleteRequestTestByID($id){

        $sql = " UPDATE tb_request_standard_list SET request_test_list_id = '0' WHERE request_test_list_id IN (SELECT request_test_list_id FROM tb_request_test_list WHERE request_test_id = '$id') ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 

        $sql = " UPDATE tb_request_special_list SET request_test_list_id = '0' WHERE request_test_list_id IN (SELECT request_test_list_id FROM tb_request_test_list WHERE request_test_id = '$id') ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " UPDATE tb_request_regrind_list SET request_test_list_id = '0' WHERE request_test_list_id IN (SELECT request_test_list_id FROM tb_request_test_list WHERE request_test_id = '$id') ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_request_test WHERE request_test_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_request_test_list WHERE request_test_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>