<?php

require_once("BaseModel.php");
class PurchaseOrderModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db,"utf8");
    }

    function getPurchaseOrderInvoiceBy($purchase_order_id) {

        $sql = "SELECT * 
        FROM `tb_invoice_supplier` 
        LEFT JOIN tb_invoice_supplier_list ON tb_invoice_supplier.invoice_supplier_id = tb_invoice_supplier_list.invoice_supplier_id
        LEFT JOIN tb_purchase_order_list ON tb_invoice_supplier_list.purchase_order_list_id = tb_purchase_order_list.purchase_order_list_id 
        WHERE tb_purchase_order_list.purchase_order_id = '$purchase_order_id' 
        AND invoice_supplier_close = '0' 
        GROUP BY tb_invoice_supplier.invoice_supplier_id
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

    function getPurchaseOrderInvoiceProductBy($purchase_order_list_id) {

        $sql = " SELECT 
        tb_invoice_supplier.invoice_supplier_id,
        tb_invoice_supplier.supplier_id,
        tb_invoice_supplier.invoice_supplier_code,
        tb_invoice_supplier.invoice_supplier_code_gen,
        tb_invoice_supplier.invoice_supplier_total_price,
        tb_invoice_supplier.invoice_supplier_vat_price,
        tb_invoice_supplier.invoice_supplier_net_price,
        tb_invoice_supplier_list.product_id,
        tb_invoice_supplier_list.invoice_supplier_list_qty,
        tb_invoice_supplier_list.invoice_supplier_list_price,
        tb_product.product_code,
        tb_product.product_name
        FROM `tb_invoice_supplier`
        LEFT JOIN tb_invoice_supplier_list ON tb_invoice_supplier.invoice_supplier_id = tb_invoice_supplier_list.invoice_supplier_id
        LEFT JOIN tb_purchase_order_list ON tb_invoice_supplier_list.purchase_order_list_id = tb_purchase_order_list.purchase_order_list_id 
        LEFT JOIN tb_product ON tb_purchase_order_list.product_id = tb_product.product_id 
        WHERE tb_purchase_order_list.purchase_order_list_id = '$purchase_order_list_id' 
        AND invoice_supplier_close = '0' 
        GROUP BY tb_invoice_supplier.invoice_supplier_id 
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

    function getPurchaseOrderListBy($date_start = "",$date_end = "",$supplier_id = "",$keyword = "") {

        $str_supplier = "";
        $str_date = "";

        if($date_start != "" && $date_end != ""){
            $str_date = " AND STR_TO_DATE(tb_purchase_order.purchase_order_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(tb_purchase_order.purchase_order_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = " AND STR_TO_DATE(tb_purchase_order.purchase_order_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = " AND STR_TO_DATE(tb_purchase_order.purchase_order_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($supplier_id != ""){
            $str_supplier = "AND tb_purchase_order.supplier_id = '$supplier_id' ";
        }
        $sql = "SELECT 
        tb_purchase_order.purchase_order_id,
        tb_purchase_order.supplier_id,
        tb_purchase_order.purchase_order_type,
        tb_purchase_order.employee_id,
        tb_purchase_order.purchase_order_code,
        tb_purchase_order.purchase_order_status,
        tb_purchase_order.purchase_order_date,
        tb_purchase_order_list.purchase_order_list_id,
        tb_purchase_order_list.product_id,
        tb_purchase_order_list.purchase_order_list_qty,
        tb_purchase_order_list.purchase_order_list_price,
        tb_supplier.supplier_id,
        tb_supplier.supplier_code,
        tb_supplier.supplier_name_th,
        tb_supplier.supplier_name_en,
        tb_product.product_code,
        tb_product.product_name,
        tb_invoice_supplier_list.invoice_supplier_list_qty
        FROM `tb_purchase_order`
        LEFT JOIN tb_purchase_order_list ON tb_purchase_order.purchase_order_id = tb_purchase_order_list.purchase_order_id
        LEFT JOIN tb_supplier ON tb_purchase_order.supplier_id = tb_supplier.supplier_id
        LEFT JOIN tb_product ON tb_purchase_order_list.product_id = tb_product.product_id
        LEFT JOIN tb_invoice_supplier_list ON tb_product.product_id = tb_invoice_supplier_list.product_id 
        LEFT JOIN tb_invoice_supplier ON  tb_invoice_supplier_list.invoice_supplier_id = tb_invoice_supplier.invoice_supplier_id 
        WHERE(  
            tb_purchase_order.purchase_order_code LIKE ('%$keyword%') OR
            product_code LIKE ('%$keyword%')
            ) 
        AND invoice_supplier_close = '0' 
            $str_date
            $str_supplier

        GROUP BY tb_purchase_order_list.purchase_order_list_id
        ORDER BY STR_TO_DATE(tb_purchase_order.purchase_order_date,'%d-%m-%Y %H:%i:%s') DESC
        ";
        // echo"<pre>";
        // print_r($sql);
        // echo "</pre>";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getPurchaseOrderListByProductID($date_start = "",$date_end = "",$supplier_id = "",$keyword = "",$product_id="") {

        $str_supplier = "";
        $str_date = "";

        if($date_start != "" && $date_end != ""){
            $str_date = " STR_TO_DATE(tb_purchase_order.purchase_order_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(tb_purchase_order.purchase_order_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = " STR_TO_DATE(tb_purchase_order.purchase_order_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = " STR_TO_DATE(tb_purchase_order.purchase_order_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($supplier_id != ""){
            $str_supplier = "AND tb_purchase_order.supplier_id = '$supplier_id' ";
        }
        $sql = "SELECT 
        tb_purchase_order.purchase_order_id,
        tb_purchase_order.supplier_id,
        tb_purchase_order.purchase_order_type,
        tb_purchase_order.employee_id,
        tb_purchase_order.purchase_order_code,
        tb_purchase_order.purchase_order_status,
        tb_purchase_order.purchase_order_date,
        tb_purchase_order_list.purchase_order_list_id,
        tb_purchase_order_list.product_id,
        tb_purchase_order_list.purchase_order_list_qty,
        tb_purchase_order_list.purchase_order_list_price,
        tb_supplier.supplier_id,
        tb_supplier.supplier_code,
        tb_supplier.supplier_name_th,
        tb_supplier.supplier_name_en,
        tb_product.product_code,
        tb_product.product_name,
        tb_invoice_supplier_list.invoice_supplier_list_qty
        FROM `tb_purchase_order`
        LEFT JOIN tb_purchase_order_list ON tb_purchase_order.purchase_order_id = tb_purchase_order_list.purchase_order_id
        LEFT JOIN tb_supplier ON tb_purchase_order.supplier_id = tb_supplier.supplier_id
        LEFT JOIN tb_product ON tb_purchase_order_list.product_id = tb_product.product_id
        LEFT JOIN tb_invoice_supplier_list ON tb_product.product_id = tb_invoice_supplier_list.product_id
        LEFT JOIN tb_invoice_supplier ON  tb_invoice_supplier_list.invoice_supplier_id = tb_invoice_supplier.invoice_supplier_id         
        WHERE  tb_purchase_order_list.product_id ='$product_id'  
        AND invoice_supplier_close = '0' 
        GROUP BY tb_purchase_order_list.purchase_order_list_id
        ORDER BY STR_TO_DATE(tb_purchase_order.purchase_order_date,'%d-%m-%Y %H:%i:%s') DESC
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

    function getPurchaseOrderByAcceptStatus($approve_status = [], $supplier_domestic = '', $date_start = '', $date_end = '', $keyword = ''){

        $str_date = ""; 
        $str_apporved = '';
        $str_supplier_domestic = '';

        if(is_array($approve_status)){ 
            for($i=0; $i < count($approve_status) ;$i++){
                $str_apporved .= " '".$approve_status[$i]."' ";
                if($i + 1 < count($approve_status)){
                    $str_apporved .= ",";
                }
            }
        }else if ($data != ''){
            $str_apporved = " '".$approve_status."' ";
        }else{
            $str_apporved="''";
        }


        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(purchase_order_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(purchase_order_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(purchase_order_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(purchase_order_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($supplier_domestic ==''){
            $str_supplier_domestic = "AND supplier_domestic = 'ภายนอกประเทศ'";
        }else{
            $str_supplier_domestic = "AND supplier_domestic = '$supplier_domestic'";
        }

        $sql =  "   SELECT *  
                    FROM tb_purchase_order  
                    LEFT JOIN tb_user as tb1 ON tb_purchase_order.employee_id = tb1.user_id 
                    LEFT JOIN tb_supplier ON tb_purchase_order.supplier_id = tb_supplier.supplier_id
                    WHERE purchase_order_accept_status IN ($str_apporved) 
                    AND purchase_order_cancelled = '0' 
                    $str_supplier_domestic  
                    $str_date
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

    function getPurchaseOrderByStatus($approve_status = [], $supplier_domestic = '', $date_start = '', $date_end = '', $keyword = ''){

        $str_date = ""; 
        $str_apporved = '';
        $str_supplier_domestic = '';

        if(is_array($approve_status)){ 
            for($i=0; $i < count($approve_status) ;$i++){
                $str_apporved .= " '".$approve_status[$i]."' ";
                if($i + 1 < count($approve_status)){
                    $str_apporved .= ",";
                }
            }
        }else if ($data != ''){
            $str_apporved = " '".$approve_status."' ";
        }else{
            $str_apporved="''";
        }


        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(purchase_order_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(purchase_order_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(purchase_order_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(purchase_order_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($supplier_domestic =='ภายนอกประเทศ'){
            $str_supplier_domestic = "AND supplier_domestic = 'ภายนอกประเทศ'";
        }else if($supplier_domestic =='ภายในประเทศ'){
            $str_supplier_domestic = "AND supplier_domestic = 'ภายในประเทศ'";
        }else {
            $str_supplier_domestic = "";
        }

        $sql =  "   SELECT *  
                    FROM tb_purchase_order  
                    LEFT JOIN tb_user as tb1 ON tb_purchase_order.employee_id = tb1.user_id 
                    LEFT JOIN tb_supplier ON tb_purchase_order.supplier_id = tb_supplier.supplier_id
                    WHERE purchase_order_status IN ($str_apporved) 
                    AND purchase_order_cancelled = '0' 
                    $str_supplier_domestic  
                    $str_date
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

    function getPurchaseOrderBy($date_start = "",$date_end = "",$supplier_id = "",$keyword = "",$user_id = "",$supplier_domestic){

        $str_supplier = "";
        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(purchase_order_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(purchase_order_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(purchase_order_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(purchase_order_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND employee_id = '$user_id' ";
        }

        if($supplier_id != ""){
            $str_supplier = "AND tb2.supplier_id = '$supplier_id' ";
        }
        if($supplier_domestic ==''){
            $supplier_domestic = "ภายนอกประเทศ";
        }

        $sql = " SELECT purchase_order_id, 
        purchase_order_type, 
        purchase_order_code, 
        purchase_order_date, 
        purchase_order_rewrite_id,
        IFNULL((
            SELECT COUNT(*) FROM tb_purchase_order WHERE purchase_order_rewrite_id = tb.purchase_order_id 
        ),0) as count_rewrite,
        purchase_order_rewrite_no,
        purchase_order_status,
        purchase_order_accept_status,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        IFNULL(CONCAT(tb3.user_name,' ',tb3.user_lastname),'-') as accept_name, 
        purchase_order_credit_term, 
        purchase_order_delivery_term, 
        purchase_order_cancelled,
        IFNULL(tb2.supplier_name_en,'-') as supplier_name, 
        purchase_order_delivery_by ,
        tb2.supplier_id 
        FROM tb_purchase_order as tb
        LEFT JOIN tb_user as tb1 ON tb.employee_id = tb1.user_id 
        LEFT JOIN tb_user as tb3 ON tb.purchase_order_accept_by = tb3.user_id 
        LEFT JOIN tb_supplier as tb2 ON tb.supplier_id = tb2.supplier_id 
        WHERE (
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%')
            OR  CONCAT(tb3.user_name,' ',tb3.user_lastname) LIKE ('%$keyword%')
            OR  purchase_order_code LIKE ('%$keyword%') 
        ) AND tb2.supplier_domestic = '$supplier_domestic' 
        $str_supplier
        $str_date
        $str_user
        GROUP BY purchase_order_id
        ORDER BY purchase_order_code DESC
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
    function getPurchaseOrderByMobile($date_start = "",$date_end = "",$supplier_id = "",$keyword = "",$user_id = "",$supplier_domestic){

        $str_supplier = "";
        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(purchase_order_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(purchase_order_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(purchase_order_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(purchase_order_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND employee_id = '$user_id' ";
        }

        if($supplier_id != ""){
            $str_supplier = "AND tb2.supplier_id = '$supplier_id' ";
        }

        if($supplier_domestic !=''){
            $supplier_domestic = " AND tb2.supplier_domestic = '$supplier_domestic' ";
        }

        $sql = " SELECT purchase_order_id, 
        purchase_order_type, 
        purchase_order_code, 
        purchase_order_date, 
        purchase_order_rewrite_id,
        IFNULL((
            SELECT COUNT(*) FROM tb_purchase_order WHERE purchase_order_rewrite_id = tb.purchase_order_id 
        ),0) as count_rewrite,
        purchase_order_rewrite_no,
        purchase_order_status,
        purchase_order_accept_status,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        IFNULL(CONCAT(tb3.user_name,' ',tb3.user_lastname),'-') as accept_name, 
        purchase_order_credit_term, 
        purchase_order_delivery_term, 
        purchase_order_cancelled,
        IFNULL(tb2.supplier_name_en,'-') as supplier_name, 
        purchase_order_delivery_by ,
        tb2.supplier_id 
        FROM tb_purchase_order as tb
        LEFT JOIN tb_user as tb1 ON tb.employee_id = tb1.user_id 
        LEFT JOIN tb_user as tb3 ON tb.purchase_order_accept_by = tb3.user_id 
        LEFT JOIN tb_supplier as tb2 ON tb.supplier_id = tb2.supplier_id 
        WHERE (
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%')
            OR  CONCAT(tb3.user_name,' ',tb3.user_lastname) LIKE ('%$keyword%')
            OR  purchase_order_code LIKE ('%$keyword%')
        ) 
        AND  purchase_order_status LIKE ('Request')
        AND purchase_order_cancelled = '0'
        $supplier_domestic
        $str_supplier
        $str_date
        $str_user
        GROUP BY purchase_order_id
        ORDER BY purchase_order_code DESC
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
    function getPurchaseOrderExport($purchase_order_id = "",$supplier_id = "",$date_start = "",$date_end = "",$keyword=""){

        $str_id = "";
        $str_supplier = "";
        $str_date = ""; 

        if($purchase_order_id == ""){

            if($date_start != "" && $date_end != ""){
                $str_date = "AND STR_TO_DATE(purchase_order_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(purchase_order_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
            }else if ($date_start != ""){
                $str_date = "AND STR_TO_DATE(purchase_order_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
            }else if ($date_end != ""){
                $str_date = "AND STR_TO_DATE(purchase_order_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
            }

            if($supplier_id != ""){
                $str_supplier = "AND tb_purchase_order.supplier_id = '$supplier_id' ";
            }

        }else{
            $str_id = "AND tb_purchase_order.purchase_order_id = '$purchase_order_id' ";
        }

        



        $sql = " SELECT *
        FROM tb_purchase_order_list 
        LEFT JOIN tb_purchase_order ON tb_purchase_order_list.purchase_order_id = tb_purchase_order.purchase_order_id 
        LEFT JOIN tb_supplier ON  tb_purchase_order.supplier_id = tb_supplier.supplier_id 
        LEFT JOIN tb_product ON tb_purchase_order_list.product_id = tb_product.product_id 
        WHERE  purchase_order_code LIKE '%$keyword%' 
        $str_id  
        $str_date 
        $str_supplier 
        
        ORDER BY purchase_order_code , purchase_order_list_id DESC 
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
    function getPurchaseOrderByKeyword($keyword = ""){

        $sql = " SELECT *
        FROM tb_purchase_order as tb
        LEFT JOIN tb_user as tb1 ON tb.employee_id = tb1.user_id 
        LEFT JOIN tb_user as tb3 ON tb.purchase_order_accept_by = tb3.user_id 
        LEFT JOIN tb_supplier as tb2 ON tb.supplier_id = tb2.supplier_id 
        WHERE  purchase_order_code LIKE ('%$keyword%')   
        ORDER BY purchase_order_code DESC 
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
    function getPurchaseOrderByID($id){
        $sql = " SELECT * 
        FROM tb_purchase_order 
        LEFT JOIN tb_supplier ON tb_purchase_order.supplier_id = tb_supplier.supplier_id
        LEFT JOIN tb_currency ON tb_supplier.currency_id = tb_currency.currency_id 
        LEFT JOIN tb_user ON tb_purchase_order.employee_id = tb_user.user_id 
        WHERE purchase_order_id = '$id' 
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

    function getPurchaseOrderByCode($code){
        $sql = " SELECT * 
        FROM tb_purchase_order 
        LEFT JOIN tb_supplier ON tb_purchase_order.supplier_id = tb_supplier.supplier_id 
        LEFT JOIN tb_user ON tb_purchase_order.employee_id = tb_user.user_id 
        WHERE purchase_order_code = '$code' 
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
    function getPurchaseOrderByAutoComplete($keyword){
        $sql = " SELECT purchase_order_code,purchase_order_date
        FROM tb_purchase_order   
        WHERE purchase_order_code LIKE ('%$keyword%')
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

    function getPurchaseOrderViewByID($id){
        $sql = " SELECT *   
        FROM tb_purchase_order 
        LEFT JOIN tb_user ON tb_purchase_order.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_supplier ON tb_purchase_order.supplier_id = tb_supplier.supplier_id 
        LEFT JOIN tb_currency ON tb_supplier.currency_id = tb_currency.currency_id 
        WHERE purchase_order_id = '$id' 
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

    function getPurchaseOrderPrintViewByID($id){
        $sql = " SELECT tb_purchase_order.*,
        tb_supplier.*, 
        tb_currency.*, 
        tb1.user_name as employee_name,
        tb1.user_lastname as employee_lastname,
        tb1.user_signature as employee_signature,
        tb2.user_name as approve_name,
        tb2.user_lastname as approve_lastname,
        tb2.user_signature as approve_signature 
        FROM tb_purchase_order 
        LEFT JOIN tb_user as tb1 ON tb_purchase_order.employee_id = tb1.user_id 
        LEFT JOIN tb_user as tb2 ON tb_purchase_order.purchase_order_accept_by = tb2.user_id  
        LEFT JOIN tb_supplier ON tb_purchase_order.supplier_id = tb_supplier.supplier_id 
        LEFT JOIN tb_currency ON tb_supplier.currency_id = tb_currency.currency_id 
        WHERE purchase_order_id = '$id' 
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

    function getPurchaseOrderLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(purchase_order_code,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  purchase_order_lastcode 
        FROM tb_purchase_order 
        WHERE purchase_order_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['purchase_order_lastcode'];
        }

    }

   
    function cancelPurchaseOrderByID($id){
        $sql = " UPDATE tb_purchase_order SET 
        purchase_order_cancelled = '1', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE purchase_order_id = '$id' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function uncancelPurchaseOrderByID($id){
        $sql = " UPDATE tb_purchase_order SET 
        purchase_order_cancelled = '0', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE purchase_order_id = '$id' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }
    function updatePurchaseOrderByID($id,$data = []){
        $sql = " UPDATE tb_purchase_order SET  
        supplier_id = '".$data['supplier_id']."', 
        employee_id = '".$data['employee_id']."', 
        purchase_order_category = '".$data['purchase_order_category']."', 
        purchase_order_code = '".$data['purchase_order_code']."', 
        purchase_order_code_online = '".$data['purchase_order_code_online']."', 
        purchase_order_credit_term = '".$data['purchase_order_credit_term']."', 
        purchase_order_delivery_term = '".$data['purchase_order_delivery_term']."', 
        purchase_order_delivery_by = '".$data['purchase_order_delivery_by']."',  
        purchase_order_agreement = '".$data['purchase_order_agreement']."',  
        purchase_order_remark = '".static::$db->real_escape_string($data['purchase_order_remark'])."',  
        purchase_order_date = '".$data['purchase_order_date']."', 
        purchase_order_status = '".$data['purchase_order_status']."', 
        purchase_order_total_price = '".$data['purchase_order_total_price']."', 
        purchase_order_vat = '".$data['purchase_order_vat']."', 
        purchase_order_vat_price = '".$data['purchase_order_vat_price']."', 
        purchase_order_net_price = '".$data['purchase_order_net_price']."',
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE purchase_order_id = '$id' 
        ";  
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }
    function updatePurchaseOrderReviseByID($id,$data = []){
        $sql = " UPDATE tb_purchase_order SET   
        purchase_order_rewrite_id = '".$data['purchase_order_rewrite_id']."', 
        purchase_order_rewrite_no = '".$data['purchase_order_rewrite_no']."' ,
        purchase_order_status = '".$data['purchase_order_status']."' ,
        updateby = '".$data['updateby']."' ,
        lastupdate = NOW() 
        WHERE purchase_order_id = '$id' 
        ";  
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function updatePurchaseOrderByOnline($id,$data = []){
        $sql = " UPDATE tb_purchase_order SET  
        
        purchase_order_code_online = '".$data['purchase_order_code_online']."'
        WHERE purchase_order_id = '$id' 
        ";

        // echo "<pre>";
        // print_r($sql);
        // echo"</pre>";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }
    function updatePurchaseOrderByDetail($id,$data = []){
        $sql = " UPDATE tb_purchase_order SET  
        
        purchase_order_total_price = '".$data['purchase_order_total_price']."',
        purchase_order_vat = '".$data['purchase_order_vat']."',
        purchase_order_vat_price = '".$data['purchase_order_vat_price']."',
        purchase_order_net_price = '".$data['purchase_order_net_price']."'
        WHERE purchase_order_id = '$id' 
        ";  
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function insertPurchaseOrder($data = []){
        $sql = " INSERT INTO tb_purchase_order ( 
            supplier_id,
            employee_id,
            purchase_order_rewrite_id,
            purchase_order_rewrite_no,
            purchase_order_accept_status,
            purchase_order_accept_by,
            purchase_order_accept_date,
            purchase_order_status,
            purchase_order_type,
            purchase_order_category,
            purchase_order_code,
            purchase_order_code_online,
            purchase_order_credit_term,
            purchase_order_delivery_term,
            purchase_order_delivery_by, 
            purchase_order_agreement, 
            purchase_order_remark, 
            purchase_order_date,
            purchase_order_total_price,
            purchase_order_vat,
            purchase_order_vat_price,
            purchase_order_net_price,
            addby,
            adddate,
            updateby,
            lastupdate) 
        VALUES ('". 
        $data['supplier_id']."','".
        $data['employee_id']."','".
        $data['purchase_order_rewrite_id']."','".
        $data['purchase_order_rewrite_no']."','".
        $data['purchase_order_accept_status']."','".
        $data['purchase_order_accept_by']."','".
        $data['purchase_order_accept_date']."','".
        $data['purchase_order_status']."','".
        $data['purchase_order_type']."','".
        $data['purchase_order_category']."','".
        $data['purchase_order_code']."','".
        $data['purchase_order_code_online']."','".
        $data['purchase_order_credit_term']."','".
        $data['purchase_order_delivery_term']."','".
        $data['purchase_order_delivery_by']."','". 
        $data['purchase_order_agreement']."','". 
        static::$db->real_escape_string($data['purchase_order_remark'])."','". 
        $data['purchase_order_date']."','".
        $data['purchase_order_total_price']."','".
        $data['purchase_order_vat']."','".
        $data['purchase_order_vat_price']."','".
        $data['purchase_order_net_price']."','".
        $data['addby']."',".
        "NOW(),'".
        $data['addby'].
        "',NOW()); 
        ";

            //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return '';
        }

    } 
    function updatePurchaseOrderAcceptByID($id,$data = []){
        $sql = " UPDATE tb_purchase_order SET 
        purchase_order_accept_status = '".$data['purchase_order_accept_status']."', 
        purchase_order_accept_by = '".$data['purchase_order_accept_by']."', 
        purchase_order_accept_date = NOW(), 
        purchase_order_status = '".$data['purchase_order_status']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE purchase_order_id = '$id' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }


    function updatePurchaseOrderRequestByID($id,$data = []){
        $sql = " UPDATE tb_purchase_order SET 
        purchase_order_accept_status = '".$data['purchase_order_accept_status']."', 
        purchase_order_accept_by = '".$data['purchase_order_accept_by']."', 
        purchase_order_accept_date = '', 
        purchase_order_status = '".$data['purchase_order_status']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE purchase_order_id = '$id' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function updatePurchaseOrderStatusByID($id,$data = []){
        if ($data['updateby'] != ""){
            $str = "updateby = '".$data['updateby']."', ";
        }
        
        $sql = " UPDATE tb_purchase_order SET 
        purchase_order_status = '".$data['purchase_order_status']."', 
        $str 
        lastupdate = NOW() 
        WHERE purchase_order_id = '$id' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function getSupplierOrder($supplier_domestic = ""){
        if($supplier_domestic !=''){
            $supplier_domestic_str = "AND supplier_domestic LIKE ('%$supplier_domestic%') ";
        }
        $sql = "SELECT tb_supplier.supplier_id, supplier_name_en , supplier_name_th 
                FROM tb_supplier 
                WHERE tb_supplier.supplier_id IN ( 
                    
                    SELECT DISTINCT tb_delivery_note_supplier.supplier_id 
                    FROM tb_delivery_note_supplier_list 
                    LEFT JOIN tb_delivery_note_supplier 
                    ON tb_delivery_note_supplier_list.delivery_note_supplier_id = tb_delivery_note_supplier.delivery_note_supplier_id
                    WHERE purchase_order_list_id = 0 
                    AND request_test_list_id = 0

                    UNION

                    SELECT DISTINCT tb_purchase_request_list.supplier_id 
                    FROM tb_purchase_request_list 
                    LEFT JOIN tb_purchase_request ON tb_purchase_request_list.purchase_request_id = tb_purchase_request.purchase_request_id                   
                    WHERE purchase_order_list_id = 0 
                    AND tb_purchase_request.purchase_request_cancelled = 0 
                    AND purchase_request_type IN ('Sale','Use') 
                    AND purchase_request_accept_status = 'Approve' 
                    

                    UNION 

                    SELECT DISTINCT tb_customer_purchase_order_list_detail.supplier_id 
                    FROM tb_customer_purchase_order_list 
                    LEFT JOIN tb_customer_purchase_order_list_detail 
                    ON  tb_customer_purchase_order_list.customer_purchase_order_list_id = tb_customer_purchase_order_list.customer_purchase_order_list_id
                    LEFT JOIN tb_customer_purchase_order
                    ON tb_customer_purchase_order_list.customer_purchase_order_id = tb_customer_purchase_order.customer_purchase_order_id        
                    WHERE tb_customer_purchase_order_list_detail.purchase_order_list_id = 0   
                    AND tb_customer_purchase_order_list_detail.supplier_id != 0 
                )
                $supplier_domestic_str
                GROUP BY supplier_id 
        ";
        $data = [];
        // echo $sql;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }
        return $data;
    }


    function getSupplierRegrind(){

        $sql = "SELECT supplier_id, supplier_name_en , supplier_name_th 
                FROM tb_supplier 
                WHERE supplier_id IN (                     

                    SELECT DISTINCT supplier_id 
                    FROM tb_regrind_supplier_receive 
                    LEFT JOIN tb_regrind_supplier_receive_list 
                    ON  tb_regrind_supplier_receive.regrind_supplier_receive_id = tb_regrind_supplier_receive_list.regrind_supplier_receive_id
                    WHERE tb_regrind_supplier_receive_list.purchase_order_list_id = 0   
                    AND tb_regrind_supplier_receive.supplier_id != 0 

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


    function getSupplierBlankedOrder(){

        $sql = "SELECT  tb_purchase_request.purchase_request_id, purchase_request_code, tb_supplier.supplier_id, supplier_name_en , supplier_name_th 
                FROM tb_purchase_request
                LEFT JOIN tb_purchase_request_list ON  tb_purchase_request.purchase_request_id = tb_purchase_request_list.purchase_request_id
                LEFT JOIN tb_supplier  ON  tb_purchase_request_list.supplier_id = tb_supplier.supplier_id 
                WHERE purchase_order_list_id = 0 
                AND purchase_request_accept_status = 'Approve' 
                AND purchase_request_type = 'Sale Blanked' 
                GROUP BY tb_purchase_request_list.supplier_id , tb_purchase_request.purchase_request_id 
                
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

    function getSupplierTestOrder(){

        $sql = "SELECT tb_supplier.supplier_id, supplier_name_en , supplier_name_th 
                FROM tb_supplier
                WHERE supplier_id IN ( 
                    
                    SELECT DISTINCT tb_delivery_note_supplier.supplier_id 
                    FROM tb_delivery_note_supplier_list 
                    LEFT JOIN tb_delivery_note_supplier 
                    ON tb_delivery_note_supplier_list.delivery_note_supplier_id = tb_delivery_note_supplier.delivery_note_supplier_id
                    WHERE purchase_order_list_id = 0 
                    AND request_test_list_id IN (

                        SELECT DISTINCT tb_request_test_list.request_test_list_id 
                        FROM tb_request_test_list 
                        LEFT JOIN tb_request_standard_list ON tb_request_test_list.request_test_list_id = tb_request_standard_list.request_test_list_id
                        WHERE IFNULL(tb_request_standard_list.tool_test_result,0) = 1  
                        
                        UNION 

                        SELECT DISTINCT tb_request_test_list.request_test_list_id 
                        FROM tb_request_test_list 
                        LEFT JOIN tb_request_special_list ON tb_request_test_list.request_test_list_id = tb_request_special_list.request_test_list_id
                        WHERE IFNULL(tb_request_special_list.tool_test_result,0) = 1  
                        
                        UNION 

                        SELECT DISTINCT tb_request_test_list.request_test_list_id 
                        FROM tb_request_test_list 
                        LEFT JOIN tb_request_regrind_list ON tb_request_test_list.request_test_list_id = tb_request_regrind_list.request_test_list_id
                        WHERE IFNULL(tb_request_regrind_list.tool_test_result,0) = 1  
                        
                    ) 

                    UNION 

                    SELECT DISTINCT supplier_id 
                    FROM tb_request_standard_list 
                    LEFT JOIN tb_request_standard 
                    ON tb_request_standard_list.request_standard_id = tb_request_standard.request_standard_id 
                    WHERE purchase_order_list_id = 0 
                    AND purchase_order_open = 1 
                    AND request_standard_accept_status = 'Approve' 

                    UNION

                    SELECT DISTINCT supplier_id 
                    FROM tb_request_special_list 
                    LEFT JOIN tb_request_special 
                    ON tb_request_special_list.request_special_id = tb_request_special.request_special_id 
                    WHERE purchase_order_list_id = 0 
                    AND purchase_order_open = 1 
                    AND request_special_accept_status = 'Approve' 
                    
                    UNION

                    SELECT DISTINCT supplier_id 
                    FROM tb_request_regrind_list 
                    LEFT JOIN tb_request_regrind 
                    ON tb_request_regrind_list.request_regrind_id = tb_request_regrind.request_regrind_id 
                    WHERE purchase_order_list_id = 0 
                    AND purchase_order_open = 1 
                    AND request_regrind_accept_status = 'Approve' 
                   

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

    function generatePurchaseOrderListByCustomerPurchaseOrderId($supplier_id,$customer_purchase_order_id){
        if($customer_purchase_order_id !=""){
            $str_cpo_id = " AND tb_customer_purchase_order.customer_purchase_order_id='$customer_purchase_order_id' ";
        }
        $sql_customer = "SELECT product_id,  
            stock_group_id,
            purchase_request_list_id,
            customer_purchase_order_list_detail_id,
            delivery_note_supplier_list_id,
            regrind_supplier_receive_list_id,
            request_standard_list_id,
            request_special_list_id,
            request_regrind_list_id,
            product_code, 
            product_name, 
            qty-purchase_order_list_qty as purchase_order_list_qty, 
            purchase_order_list_price ,
            purchase_order_list_remark  FROM 
            (
            SELECT tb_customer_purchase_order_list.product_id,  
            stock_group_id,
            '0' as purchase_request_list_id,
            customer_purchase_order_list_detail_id,
            '0' as delivery_note_supplier_list_id,
            '0' as regrind_supplier_receive_list_id,
            '0' as request_standard_list_id,
            '0' as request_special_list_id,
            '0' as request_regrind_list_id,
            CONCAT(product_code_first,product_code) as product_code, 
            product_name, 
            IFNULL((
                SELECT SUM(purchase_order_list_qty) 
                FROM tb_purchase_order_list_data 
                LEFT JOIN tb_purchase_order_list ON tb_purchase_order_list_data.purchase_order_list_id = tb_purchase_order_list.purchase_order_list_id 
                WHERE customer_purchase_order_list_detail_id = tb1.customer_purchase_order_list_detail_id
                GROUP BY customer_purchase_order_list_detail_id
            ),0) as purchase_order_list_qty, 
            MAX(qty) AS qty,
            IFNULL(product_buyprice,0) as purchase_order_list_price ,
            CONCAT('Customer : ',customer_name_en,' PO : ',customer_purchase_order_code) as purchase_order_list_remark 
            FROM tb_customer_purchase_order 
            LEFT JOIN tb_customer ON tb_customer_purchase_order.customer_id = tb_customer.customer_id
            LEFT JOIN tb_customer_purchase_order_list ON tb_customer_purchase_order.customer_purchase_order_id = tb_customer_purchase_order_list.customer_purchase_order_id 
            LEFT JOIN tb_product ON tb_customer_purchase_order_list.product_id = tb_product.product_id 
            LEFT JOIN tb_product_supplier ON tb_customer_purchase_order_list.product_id = tb_product_supplier.product_id 
            LEFT JOIN tb_customer_purchase_order_list_detail AS tb1 ON tb_customer_purchase_order_list.customer_purchase_order_list_id = tb1.customer_purchase_order_list_id 
            WHERE tb1.supplier_id = '$supplier_id' 
            $str_cpo_id
            AND ( customer_purchase_order_code LIKE ('%$search%') OR CONCAT(product_code_first,product_code) LIKE ('%$search%') OR product_name LIKE ('%$search%') )  
            GROUP BY customer_purchase_order_list_detail_id 
            ) AS tb2 WHERE purchase_order_list_qty < qty";

            // echo $sql_customer."<br><br>";
            if ($result = mysqli_query(static::$db,$sql_customer, MYSQLI_USE_RESULT)) {
                
                while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                    $data[] = $row;
                }
                $result->close();
                
            }
            return $data;
    }
    function generatePurchaseOrderListBySupplierId(
        $supplier_id /*รหัสผู้ขาย*/, 
        $purchase_request_id /*รหัสใบร้องขอ*/,
        $type /*ประเภทใบ PO*/, 
        $data_pr = [] /*purchase request*/, 
        $data_cpo = [] /*customer purchase order*/, 
        $data_dn = [] /*delivery note supplier*/, 
        $data_srr = [] /*supplier regrind recieve*/, 
        $data_rst = [] /*request standard tool*/, 
        $data_rspt = [] /*request special tool*/, 
        $data_rrt = [] /*request regrind tool*/, 
        $data_qty = [] /*purchase_order_list_qty*/, 
        $search = "" /*คำค้น*/){
            // $data_cpo = [];
            // $data_pr = [];
        $data = [];
        

        //echo "5555".$type;
        if($type == "BLANKED"){

            $str_pr ="'0'";

            if(is_array($data_pr) && count($data_pr) > 0){ 

                $str_pr ="";
                for($i=0; $i < count($data_pr) ;$i++){
                    $str_pr .= " '".$data_pr[$i]."' ";
                    if($i + 1 < count($data_pr)){
                        $str_pr .= ",";
                    }
                }

            }else if ($data_pr != ''){
                $str_pr = "'".$data_pr."'";
            }else{
                $str_pr="'0'";
            }

            if(is_array($data_qty) && count($data_qty) > 0){ 

                $str_qty ="";
                for($i=0; $i < count($data_qty) ;$i++){
                    $str_qty .= " '".$data_qty[$i]."' ";
                    if($i + 1 < count($data_qty)){
                        $str_qty .= ",";
                    }
                }

            }else if ($data_qty != ''){
                $str_qty = "'".$data_qty."'";
            }else{
                $str_qty="'0'";
            }

            

            $sql_request = "SELECT tb_purchase_request_list.product_id,  
            stock_group_id,
            purchase_request_list_id,
            '0' as customer_purchase_order_list_detail_id,
            '0' as delivery_note_supplier_list_id,
            '0' as regrind_supplier_receive_list_id,
            '0' as request_standard_list_id,
            '0' as request_special_list_id,
            '0' as request_regrind_list_id,
            CONCAT(product_code_first,product_code) as product_code, 
            product_name, 
            purchase_request_list_delivery as purchase_order_list_delivery_min, 
            purchase_request_list_qty as purchase_order_list_qty, 
            IFNULL((
                SELECT product_buyprice 
                FROM  tb_product_supplier 
                WHERE product_id = tb_product.product_id 
                AND supplier_id = tb_purchase_request_list.supplier_id
                AND tb_purchase_request_list.purchase_request_list_qty >= product_supplier_qty
                ORDER BY product_supplier_qty DESC
                LIMIT 0,1
            ),0) as purchase_order_list_price , 
            IF(customer_name_en IS NULL,CONCAT('PR : ',purchase_request_code),CONCAT('PR : ',purchase_request_code,', Customer : ',customer_name_en)) as purchase_order_list_remark 
            FROM tb_purchase_request 
            LEFT JOIN tb_purchase_request_list ON tb_purchase_request.purchase_request_id = tb_purchase_request_list.purchase_request_id 
            LEFT JOIN tb_customer ON tb_purchase_request.customer_id = tb_customer.customer_id 
            LEFT JOIN tb_product ON tb_purchase_request_list.product_id = tb_product.product_id 
            LEFT JOIN tb_product_supplier ON (tb_purchase_request_list.product_id = tb_product_supplier.product_id AND tb_purchase_request_list.supplier_id = tb_product_supplier.supplier_id) 
            WHERE tb_purchase_request_list.supplier_id = '$supplier_id'
            AND purchase_order_list_id = '0' 
            AND purchase_request_list_id NOT IN ($str_pr) 
            AND ( 
                    purchase_request_code LIKE ('%$search%') 
                    OR CONCAT(product_code_first,product_code) LIKE ('%$search%') 
                    OR product_name LIKE ('%$search%')
                )  
            AND purchase_request_type = 'Sale Blanked' 
            AND purchase_request_accept_status = 'Approve' 
            GROUP BY purchase_request_list_id 
            ORDER BY purchase_request_list_id ASC   
               ";

            //echo "LLL : ".$sql_request."<br><br>";
            if ($result = mysqli_query(static::$db,$sql_request, MYSQLI_USE_RESULT)) {
                
                while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                    $data[] = $row;
                }
                $result->close();
                
            }




        }else if($type == "TEST"){
            
            $str_dn ="'0'";

            if(is_array($data_dn) && count($data_dn) > 0){ 
                $str_dn ="";
                for($i=0; $i < count($data_dn) ;$i++){
                    $str_dn .= " '".$data_dn[$i]."' ";
                    if($i + 1 < count($data_dn)){
                        $str_dn .= ',';
                    }
                }
            }else if ($data_dn != ''){
                $str_dn = "'".$data_dn."'";
            }else{
                $str_dn="'0'";
            }


            $str_rst ="'0'";

            if(is_array($data_rst) && count($data_rst) > 0){ 
                $str_rst ="";
                for($i=0; $i < count($data_rst) ;$i++){
                    $str_rst .= " '".$data_rst[$i]."' ";
                    if($i + 1 < count($data_rst)){
                        $str_rst .= ',';
                    }
                }
            }else if ($data_rst != ''){
                $str_rst = "'".$data_rst."'";
            }else{
                $str_rst="'0'";
            }


            $str_rspt ="'0'";

            if(is_array($data_rspt) && count($data_rspt) > 0){ 
                $str_rspt ="";
                for($i=0; $i < count($data_rspt) ;$i++){
                    $str_rspt .= " '".$data_rspt[$i]."' ";
                    if($i + 1 < count($data_rspt)){
                        $str_rspt .= ',';
                    }
                }
            }else if ($data_rspt != ''){
                $str_rspt = "'".$data_rspt."'";
            }else{
                $str_rspt="'0'";
            }


            $str_rrt ="'0'";

            if(is_array($data_rrt) && count($data_rrt) > 0){ 
                $str_rrt ="";
                for($i=0; $i < count($data_rrt) ;$i++){
                    $str_rrt .= " '".$data_rrt[$i]."' ";
                    if($i + 1 < count($data_rrt)){
                        $str_rrt .= ',';
                    }
                }
            }else if ($data_rrt != ''){
                $str_rrt = "'".$data_rrt."'";
            }else{
                $str_rrt="'0'";
            }

            $sql_dn = "SELECT tb_delivery_note_supplier_list.product_id, 
            '0' as stock_group_id,
            '0' as purchase_request_list_id,
            '0' as customer_purchase_order_list_detail_id,
            delivery_note_supplier_list_id,
            '0' as regrind_supplier_receive_list_id,
            '0' as request_standard_list_id,
            '0' as request_special_list_id,
            '0' as request_regrind_list_id,
            CONCAT(product_code_first,product_code) as product_code, 
            product_name, 
            (delivery_note_supplier_list_qty) as purchase_order_list_qty, 
            IFNULL(product_buyprice,0) as purchase_order_list_price ,
            '' as purchase_order_list_delivery_min, 
            CONCAT('Supplier DN : ',delivery_note_supplier_code) as purchase_order_list_remark 
            FROM tb_delivery_note_supplier 
            LEFT JOIN tb_delivery_note_supplier_list ON tb_delivery_note_supplier.delivery_note_supplier_id = tb_delivery_note_supplier_list.delivery_note_supplier_id 
            LEFT JOIN tb_product ON tb_delivery_note_supplier_list.product_id = tb_product.product_id 
            LEFT JOIN tb_product_supplier ON tb_delivery_note_supplier_list.product_id = tb_product_supplier.product_id 
            WHERE tb_delivery_note_supplier.supplier_id = '$supplier_id' 
            AND purchase_order_list_id = 0 
            AND request_test_list_id IN (
                SELECT DISTINCT request_test_list_id 
                FROM tb_request_standard_list  
                LEFT JOIN tb_request_standard ON tb_request_standard_list.request_standard_id = tb_request_standard.request_standard_id 
                WHERE (purchase_order_open = 0 
                AND tool_test_result = 1 ) OR  purchase_order_open = 1

                UNION 

                SELECT DISTINCT request_test_list_id 
                FROM tb_request_special_list  
                LEFT JOIN tb_request_special ON tb_request_special_list.request_special_id = tb_request_special.request_special_id 
                WHERE (purchase_order_open = 0 
                AND tool_test_result = 1 ) OR  purchase_order_open = 1

                UNION 

                SELECT DISTINCT request_test_list_id 
                FROM tb_request_regrind_list  
                LEFT JOIN tb_request_regrind ON tb_request_regrind_list.request_regrind_id = tb_request_regrind.request_regrind_id 
                WHERE (purchase_order_open = 0 
                AND tool_test_result = 1 ) OR  purchase_order_open = 1

            ) 
            AND delivery_note_supplier_list_id NOT IN ($str_dn) 
            AND ( delivery_note_supplier_code LIKE ('%$search%') OR CONCAT(product_code_first,product_code) LIKE ('%$search%') OR product_name LIKE ('%$search%') )
            GROUP BY request_test_list_id;
            ";

            

            if ($result = mysqli_query(static::$db,$sql_dn, MYSQLI_USE_RESULT)) {
                
                while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                    $data[] = $row;
                }
                $result->close();
                
            }


            $sql_rspt = "SELECT tb_request_standard_list.product_id,  
            '0' as stock_group_id,
            '0' as purchase_request_list_id,
            '0' as customer_purchase_order_list_detail_id,
            '0' as delivery_note_supplier_list_id,
            '0' as regrind_supplier_receive_list_id,
            request_standard_list_id,
            '0' as request_special_list_id,
            '0' as request_regrind_list_id,
            CONCAT(product_code_first,product_code) as product_code, 
            product_name, 
            request_standard_list_delivery as purchase_order_list_delivery_min, 
            (request_standard_list_qty) as purchase_order_list_qty, 
            IFNULL(product_buyprice,0) as purchase_order_list_price ,
            CONCAT('Supplier RST : ',request_standard_code) as purchase_order_list_remark 
            FROM tb_request_standard 
            LEFT JOIN tb_request_standard_list ON tb_request_standard.request_standard_id = tb_request_standard_list.request_standard_id 
            LEFT JOIN tb_product ON tb_request_standard_list.product_id = tb_product.product_id 
            LEFT JOIN tb_product_supplier ON tb_request_standard_list.product_id = tb_product_supplier.product_id 
            WHERE tb_request_standard.supplier_id = '$supplier_id' 
            AND purchase_order_list_id = 0 
            AND purchase_order_open = 1 
            AND request_standard_list_id NOT IN ($str_rspt) 
            AND ( request_standard_code LIKE ('%$search%') OR CONCAT(product_code_first,product_code) LIKE ('%$search%') OR product_name LIKE ('%$search%') )
            GROUP BY request_standard_list_id;
            ";

            // echo $sql_rspt."<br><br>";

            if ($result = mysqli_query(static::$db,$sql_rspt, MYSQLI_USE_RESULT)) {
                
                while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                    $data[] = $row;
                }
                $result->close();
                
            }


            $sql_rst = "SELECT tb_request_special_list.product_id,  
            '0' as stock_group_id,
            '0' as purchase_request_list_id,
            '0' as customer_purchase_order_list_detail_id,
            '0' as delivery_note_supplier_list_id,
            '0' as regrind_supplier_receive_list_id,
            '0' as request_standard_list_id,
            request_special_list_id,
            '0' as request_regrind_list_id,
            CONCAT(product_code_first,product_code) as product_code, 
            product_name, 
            request_special_list_delivery as purchase_order_list_delivery_min, 
            (request_special_list_qty) as purchase_order_list_qty, 
            IFNULL(product_buyprice,0) as purchase_order_list_price ,
            CONCAT('Supplier RST : ',request_special_code) as purchase_order_list_remark 
            FROM tb_request_special 
            LEFT JOIN tb_request_special_list ON tb_request_special.request_special_id = tb_request_special_list.request_special_id 
            LEFT JOIN tb_product ON tb_request_special_list.product_id = tb_product.product_id 
            LEFT JOIN tb_product_supplier ON tb_request_special_list.product_id = tb_product_supplier.product_id 
            WHERE tb_request_special.supplier_id = '$supplier_id' 
            AND purchase_order_list_id = 0 
            AND tool_test_result = 1 
            AND request_special_list_id NOT IN ($str_rst) 
            AND ( request_special_code LIKE ('%$search%') OR CONCAT(product_code_first,product_code) LIKE ('%$search%') OR product_name LIKE ('%$search%'))
            GROUP BY request_special_list_id;
            ";

            // echo $sql_rst."<br><br>";

            if ($result = mysqli_query(static::$db,$sql_rst, MYSQLI_USE_RESULT)) {
                
                while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                    $data[] = $row;
                }
                $result->close();
                
            }


            $sql_rst = "SELECT tb_request_regrind_list.product_id,  
            '0' as stock_group_id,
            '0' as purchase_request_list_id,
            '0' as customer_purchase_order_list_detail_id,
            '0' as delivery_note_supplier_list_id,
            '0' as regrind_supplier_receive_list_id,
            '0' as request_standard_list_id,
            '0' as request_special_list_id,
            request_regrind_list_id,
            CONCAT(product_code_first,product_code) as product_code, 
            product_name, 
            request_regrind_list_delivery as purchase_order_list_delivery_min, 
            (request_regrind_list_qty) as purchase_order_list_qty, 
            IFNULL(product_buyprice,0) as purchase_order_list_price ,
            CONCAT('Supplier RST : ',request_regrind_code) as purchase_order_list_remark 
            FROM tb_request_regrind 
            LEFT JOIN tb_request_regrind_list ON tb_request_regrind.request_regrind_id = tb_request_regrind_list.request_regrind_id 
            LEFT JOIN tb_product ON tb_request_regrind_list.product_id = tb_product.product_id 
            LEFT JOIN tb_product_supplier ON tb_request_regrind_list.product_id = tb_product_supplier.product_id 
            WHERE tb_request_regrind.supplier_id = '$supplier_id' 
            AND purchase_order_list_id = 0 
            AND tool_test_result = 1 
            AND request_regrind_list_id NOT IN ($str_rst) 
            AND ( request_regrind_code LIKE ('%$search%') OR CONCAT(product_code_first,product_code) LIKE ('%$search%') OR product_name LIKE ('%$search%'))
            GROUP BY request_regrind_list_id ;
            ";

            // echo $sql_rst."<br><br>";

            if ($result = mysqli_query(static::$db,$sql_rst, MYSQLI_USE_RESULT)) {
                
                while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                    $data[] = $row;
                }
                $result->close();
                
            }


        }else if($type == "STANDARD"){
            $str_pr ="'0'";

            if(is_array($data_pr) && count($data_pr) > 0){ 
                $str_pr ="";
                for($i=0; $i < count($data_pr) ;$i++){
                    $str_pr .= " '".$data_pr[$i]."' ";
                    if($i + 1 < count($data_pr)){
                        $str_pr .= ",";
                    }
                }
            }else if ($data_pr != ''){
                $str_pr = "'".$data_pr."'";
            }else{
                $str_pr="'0'";
            }


            $str_cpo ="'0'";

            if(is_array($data_cpo) && count($data_cpo) > 0){ 
                $str_cpo ="";
                for($i=0; $i < count($data_cpo) ;$i++){
                    $str_cpo .= " '".$data_cpo[$i]."' ";
                    if($i + 1 < count($data_cpo)){
                        $str_cpo .= ",";
                    }
                }
            }else if ($data_cpo != ''){
                $str_cpo = "'".$data_cpo."'";
            }else{
                $str_cpo="'0'";
            }


            $str_dn ="'0'";

            if(is_array($data_dn)  && count($data_dn) > 0 ){ 
                $str_dn ="";
                for($i=0; $i < count($data_dn) ;$i++){
                    $str_dn .= " '".$data_dn[$i]."' ";
                    if($i + 1 < count($data_dn)){
                        $str_dn .= ",";
                    }
                }
            }else if ($data_dn != ''){
                $str_dn = "'".$data_dn."'";
            }else{
                $str_dn="'0'";
            }



            $sql_request = "SELECT tb_purchase_request_list.product_id,  
            stock_group_id,
            purchase_request_list_id,
            '0' as customer_purchase_order_list_detail_id,
            '0' as delivery_note_supplier_list_id,
            '0' as regrind_supplier_receive_list_id,
            '0' as request_standard_list_id,
            '0' as request_special_list_id,
            '0' as request_regrind_list_id,
            CONCAT(product_code_first,product_code) as product_code, 
            product_name, 
            purchase_request_list_delivery as purchase_order_list_delivery_min, 
            purchase_request_list_qty as purchase_order_list_qty, 
            IFNULL((
                SELECT product_buyprice 
                FROM  tb_product_supplier 
                WHERE product_id = tb_product.product_id 
                AND supplier_id = tb_purchase_request_list.supplier_id
                AND tb_purchase_request_list.purchase_request_list_qty >= product_supplier_qty
                ORDER BY product_supplier_qty DESC
                LIMIT 0,1
            ),0) as purchase_order_list_price , 
            IF(customer_name_en IS NULL,CONCAT('PR : ',purchase_request_code),CONCAT('PR : ',purchase_request_code,', Customer : ',customer_name_en)) as purchase_order_list_remark 
            FROM tb_purchase_request 
            LEFT JOIN tb_purchase_request_list ON tb_purchase_request.purchase_request_id = tb_purchase_request_list.purchase_request_id 
            LEFT JOIN tb_customer ON tb_purchase_request.customer_id = tb_customer.customer_id 
            LEFT JOIN tb_product ON tb_purchase_request_list.product_id = tb_product.product_id 
            LEFT JOIN tb_product_supplier ON (tb_purchase_request_list.product_id = tb_product_supplier.product_id AND tb_purchase_request_list.supplier_id = tb_product_supplier.supplier_id) 
            WHERE tb_purchase_request_list.supplier_id = '$supplier_id'
            AND purchase_order_list_id = '0' 
            AND purchase_request_list_id NOT IN ($str_pr) 
            AND ( 
                    purchase_request_code LIKE ('%$search%') 
                    OR CONCAT(product_code_first,product_code) LIKE ('%$search%') 
                    OR product_name LIKE ('%$search%')
                )  
            AND purchase_request_type IN ('Sale','Use') 
            AND purchase_request_accept_status = 'Approve' 
            GROUP BY purchase_request_list_id 
            ORDER BY purchase_request_list_id ASC
             ";

            

            // echo $sql_request."<br>sql_request<br>";

            if ($result = mysqli_query(static::$db,$sql_request, MYSQLI_USE_RESULT)) {
                
                while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                    $data[] = $row;
                }
                $result->close();
                
            }

        

            $sql_customer = "SELECT product_id,  
            '' as purchase_order_list_delivery_min, 
            stock_group_id,
            purchase_request_list_id,
            customer_purchase_order_list_detail_id,
            delivery_note_supplier_list_id,
            regrind_supplier_receive_list_id,
            request_standard_list_id,
            request_special_list_id,
            request_regrind_list_id,
            product_code, 
            product_name, 
            qty-purchase_order_list_qty as purchase_order_list_qty, 
            purchase_order_list_price ,
            purchase_order_list_remark  FROM 
            (
                SELECT tb_customer_purchase_order_list.product_id,  
                    stock_group_id,
                    '0' as purchase_request_list_id,
                    customer_purchase_order_list_detail_id,
                    '0' as delivery_note_supplier_list_id,
                    '0' as regrind_supplier_receive_list_id,
                    '0' as request_standard_list_id,
                    '0' as request_special_list_id,
                    '0' as request_regrind_list_id,
                    CONCAT(product_code_first,product_code) as product_code, 
                    product_name, 
                        IFNULL((
                            SELECT SUM(purchase_order_list_qty) 
                            FROM tb_purchase_order_list_data 
                            LEFT JOIN tb_purchase_order_list ON tb_purchase_order_list_data.purchase_order_list_id = tb_purchase_order_list.purchase_order_list_id 
                            WHERE customer_purchase_order_list_detail_id = tb1.customer_purchase_order_list_detail_id
                            GROUP BY customer_purchase_order_list_detail_id
                        ),0) as purchase_order_list_qty, 
                    MAX(qty) AS qty,
                    IFNULL((
                            SELECT product_buyprice 
                            FROM  tb_product_supplier 
                            WHERE product_id = tb_product.product_id 
                            AND supplier_id = tb1.supplier_id
                            AND tb1.qty >= product_supplier_qty
                            ORDER BY product_supplier_qty DESC
                            LIMIT 0,1
                        ),0) as purchase_order_list_price ,

            CONCAT('Customer : ',customer_name_en,' PO : ',customer_purchase_order_code) as purchase_order_list_remark 
            FROM tb_customer_purchase_order 
            LEFT JOIN tb_customer ON tb_customer_purchase_order.customer_id = tb_customer.customer_id
            LEFT JOIN tb_customer_purchase_order_list ON tb_customer_purchase_order.customer_purchase_order_id = tb_customer_purchase_order_list.customer_purchase_order_id 
            LEFT JOIN tb_product ON tb_customer_purchase_order_list.product_id = tb_product.product_id 
            LEFT JOIN tb_product_supplier ON tb_customer_purchase_order_list.product_id = tb_product_supplier.product_id 
            LEFT JOIN tb_customer_purchase_order_list_detail AS tb1 ON tb_customer_purchase_order_list.customer_purchase_order_list_id = tb1.customer_purchase_order_list_id 
            WHERE tb1.supplier_id = '$supplier_id' 
            AND tb_customer_purchase_order.customer_purchase_order_finish ='0'
            AND customer_purchase_order_list_detail_id NOT IN ($str_cpo) 
            AND ( customer_purchase_order_code LIKE ('%$search%') OR CONCAT(product_code_first,product_code) LIKE ('%$search%') OR product_name LIKE ('%$search%'))  
            GROUP BY customer_purchase_order_list_detail_id 
            ) AS tb2 WHERE purchase_order_list_qty < qty";

            // echo $sql_customer."<br>sql_customer<br>";
            if ($result = mysqli_query(static::$db,$sql_customer, MYSQLI_USE_RESULT)) {
                
                while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                    $data[] = $row;
                }
                $result->close();
                
            }

            $sql_dn = "SELECT tb_delivery_note_supplier_list.product_id,   
            '0' as stock_group_id,
            '0' as purchase_request_list_id,
            '0' as customer_purchase_order_list_detail_id,
            delivery_note_supplier_list_id,
            '0' as regrind_supplier_receive_list_id,
            '0' as request_standard_list_id,
            '0' as request_special_list_id,
            '0' as request_regrind_list_id,
            '' as purchase_order_list_delivery_min, 
            CONCAT(product_code_first,product_code) as product_code, 
            product_name, 
            (delivery_note_supplier_list_qty) as purchase_order_list_qty, 
            IFNULL((
                            SELECT product_buyprice 
                            FROM  tb_product_supplier 
                            WHERE product_id = tb_product.product_id 
                            AND supplier_id = tb_delivery_note_supplier.supplier_id
                            AND delivery_note_supplier_list_qty >= product_supplier_qty
                            ORDER BY product_supplier_qty DESC
                            LIMIT 0,1
                        ),0) as purchase_order_list_price ,
            
            CONCAT('Supplier DN : ',delivery_note_supplier_code) as purchase_order_list_remark 
            FROM tb_delivery_note_supplier 
            LEFT JOIN tb_delivery_note_supplier_list ON tb_delivery_note_supplier.delivery_note_supplier_id = tb_delivery_note_supplier_list.delivery_note_supplier_id 
            LEFT JOIN tb_product ON tb_delivery_note_supplier_list.product_id = tb_product.product_id 
            LEFT JOIN tb_product_supplier ON tb_delivery_note_supplier_list.product_id = tb_product_supplier.product_id 
            WHERE tb_delivery_note_supplier.supplier_id = '$supplier_id' 
            AND purchase_order_list_id = 0 
            AND request_test_list_id = 0 
            AND delivery_note_supplier_list_id NOT IN ($str_dn) 
            AND (delivery_note_supplier_code LIKE ('%$search%') OR CONCAT(product_code_first,product_code) LIKE ('%$search%') OR product_name LIKE ('%$search%'))
            GROUP BY delivery_note_supplier_list_id
            ;
            ";

            // echo $sql_dn."<br>sql_dn<br>";

            if ($result = mysqli_query(static::$db,$sql_dn, MYSQLI_USE_RESULT)) {
                
                while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                    $data[] = $row;
                }
                $result->close();
                
            }


        }else if($type == "REGRIND"){
 
            $str_srr ="'0'";

            if(is_array($data_srr) && count($data_srr) > 0){ 
                $str_srr ='';
                for($i=0; $i < count($data_srr) ;$i++){
                    $str_srr .= " '".$data_srr[$i]."' ";
                    if($i + 1 < count($data_srr)){
                        $str_srr .= ",";
                    }
                }
            }else if ($data_srr != ''){
                $str_srr = "'".$data_srr."'";
            }else{
                $str_srr="'0'";
            }



            
            $sql_customer = "SELECT tb_regrind_supplier_receive_list.product_id,   
            '0' as stock_group_id,
            '0' as purchase_request_list_id,
            '0' as customer_purchase_order_list_detail_id,
            '0' as delivery_note_supplier_list_id,
            regrind_supplier_receive_list_id,
            '0' as request_standard_list_id,
            '0' as request_special_list_id,
            '0' as request_regrind_list_id,
            CONCAT(product_code_first,product_code) as product_code, 
            IFNULL(stock_group_id,(SELECT IFNULL(MIN(stock_group_id),0) FROM tb_stock_group WHERE 1)) as stock_group_id,
            product_name,  
            regrind_supplier_receive_list_qty as purchase_order_list_qty, 
            IFNULL(product_buyprice,0) as purchase_order_list_price, 
            '' as purchase_order_list_delivery_min, 
            CONCAT('RGR : ',regrind_supplier_receive_code) as purchase_order_list_remark 
            FROM tb_regrind_supplier_receive 
            LEFT JOIN tb_regrind_supplier_receive_list ON tb_regrind_supplier_receive.regrind_supplier_receive_id = tb_regrind_supplier_receive_list.regrind_supplier_receive_id
            LEFT JOIN tb_product ON tb_regrind_supplier_receive_list.product_id = tb_product.product_id 
            LEFT JOIN tb_product_supplier ON tb_regrind_supplier_receive_list.product_id = tb_product_supplier.product_id 
            WHERE tb_regrind_supplier_receive.supplier_id = '$supplier_id' 
            AND purchase_order_list_id = 0 
            AND regrind_supplier_receive_list_id NOT IN ($str_srr) 
            AND (product_name LIKE ('%$search%') OR regrind_supplier_receive_code LIKE ('%$search%') OR CONCAT(product_code_first,product_code) LIKE ('%$search%') OR product_name LIKE ('%$search%') ) 
            GROUP BY regrind_supplier_receive_list_id ";


            if ($result = mysqli_query(static::$db,$sql_customer, MYSQLI_USE_RESULT)) {
                
                while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                    $data[] = $row;
                }
                $result->close();
                
            }
        }  
        

        return $data;
    }


    function deletePurchaseOrderByID($id){

        $sql = " UPDATE tb_purchase_request_list SET purchase_order_list_id = '0' WHERE purchase_order_list_id (SELECT purchase_order_list_id FROM tb_purchase_order_list WHERE purchase_order_id = '$id') ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " UPDATE tb_customer_purchase_order_list_detail SET purchase_order_list_id = '0' WHERE purchase_order_list_id (SELECT purchase_order_list_id FROM tb_purchase_order_list WHERE purchase_order_id = '$id') ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " UPDATE tb_delivery_note_supplier_list SET purchase_order_list_id = '0' WHERE purchase_order_list_id (SELECT purchase_order_list_id FROM tb_purchase_order_list WHERE purchase_order_id = '$id') ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " UPDATE tb_regrind_supplier_receive_list SET purchase_order_list_id = '0' WHERE purchase_order_list_id (SELECT purchase_order_list_id FROM tb_purchase_order_list WHERE purchase_order_id = '$id') ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " UPDATE tb_request_standard_list SET purchase_order_list_id = '0' WHERE purchase_order_list_id (SELECT purchase_order_list_id FROM tb_purchase_order_list WHERE purchase_order_id = '$id') ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " UPDATE tb_request_special_list SET purchase_order_list_id = '0' WHERE purchase_order_list_id (SELECT purchase_order_list_id FROM tb_purchase_order_list WHERE purchase_order_id = '$id') ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " UPDATE tb_request_regrind_list SET purchase_order_list_id = '0' WHERE purchase_order_list_id (SELECT purchase_order_list_id FROM tb_purchase_order_list WHERE purchase_order_id = '$id') ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_purchase_order WHERE purchase_order_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        
        $sql = " DELETE FROM tb_purchase_order_list WHERE purchase_order_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function getPurchaseOrderCodeByInvoiceSupplierID($id){
        $sql = "  SELECT GROUP_CONCAT( DISTINCT tb_purchase_order.purchase_order_code) As purchase_order_code FROM `tb_invoice_supplier_list` 
                  LEFT JOIN tb_purchase_order_list ON tb_invoice_supplier_list.purchase_order_list_id = tb_purchase_order_list.purchase_order_list_id
                  LEFT JOIN tb_purchase_order ON tb_purchase_order_list.purchase_order_id = tb_purchase_order.purchase_order_id
                  LEFT JOIN tb_invoice_supplier ON  tb_invoice_supplier_list.invoice_supplier_id = tb_invoice_supplier.invoice_supplier_id 
                  WHERE tb_invoice_supplier_list.invoice_supplier_id = '$id'
                  AND invoice_supplier_close = '0'";


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