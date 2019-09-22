<?php

require_once("BaseModel.php");
class CustomerModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db,"utf8");
        
        
    }
    
    
    function getCustomerByKeyword( $keyword=''){ 
        $sql = " SELECT customer_id, customer_code, customer_name_th, customer_name_en , customer_tax , customer_tel, customer_email,customer_branch    
        FROM tb_customer 
        WHERE ( 
                customer_code LIKE ('%$keyword%') 
                OR customer_name_en LIKE ('%$keyword%') 
                OR customer_name_th LIKE ('%$keyword%')  
              )
              $str_domestic
        ORDER BY customer_code 
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
    function getCustomeByID( $customer_id=''){ 
        $sql = " SELECT *  
        FROM tb_customer 
        WHERE customer_id ='$customer_id' 
        ";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data  = $row;
            }
            $result->close();
            return $data;
        }

    }


    function getCustomerBy($customer_type = '',$customer_end_user_type = '',$keyword = '',$keyword_end = ''){

        if($customer_type != ""){
            $str_type = " AND tb1.customer_type_id = '$customer_type' ";
        }

        if($customer_end_user_type == "0"){
            $str_end = "AND tb1.customer_end_user_type = '0'"; 
            
        }else if($customer_end_user_type == "1"){
            $str_end = "AND tb1.customer_end_user_type = '1'"; 
       
        }

        $sql = " SELECT tb1.customer_id, 
                        tb1.customer_code, 
                        tb1.customer_name_th, 
                        tb1.customer_name_en , 
                        tb1.customer_tax , 
                        tb1.customer_tel, 
                        tb1.customer_email, 
                        tb1.customer_end_user_type,
                        customer_type_name,  
                        tb1.customer_branch, 
                        tb1.customer_approve,
                        IFNULL(CONCAT(tb_user.user_name,' ',tb_user.user_lastname),'-') as employee_name, 
                        (SELECT COUNT(*) FROM tb_invoice_customer WHERE customer_id = tb1.customer_id) as sale_status,
                        tb2.customer_name_en as customer_end_user_name,
                        (SELECT COUNT(*) FROM tb_customer WHERE customer_end_user = tb1.customer_id AND customer_end_user_type = '1') as count_end_user
        FROM tb_customer as tb1 
        LEFT JOIN tb_customer as tb2 ON tb1.customer_end_user = tb2.customer_id 
        LEFT JOIN tb_customer_type ON tb1.customer_type_id = tb_customer_type.customer_type_id 
        LEFT JOIN tb_user ON tb1.sale_id = tb_user.user_id 
        WHERE ( 
            tb1.customer_code LIKE ('%$keyword%') 
            OR tb1.customer_name_th LIKE ('%$keyword%') 
            OR tb1.customer_name_en LIKE ('%$keyword%') 
            OR tb1.customer_tax LIKE ('%$keyword%') 
            OR tb1.customer_tel LIKE ('%$keyword%') 
            OR tb1.customer_email LIKE ('%$keyword%') 
        ) AND ( 
            tb2.customer_code LIKE ('%$keyword_end%') 
            OR IFNULL(tb2.customer_name_th,'') LIKE ('%$keyword_end%') 
            OR IFNULL(tb2.customer_name_en,'') LIKE ('%$keyword_end%') 
            OR IFNULL(tb2.customer_tax,'') LIKE ('%$keyword_end%') 
            OR IFNULL(tb2.customer_tel,'') LIKE ('%$keyword_end%') 
            OR IFNULL(tb2.customer_email,'') LIKE ('%$keyword_end%') 
        )
        $str_type 
        $str_end  
        ORDER BY tb1.customer_code  
        "; 
        // echo "<pre>";
        // echo $sql;
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

    function getCustomerByDealer($keyword=''){
        $sql = " SELECT customer_id, customer_code, customer_name_en, customer_branch
        FROM tb_customer 
        WHERE customer_end_user_type = '0' AND customer_approve = 'Approved'  
        ORDER BY customer_code
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

    function getCustomerBySale($sale_id, $customer_type = '',$customer_end_user_type = '',$keyword = '',$keyword_end = ''){

        if($customer_type != ""){
            $str_type = " AND tb1.customer_type_id = '$customer_type' ";
        }

        if($customer_end_user_type == "0"){
            $str_end = "AND tb1.customer_end_user_type = '0'"; 
            
        }else if($customer_end_user_type == "1"){
            $str_end = "AND tb1.customer_end_user_type = '1'"; 
        
        }

        $sql = " SELECT tb1.customer_id, 
                        tb1.customer_code, 
                        tb1.customer_name_th, 
                        tb1.customer_name_en , 
                        tb1.customer_tax , 
                        tb1.customer_tel, 
                        tb1.customer_email, 
                        tb1.customer_end_user_type,
                        tb1.customer_approve,
                        customer_type_name,   
                        tb2.customer_name_en as customer_end_user_name,
                        (SELECT COUNT(*) FROM tb_customer WHERE customer_end_user = tb1.customer_id AND customer_end_user_type = '1') as count_end_user
        FROM tb_customer as tb1 
        LEFT JOIN tb_customer as tb2 ON tb1.customer_end_user = tb2.customer_id 
        LEFT JOIN tb_customer_type ON tb1.customer_type_id = tb_customer_type.customer_type_id 
        WHERE (
            tb1.sale_id = '$sale_id' 
            OR tb2.sale_id = '$sale_id' 
        ) AND ( 
            tb1.customer_code LIKE ('%$keyword%') 
            OR tb1.customer_name_th LIKE ('%$keyword%') 
            OR tb1.customer_name_en LIKE ('%$keyword%') 
            OR tb1.customer_tax LIKE ('%$keyword%') 
            OR tb1.customer_tel LIKE ('%$keyword%') 
            OR tb1.customer_email LIKE ('%$keyword%') 
        ) AND ( 
            tb2.customer_code LIKE ('%$keyword_end%') 
            OR IFNULL(tb2.customer_name_th,'') LIKE ('%$keyword_end%') 
            OR IFNULL(tb2.customer_name_en,'') LIKE ('%$keyword_end%') 
            OR IFNULL(tb2.customer_tax,'') LIKE ('%$keyword_end%') 
            OR IFNULL(tb2.customer_tel,'') LIKE ('%$keyword_end%') 
            OR IFNULL(tb2.customer_email,'') LIKE ('%$keyword_end%') 
        )
        $str_type 
        $str_end  
        ORDER BY tb1.customer_code  
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
    function getCustomerBySaleApp($sale_id, $customer_type = '',$customer_end_user_type = '',$keyword = '',$keyword_end = ''){

        if($customer_type != ""){
            $str_type = " AND tb1.customer_type_id = '$customer_type' ";
        }

        if($customer_end_user_type == "0"){
            $str_end = "AND tb1.customer_end_user_type = '0'"; 
            
        }else if($customer_end_user_type == "1"){
            $str_end = "AND tb1.customer_end_user_type > '0'"; 
            
        }

        $sql = " SELECT tb1.customer_id, 
                        tb1.customer_code, 
                        tb1.customer_name_th, 
                        tb1.customer_name_en , 
                        tb1.customer_tax , 
                        tb1.customer_tel, 
                        tb1.customer_email, 
                        tb1.customer_end_user_type,
                        tb1.customer_approve,
                        customer_type_name, 
                        (SELECT COUNT(*) FROM tb_customer WHERE customer_end_user = tb1.customer_id) as count_end_user
        FROM tb_customer as tb1  
        LEFT JOIN tb_customer_type ON tb1.customer_type_id = tb_customer_type.customer_type_id 
        WHERE (
            tb1.sale_id = '$sale_id'  
        ) AND ( 
            tb1.customer_code LIKE ('%$keyword%') 
            OR tb1.customer_name_th LIKE ('%$keyword%') 
            OR tb1.customer_name_en LIKE ('%$keyword%') 
            OR tb1.customer_tax LIKE ('%$keyword%') 
            OR tb1.customer_tel LIKE ('%$keyword%') 
            OR tb1.customer_email LIKE ('%$keyword%') 
        )
        $str_type  
        ORDER BY tb1.customer_end_user_type ,tb1.customer_code 
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
    function getCustomerByManager($sale_id, $customer_type = '',$customer_end_user_type = '',$keyword = '',$keyword_end = ''){

        if($customer_type != ""){
            $str_type = " AND tb1.customer_type_id = '$customer_type' ";
        }

        if($customer_end_user_type == "0"){
            $str_end = "AND tb1.customer_end_user_type = '0'"; 
            
        }else if($customer_end_user_type == "1"){
            $str_end = "AND tb1.customer_end_user_type > '0'"; 
            
        }

        $sql = " SELECT tb1.customer_id, 
                        tb1.customer_code, 
                        tb1.customer_name_th, 
                        tb1.customer_name_en , 
                        tb1.customer_tax , 
                        tb1.customer_tel, 
                        tb1.customer_email, 
                        tb1.customer_end_user_type,
                        tb1.customer_approve,
                        customer_type_name, 
                        (SELECT COUNT(*) FROM tb_customer WHERE customer_end_user = tb1.customer_id) as count_end_user
        FROM tb_customer as tb1  
        LEFT JOIN tb_customer_type ON tb1.customer_type_id = tb_customer_type.customer_type_id 
        WHERE ( 1
        ) AND ( 
            tb1.customer_code LIKE ('%$keyword%') 
            OR tb1.customer_name_th LIKE ('%$keyword%') 
            OR tb1.customer_name_en LIKE ('%$keyword%') 
            OR tb1.customer_tax LIKE ('%$keyword%') 
            OR tb1.customer_tel LIKE ('%$keyword%') 
            OR tb1.customer_email LIKE ('%$keyword%') 
        )
        $str_type 
        $str_end  
        ORDER BY tb1.customer_code  
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


    function getCustomerProductBy($customer_id){
        $sql = "SELECT tb_product.* FROM `tb_invoice_customer` 
        LEFT JOIN tb_invoice_customer_list ON tb_invoice_customer.invoice_customer_id = tb_invoice_customer_list.invoice_customer_id
        LEFT JOIN tb_product ON tb_invoice_customer_list.product_id = tb_product.product_id  
        LEFT JOIN tb_user ON tb_invoice_customer.employee_id = tb_user.user_id 
        WHERE tb_invoice_customer.customer_id =  $customer_id
        AND tb_product.product_id IS NOT NULL 
        GROUP BY tb_product.product_id
        ";
        // echo "<pre>";
        // print_r($sql);
        // echo"</pre>";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }



    function getCustomerProductInvoiceBy($customer_id){
        $sql = "SELECT * FROM  tb_invoice_customer 
        LEFT JOIN tb_user ON tb_invoice_customer.employee_id = tb_user.user_id 
        WHERE customer_id =  $customer_id 
        AND invoice_customer_begin = '0' 

        ";
        // echo "<pre>";
        // print_r($sql);
        // echo"</pre>";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }


    function getCustomerProductQuotationBy($customer_id){
        $sql = "SELECT * FROM `tb_customer_purchase_order` 
        LEFT JOIN tb_customer_purchase_order_list ON tb_customer_purchase_order.customer_purchase_order_id = tb_customer_purchase_order_list.customer_purchase_order_list_id
        LEFT JOIN tb_product ON tb_customer_purchase_order_list.product_id = tb_product.product_id 
        LEFT JOIN tb_product_customer_price ON tb_customer_purchase_order_list.product_id = tb_product_customer_price.product_id 
        LEFT JOIN tb_user ON tb_customer_purchase_order.employee_id = tb_user.user_id
        LEFT JOIN  tb_customer ON tb_customer_purchase_order.customer_id = tb_customer.customer_id
        WHERE tb_customer_purchase_order.customer_id =  $customer_id
        ";
        // echo "<pre>";
        // print_r($sql);
        // echo"</pre>";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }

    function getCustomerQuotationBy($customer_id){
        $sql = "SELECT * FROM `tb_quotation`
        LEFT JOIN tb_user ON `tb_quotation`.employee_id = `tb_user`.`user_id`
        WHERE `tb_quotation`.`customer_id` =  $customer_id
        ";
        // echo "<pre>";
        // print_r($sql);
        // echo"</pre>";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }

    function getEndUserByCustomerID($customer_id,$employee_id = ''){

        $sql_employee = '';
        if($employee_id){
            $sql_employee = "AND sale_id = '$employee_id' ";
        }
        $sql = " SELECT customer_id, customer_code, customer_name_th, customer_name_en ,
        customer_register_status, customer_tax , customer_tel,customer_register_date,
         customer_email ,customer_approve,customer_zipcode  
        FROM tb_customer as tb1 
        WHERE customer_end_user = '$customer_id' 
        $sql_employee 
        ORDER BY customer_code  
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
    // Est Edit
    function getEndUserByViewCustomerID($customer_id,$employee_id = ''){

        $sql_employee = '';
        if($employee_id){
            $sql_employee = "AND sale_id = '$employee_id' ";
        }
        $sql = " SELECT DATEDIFF( CURDATE(),DATE_FORMAT(STR_TO_DATE(`customer_register_date`,'%d-%m-%Y'),'%Y-%m-%d')) AS check_limit,
        customer_id, customer_code, customer_name_th, customer_name_en ,
        customer_register_status, customer_tax , customer_tel,customer_register_date,
        customer_email ,customer_approve,customer_zipcode,customer_register_relimit
        FROM tb_customer as tb1 
        WHERE customer_end_user = '$customer_id'
        AND customer_end_user_type = '1'
        $sql_employee 
        ORDER BY customer_code  
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

    function getEndUserByCustomer($customer_id,$employee_id = ''){

        $sql_employee = '';
        if($employee_id){
            $sql_employee = "AND sale_id = '$employee_id' ";
        }
        $sql = " SELECT customer_id, customer_code, customer_name_th, customer_name_en , customer_tax , customer_tel, customer_email ,customer_zipcode  
        FROM tb_customer as tb1 
        WHERE customer_end_user = '$customer_id' 
        $sql_employee 
        AND customer_end_user_type = '1'
        ORDER BY customer_code  
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

    function getCustomerEndUserBy($type = "non-parent"){

        $str_type = "";
        if($type == "non-parent"){
            $str_type = "AND customer_end_user = '0' ";
        }else{
            $str_type = "";
        }
        $sql = " SELECT customer_id, customer_code, customer_name_th, customer_name_en , customer_tax , customer_tel, customer_email   
        FROM tb_customer as tb1
        WHERE  customer_end_user_type = '1'  
        $str_type 
        ORDER BY customer_code  
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

    function getCustomerByStatus($approve_status){

        $sql =  "   SELECT *  
                    FROM tb_customer 
                    LEFT JOIN tb_customer_type ON tb_customer.customer_type_id = tb_customer_type.customer_type_id 
                    LEFT JOIN tb_customer_size ON tb_customer.customer_size_id = tb_customer_size.customer_size_id 
                    LEFT JOIN tb_user ON tb_customer.sale_id = tb_user.user_id 
                    WHERE customer_approve = '$approve_status' 
                ";
        $data = [];        
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }else{
            return $data;
        }

    }

    function getInvoiceCustomerByAutoComplete($name){
        $sql = " SELECT customer_name_en
        FROM tb_customer 
        WHERE customer_name_en LIKE ('%$name%')
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

    function similarity($str1, $str2) {
        $str1 = strtolower($str1);
        $str2 = strtolower($str2);
        
        $str1 = str_replace(' ', '', $str1);
        $str2 = str_replace(' ', '', $str2);
        
        $str1 = preg_replace('/[^A-Za-z0-9\-]/', '',$str1);
        $str2 = preg_replace('/[^A-Za-z0-9\-]/', '',$str2);
        
        $len1 = strlen($str1);
        $len2 = strlen($str2);
        
        $percent_max = 0;
        for($i=0; $i<=$len2-$len1; $i++){
            
            $str = substr($str2, $i,$len1);
            similar_text($str1,$str,$percent); 
            
            if($percent_max < $percent){
                $percent_max = $percent;
                
            }
            
            if($i+1 == $len2-$len1) break;
        }
        
        
        return $percent_max;
    }
    
    

    function getNameTHCustomerByAutoComplete($name){
        
        $sql = " SELECT customer_name_th
        FROM tb_customer 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){

                $percent = $this->similarity($name,$row['customer_name_th']);

                if($percent > 60 ){
                    $data[] = $row;
                }
                
            }
            $result->close();
            return $data;
        }

    }

    function getNameENCustomerByAutoComplete($name){
        $sql = " SELECT customer_name_en
        FROM tb_customer 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){

                $percent = $this->similarity($name,$row['customer_name_en']);

                if($percent > 60 ){
                    $data[] = $row;
                }
                
            }
            $result->close();
            return $data;
        }

    }

    function getCustomerNotEndUserBy(){
        $sql = " SELECT customer_id, customer_code, customer_name_th, customer_name_en , customer_tax , customer_tel, customer_email   
        FROM tb_customer as tb1
        WHERE customer_end_user_type = '0'  

        ORDER BY customer_code  
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


    function getCustomerBySaleID($sale_id){
        $sql = " SELECT *    
        FROM tb_customer as tb1
        WHERE sale_id = '$sale_id'  
        ORDER BY customer_code  
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


    function getCustomerCodeIndexByChar($char){
        $sql = " SELECT IFNULL(MAX(CAST(RIGHT(customer_code,3) AS SIGNED )),0) as customer_code  
        FROM tb_customer 
        WHERE customer_code LIKE '$char%' 
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
    function getCustomerCodeIndexByCharEndUser($char){
        $sql = " SELECT IFNULL(MAX(CAST(RIGHT(customer_code,3) AS SIGNED )),0) as customer_code  
        FROM tb_customer 
        WHERE customer_code LIKE '$char%' 
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

    function getCustomerByID($id){
        $sql = " SELECT * 
        FROM tb_customer 
        WHERE customer_id = '$id' 
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

    function getCustomerViewByID($id){
        $sql = " SELECT * 
        FROM tb_customer 
        LEFT JOIN tb_account ON tb_customer.account_id = tb_account.account_id
        LEFT JOIN tb_currency ON tb_customer.currency_id = tb_currency.currency_id 
        LEFT JOIN tb_customer_group ON tb_customer.customer_group_id = tb_customer_group.customer_group_id 
        WHERE customer_id = '$id' 
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

    function getCustomerByCode($code){
        $sql = " SELECT * 
        FROM tb_customer 
        WHERE customer_code = '$code' 
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
    function getCustomerByCodeArray($code){
        $sql = " SELECT * 
        FROM tb_customer 
        WHERE customer_name_en = '$code'
        ";  
        $data =[]; 
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data [] = $row;
            }
            $result->close();
            return $data;
        }

    }
    function getCustomerLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(customer_code,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  customer_lastcode 
        FROM tb_customer 
        WHERE customer_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['customer_lastcode'];
        } 
    }

    function deleteEndUserByID($id){
        $sql = " UPDATE tb_customer SET 
        customer_end_user = '0' 
        WHERE customer_id = '$id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function insertEndUserByID($customer_id,$id){
        $sql = " UPDATE tb_customer SET 
        customer_end_user_type = '1', 
        customer_end_user = '$customer_id' 
        WHERE customer_id = '$id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }


    function updateCustomerBillByID($id,$data = []){
        $sql = " UPDATE tb_customer SET 
        date_bill = '".$data['date_bill']."', 
        bill_shift = '".$data['bill_shift']."', 
        updateby = '".$data['updateby']."',  
        lastupdate = NOW() 
        WHERE customer_id = '$id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function updateCustomerInvoiceByID($id,$data = []){
        $sql = " UPDATE tb_customer SET 
        date_invoice = '".$data['date_invoice']."', 
        invoice_shift = '".$data['invoice_shift']."', 
        updateby = '".$data['updateby']."',  
        lastupdate = NOW() 
        WHERE customer_id = '$id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }
    function updateCustomerRegisterStatusByID($id){
        $sql = " UPDATE tb_customer SET  
        customer_register_status = 'ขาย',   
        lastupdate = NOW() 
        WHERE customer_id = '$id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }


    function updateSaleIDByID($id,$sale_id){
        $sql = " UPDATE tb_customer SET  
        sale_id = '".$sale_id."',   
        lastupdate = NOW() 
        WHERE customer_id = '$id'
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }


    function updateApproveByID($id,$customer_approve,$user_id="0"){
        $sql = " UPDATE tb_customer SET  
        customer_approve = '".$customer_approve."',  
        updateby = '".$user_id."',   
        lastupdate = NOW() 
        WHERE customer_id = '$id'
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }






    function updateCustomerByID($id,$data = []){
        $sql = " UPDATE tb_customer SET  
        customer_code = '".$data['customer_code']."', 
        customer_name_th = '".$data['customer_name_th']."', 
        customer_name_en = '".$data['customer_name_en']."', 
        customer_type = '".$data['customer_type']."', 
        customer_tax = '".$data['customer_tax']."', 
        customer_address_1 = '".$data['customer_address_1']."', 
        customer_address_2 = '".$data['customer_address_2']."', 
        customer_address_3 = '".$data['customer_address_3']."', 
        customer_zipcode = '".$data['customer_zipcode']."', 
        customer_tel = '".$data['customer_tel']."', 
        customer_fax = '".$data['customer_fax']."', 
        customer_email = '".$data['customer_email']."', 
        customer_domestic = '".$data['customer_domestic']."', 
        customer_remark = '".$data['customer_remark']."', 
        customer_branch = '".$data['customer_branch']."', 
        customer_zone = '".$data['customer_zone']."', 
        credit_day = '".$data['credit_day']."', 
        condition_pay = '".$data['condition_pay']."', 
        pay_limit = '".$data['pay_limit']."' , 
        account_id = '".$data['account_id']."', 
        sale_id = '".$data['sale_id']."', 
        customer_type_id = '".$data['customer_type_id']."', 
        customer_size_id = '".$data['customer_size_id']."', 
        customer_group_id = '".$data['customer_group_id']."', 
        vat_type = '".$data['vat_type']."', 
        vat = '".$data['vat']."', 
        currency_id = '".$data['currency_id']."' , 
        customer_logo = '".$data['customer_logo']."' , 
        customer_approve = '".$data['customer_approve']."' , 
        customer_end_user_type = '".$data['customer_end_user_type']."' , 
        customer_end_user = '".$data['customer_end_user']."' , 
        customer_register_date = '".$data['customer_register_date']."' , 
        customer_register_relimit = '".$data['customer_register_relimit']."' , 
        customer_register_status = '".$data['customer_register_status']."' , 
        customer_logo = '".$data['customer_logo']."' , 
        updateby = '".$data['updateby']."',  
        lastupdate = NOW() 
        WHERE customer_id = '$id' 
        "; 

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function insertCustomer($data = []){
        $sql = " INSERT INTO tb_customer ( 
            customer_code,
            customer_name_th,
            customer_name_en,
            customer_type,
            customer_tax,
            customer_address_1,
            customer_address_2,
            customer_address_3,
            customer_zipcode,
            customer_tel, 
            customer_fax, 
            customer_email, 
            customer_domestic, 
            customer_remark, 
            customer_branch, 
            customer_zone, 
            credit_day, 
            condition_pay,  
            pay_limit,
            account_id, 
            sale_id, 
            customer_type_id,
            customer_size_id,
            customer_group_id,
            vat_type, 
            vat,  
            currency_id,
            customer_logo,
            customer_approve,
            customer_end_user,
            customer_end_user_type,
            customer_register_date,
            customer_register_relimit,
            customer_register_status,
            addby,
            adddate
        ) VALUES ( 
            '".$data['customer_code']."', 
            '".$data['customer_name_th']."', 
            '".$data['customer_name_en']."', 
            '".$data['customer_type']."', 
            '".$data['customer_tax']."', 
            '".$data['customer_address_1']."', 
            '".$data['customer_address_2']."', 
            '".$data['customer_address_3']."', 
            '".$data['customer_zipcode']."', 
            '".$data['customer_tel']."', 
            '".$data['customer_fax']."', 
            '".$data['customer_email']."', 
            '".$data['customer_domestic']."', 
            '".$data['customer_remark']."', 
            '".$data['customer_branch']."', 
            '".$data['customer_zone']."', 
            '".$data['credit_day']."', 
            '".$data['condition_pay']."',  
            '".$data['pay_limit']."', 
            '".$data['account_id']."', 
            '".$data['sale_id']."',
            '".$data['customer_type_id']."',
            '".$data['customer_size_id']."',
            '".$data['customer_group_id']."',
            '".$data['vat_type']."', 
            '".$data['vat']."',  
            '".$data['currency_id']."', 
            '".$data['customer_logo']."',    
            '".$data['customer_approve']."',    
            '".$data['customer_end_user']."',    
            '".$data['customer_end_user_type']."',    
            '".$data['customer_register_date']."',    
            '".$data['customer_register_relimit']."',    
            '".$data['customer_register_status']."',    
            '".$data['addby']."', 
            NOW()  
        ); 
        ";

        // echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }


    function deleteCustomerByID($id){
        $sql = " DELETE FROM tb_customer WHERE customer_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }



    //----------------------------------------------------------------- App Mobile ---------------------------------------------------------------------------------------///


    function getCustomerAll(){
        $sql ="SELECT tb1.customer_id, tb1.customer_code, tb1.customer_name_th, 
                      tb1.customer_name_en , tb1.customer_tax , tb1.customer_tel, 
                      tb1.customer_email ,
                      customer_type_name 
               FROM `tb_customer` AS tb1 
               LEFT JOIN tb_customer_type ON tb1.customer_type_id = tb_customer_type.customer_type_id";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }



}
?>
