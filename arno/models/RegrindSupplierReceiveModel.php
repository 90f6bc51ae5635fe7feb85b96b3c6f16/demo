<?php

require_once("BaseModel.php");
class RegrindSupplierReceiveModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getRegrindSupplierReceiveBy($date_start = "",$date_end = "",$supplier_id = "",$keyword = "",$user_id = ""){

        $str_supplier = "";
        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(regrind_supplier_receive_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(regrind_supplier_receive_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(regrind_supplier_receive_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(regrind_supplier_receive_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND tb_regrind_supplier_receive.employee_id  = '$user_id' ";
        }

        if($supplier_id != ""){
            $str_supplier = "AND tb2.supplier_id = '$supplier_id' ";
        }


        $sql = " SELECT regrind_supplier_receive_id, 
        tb_regrind_supplier_receive.employee_id ,
        regrind_supplier_receive_code, 
        regrind_supplier_receive_date, 
        regrind_supplier_receive_file,
        contact_name,
        regrind_supplier_receive_remark,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        IFNULL(CONCAT(tb2.supplier_name_en,' (',tb2.supplier_name_th,')'),'-') as supplier_name 
        FROM tb_regrind_supplier_receive 
        LEFT JOIN tb_user as tb1 ON tb_regrind_supplier_receive.employee_id = tb1.user_id 
        LEFT JOIN tb_supplier as tb2 ON tb_regrind_supplier_receive.supplier_id = tb2.supplier_id 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%') 
            OR  contact_name LIKE ('%$keyword%') 
            OR  regrind_supplier_receive_code LIKE ('%$keyword%') 
        ) 
        $str_supplier 
        $str_date 
        $str_user  
        ORDER BY STR_TO_DATE(regrind_supplier_receive_date,'%Y-%m-%d %H:%i:%s') DESC 
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

    function getRegrindSupplierReceiveByID($id){
        $sql = " SELECT 
        regrind_supplier_receive_id,
        regrind_supplier_receive_code,
        regrind_supplier_receive_date,
        regrind_supplier_receive_remark,
        contact_name,
        employee_id,
        supplier_name_en,
        CONCAT(tb_user.user_name,' ',tb_user.user_lastname) as 'employee_name',
        tb_regrind_supplier_receive.supplier_id
        FROM tb_regrind_supplier_receive 
        LEFT JOIN tb_supplier ON tb_regrind_supplier_receive.supplier_id = tb_supplier.supplier_id 
        LEFT JOIN tb_user ON tb_regrind_supplier_receive.employee_id = tb_user.user_id 
        WHERE regrind_supplier_receive_id = '$id' 
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

    function getRegrindSupplierReceiveViewByID($id){
        $sql = " SELECT *   
        FROM tb_regrind_supplier_receive 
        LEFT JOIN tb_user ON tb_regrind_supplier_receive.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user.user_position_id 
        LEFT JOIN tb_supplier ON tb_regrind_supplier_receive.supplier_id = tb_supplier.supplier_id 
        WHERE regrind_supplier_receive_id = '$id' 
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

    function getRegrindSupplier($employee_id = ""){

        $sql = "SELECT tb_regrind_supplier.regrind_supplier_id, regrind_supplier_code, tb_supplier.supplier_id, supplier_name_en , supplier_name_th 
                FROM tb_regrind_supplier 
                LEFT JOIN tb_supplier 
                ON tb_regrind_supplier.supplier_id = tb_supplier.supplier_id 
                LEFT JOIN tb_regrind_supplier_list 
                ON tb_regrind_supplier.regrind_supplier_id = tb_regrind_supplier_list.regrind_supplier_id 
                WHERE regrind_supplier_list_id IN ( 
                    SELECT tb_regrind_supplier_list.regrind_supplier_list_id 
                    FROM tb_regrind_supplier_list  
                    LEFT JOIN tb_regrind_supplier_receive_list ON  tb_regrind_supplier_list.regrind_supplier_list_id = tb_regrind_supplier_receive_list.regrind_supplier_list_id 
                    GROUP BY tb_regrind_supplier_list.regrind_supplier_list_id 
                    HAVING IFNULL(SUM(regrind_supplier_receive_list_qty),0)  < AVG(regrind_supplier_list_qty)  
                ) 
                GROUP BY tb_regrind_supplier.regrind_supplier_id
        ";

        //echo $sql;
        $data = [];
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }
        return $data;
    }


    function getSupplier($employee_id = ""){

        $sql = "SELECT tb_supplier.supplier_id, supplier_name_en , supplier_name_th 
                FROM tb_supplier 
                WHERE supplier_id IN ( 
                    SELECT DISTINCT supplier_id 
                    FROM tb_regrind_supplier 
                    LEFT JOIN tb_regrind_supplier_list 
                    ON tb_regrind_supplier.regrind_supplier_id = tb_regrind_supplier_list.regrind_supplier_id 
                    WHERE regrind_supplier_list_id IN ( 
                        SELECT tb_regrind_supplier_list.regrind_supplier_list_id 
                        FROM tb_regrind_supplier_list   
                        LEFT JOIN tb_regrind_supplier_receive_list ON  tb_regrind_supplier_list.regrind_supplier_list_id = tb_regrind_supplier_receive_list.regrind_supplier_list_id 
                        GROUP BY tb_regrind_supplier_list.regrind_supplier_list_id 
                        HAVING IFNULL(SUM(regrind_supplier_receive_list_qty),0) < AVG(regrind_supplier_list_qty) 
                    ) 
                ) 
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

    function generateRegrindSupplierReceiveListBySupplierId($supplier_id, $user_id, $data = []){

        $str ="";

        if(is_array($data)){ 
            for($i=0; $i < count($data) ;$i++){
                $str .= $data[$i]['regrind_supplier_list_id'];
                if($i + 1 < count($data)){
                    $str .= ',';
                }
            }
        }else if ($data != ''){
            $str = $data;
        }else{
            $str="";
        }

        if($regrind_supplier_id != ""){
            $str_po = "AND tb_regrind_supplier.regrind_supplier_id = '$regrind_supplier_id' ";
        }

        $sql_supplier = "SELECT tb2.product_id, 
        tb2.regrind_supplier_list_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,  
         
        IFNULL(regrind_supplier_list_qty 
            - (IFNULL((
                SELECT SUM(regrind_supplier_receive_list_qty) 
                FROM tb_regrind_supplier_receive_list 
                WHERE regrind_supplier_list_id = tb2.regrind_supplier_list_id 
            ),0)+
            IFNULL((
                SELECT SUM(regrind_supplier_receive_list_scrap_qty) 
                FROM tb_regrind_supplier_receive_list 
                WHERE regrind_supplier_list_id = tb2.regrind_supplier_list_id 
            ),0) 
            )
         ,0) as regrind_supplier_receive_list_qty,  
        CONCAT('Order for supplier PO : ',regrind_supplier_code) as regrind_supplier_receive_list_remark 
        FROM tb_regrind_supplier 
        LEFT JOIN tb_regrind_supplier_list as tb2 ON tb_regrind_supplier.regrind_supplier_id = tb2.regrind_supplier_id 
        LEFT JOIN tb_product ON tb2.product_id = tb_product.product_id
         
        WHERE tb_regrind_supplier.supplier_id = '$supplier_id' 
        $str_po 
        AND tb2.regrind_supplier_list_id  IN ($str) 
        AND tb2.regrind_supplier_list_id IN (
            SELECT tb_regrind_supplier_list.regrind_supplier_list_id
            FROM tb_regrind_supplier_list  
            LEFT JOIN tb_regrind_supplier_receive_list ON  tb_regrind_supplier_list.regrind_supplier_list_id = tb_regrind_supplier_receive_list.regrind_supplier_list_id 
            GROUP BY tb_regrind_supplier_list.regrind_supplier_list_id 
            -- HAVING IFNULL(SUM(regrind_supplier_receive_list_qty),0)  < AVG(regrind_supplier_list_qty)  

        ) 
        AND (product_name LIKE ('%$search%') OR regrind_supplier_code LIKE ('%$search%')) ";

                // echo '<pre>';
                // print_r($sql_supplier);
                // echo '</pre>';
        // echo $sql_supplier;
        $data = [];
        if ($result = mysqli_query(static::$db,$sql_supplier, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }

        return $data;
    }
    function generateMobileRegrindSupplierReceiveListBySupplierId($supplier_id,$user_id, $data = []){

        $str ='0';
        $str_user = "";
        if($user_id != ""){
            $str_user = " AND tb_regrind_supplier.employee_id = '$user_id' ";
        }
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

        $sql_supplier = "SELECT tb2.product_id, 
        tb2.regrind_supplier_list_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,  
        IFNULL(regrind_supplier_list_qty 
            - (IFNULL((
                SELECT SUM(regrind_supplier_receive_list_qty) 
                FROM tb_regrind_supplier_receive_list 
                WHERE regrind_supplier_list_id = tb2.regrind_supplier_list_id 
            ),0)+
            IFNULL((
                SELECT SUM(regrind_supplier_receive_list_scrap_qty) 
                FROM tb_regrind_supplier_receive_list 
                WHERE regrind_supplier_list_id = tb2.regrind_supplier_list_id 
            ),0)
            )
         ,0) as regrind_supplier_receive_list_qty,  
        CONCAT('Order for supplier PO : ',regrind_supplier_code) as regrind_supplier_receive_list_remark 
        FROM tb_regrind_supplier 
        LEFT JOIN tb_regrind_supplier_list as tb2 ON tb_regrind_supplier.regrind_supplier_id = tb2.regrind_supplier_id 
        LEFT JOIN tb_product ON tb2.product_id = tb_product.product_id 
        WHERE tb_regrind_supplier.supplier_id = '$supplier_id'  
        $str_user         
        AND tb2.product_id IN ($str) 
        HAVING regrind_supplier_receive_list_qty > 0
        ORDER BY tb2.product_id,tb2.regrind_supplier_list_id
         
        ";
        $data = [];
        if ($result = mysqli_query(static::$db,$sql_supplier, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }

        return $data;
    }


    function getRegrindSupplierReceiveLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(regrind_supplier_receive_code,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  regrind_supplier_receive_lastcode 
        FROM tb_regrind_supplier_receive 
        WHERE regrind_supplier_receive_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['regrind_supplier_receive_lastcode'];
        }

    }

   
    function updateRegrindSupplierReceiveByID($id,$data = []){
        $sql = " UPDATE tb_regrind_supplier_receive SET 
        supplier_id = '".$data['supplier_id']."', 
        employee_id = '".$data['employee_id']."', 
        regrind_supplier_receive_code = '".$data['regrind_supplier_receive_code']."', 
        regrind_supplier_receive_date = '".$data['regrind_supplier_receive_date']."', 
        regrind_supplier_receive_remark = '".$data['regrind_supplier_receive_remark']."', 
        regrind_supplier_receive_file = '".$data['regrind_supplier_receive_file']."', 
        employee_signature = '".$data['employee_signature']."', 
        contact_name = '".$data['contact_name']."', 
        contact_signature = '".$data['contact_signature']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE regrind_supplier_receive_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }



    function insertRegrindSupplierReceive($data = []){
        $sql = " INSERT INTO tb_regrind_supplier_receive (
            supplier_id,
            employee_id,
            regrind_supplier_receive_code,
            regrind_supplier_receive_date,
            regrind_supplier_receive_remark,
            regrind_supplier_receive_file,
            employee_signature,
            contact_name,
            contact_signature,
            addby,
            adddate ) 
        VALUES ('".
        $data['supplier_id']."','".
        $data['employee_id']."','".
        $data['regrind_supplier_receive_code']."','".
        $data['regrind_supplier_receive_date']."','".
        $data['regrind_supplier_receive_remark']."','".
        $data['regrind_supplier_receive_file']."','".
        $data['employee_signature']."','".
        $data['contact_name']."','".
        $data['contact_signature']."','".
        $data['addby']."',".
        "NOW()); 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }



    function deleteRegrindSupplierReceiveByID($id){

        $sql = " DELETE FROM tb_regrind_supplier_receive WHERE regrind_supplier_receive_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        $sql = " DELETE FROM tb_regrind_supplier_receive_list WHERE regrind_supplier_receive_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function generateRegrindSupplierReceiveListAllBySupplierId($supplier_id,$user_id, $data = []){

        $str ='0';
        $str_user = "";
        if($user_id != ""){
            $str_user = " AND tb_regrind_supplier.employee_id = '$user_id' ";
        }
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

        $sql_supplier = "SELECT tb2.product_id, 
        tb2.regrind_supplier_list_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,  
        IFNULL(regrind_supplier_list_qty 
            - IFNULL((
                SELECT SUM(regrind_supplier_receive_list_qty) 
                FROM tb_regrind_supplier_receive_list 
                WHERE regrind_supplier_list_id = tb2.regrind_supplier_list_id 
            ),0)
         ,0) as regrind_supplier_receive_list_qty,  
        CONCAT('Order for supplier PO : ',regrind_supplier_code) as regrind_supplier_receive_list_remark 
        FROM tb_regrind_supplier 
        LEFT JOIN tb_regrind_supplier_list as tb2 ON tb_regrind_supplier.regrind_supplier_id = tb2.regrind_supplier_id 
        LEFT JOIN tb_product ON tb2.product_id = tb_product.product_id 
        WHERE tb_regrind_supplier.supplier_id = '$supplier_id'  
        $str_user         
        AND tb2.product_id IN ($str) 
        ORDER BY tb2.product_id,tb2.regrind_supplier_list_id
         
        ";
        $data = [];
        if ($result = mysqli_query(static::$db,$sql_supplier, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }

        return $data;
    }


}
?>