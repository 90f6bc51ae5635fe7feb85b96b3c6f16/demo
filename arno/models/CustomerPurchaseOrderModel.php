<?php

require_once("BaseModel.php");
class CustomerPurchaseOrderModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getCustomerPurchaseOrderBy($date_start = "",$date_end = "",$customer_id = "" , $status = "",$keyword = "",$user_id = ""){

        $str_customer = "";
        $str_date = "";
        $str_user = "";
        $str_status = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(customer_purchase_order_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(customer_purchase_order_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(customer_purchase_order_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(customer_purchase_order_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND employee_id = '$user_id' ";
        }

        if($customer_id != ""){
            $str_customer = "AND tb2.customer_id = '$customer_id' ";
        }


        if($status == "1"){//ยังไม่มีการสั่งสินค้า
            $str_status = " AND  IFNULL((
                    SELECT COUNT(*) 
                    FROM tb_customer_purchase_order_list
                    LEFT JOIN tb_customer_purchase_order_list_detail  
                    ON tb_customer_purchase_order_list.customer_purchase_order_list_id = tb_customer_purchase_order_list_detail.customer_purchase_order_list_id 
                    WHERE IFNULL(tb_customer_purchase_order_list_detail.supplier_id,0) > 0 
                    AND IFNULL(tb_customer_purchase_order_list_detail.purchase_order_list_id,0) > 0 
                    AND customer_purchase_order_id = tb.customer_purchase_order_id 
                    ),0) = 0";
        } else if($status == "2"){//สั่งสินค้าแล้ว
            $str_status = " AND  IFNULL((
                    SELECT COUNT(*) 
                    FROM tb_customer_purchase_order_list
                    LEFT JOIN tb_customer_purchase_order_list_detail  
                    ON tb_customer_purchase_order_list.customer_purchase_order_list_id = tb_customer_purchase_order_list_detail.customer_purchase_order_list_id 
                    WHERE IFNULL(tb_customer_purchase_order_list_detail.supplier_id,0) > 0 
                    AND IFNULL(tb_customer_purchase_order_list_detail.purchase_order_list_id,0) = 0 
                    AND customer_purchase_order_id = tb.customer_purchase_order_id 
                    ),0) = 0";
        } else if($status == "3"){//ส่งสินค้ายังไม่ครบ
            $str_status = "  AND  IFNULL((
                        SELECT COUNT(tb_customer_purchase_order_list.customer_purchase_order_list_id) 
                        FROM tb_customer_purchase_order_list  
                        LEFT JOIN tb_invoice_customer_list ON  tb_customer_purchase_order_list.customer_purchase_order_list_id = tb_invoice_customer_list.customer_purchase_order_list_id  
                        WHERE customer_purchase_order_id = tb.customer_purchase_order_id
                        GROUP BY tb_customer_purchase_order_list.customer_purchase_order_id 
                        HAVING IFNULL( IFNULL(SUM(invoice_customer_list_qty,0) ),0) < AVG( IFNULL(customer_purchase_order_list_qty,0) )  
                    ),0) > 0 ";
        }else if($status == "4"){//ส่งสินค้าครบแล้ว
            $str_status = "  AND IFNULL(( 
                SELECT COUNT(tb_customer_purchase_order_list.customer_purchase_order_list_id) 
                FROM tb_customer_purchase_order_list 
                LEFT JOIN tb_invoice_customer_list 
                ON tb_customer_purchase_order_list.customer_purchase_order_list_id = tb_invoice_customer_list.customer_purchase_order_list_id 
                WHERE customer_purchase_order_id = tb.customer_purchase_order_id 
                GROUP BY tb_customer_purchase_order_list.customer_purchase_order_id 
                HAVING IFNULL(SUM( IFNULL(invoice_customer_list_qty,0) ),0) < SUM( IFNULL(customer_purchase_order_list_qty,0) ) 
            ),0) = 0 ";
        }

        $sql = " SELECT customer_purchase_order_id, 
        customer_purchase_order_code, 
        customer_purchase_order_code_gen,
        customer_purchase_order_date, 
        customer_purchase_order_status, 
        customer_purchase_order_finish, 
        customer_purchase_order_file, 
        customer_purchase_order_remark, 
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        user_position_name,
        customer_purchase_order_credit_term, 
        customer_purchase_order_delivery_term, 
        IFNULL(CONCAT(tb2.customer_name_en,' (',tb2.customer_name_th,')'),'-') as customer_name, 
        customer_purchase_order_delivery_by 
        
        FROM tb_customer_purchase_order  as tb 
        LEFT JOIN tb_user as tb1 ON tb.employee_id = tb1.user_id 
        LEFT JOIN tb_user_position  ON tb1.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_customer as tb2 ON tb.customer_id = tb2.customer_id 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%') 
            OR  customer_purchase_order_code LIKE ('%$keyword%') 
            OR  customer_purchase_order_code_gen LIKE ('%$keyword%') 
        ) 
        $str_customer 
        $str_date 
        $str_user  
        $str_status 
        ORDER BY STR_TO_DATE(customer_purchase_order_date,'%Y-%m-%d %H:%i:%s'),customer_purchase_order_code_gen,customer_purchase_order_code DESC 
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

    
    function getCustomerPurchaseOrder1By($date_start = "",$date_end = "",$customer_id = "" , $status = "",$keyword = "",$user_id = ""){

        $str_customer = "";
        $str_date = "";
        $str_user = "";
        $str_status = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(customer_purchase_order_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(customer_purchase_order_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(customer_purchase_order_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(customer_purchase_order_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND employee_id = '$user_id' ";
        }

        if($customer_id != ""){
            $str_customer = "AND tb2.customer_id = '$customer_id' ";
        }


        if($status == "1"){//ยังไม่มีการสั่งสินค้า
            $str_status = " AND  IFNULL((
                    SELECT COUNT(*) 
                    FROM tb_customer_purchase_order_list
                    LEFT JOIN tb_customer_purchase_order_list_detail  
                    ON tb_customer_purchase_order_list.customer_purchase_order_list_id = tb_customer_purchase_order_list_detail.customer_purchase_order_list_id 
                    WHERE IFNULL(tb_customer_purchase_order_list_detail.supplier_id,0) > 0 
                    AND IFNULL(tb_customer_purchase_order_list_detail.purchase_order_list_id,0) > 0 
                    AND customer_purchase_order_id = tb.customer_purchase_order_id 
                    ),0) = 0";
        } else if($status == "2"){//สั่งสินค้าแล้ว
            $str_status = " AND  IFNULL((
                    SELECT COUNT(*) 
                    FROM tb_customer_purchase_order_list
                    LEFT JOIN tb_customer_purchase_order_list_detail  
                    ON tb_customer_purchase_order_list.customer_purchase_order_list_id = tb_customer_purchase_order_list_detail.customer_purchase_order_list_id 
                    WHERE IFNULL(tb_customer_purchase_order_list_detail.supplier_id,0) > 0 
                    AND IFNULL(tb_customer_purchase_order_list_detail.purchase_order_list_id,0) = 0 
                    AND customer_purchase_order_id = tb.customer_purchase_order_id 
                    ),0) = 0";
        } else if($status == "3"){//ส่งสินค้ายังไม่ครบ
            $str_status = "  AND  IFNULL((
                        SELECT COUNT(tb_customer_purchase_order_list.customer_purchase_order_list_id) 
                        FROM tb_customer_purchase_order_list  
                        LEFT JOIN tb_invoice_customer_list ON  tb_customer_purchase_order_list.customer_purchase_order_list_id = tb_invoice_customer_list.customer_purchase_order_list_id  
                        WHERE customer_purchase_order_id = tb.customer_purchase_order_id
                        GROUP BY tb_customer_purchase_order_list.customer_purchase_order_id 
                        HAVING IFNULL( IFNULL(SUM(invoice_customer_list_qty,0) ),0) < AVG( IFNULL(customer_purchase_order_list_qty,0) )  
                    ),0) > 0 ";
        }else if($status == "4"){//ส่งสินค้าครบแล้ว
            $str_status = "  AND IFNULL(( 
                SELECT COUNT(tb_customer_purchase_order_list.customer_purchase_order_list_id) 
                FROM tb_customer_purchase_order_list 
                LEFT JOIN tb_invoice_customer_list 
                ON tb_customer_purchase_order_list.customer_purchase_order_list_id = tb_invoice_customer_list.customer_purchase_order_list_id 
                WHERE customer_purchase_order_id = tb.customer_purchase_order_id 
                GROUP BY tb_customer_purchase_order_list.customer_purchase_order_id 
                HAVING IFNULL(SUM( IFNULL(invoice_customer_list_qty,0) ),0) < SUM( IFNULL(customer_purchase_order_list_qty,0) ) 
            ),0) = 0 ";
        }

        $sql = " SELECT customer_purchase_order_id, 
        customer_purchase_order_code, 
        customer_purchase_order_code_gen,
        customer_purchase_order_date, 
        customer_purchase_order_status, 
        customer_purchase_order_finish, 
        customer_purchase_order_file, 
        customer_purchase_order_remark, 
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        user_position_name,
        customer_purchase_order_credit_term, 
        customer_purchase_order_delivery_term, 
        IFNULL(tb2.customer_name_en,'-') as customer_name, 
        customer_purchase_order_delivery_by 
        
        FROM tb_customer_purchase_order  as tb 
        LEFT JOIN tb_user as tb1 ON tb.employee_id = tb1.user_id 
        LEFT JOIN tb_user_position  ON tb1.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_customer as tb2 ON tb.customer_id = tb2.customer_id 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%') 
            OR  customer_purchase_order_code LIKE ('%$keyword%') 
            OR  customer_purchase_order_code_gen LIKE ('%$keyword%') 
        ) 
        $str_customer 
        $str_date 
        $str_user  
        $str_status 
        ORDER BY STR_TO_DATE(customer_purchase_order_date,'%Y-%m-%d %H:%i:%s'),customer_purchase_order_code_gen,customer_purchase_order_code DESC 
         ";  
        //  echo "<pre>";
        //  echo $sql;
        //  echo"</pre>";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }

    
    function getCustomerPurchaseOrderlistProductBy($date_start = "",$date_end = "",$customer_id = "" , $status = "",$keyword = "",$user_id = ""){

        $str_customer = "";
        $str_date = "";
        $str_status = "";

        if($date_start != "" && $date_end != ""){
            $str_date = " STR_TO_DATE(tb_customer_purchase_order.customer_purchase_order_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(tb_customer_purchase_order.customer_purchase_order_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = " STR_TO_DATE(tb_customer_purchase_order.customer_purchase_order_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = " STR_TO_DATE(tb_customer_purchase_order.customer_purchase_order_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }



        if($status == "1"){//ยังไม่มีการสั่งสินค้า
            $str_status = " AND  IFNULL((
                    SELECT COUNT(*) 
                    FROM tb_customer_purchase_order_list
                    LEFT JOIN tb_customer_purchase_order_list_detail  
                    ON tb_customer_purchase_order_list.customer_purchase_order_list_id = tb_customer_purchase_order_list_detail.customer_purchase_order_list_id 
                    WHERE IFNULL(tb_customer_purchase_order_list_detail.supplier_id,0) > 0 
                    AND IFNULL(tb_customer_purchase_order_list_detail.purchase_order_list_id,0) > 0 
                    AND customer_purchase_order_id = tb.customer_purchase_order_id 
                    ),0) = 0";
        } else if($status == "2"){//สั่งสินค้าแล้ว
            $str_status = " AND  IFNULL((
                    SELECT COUNT(*) 
                    FROM tb_customer_purchase_order_list
                    LEFT JOIN tb_customer_purchase_order_list_detail  
                    ON tb_customer_purchase_order_list.customer_purchase_order_list_id = tb_customer_purchase_order_list_detail.customer_purchase_order_list_id 
                    WHERE IFNULL(tb_customer_purchase_order_list_detail.supplier_id,0) > 0 
                    AND IFNULL(tb_customer_purchase_order_list_detail.purchase_order_list_id,0) = 0 
                    AND customer_purchase_order_id = tb.customer_purchase_order_id 
                    ),0) = 0";
        } else if($status == "3"){//ส่งสินค้ายังไม่ครบ
            $str_status = "  AND  IFNULL((
                        SELECT COUNT(tb_customer_purchase_order_list.customer_purchase_order_list_id) 
                        FROM tb_customer_purchase_order_list  
                        LEFT JOIN tb_invoice_customer_list ON  tb_customer_purchase_order_list.customer_purchase_order_list_id = tb_invoice_customer_list.customer_purchase_order_list_id  
                        WHERE customer_purchase_order_id = tb.customer_purchase_order_id
                        GROUP BY tb_customer_purchase_order_list.customer_purchase_order_id 
                        HAVING IFNULL( IFNULL(SUM(invoice_customer_list_qty,0) ),0) < AVG( IFNULL(customer_purchase_order_list_qty,0) )  
                    ),0) > 0 ";
        }else if($status == "4"){//ส่งสินค้าครบแล้ว
            $str_status = "  AND IFNULL(( 
                SELECT COUNT(tb_customer_purchase_order_list.customer_purchase_order_list_id) 
                FROM tb_customer_purchase_order_list 
                LEFT JOIN tb_invoice_customer_list 
                ON tb_customer_purchase_order_list.customer_purchase_order_list_id = tb_invoice_customer_list.customer_purchase_order_list_id 
                WHERE customer_purchase_order_id = tb.customer_purchase_order_id 
                GROUP BY tb_customer_purchase_order_list.customer_purchase_order_id 
                HAVING IFNULL(SUM( IFNULL(invoice_customer_list_qty,0) ),0) < SUM( IFNULL(customer_purchase_order_list_qty,0) ) 
            ),0) = 0 ";
        }

        $sql = " SELECT 
        tb_customer_purchase_order_list.customer_purchase_order_list_id,
        tb_customer_purchase_order.customer_purchase_order_id,
        tb_customer_purchase_order.customer_purchase_order_date ,
        tb_customer_purchase_order.customer_purchase_order_code_gen,
        tb_customer.customer_name_en,
        tb_customer.customer_name_th,
        tb_product.product_code,
        tb_product.product_name,
        tb_invoice_customer_list.invoice_customer_list_qty,
        tb_customer_purchase_order_list.customer_purchase_order_list_qty
        
        FROM tb_customer_purchase_order_list 
        LEFT JOIN tb_customer_purchase_order  ON tb_customer_purchase_order_list.customer_purchase_order_id = tb_customer_purchase_order.customer_purchase_order_id
        LEFT JOIN tb_invoice_customer_list ON tb_customer_purchase_order_list.customer_purchase_order_list_id = tb_invoice_customer_list.customer_purchase_order_list_id  
        LEFT JOIN tb_product ON tb_customer_purchase_order_list.product_id = tb_product.product_id
        LEFT JOIN tb_customer ON tb_customer_purchase_order.customer_id = tb_customer.customer_id 
        WHERE 
        $str_date
        GROUP BY tb_customer_purchase_order_list.customer_purchase_order_list_id
         ";  
        //  echo "<pre>";
        //  print_r($sql);
        //  echo"</pre>";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getCustomerPurchaseOrderLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(customer_purchase_order_code_gen,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  customer_purchase_order_lastcode 
        FROM tb_customer_purchase_order 
        WHERE customer_purchase_order_code_gen LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['customer_purchase_order_lastcode'];
        }

    }

    function getCustomerPurchaseOrderByID($id){
        $sql = " SELECT *   
        FROM tb_customer_purchase_order as tb1 
        LEFT JOIN tb_user ON tb1.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_customer ON tb1.customer_id = tb_customer.customer_id 
        WHERE customer_purchase_order_id = '$id' 
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

    function getCustomerPurchaseOrderByAutoComplete($code){
        $sql = " SELECT customer_purchase_order_code_gen, customer_purchase_order_date
        FROM tb_customer_purchase_order 
        WHERE customer_purchase_order_code_gen LIKE('%$code%')
        "; 

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data =[];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }
    function getCustomerPurchaseOrderByCodeGen($code){
        $sql = " SELECT *
        FROM tb_customer_purchase_order 
        WHERE customer_purchase_order_code_gen = '$code'
        "; 

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data ;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getCustomerPurchaseOrderViewByID($id){
        $sql = " SELECT *  
        FROM tb_customer_purchase_order as tb1 
        LEFT JOIN tb_user ON tb1.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_customer ON tb1.customer_id = tb_customer.customer_id 
        WHERE customer_purchase_order_id = '$id' 
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

    function getCustomerOrder(){

        $sql = "SELECT customer_id, customer_name_en , customer_name_th 
                FROM tb_customer 
                WHERE customer_id IN ( 
                    SELECT DISTINCT customer_id 
                    FROM  tb_delivery_note_customer_list 
                    LEFT JOIN  tb_customer_purchase_order_list ON tb_delivery_note_customer_list.delivery_note_customer_list_id = tb_customer_purchase_order_list.delivery_note_customer_list_id 
                    LEFT JOIN tb_delivery_note_customer  ON tb_delivery_note_customer_list.delivery_note_customer_id = tb_delivery_note_customer.delivery_note_customer_id 
                    WHERE tb_delivery_note_customer.delivery_note_customer_complete = 0
                    
                    GROUP BY tb_delivery_note_customer_list.delivery_note_customer_list_id 
                    HAVING SUM(IFNULL(delivery_note_customer_list_qty,0)) - SUM(IFNULL(customer_purchase_order_list_qty,0)) > 0 
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


    function getCustomerPurchaseOrderCodeByListID($id = []){

        $str ="'0'";

        if(is_array($id)){ 
            for($i=0; $i < count($id) ;$i++){
                $str .= $id[$i];
                if($i + 1 < count($id)){
                    $str .= "','";
                }
            }
        }else if ($id != ''){
            $str = $id;
        }else{
            $str="'0'";
        }

        $sql = "  SELECT GROUP_CONCAT( DISTINCT tb_customer_purchase_order.customer_purchase_order_code) As customer_purchase_order_code 
                  FROM tb_customer_purchase_order_list  
                  LEFT JOIN tb_customer_purchase_order ON tb_customer_purchase_order_list.customer_purchase_order_id = tb_customer_purchase_order.customer_purchase_order_id
                  WHERE customer_purchase_order_list_id IN ($str)";
 
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data['customer_purchase_order_code'];
        }
    }


    function generateCustomerPurchaseOrderListByCustomerId($customer_id, $data_rt = [], $search = ""){

        $str_rt ='0';

        if(is_array($data_rt)){ 
            for($i=0; $i < count($data_rt) ;$i++){ 
                if($data_rt[$i] !=''){
                    $str_rt .= $data_rt[$i];
                    if($i + 1 < count($data_rt)){
                        $str_rt .= ',';
                    } 
                }
                
            }
        }else if ($data_rt != ''){
            $str_rt = $data_rt;
        }else{
            $str_rt='0';
        }



        $sql_request = "SELECT tb_delivery_note_customer_list.product_id, 
        tb_delivery_note_customer_list.delivery_note_customer_list_id , 
        '0' as customer_purchase_order_list_id,
        CONCAT(product_code_first,product_code) as product_code, 
        product_name, 
        IFNULL(product_price_1,0) AS product_price_1,
        IFNULL(product_price_2,0) AS product_price_2,
        IFNULL(product_price_3,0) AS product_price_3,
        IFNULL(product_price_4,0) AS product_price_4,
        IFNULL(product_price_5,0) AS product_price_5,
        IFNULL(product_price_6,0) AS product_price_6,
        IFNULL(product_price_7,0) AS product_price_7, 
        IFNULL(tb_product_customer_price.product_price,0) AS product_price,
        MAX(IFNULL(delivery_note_customer_list_qty,0)) as max_qty,
        SUM(IFNULL(customer_purchase_order_list_qty,0)) as sum_qty,
        MAX(IFNULL(delivery_note_customer_list_qty,0)) - SUM(IFNULL(customer_purchase_order_list_qty,0)) as customer_purchase_order_list_qty,  
        CONCAT('DN : ',delivery_note_customer_code) as customer_purchase_order_list_remark  ,
        delivery_note_customer_code,
        tb_delivery_note_customer.delivery_note_customer_complete
        FROM tb_delivery_note_customer_list 
        LEFT JOIN tb_delivery_note_customer ON tb_delivery_note_customer_list.delivery_note_customer_id = tb_delivery_note_customer.delivery_note_customer_id 
        LEFT JOIN tb_customer_purchase_order_list ON tb_delivery_note_customer_list.delivery_note_customer_list_id = tb_customer_purchase_order_list.delivery_note_customer_list_id 
        LEFT JOIN tb_product ON tb_delivery_note_customer_list.product_id = tb_product.product_id 
        LEFT JOIN tb_product_customer_price ON (tb_delivery_note_customer_list.product_id = tb_product_customer_price.product_id AND tb_delivery_note_customer.customer_id = tb_product_customer_price.customer_id)
        WHERE tb_delivery_note_customer.customer_id = '$customer_id' 
        AND tb_delivery_note_customer_list.delivery_note_customer_list_id NOT IN ($str_rt) 
        AND delivery_note_customer_code LIKE ('%$search%')
        GROUP BY  tb_delivery_note_customer_list.delivery_note_customer_list_id 
        HAVING MAX(IFNULL(delivery_note_customer_list_qty,0)) - SUM(IFNULL(customer_purchase_order_list_qty,0)) > 0 ";

        $data = [];

        // echo $sql_request."<br><br>"; 

        if ($result = mysqli_query(static::$db,$sql_request, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }


        return $data;
    }

    function generateCustomerPurchaseOrderByID($id){
        $sql = " SELECT * 
        FROM tb_quotation 
        LEFT JOIN tb_customer ON tb_quotation.customer_id = tb_customer.customer_id 
        LEFT JOIN tb_user ON tb_quotation.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position  ON tb_user.user_position_id = tb_user_position.user_position_id 
        WHERE quotation_id = '$id' 
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

    function generateCustomerPurchaseOrderListBy($quotation_id){
        $sql = " SELECT tb_quotation_list.product_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,   
        '' as customer_purchase_order_product_name, 
        '' as customer_purchase_order_product_detail, 
        '0' as customer_purchase_order_list_id,  
        quotation_list_qty as customer_purchase_order_list_qty, 
        quotation_list_price as customer_purchase_order_list_price, 
        quotation_list_sum as customer_purchase_order_list_price_sum, 
        '' as customer_purchase_order_list_delivery_min,  
        '' as customer_purchase_order_list_delivery_max, 
        quotation_list_remark as customer_purchase_order_list_remark 
        FROM tb_quotation_list LEFT JOIN tb_product ON tb_quotation_list.product_id = tb_product.product_id 
        WHERE quotation_id = '$quotation_id' 
        ORDER BY quotation_list_id 
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
    function getSupplierByCustomerPurchaseOrder($customer_purchase_order_id){
        $sql = " SELECT tb_customer_purchase_order_list_detail.supplier_id, supplier_name_en ,supplier_domestic ,tb_customer_purchase_order.customer_purchase_order_id
        FROM tb_customer_purchase_order_list_detail
        LEFT JOIN tb_customer_purchase_order_list ON tb_customer_purchase_order_list.customer_purchase_order_list_id = tb_customer_purchase_order_list_detail.customer_purchase_order_list_id 
        LEFT JOIN tb_customer_purchase_order ON tb_customer_purchase_order.customer_purchase_order_id = tb_customer_purchase_order_list.customer_purchase_order_id 
        LEFT JOIN tb_supplier ON tb_customer_purchase_order_list_detail.supplier_id = tb_supplier.supplier_id 
        WHERE tb_customer_purchase_order.customer_purchase_order_id = '$customer_purchase_order_id' 
        AND purchase_order_list_id ='0' 
        AND tb_customer_purchase_order_list_detail.supplier_id != '0'
        GROUP BY tb_customer_purchase_order_list_detail.supplier_id 
        ";
        // echo $sql;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }
    function updateCustomerPurchaseOrderByID($id,$data = []){
        $sql = " UPDATE tb_customer_purchase_order SET 
        customer_id = '".$data['customer_id']."',  
        employee_id = '".$data['employee_id']."', 
        customer_purchase_order_code_gen = '".$data['customer_purchase_order_code_gen']."', 
        customer_purchase_order_code = '".$data['customer_purchase_order_code']."', 
        customer_purchase_order_file = '".$data['customer_purchase_order_file']."', 
        customer_purchase_order_credit_term = '".$data['customer_purchase_order_credit_term']."', 
        customer_purchase_order_delivery_term = '".$data['customer_purchase_order_delivery_term']."', 
        customer_purchase_order_delivery_by = '".$data['customer_purchase_order_delivery_by']."', 
        customer_purchase_order_date = '".$data['customer_purchase_order_date']."', 
        customer_purchase_order_remark = '".$data['customer_purchase_order_remark']."', 
        customer_purchase_order_finish = '".$data['customer_purchase_order_finish']."', 
        customer_purchase_order_status = '".$data['customer_purchase_order_status']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE customer_purchase_order_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function setCustomerPurchaseOrderStatusByID($id,$data = []){
        $sql = " UPDATE tb_customer_purchase_order SET  
        customer_purchase_order_finish = '".$data['customer_purchase_order_finish']."', 
        customer_purchase_order_status = '".$data['customer_purchase_order_status']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE customer_purchase_order_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }


    function insertCustomerPurchaseOrder($data = []){
        $sql = " INSERT INTO tb_customer_purchase_order (
            customer_id, 
            employee_id,           
            customer_purchase_order_code_gen,
            customer_purchase_order_code,
            customer_purchase_order_file,
            customer_purchase_order_credit_term,
            customer_purchase_order_delivery_term,
            customer_purchase_order_delivery_by,
            customer_purchase_order_date,
            customer_purchase_order_remark,
            customer_purchase_order_status,
            customer_purchase_order_finish,
            addby,
            adddate,
            updateby,
            lastupdate) 
        VALUES ('".
        $data['customer_id']."','". 
        $data['employee_id']."','".
        $data['customer_purchase_order_code_gen']."','".
        $data['customer_purchase_order_code']."','".
        $data['customer_purchase_order_file']."','".
        $data['customer_purchase_order_credit_term']."','".
        $data['customer_purchase_order_delivery_term']."','".
        $data['customer_purchase_order_delivery_by']."','".
        $data['customer_purchase_order_date']."','".
        $data['customer_purchase_order_remark']."','".
        $data['customer_purchase_order_status']."','". 
        $data['customer_purchase_order_finish']."','". 
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


    function deleteCustomerPurchaseOrderByID($id){
        $sql = " DELETE FROM tb_customer_purchase_order_list_detail WHERE customer_purchase_order_list_id IN (SELECT customer_purchase_order_list_id FROM tb_customer_purchase_order_list WHERE customer_purchase_order_id = '$id') ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        
        $sql = " DELETE FROM tb_customer_purchase_order_list WHERE customer_purchase_order_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_customer_purchase_order WHERE customer_purchase_order_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        

    }
}
?>