<?php

require_once("BaseModel.php");
class RegrindSupplierModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getRegrindSupplierBy($date_start = "",$date_end = "",$supplier_id = "",$keyword = "",$user_id = ""){
        $str_supplier = "";
        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(regrind_supplier_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(regrind_supplier_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(regrind_supplier_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(regrind_supplier_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND tb_regrind_supplier.employee_id = '$user_id' ";
        }

        if($supplier_id != ""){
            $str_supplier = "AND tb2.supplier_id = '$supplier_id' ";
        }

        $sql = " SELECT regrind_supplier_id, 
        tb_regrind_supplier.employee_id,
        regrind_supplier_code, 
        regrind_supplier_date, 
        regrind_supplier_file,
        contact_name,
        regrind_supplier_remark,
        tb2.supplier_id ,
        tb3.customer_id ,
        supplier_tel,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        IFNULL(CONCAT(tb2.supplier_name_en,' (',tb2.supplier_name_th,')'),'-') as supplier_name ,
        IFNULL(CONCAT(tb3.customer_name_en,' (',tb3.customer_name_th,')'),'-') as customer_name 
        FROM tb_regrind_supplier  
        LEFT JOIN tb_user as tb1 ON tb_regrind_supplier.employee_id = tb1.user_id 
        LEFT JOIN tb_supplier as tb2 ON tb_regrind_supplier.supplier_id = tb2.supplier_id 
        LEFT JOIN tb_customer as tb3 ON tb_regrind_supplier.customer_id = tb3.customer_id 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%') 
            OR  contact_name LIKE ('%$keyword%') 
            OR  regrind_supplier_code LIKE ('%$keyword%') 
        ) 
        $str_supplier 
        $str_date 
        $str_user  
        ORDER BY STR_TO_DATE(regrind_supplier_date,'%d-%m-%Y %H:%i:%s') , regrind_supplier_code DESC 
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
    function getRegrindSupplierByMobile($date_start = "",$date_end = "",$customer_id = "",$keyword = "",$user_id = ""){
        $str_customer = "";
        $str_date = "";
        $str_user = "";

        // if($date_start != "" && $date_end != ""){
        //     $str_date = "AND STR_TO_DATE(regrind_supplier_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(regrind_supplier_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        // }else if ($date_start != ""){
        //     $str_date = "AND STR_TO_DATE(regrind_supplier_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        // }else if ($date_end != ""){
        //     $str_date = "AND STR_TO_DATE(regrind_supplier_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        // }

        if($user_id != ""){
            $str_user = "AND tb_regrind_supplier.employee_id = '$user_id' ";
        }

        if($customer_id != ""){
            $str_customer = "AND tb2.customer_id = '$customer_id' ";
        }

        $sql = " SELECT regrind_supplier_id, 
        tb_regrind_supplier.employee_id,
        regrind_supplier_code, 
        regrind_supplier_date, 
        regrind_supplier_file,
        contact_name,
        regrind_supplier_remark,
        tb2.customer_id , 
        customer_code,
        customer_zone,
        customer_tel,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        IFNULL(tb2.customer_name_en,'-') as customer_name 
        FROM tb_regrind_supplier  
        LEFT JOIN tb_user as tb1 ON tb_regrind_supplier.employee_id = tb1.user_id 
        LEFT JOIN tb_customer as tb2 ON tb_regrind_supplier.customer_id = tb2.customer_id 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%') 
            OR  contact_name LIKE ('%$keyword%') 
            OR  regrind_supplier_code LIKE ('%$keyword%') 
            OR  tb2.customer_name_en LIKE ('%$keyword%') 
        ) 
        $str_customer 
        $str_date 
        $str_user  
        GROUP BY tb_regrind_supplier.customer_id 
        ORDER BY STR_TO_DATE(regrind_supplier_date,'%d-%m-%Y %H:%i:%s') , regrind_supplier_code DESC 
         ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;            
        } 
        // return $sql;
    }
    function getRegrindSupplierByCustomerID($date_start = "",$date_end = "",$customer_id = "",$keyword = "",$user_id = ""){
        $str_customer = "";
        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(regrind_supplier_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(regrind_supplier_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(regrind_supplier_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(regrind_supplier_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND tb_regrind_supplier.employee_id = '$user_id' ";
        }

        if($customer_id != ""){
            $str_customer = "AND tb_regrind_supplier.customer_id = '$customer_id' ";
        }

        $sql = " SELECT regrind_supplier_id, 
        tb_regrind_supplier.employee_id,
        regrind_supplier_code, 
        supplier_code, 
        regrind_supplier_date, 
        regrind_supplier_file,
        contact_name,
        regrind_supplier_remark,
        tb2.supplier_id , 
        supplier_tel,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        IFNULL(tb2.supplier_name_en,'-') as supplier_name 
        FROM tb_regrind_supplier  
        LEFT JOIN tb_user as tb1 ON tb_regrind_supplier.employee_id = tb1.user_id 
        LEFT JOIN tb_supplier as tb2 ON tb_regrind_supplier.supplier_id = tb2.supplier_id  
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%') 
            OR  contact_name LIKE ('%$keyword%') 
            OR  regrind_supplier_code LIKE ('%$keyword%') 
            OR  tb2.supplier_name_en LIKE ('%$keyword%') 
        ) 
        $str_customer 
        $str_date 
        $str_user  
        GROUP BY tb_regrind_supplier.supplier_id 
        ORDER BY STR_TO_DATE(regrind_supplier_date,'%d-%m-%Y %H:%i:%s') , regrind_supplier_code DESC 
         ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;            
        } 
        // return $sql;  
    }
    function getRegrindBySupplierListID($id = "",$user_id = ""){
        $sql = " SELECT tb_regrind_supplier_list.regrind_supplier_list_id,
        tb_regrind_supplier.regrind_supplier_id,
        tb_regrind_supplier.supplier_id,
        tb_regrind_supplier.employee_id,
        tb_regrind_supplier_list.product_id,
        tb_regrind_supplier_list.product_id,
        regrind_supplier_code,
        product_code,
        product_name,
        SUM(regrind_supplier_list_qty) as qty,
        IFNULL(SUM(regrind_supplier_receive_list_qty),0) as qty_receive,        
        FROM tb_regrind_supplier 
        LEFT JOIN tb_regrind_supplier_list ON tb_regrind_supplier.regrind_supplier_id = tb_regrind_supplier_list.regrind_supplier_id 
        LEFT JOIN tb_regrind_supplier_receive_list ON tb_regrind_supplier_list.regrind_supplier_list_id = tb_regrind_supplier_receive_list.regrind_supplier_list_id 
        LEFT JOIN tb_product ON tb_regrind_supplier_list.product_id = tb_product.product_id 
        LEFT JOIN tb_supplier ON tb_regrind_supplier.supplier_id = tb_supplier.supplier_id 
        LEFT JOIN tb_user ON tb_regrind_supplier.employee_id = tb_user.user_id 
        WHERE tb_regrind_supplier.supplier_id = '$id' AND tb_regrind_supplier.employee_id = '$user_id'
        GROUP BY  tb_regrind_supplier_list.regrind_supplier_list_id
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data=[];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }
    function getRegrindSupplieRestBy($id = "",$user_id = "",$keyword="",$customer_id="" , $regrind_supplier_list_id =""){
        $str_user = "";
        if($user_id != ""){
            $str_user = " AND tb_regrind_supplier.employee_id = '$user_id' ";
        }
        $str_customer = "";
        if($customer_id != ""){
            $str_customer = " AND tb_regrind_supplier.customer_id = '$customer_id' ";
        }
        if($id != ""){
            $str_id = " AND tb_regrind_supplier.supplier_id = '$id' ";
        }
        if($regrind_supplier_list_id != ""){
            $str_list = " AND tb2.regrind_supplier_list_id = '$regrind_supplier_list_id' ";
        }

        $sql = "SELECT tb2.product_id, 
        tb2.regrind_supplier_list_id, 
        CONCAT(product_code_first,product_code) as product_code,
        tb2.regrind_supplier_list_complete, 
        product_name,  
        regrind_supplier_date,
        'false' as 'checkbox' ,
        tb_regrind_supplier.regrind_supplier_id,
        SUM(IFNULL(( SELECT SUM(regrind_supplier_receive_list_qty) 
                FROM tb_regrind_supplier_receive_list 
                WHERE regrind_supplier_list_id = tb2.regrind_supplier_list_id 
            ),0)) as qty_receive,
        SUM(IFNULL(( SELECT SUM(regrind_supplier_receive_list_scrap_qty) 
            FROM tb_regrind_supplier_receive_list 
            WHERE regrind_supplier_list_id = tb2.regrind_supplier_list_id 
        ),0)) as qty_receive_scrap,
        SUM(IFNULL(regrind_supplier_list_qty ,0)) as qty ,
        SUM(IFNULL(regrind_supplier_list_qty ,0))-(
            SUM(IFNULL(( SELECT SUM(regrind_supplier_receive_list_qty) 
                FROM tb_regrind_supplier_receive_list 
                WHERE regrind_supplier_list_id = tb2.regrind_supplier_list_id 
            ),0)) +
            SUM(IFNULL(( SELECT SUM(regrind_supplier_receive_list_scrap_qty) 
            FROM tb_regrind_supplier_receive_list 
            WHERE regrind_supplier_list_id = tb2.regrind_supplier_list_id 
        ),0))
        ) as balance   
        FROM tb_regrind_supplier 
        LEFT JOIN tb_regrind_supplier_list as tb2 ON tb_regrind_supplier.regrind_supplier_id = tb2.regrind_supplier_id 
        LEFT JOIN tb_product ON tb2.product_id = tb_product.product_id 
        WHERE 
        (  
            tb_product.product_name LIKE ('%$keyword%') 
            OR tb_product.product_code LIKE ('%$keyword%') 
        )  
        AND tb2.regrind_supplier_list_complete = '0'
        $str_id
        $str_customer
        $str_user 
        $str_list 
        GROUP BY tb2.product_id
        HAVING SUM(IFNULL(( SELECT SUM(regrind_supplier_receive_list_qty) 
                FROM tb_regrind_supplier_receive_list 
                WHERE regrind_supplier_list_id = tb2.regrind_supplier_list_id 
            ),0))+
                SUM(IFNULL(( SELECT SUM(regrind_supplier_receive_list_scrap_qty) 
                    FROM tb_regrind_supplier_receive_list 
                    WHERE regrind_supplier_list_id = tb2.regrind_supplier_list_id 
                ),0))  
             <  SUM(IFNULL(regrind_supplier_list_qty ,0)) 
        ORDER BY tb_regrind_supplier.regrind_supplier_id DESC,STR_TO_DATE(regrind_supplier_date,'%d-%m-%Y %H:%i:%s') DESC 
        ";

        // echo'<pre>';
        // print_r($sql);
        // echo'</pre>';
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data=[];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
        // return $sql;
    }
    function getRegrindSupplieCompleteBy($id = "",$user_id = "",$keyword="",$customer_id="", $regrind_supplier_list_id =""){
        $str_user = "";
        if($user_id != ""){
            $str_user = " AND tb_regrind_supplier.employee_id = '$user_id' ";
        }
        $str_customer = "";
        if($customer_id != ""){
            $str_customer = " AND tb_regrind_supplier.customer_id = '$customer_id' ";
        }
        if($id != ""){
            $str_id = " AND tb_regrind_supplier.supplier_id = '$id' ";
        }
        if($regrind_supplier_list_id != ""){
            $str_list = " AND tb2.regrind_supplier_list_id = '$regrind_supplier_list_id' ";
        }
        $sql = "SELECT tb2.product_id, 
        tb2.regrind_supplier_list_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,  
        regrind_supplier_date,
        'false' as 'checkbox' ,
        SUM(IFNULL(( SELECT SUM(regrind_supplier_receive_list_qty) 
                FROM tb_regrind_supplier_receive_list 
                WHERE regrind_supplier_list_id = tb2.regrind_supplier_list_id 
            ),0)) as qty_receive,
        SUM(IFNULL(( SELECT SUM(regrind_supplier_receive_list_scrap_qty) 
                FROM tb_regrind_supplier_receive_list 
                WHERE regrind_supplier_list_id = tb2.regrind_supplier_list_id 
            ),0)) as qty_receive_scrap,
        SUM(IFNULL(regrind_supplier_list_qty ,0)) as qty ,
        SUM(IFNULL(regrind_supplier_list_qty ,0))-(
            SUM(IFNULL(( SELECT SUM(regrind_supplier_receive_list_qty) 
                FROM tb_regrind_supplier_receive_list 
                WHERE regrind_supplier_list_id = tb2.regrind_supplier_list_id 
            ),0)) +
            SUM(IFNULL(( SELECT SUM(regrind_supplier_receive_list_scrap_qty) 
            FROM tb_regrind_supplier_receive_list 
            WHERE regrind_supplier_list_id = tb2.regrind_supplier_list_id 
        ),0))
        ) as balance 
        FROM tb_regrind_supplier 
        LEFT JOIN tb_regrind_supplier_list as tb2 ON tb_regrind_supplier.regrind_supplier_id = tb2.regrind_supplier_id 
        LEFT JOIN tb_product ON tb2.product_id = tb_product.product_id 
        WHERE 
        (  
            tb_product.product_name LIKE ('%$keyword%') 
            OR tb_product.product_code LIKE ('%$keyword%') 
        )  
        AND tb2.regrind_supplier_list_complete = '1'
        $str_id
        $str_customer
        $str_user
        $str_list 
        GROUP BY tb2.product_id 
        HAVING SUM(IFNULL(( SELECT 
                    SUM(regrind_supplier_receive_list_qty) 
                    FROM tb_regrind_supplier_receive_list 
                    WHERE regrind_supplier_list_id = tb2.regrind_supplier_list_id ),0)) +
                SUM(IFNULL(( SELECT 
                SUM(regrind_supplier_receive_list_scrap_qty) 
                FROM tb_regrind_supplier_receive_list 
                WHERE regrind_supplier_list_id = tb2.regrind_supplier_list_id ),0))  
                    =  SUM(IFNULL(regrind_supplier_list_qty ,0)) 
        ORDER BY STR_TO_DATE(regrind_supplier_date,'%d-%m-%Y %H:%i:%s') DESC 
        ";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data=[];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
        // return $sql;
    }
    function getRegrindSupplierProductDetail($supplier_id = "",$user_id = "",$product_id = []){
        $str_product_id ="'0'";

        if(is_array($product_id) && count($product_id) > 0){ 

            $str_product_id ="";
            for($i=0; $i < count($product_id) ;$i++){
                $str_product_id .= " '".$product_id[$i]."' ";
                if($i + 1 < count($product_id)){
                    $str_product_id .= ",";
                }
            }

        }else if ($product_id != ''){
            $str_product_id = "'".$product_id."'";
        }else{
            $str_product_id="'0'";
        }


        $sql = "SELECT tb_regrind_supplier_list.regrind_supplier_list_id,
        tb_regrind_supplier.regrind_supplier_id,
        tb_regrind_supplier_list.product_id,
        regrind_supplier_code,
        product_code,
        product_name,
        regrind_supplier_list_qty as qty ,
        IFNULL(regrind_supplier_receive_list_qty,0 )as qty_receive 
        FROM tb_regrind_supplier_list  
        LEFT JOIN tb_regrind_supplier_receive_list  ON tb_regrind_supplier_list .regrind_supplier_list_id = tb_regrind_supplier_receive_list.regrind_supplier_list_id 
        LEFT JOIN tb_regrind_supplier  ON tb_regrind_supplier_list.regrind_supplier_id = tb_regrind_supplier.regrind_supplier_id
        LEFT JOIN tb_product ON tb_regrind_supplier_list.product_id = tb_product.product_id
        WHERE tb_regrind_supplier.supplier_id = '$supplier_id' AND tb_regrind_supplier.employee_id = '$user_id' 
        AND tb_regrind_supplier_list.product_id IN ($str_product_id)
        ORDER BY 
       
        ";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data=[];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }
    function getRegrindSupplierReceiveAndSend($supplier_id = "",$user_id = "",$product_id = []){
        $str_product_id ="'0'";
        $str_user = "";
        if($user_id != ""){
            $str_user = " AND tb_regrind_supplier.employee_id = '$user_id' ";
        }
        if($supplier_id != ""){
            $str_supplier = " AND tb_regrind_supplier.supplier_id = '$supplier_id' ";
        }
        if(is_array($product_id) && count($product_id) > 0){ 

            $str_product_id ="";
            for($i=0; $i < count($product_id) ;$i++){
                $str_product_id .= " '".$product_id[$i]."' ";
                if($i + 1 < count($product_id)){
                    $str_product_id .= ",";
                }
            }

        }else if ($product_id != ''){
            $str_product_id = "".$product_id."";
        }else{
            $str_product_id="'0'";
        }


        $sql = "SELECT 
        'SendReceive' as 'regrind_type',
        tb2.product_id, 
        tb2.regrind_supplier_list_id, 
        regrind_supplier_code as code,
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,          
        CONCAT(IFNULL((
                SELECT SUM(regrind_supplier_receive_list_scrap_qty) 
                FROM tb_regrind_supplier_receive_list 
                WHERE regrind_supplier_list_id = tb2.regrind_supplier_list_id 
            ),0) 
            ,'/',
            IFNULL((
           SELECT SUM(regrind_supplier_receive_list_qty) 
           FROM tb_regrind_supplier_receive_list 
           WHERE regrind_supplier_list_id = tb2.regrind_supplier_list_id 
            ),0),'/',
            IFNULL(regrind_supplier_list_qty ,0)) as qty,
        IFNULL((
            SELECT SUM(regrind_supplier_receive_list_qty) 
            FROM tb_regrind_supplier_receive_list 
            WHERE regrind_supplier_list_id = tb2.regrind_supplier_list_id 
        ),0) as qty_receive,
        IFNULL((
                SELECT SUM(regrind_supplier_receive_list_scrap_qty) 
                FROM tb_regrind_supplier_receive_list 
                WHERE regrind_supplier_list_id = tb2.regrind_supplier_list_id 
            ),0) as scrap_qty,
        IFNULL(regrind_supplier_list_qty ,0) as qty_send,
        IFNULL(regrind_supplier_list_qty 
            - (
                IFNULL((
                SELECT SUM(regrind_supplier_receive_list_qty) 
                FROM tb_regrind_supplier_receive_list 
                WHERE regrind_supplier_list_id = tb2.regrind_supplier_list_id 
                ),0) +
                IFNULL((
                        SELECT SUM(regrind_supplier_receive_list_scrap_qty) 
                        FROM tb_regrind_supplier_receive_list 
                        WHERE regrind_supplier_list_id = tb2.regrind_supplier_list_id 
                ),0) 
            ) 
            
         ,0) as balance,
         CASE
          WHEN (  IFNULL(regrind_supplier_list_qty 
                - (
                    IFNULL((
                        SELECT SUM(regrind_supplier_receive_list_qty) 
                        FROM tb_regrind_supplier_receive_list 
                        WHERE regrind_supplier_list_id = tb2.regrind_supplier_list_id 
                    ),0) + IFNULL((
                        SELECT SUM(regrind_supplier_receive_list_scrap_qty) 
                        FROM tb_regrind_supplier_receive_list 
                        WHERE regrind_supplier_list_id = tb2.regrind_supplier_list_id 
                    ),0) 
                ) 
            ,0) ) > 0 THEN 'Incomplete'
            ELSE 'Complete'
        END AS complete
        FROM tb_regrind_supplier 
        LEFT JOIN tb_regrind_supplier_list as tb2 ON tb_regrind_supplier.regrind_supplier_id = tb2.regrind_supplier_id 
        LEFT JOIN tb_product ON tb2.product_id = tb_product.product_id 
        WHERE tb2.product_id IN ($str_product_id) 
        $str_user        
        $str_supplier
        ORDER BY code DESC, case balance when 0 then 1 else 0 end, balance ,tb2.regrind_supplier_list_id
       
        ";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data=[];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
        // return $sql;
    }
    function getRegrindSupplierUnionAllBy($supplier_id = "",$user_id = "",$product_id = []){
        $str_product_id ="'0'";
        $str_user = "";
        if($user_id != ""){
            $str_user = " AND tb_regrind_supplier.employee_id = '$user_id' ";
        }
        if($supplier_id !== ""){
            $str_supplier = " AND tb_regrind_supplier.supplier_id = '$supplier_id' ";
        }
        if(is_array($product_id) && count($product_id) > 0){ 

            $str_product_id ="";
            for($i=0; $i < count($product_id) ;$i++){
                $str_product_id .= " '".$product_id[$i]."' ";
                if($i + 1 < count($product_id)){
                    $str_product_id .= ",";
                }
            }

        }else if ($product_id != ''){
            $str_product_id = "".$product_id."";
        }else{
            $str_product_id="'0'";
        }

        $sql = "SELECT * FROM (
            (
                SELECT 
                '1_Send' as 'regrind_type',
                tb2.product_id, 
                tb_regrind_supplier.regrind_supplier_id as 'id', 
                tb2.regrind_supplier_list_id as 'list_id',         
                tb_regrind_supplier.regrind_supplier_date as 'date',         
                regrind_supplier_code as code, 
                CONCAT(product_code_first,product_code) as product_code,         
                product_name,
                regrind_supplier_list_qty as qty,
                '' as scrap_qty
                FROM tb_regrind_supplier_list as tb2
                LEFT JOIN tb_regrind_supplier ON tb2.regrind_supplier_id = tb_regrind_supplier.regrind_supplier_id 
                LEFT JOIN tb_product ON tb2.product_id = tb_product.product_id 
                WHERE tb2.product_id IN ($str_product_id)
                $str_supplier
                $str_user        
            )
        UNION ALL
            (
                SELECT
                '2_Receive' as 'regrind_type',
                tb_regrind_supplier_receive_list.product_id, 
                tb_regrind_supplier_receive.regrind_supplier_receive_id as 'id', 
                tb_regrind_supplier_receive_list.regrind_supplier_receive_list_id as 'list_id',        
                tb_regrind_supplier_receive.regrind_supplier_receive_date as 'date',               
                regrind_supplier_receive_code as code, 
                CONCAT(product_code_first,product_code) as product_code,         
                product_name,
                regrind_supplier_receive_list_qty as qty,
                regrind_supplier_receive_list_scrap_qty as scrap_qty
                FROM tb_regrind_supplier_receive_list 
                LEFT JOIN tb_regrind_supplier_list ON tb_regrind_supplier_receive_list.regrind_supplier_list_id = tb_regrind_supplier_list.regrind_supplier_list_id 
                LEFT JOIN tb_regrind_supplier ON tb_regrind_supplier_list.regrind_supplier_id = tb_regrind_supplier.regrind_supplier_id 
                LEFT JOIN tb_regrind_supplier_receive ON tb_regrind_supplier_receive_list.regrind_supplier_receive_id = tb_regrind_supplier_receive.regrind_supplier_receive_id 
                LEFT JOIN tb_product ON tb_regrind_supplier_receive_list.product_id = tb_product.product_id 
                WHERE tb_regrind_supplier_receive_list.product_id IN ($str_product_id)  
                $str_user           
                $str_supplier
            )
        ) AS tb_data
        ORDER BY STR_TO_DATE(`date`,'%d-%m-%Y %H:%i:%s') DESC,regrind_type DESC, code DESC
            
        ";
        // echo "<pre>$sql</pre>";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data=[];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        } 
        // return $sql;
    }
    function getRegrindSupplierByID($id){
        $sql = " SELECT 
        regrind_supplier_id,
        regrind_supplier_code,
        regrind_supplier_date,
        regrind_supplier_remark,
        contact_name,
        supplier_name_en,
        CONCAT(tb_user.user_name,' ',tb_user.user_lastname) as 'employee_name',
        tb_regrind_supplier.supplier_id,
        tb_regrind_supplier.customer_id,
        tb_regrind_supplier.employee_id
        FROM tb_regrind_supplier 
        LEFT JOIN tb_supplier ON tb_regrind_supplier.supplier_id = tb_supplier.supplier_id 
        LEFT JOIN tb_user ON tb_regrind_supplier.employee_id = tb_user.user_id 
        WHERE regrind_supplier_id = '$id' 
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

    function getRegrindSupplierViewByID($id){
        $sql = " SELECT *   
        FROM tb_regrind_supplier 
        LEFT JOIN tb_user ON tb_regrind_supplier.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_supplier ON tb_regrind_supplier.supplier_id = tb_supplier.supplier_id 
        LEFT JOIN tb_customer ON tb_regrind_supplier.customer_id = tb_customer.customer_id
        WHERE regrind_supplier_id = '$id' 
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

    function getRegrindSupplierLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(regrind_supplier_code,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  regrind_supplier_lastcode 
        FROM tb_regrind_supplier 
        WHERE regrind_supplier_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['regrind_supplier_lastcode'];
        }

    }
    function updateRegrindSupplierByID($id,$data = []){
        $sql = " UPDATE tb_regrind_supplier SET 
        supplier_id = '".$data['supplier_id']."', 
        customer_id = '".$data['customer_id']."', 
        employee_id = '".$data['employee_id']."', 
        regrind_supplier_code = '".$data['regrind_supplier_code']."', 
        regrind_supplier_date = '".$data['regrind_supplier_date']."', 
        regrind_supplier_remark = '".$data['regrind_supplier_remark']."', 
        regrind_supplier_file = '".$data['regrind_supplier_file']."', 
        contact_name = '".$data['contact_name']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW()
        WHERE regrind_supplier_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function updateContactSignatureByID($id,$data = []){
        $sql = " UPDATE tb_regrind_supplier SET 
        contact_signature = '".$data['contact_signature']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW()  
        WHERE regrind_supplier_id = $id 
        ";

        //echo $sql;

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }
    function insertRegrindSupplier($data = []){
        $sql = " INSERT INTO tb_regrind_supplier (
            supplier_id,
            customer_id,
            employee_id,
            regrind_supplier_code,
            regrind_supplier_date,
            regrind_supplier_remark,
            regrind_supplier_file,
            employee_signature,
            contact_name,
            contact_signature,
            addby,
            adddate ) 
        VALUES ('".
        $data['supplier_id']."','".
        $data['customer_id']."','".
        $data['employee_id']."','".
        $data['regrind_supplier_code']."','".
        $data['regrind_supplier_date']."','".
        $data['regrind_supplier_remark']."','".
        $data['regrind_supplier_file']."','".
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
    function deleteRegrindSupplierByID($id){

        $sql = " DELETE FROM tb_regrind_supplier WHERE regrind_supplier_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        $sql = " DELETE FROM tb_regrind_supplier_list WHERE regrind_supplier_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
    function RegrindSupplierByReceiveID($id){

        $sql = " SELECT *
        FROM tb_regrind_supplier_list
        LEFT JOIN tb_regrind_supplier_receive_list ON tb_regrind_supplier_list.regrind_supplier_list_id = tb_regrind_supplier_receive_list.regrind_supplier_list_id
        LEFT JOIN tb_regrind_supplier_receive ON tb_regrind_supplier_receive_list.regrind_supplier_receive_id = tb_regrind_supplier_receive.regrind_supplier_receive_id
        WHERE regrind_supplier_id = '$id' ";
           if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }
    }


}
?>