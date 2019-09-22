<?php

require_once("BaseModel.php");
require_once("MaintenanceStockModel.php"); 
class InvoiceCustomerModel extends BaseModel{

    private $maintenance_stock;

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db,"utf8");

        $this->maintenance_stock =  new MaintenanceStockModel;
    }

   

    function getInvoiceCustomerBy($date_start = "",$date_end = "",$customer_id = "",$keyword = "",$user_id = "",$begin = "0", $lock_1 = "0", $lock_2 = "0" ){

        $str_customer = "";
        $str_date = "";
        $str_user = "";
        $str_lock = "";

        if($lock_1 == "1" && $lock_2 == "1"){
            $str_lock = "AND (paper_lock_1 = '0' OR paper_lock_2 = '0')";
        }else if ($lock_1 == "1") {
            $str_lock = "AND paper_lock_1 = '0' ";
        }else if($lock_2 == "1"){
            $str_lock = "AND paper_lock_2 = '0' ";
        }



        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND employee_id = '$user_id' ";
        }

        if($customer_id != ""){
            $str_customer = "AND tb2.customer_id = '$customer_id' ";
        }

        $sql = " SELECT tb_invoice_customer.invoice_customer_id, 
        invoice_customer_code, 
        invoice_customer_date, 
        invoice_customer_total_price,
        invoice_customer_vat,
        invoice_customer_vat_price,
        invoice_customer_net_price,
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        invoice_customer_term, 
        invoice_customer_due, 
        invoice_customer_name,
        invoice_customer_close, 
        IFNULL(tb2.customer_name_en,'-') as customer_name  
        FROM tb_invoice_customer 
        LEFT JOIN tb_user as tb1 ON tb_invoice_customer.employee_id = tb1.user_id 
        LEFT JOIN tb_customer as tb2 ON tb_invoice_customer.customer_id = tb2.customer_id 
        LEFT JOIN tb_invoice_customer_list ON tb_invoice_customer.invoice_customer_id = tb_invoice_customer_list.invoice_customer_id 
        LEFT JOIN tb_product ON tb_invoice_customer_list.product_id = tb_product.product_id   
        LEFT JOIN tb_paper_lock ON SUBSTRING(tb_invoice_customer.invoice_customer_date,3,9)=SUBSTRING(tb_paper_lock.paper_lock_date,3,9) 
        WHERE ( 
            invoice_customer_code LIKE ('%$keyword%') 
            OR  product_code LIKE ('%$keyword%') 
            OR  product_name LIKE ('%$keyword%') 
        ) 
        AND invoice_customer_begin = '$begin' 
        $str_lock 
        $str_customer 
        $str_date 
        $str_user   
        GROUP BY tb_invoice_customer.invoice_customer_id
        ORDER BY invoice_customer_code DESC 
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


    function getInvoiceCustomerByCustomerPurchaseListId ($customer_purchase_order_list_id){
        
        $sql = " SELECT tb_invoice_customer.invoice_customer_code,
        tb_invoice_customer.invoice_customer_id
        FROM `tb_invoice_customer` 
        LEFT JOIN tb_invoice_customer_list ON tb_invoice_customer.invoice_customer_id =  tb_invoice_customer_list.invoice_customer_id
        WHERE tb_invoice_customer_list.customer_purchase_order_list_id = '$customer_purchase_order_list_id'
        GROUP BY  tb_invoice_customer.invoice_customer_id
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

    function getInvoiceCustomerByCustomerPurchaseId ($customer_purchase_order_id){
        
        $sql = " SELECT tb_invoice_customer.invoice_customer_code,
        tb_invoice_customer.invoice_customer_id
        FROM `tb_invoice_customer` 
        LEFT JOIN tb_invoice_customer_list ON tb_invoice_customer.invoice_customer_id =  tb_invoice_customer_list.invoice_customer_id
        WHERE tb_invoice_customer_list.customer_purchase_order_list_id IN (
            SELECT customer_purchase_order_list_id FROM tb_customer_purchase_order_list WHERE customer_purchase_order_id = '$customer_purchase_order_id'
        ) 
        GROUP BY  tb_invoice_customer.invoice_customer_id
         ";

        //echo $sql;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }




    function getInvoiceCustomerByID($id){
        $sql = " SELECT * 
        FROM tb_invoice_customer 
        LEFT JOIN tb_customer ON tb_invoice_customer.customer_id = tb_customer.customer_id 
        LEFT JOIN tb_user ON tb_invoice_customer.employee_id = tb_user.user_id 
        WHERE invoice_customer_id = '$id' 
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
    function getInvoiceCustomerByCodeGen($code){
        $sql = " SELECT * 
        FROM tb_invoice_customer  
        WHERE invoice_customer_code  = '$code' 
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
    function getInvoiceCustomerByAutoComplete($code){
        $sql = " SELECT invoice_customer_code ,invoice_customer_date 
        FROM tb_invoice_customer 
        WHERE invoice_customer_code LIKE ('%$code%')
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


    function getInvoiceCustomerLastSaleByProductID($product_id, $customer_id = ''){
        $str_customer = "";
        if($customer_id != ""){
            $str_customer = "AND tb_invoice_customer.customer_id = '$customer_id' ";
        }

        $sql = " SELECT * 
        FROM tb_invoice_customer 
        LEFT JOIN tb_invoice_customer_list ON tb_invoice_customer.invoice_customer_id = tb_invoice_customer_list.invoice_customer_id 
        LEFT JOIN tb_customer ON tb_invoice_customer.customer_id = tb_customer.customer_id 
        LEFT JOIN tb_user ON tb_invoice_customer.employee_id = tb_user.user_id 
        WHERE product_id = '$product_id' 
        $str_customer 
        GROUP BY tb_invoice_customer_list.invoice_customer_list_id 
        ORDER BY STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') DESC 
        LIMIT 0 , 1 
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

    function getInvoiceCustomerByKeyword($keyword){
        $sql = " SELECT * 
        FROM tb_invoice_customer 
        WHERE invoice_customer_code LIKE '%$keyword%' 
        ";

        //echo $sql;

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            //echo $sql;
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getInvoiceCustomerByCode($invoice_customer_code){
        $sql = " SELECT * 
        FROM tb_invoice_customer 
        WHERE invoice_customer_code = '$invoice_customer_code' 
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

    function getInvoiceCustomerByCustomerID($id){
        $sql = " SELECT * 
        FROM tb_invoice_customer 
        LEFT JOIN tb_customer ON tb_invoice_customer.customer_id = tb_customer.customer_id 
        LEFT JOIN tb_user ON tb_invoice_customer.employee_id = tb_user.user_id 
        WHERE tb_invoice_customer.customer_id = '$id' 
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

    function getInvoiceCustomerViewByID($id){
        $sql = " SELECT *   
        FROM tb_invoice_customer 
        LEFT JOIN tb_user ON tb_invoice_customer.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_customer ON tb_invoice_customer.customer_id = tb_customer.customer_id 
        WHERE invoice_customer_id = '$id' 
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


    function getInvoiceCustomerViewListByjournalGeneralID($id){
        $sql = " SELECT *   
        FROM tb_journal_general_list 
        LEFT JOIN tb_invoice_customer ON tb_journal_general_list.journal_invoice_customer_id = tb_invoice_customer.invoice_customer_id
        LEFT JOIN tb_user ON tb_invoice_customer.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_customer ON tb_invoice_customer.customer_id = tb_customer.customer_id 
        WHERE journal_general_id = '$id' AND tb_journal_general_list.journal_invoice_customer_id > 0
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data [] = $row;
            }
            $result->close();
            return $data;
        }   
    }

    function getInvoiceCustomerViewListByjournalPaymentID($id){
        $sql = " SELECT *   
        FROM tb_journal_cash_payment_list 
        LEFT JOIN tb_invoice_customer ON tb_journal_cash_payment_list.journal_invoice_customer_id = tb_invoice_customer.invoice_customer_id
        LEFT JOIN tb_user ON tb_invoice_customer.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_customer ON tb_invoice_customer.customer_id = tb_customer.customer_id 
        WHERE journal_cash_payment_id = '$id' AND tb_journal_cash_payment_list.journal_invoice_customer_id > 0
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data [] = $row;
            }
            $result->close();
            return $data;
        }   
    }

    function getInvoiceCustomerViewListByjournalReceiptID($id){
        $sql = " SELECT *   
        FROM tb_journal_cash_receipt_list 
        LEFT JOIN tb_invoice_customer ON tb_journal_cash_receipt_list.journal_invoice_customer_id = tb_invoice_customer.invoice_customer_id
        LEFT JOIN tb_user ON tb_invoice_customer.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_customer ON tb_invoice_customer.customer_id = tb_customer.customer_id 
        WHERE journal_cash_receipt_id = '$id' AND tb_journal_cash_receipt_list.journal_invoice_customer_id > 0
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data [] = $row;
            }
            $result->close();
            return $data;
        }   
    }
    
    function getInvoiceCustomerViewListByjournalPurchaseID($id){
        $sql = " SELECT *   
        FROM tb_journal_purchase_list 
        LEFT JOIN tb_invoice_customer ON tb_journal_purchase_list.journal_invoice_customer_id = tb_invoice_customer.invoice_customer_id
        LEFT JOIN tb_user ON tb_invoice_customer.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_customer ON tb_invoice_customer.customer_id = tb_customer.customer_id 
        WHERE journal_purchase_id = '$id' AND tb_journal_purchase_list.journal_invoice_customer_id > 0
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data [] = $row;
            }
            $result->close();
            return $data;
        }   
    }

    function getInvoiceCustomerViewListByjournalSaleID($id){
        $sql = " SELECT *   
        FROM tb_journal_sale_list 
        LEFT JOIN tb_invoice_customer ON tb_journal_sale_list.journal_invoice_customer_id = tb_invoice_customer.invoice_customer_id
        LEFT JOIN tb_user ON tb_invoice_customer.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_customer ON tb_invoice_customer.customer_id = tb_customer.customer_id 
        WHERE journal_sale_id = '$id' AND tb_journal_sale_list.journal_invoice_customer_id > 0
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data [] = $row;
            }
            $result->close();
            return $data;
        }   
    }


    function getInvoiceCustomerViewListByjournalSaleReturnID($id){
        $sql = " SELECT *   
        FROM tb_journal_sale_return_list 
        LEFT JOIN tb_invoice_customer ON tb_journal_sale_return_list.journal_invoice_customer_id = tb_invoice_customer.invoice_customer_id
        LEFT JOIN tb_user ON tb_invoice_customer.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_customer ON tb_invoice_customer.customer_id = tb_customer.customer_id 
        WHERE journal_sale_return_id = '$id' AND tb_journal_sale_return_list.journal_invoice_customer_id > 0
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data [] = $row;
            }
            $result->close();
            return $data;
        }   
    }

    function getInvoiceCustomerLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(invoice_customer_code,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  invoice_customer_lastcode 
        FROM tb_invoice_customer
        WHERE invoice_customer_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['invoice_customer_lastcode'];
        }

    }



    //#####################################################################################################################
    //
    //
    //------------------------------------------------------- Get invoice customer cost --------------------------------------------
    //
    //
    //#####################################################################################################################
    function getInvoiceCustomerCostByID($invoice_customer_id,$product_category_id=""){

        $str_category = "";

        if($product_category_id != ""){
            $str_category = " AND tb_product.product_category_id = '$product_category_id' ";
        }

        $sql = "SELECT tb_stock_group.* 
        FROM tb_invoice_customer_list 
        LEFT JOIN tb_stock_group ON tb_invoice_customer_list.stock_group_id = tb_stock_group.stock_group_id 
        WHERE invoice_customer_id = '$invoice_customer_id' 
        GROUP BY tb_invoice_customer_list.stock_group_id ";

        $data_stock = [];
        $cost_total = 0;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data_stock[] = $row;
            } 
            $result->close(); 

            for($i=0;$i<count($data_stock);$i++){
                $sql = "SELECT SUM(IFNULL(tb1.out_stock_cost_avg_total,0)) as out_stock_cost_avg_total
                        FROM tb_invoice_customer_list 
                        LEFT  JOIN tb_product ON tb_invoice_customer_list.product_id = tb_product.product_id 
                        LEFT JOIN ".$data_stock[$i]['table_name']." as tb1 ON tb_invoice_customer_list.invoice_customer_list_id = tb1.invoice_customer_list_id 
                        WHERE invoice_customer_id = '$invoice_customer_id' 
                        $str_category 
                        AND tb1.invoice_customer_list_id IS NOT NULL ";

                        //echo $sql ."<br><br>";
 
                if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                    while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                        $cost_total += $row['out_stock_cost_avg_total'];
                    } 
                    $result->close(); 
                } 
            }

            $sql = "SELECT IFNULL(SUM(stock_issue_total_cost),0) as stock_issue_total_cost FROM tb_stock_issue WHERE invoice_customer_id = '$invoice_customer_id' ";
           
            if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                if($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                    if($row['stock_issue_total_cost'] > 0){ 
                        $cost_total += $row['stock_issue_total_cost'];
                    }
                } 
                $result->close(); 
            }

        }

        return $cost_total;
    }



    //#####################################################################################################################
    //
    //
    //------------------------------------------------------- Get invoice customer cost --------------------------------------------
    //
    //
    //#####################################################################################################################
    function getInvoiceCustomerTotalByID($invoice_customer_id,$product_category_id=""){

        $str_category = "";

        if($product_category_id != ""){
            $str_category = " AND tb_product.product_category_id = '$product_category_id' ";
        }

        $sql = "SELECT SUM(IFNULL(invoice_customer_list_total,0)) as invoice_customer_total
                FROM tb_invoice_customer_list 
                LEFT  JOIN tb_product ON tb_invoice_customer_list.product_id = tb_product.product_id 
                WHERE invoice_customer_id = '$invoice_customer_id' 
                $str_category 
                AND invoice_customer_list_id IS NOT NULL ";

                // echo $sql ."<br><br>";
        $total = 0;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) { 
            
            while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $total += $row['invoice_customer_total']; 
            }
            $result->close(); 
        } 
         

        return $total;
    }


    //#####################################################################################################################
    //
    //
    //------------------------------------------------------- Get invoice customer list cost --------------------------------------------
    //
    //
    //#####################################################################################################################
    function getInvoiceCustomerListCostByID($invoice_customer_list_id){
        $sql = "SELECT tb_stock_group.* 
        FROM tb_invoice_customer_list 
        LEFT JOIN tb_stock_group ON tb_invoice_customer_list.stock_group_id = tb_stock_group.stock_group_id 
        WHERE invoice_customer_list_id = '$invoice_customer_list_id' 
        GROUP BY tb_invoice_customer_list.stock_group_id ";

        $data_stock;
        $cost_total;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            if($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data_stock = $row;
            } 
            $result->close();  
            $sql = "SELECT MAX(IFNULL(tb1.out_stock_cost_avg,0)) as out_stock_cost_avg, 
                    SUM(IFNULL(tb1.out_stock_cost_avg_total,0)) as out_stock_cost_avg_total,
                    invoice_customer_list_qty
                    FROM tb_invoice_customer_list 
                    LEFT JOIN tb_invoice_customer ON tb_invoice_customer_list.invoice_customer_id = tb_invoice_customer.invoice_customer_id 
                    LEFT JOIN ".$data_stock['table_name']." as tb1 ON tb_invoice_customer_list.invoice_customer_list_id = tb1.invoice_customer_list_id 
                    WHERE tb_invoice_customer_list.invoice_customer_list_id = '$invoice_customer_list_id' 
                    AND tb1.invoice_customer_list_id IS NOT NULL "; 

            if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                if($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                    $cost_total = $row;
                } 
                $result->close(); 
            }  else{
                $sql = "SELECT 0 as out_stock_cost_avg, 
                        0 as out_stock_cost_avg_total,
                        invoice_customer_list_qty
                        FROM tb_invoice_customer_list  
                        WHERE tb_invoice_customer_list.invoice_customer_list_id = '$invoice_customer_list_id' ";

                        //echo $sql ."<br><br>";

                if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                    if($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                        $cost_total = $row;
                    } 
                    $result->close(); 
                } 
            } 
        } 

        $sql = "SELECT count(*) as have_data FROM tb_stock_issue WHERE invoice_customer_list_id = '$invoice_customer_list_id' ";
        
          
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) { 
            
            if($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $result->close(); 
                if($row['have_data'] > 0){
                    $sql = "SELECT IFNULL(SUM(stock_issue_total_cost),0) as stock_issue_total_cost FROM tb_stock_issue WHERE invoice_customer_list_id = '$invoice_customer_list_id' ";
            
            
                    if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) { 
        
                        if($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                            if($row['stock_issue_total_cost'] > 0){ 
                                $cost_total['out_stock_cost_avg_total'] += $row['stock_issue_total_cost'];
                                if($cost_total['invoice_customer_list_qty'] == 0){
                                    $cost_total['invoice_customer_list_qty'] = 1;
                                }
                                $cost_total['out_stock_cost_avg'] = round($cost_total['out_stock_cost_avg_total'] / $cost_total['invoice_customer_list_qty'],2);
                                
                            }
                        }  
                        $result->close(); 
                    } 
                }else{
                    $sql = "SELECT ( IFNULL(SUM(stock_issue_total_cost),0) * (invoice_customer_list_total / IFNULL(SUM(stock_issue_invoice_cost),0)) )  as stock_issue_total_cost 
                            FROM tb_stock_issue 
                            LEFT JOIN tb_invoice_customer ON tb_stock_issue.invoice_customer_id = tb_invoice_customer.invoice_customer_id 
                            LEFT JOIN tb_invoice_customer_list ON tb_invoice_customer.invoice_customer_id = tb_invoice_customer_list.invoice_customer_id 
                            WHERE tb_invoice_customer_list.invoice_customer_list_id = '$invoice_customer_list_id' ";
            
            
                    if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) { 
        
                        if($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                            if($row['stock_issue_total_cost'] > 0){ 
                                $cost_total['out_stock_cost_avg_total'] += $row['stock_issue_total_cost'];
                                $cost_total['out_stock_cost_avg'] = round($cost_total['out_stock_cost_avg_total'] / $cost_total['invoice_customer_list_qty'],2);
                                
                            } 
                        } 
                        $result->close(); 
                    } 
                }
            }
 
            

        }


        

        return $cost_total;
    }


   
    function updateInvoiceCustomerByID($id,$data = []){
        $sql = " UPDATE tb_invoice_customer SET 
        customer_id = '".$data['customer_id']."', 
        employee_id = '".$data['employee_id']."', 
        invoice_customer_code = '".static::$db->real_escape_string($data['invoice_customer_code'])."', 
        invoice_customer_total_price = '".$data['invoice_customer_total_price']."', 
        invoice_customer_vat = '".$data['invoice_customer_vat']."', 
        invoice_customer_vat_price = '".$data['invoice_customer_vat_price']."', 
        invoice_customer_net_price = '".$data['invoice_customer_net_price']."', 
        invoice_customer_date = '".static::$db->real_escape_string($data['invoice_customer_date'])."', 
        invoice_customer_name = '".static::$db->real_escape_string($data['invoice_customer_name'])."', 
        invoice_customer_address = '".static::$db->real_escape_string($data['invoice_customer_address'])."', 
        invoice_customer_term = '".static::$db->real_escape_string($data['invoice_customer_term'])."', 
        invoice_customer_tax = '".static::$db->real_escape_string($data['invoice_customer_tax'])."', 
        invoice_customer_branch = '".static::$db->real_escape_string($data['invoice_customer_branch'])."', 
        invoice_customer_due = '".static::$db->real_escape_string($data['invoice_customer_due'])."', 
        invoice_customer_due_day = '".static::$db->real_escape_string($data['invoice_customer_due_day'])."', 
        invoice_customer_purchase = '".static::$db->real_escape_string($data['invoice_customer_purchase'])."', 
        invoice_customer_close = '".$data['invoice_customer_close']."', 
        invoice_customer_begin = '".$data['invoice_customer_begin']."', 
        vat_section = '".static::$db->real_escape_string($data['vat_section'])."', 
        vat_section_add = '".static::$db->real_escape_string($data['vat_section_add'])."', 
        invoice_customer_total_price_non = '".$data['invoice_customer_total_price_non']."', 
        invoice_customer_vat_price_non = '".$data['invoice_customer_vat_price_non']."', 
        invoice_customer_total_non = '".$data['invoice_customer_total_non']."', 
        invoice_customer_description = '".static::$db->real_escape_string($data['invoice_customer_description'])."', 
        invoice_customer_remark = '".static::$db->real_escape_string($data['invoice_customer_remark'])."', 
        invoice_customer_print_line = '".$data['invoice_customer_print_line']."', 
        invoice_customer_remark_top = '".$data['invoice_customer_remark_top']."', 
        invoice_customer_remark_bottom = '".$data['invoice_customer_remark_bottom']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE invoice_customer_id = $id 
        ";
 

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }


    function getCustomerPurchaseOrder($keyword = ""){

        $sql = "SELECT tb_customer_purchase_order.customer_purchase_order_id, customer_purchase_order_code, customer_purchase_order_date , tb_customer.customer_id, customer_name_en , customer_name_th 
                FROM tb_customer_purchase_order 
                LEFT JOIN tb_customer 
                ON tb_customer_purchase_order.customer_id = tb_customer.customer_id 
                LEFT JOIN tb_customer_purchase_order_list 
                ON tb_customer_purchase_order.customer_purchase_order_id = tb_customer_purchase_order_list.customer_purchase_order_id 
                WHERE customer_purchase_order_list_id IN ( 
                    SELECT tb_customer_purchase_order_list.customer_purchase_order_list_id 
                    FROM tb_customer_purchase_order_list  
                    LEFT JOIN tb_invoice_customer_list ON  tb_customer_purchase_order_list.customer_purchase_order_list_id = tb_invoice_customer_list.customer_purchase_order_list_id  
                    GROUP BY tb_customer_purchase_order_list.customer_purchase_order_list_id 
                    HAVING IFNULL(SUM(invoice_customer_list_qty),0) < AVG(customer_purchase_order_list_qty)  
                ) 
                AND customer_purchase_order_finish = '0'
                AND customer_purchase_order_code LIKE ('%$keyword%')
                GROUP BY tb_customer_purchase_order.customer_purchase_order_id
                ORDER BY STR_TO_DATE(customer_purchase_order_date,'%d-%m-%Y %H:%i:%s') DESC
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


    function getCustomerPurchaseOrderStock($customer_purchase_order_id=""){

       

        if($customer_purchase_order_id != ""){
            $str_po = "AND tb_customer_purchase_order.customer_purchase_order_id = '$customer_purchase_order_id' ";
        }

        $sql_customer = "SELECT tb2.product_id, 
        tb2.customer_purchase_order_list_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,  
        IFNULL(customer_purchase_order_list_qty 
        - IFNULL((
            SELECT SUM(invoice_customer_list_qty) 
            FROM tb_invoice_customer_list 
            WHERE customer_purchase_order_list_id = tb2.customer_purchase_order_list_id 
        ),0) ,0) as invoice_customer_list_qty,  
        customer_purchase_order_product_name as invoice_customer_list_product_name,
        customer_purchase_order_product_detail as invoice_customer_list_product_detail,
        customer_purchase_order_list_price as invoice_customer_list_price, 
        customer_purchase_order_list_price_sum as invoice_customer_list_total, 
        customer_purchase_order_list_price_sum as invoice_customer_list_cost,
        stock_event,
        CONCAT('Order for customer PO : ',customer_purchase_order_code) as invoice_customer_list_remark ,
        tb_stock_report.* 
        FROM tb_customer_purchase_order 
        LEFT JOIN tb_customer_purchase_order_list as tb2 ON tb_customer_purchase_order.customer_purchase_order_id = tb2.customer_purchase_order_id 
        LEFT JOIN tb_product ON tb2.product_id = tb_product.product_id 
        LEFT JOIN tb_product_category ON tb_product.product_category_id = tb_product_category.product_category_id 
        LEFT JOIN tb_stock_report ON tb_product.product_id = tb_stock_report.product_id 
        WHERE stock_report_qty > 0 
        $str_po   
        AND customer_purchase_order_finish = '0' 
        GROUP BY tb2.customer_purchase_order_list_id 
        HAVING invoice_customer_list_qty > 0 
        "; 
        $data = [];
        // if($customer_purchase_order_id == '94'){
        //     echo "<pre>";
        //     echo $sql_customer.'<br><br>';
        //     echo "</pre>";
        // }
        // echo $sql_customer;
        if ($result = mysqli_query(static::$db,$sql_customer, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }

        return $data;
    }

    
    function getCustomerPurchaseOrderStockFix($customer_purchase_order_id=""){
 
       
        
        if($customer_purchase_order_id != ""){
            $str_po = "AND tb2.customer_purchase_order_id = '$customer_purchase_order_id' ";
        }

        $sql_customer = "SELECT * 
        FROM (
           (SELECT tb2.product_id, 
           tb3.stock_group_id,
            tb2.customer_purchase_order_list_id, 
            CONCAT(product_code_first,product_code) as product_code, 
            product_name,  
            (
                SELECT COUNT(invoice_supplier_list_id)
                FROM tb_invoice_supplier_list
                WHERE purchase_order_list_id = tb3.purchase_order_list_id
                AND purchase_order_list_id != 0
            ) as count_receive,
            IFNULL(customer_purchase_order_list_qty 
            - IFNULL((
                SELECT SUM(invoice_customer_list_qty) 
                FROM tb_invoice_customer_list 
                WHERE customer_purchase_order_list_id = tb2.customer_purchase_order_list_id 
            ),0) ,0) as invoice_customer_list_qty,  
            customer_purchase_order_product_name as invoice_customer_list_product_name,
            customer_purchase_order_product_detail as invoice_customer_list_product_detail,
            customer_purchase_order_list_price as invoice_customer_list_price, 
            customer_purchase_order_list_price_sum as invoice_customer_list_total, 
            customer_purchase_order_list_price_sum as invoice_customer_list_cost,
            stock_event,
            CONCAT('Order for customer PO : ',customer_purchase_order_code) as invoice_customer_list_remark ,
            tb_stock_report.stock_report_qty 
            FROM tb_customer_purchase_order_list as tb2
            LEFT JOIN tb_customer_purchase_order ON tb2.customer_purchase_order_id = tb_customer_purchase_order.customer_purchase_order_id   
            LEFT JOIN tb_product ON tb2.product_id = tb_product.product_id 
            LEFT JOIN tb_product_category ON tb_product.product_category_id = tb_product_category.product_category_id 
            LEFT JOIN tb_customer_purchase_order_list_detail as tb3 ON tb2.customer_purchase_order_list_id = tb3.customer_purchase_order_list_id  
            LEFT JOIN tb_invoice_customer_list ON  tb2.customer_purchase_order_list_id = tb_invoice_customer_list.customer_purchase_order_list_id 
            LEFT JOIN tb_stock_report ON (tb2.product_id = tb_stock_report.product_id  AND tb3.stock_group_id = tb_stock_report.stock_group_id)
            WHERE stock_report_qty > 0 
            $str_po   
            AND customer_purchase_order_finish = '0' 
            GROUP BY tb2.customer_purchase_order_list_id 
            HAVING invoice_customer_list_qty > 0 
            AND count_receive > 0
        ) UNION ALL (
            SELECT tb2.product_id, 
            tb_customer_purchase_order_list_detail.stock_hold_id as stock_group_id,
            tb2.customer_purchase_order_list_id, 
            CONCAT(product_code_first,product_code) as product_code, 
            product_name,  
            0 as count_receive,
            IFNULL(customer_purchase_order_list_qty 
            - IFNULL((
                SELECT SUM(invoice_customer_list_qty) 
                FROM tb_invoice_customer_list 
                WHERE customer_purchase_order_list_id = tb2.customer_purchase_order_list_id 
            ),0) ,0) as invoice_customer_list_qty,  
            customer_purchase_order_product_name as invoice_customer_list_product_name,
            customer_purchase_order_product_detail as invoice_customer_list_product_detail,
            customer_purchase_order_list_price as invoice_customer_list_price, 
            customer_purchase_order_list_price_sum as invoice_customer_list_total, 
            customer_purchase_order_list_price_sum as invoice_customer_list_cost,
            stock_event,
            CONCAT('Order for customer PO : ',customer_purchase_order_code) as invoice_customer_list_remark ,
            tb_stock_report.stock_report_qty 
            FROM tb_customer_purchase_order_list as tb2
            LEFT JOIN tb_customer_purchase_order ON tb2.customer_purchase_order_id = tb_customer_purchase_order.customer_purchase_order_id   
            LEFT JOIN tb_product ON tb2.product_id = tb_product.product_id 
            LEFT JOIN tb_product_category ON tb_product.product_category_id = tb_product_category.product_category_id 
            LEFT JOIN tb_customer_purchase_order_list_detail ON tb2.customer_purchase_order_list_id = tb_customer_purchase_order_list_detail.customer_purchase_order_list_id  
            LEFT JOIN tb_invoice_customer_list ON  tb2.customer_purchase_order_list_id = tb_invoice_customer_list.customer_purchase_order_list_id 
            LEFT JOIN tb_stock_report ON (tb2.product_id = tb_stock_report.product_id  AND tb_customer_purchase_order_list_detail.stock_hold_id = tb_stock_report.stock_group_id)
            WHERE stock_report_qty > 0 
            $str_po   
            AND customer_purchase_order_finish = '0' 
            GROUP BY tb2.customer_purchase_order_list_id 
            HAVING invoice_customer_list_qty > 0 
        ) )AS tb_data
        "; 

        $data = [];

        
        // echo "<pre>".$sql_customer."</pre>";
        if ($result = mysqli_query(static::$db,$sql_customer, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }

        return $data;
    }


    function getCustomerPurchaseOrderByCode($customer_purchase_order_code = ""){

        $sql = "SELECT tb_customer_purchase_order.customer_purchase_order_id, customer_purchase_order_code, tb_customer.customer_id, customer_name_en , customer_name_th 
                FROM tb_customer_purchase_order 
                LEFT JOIN tb_customer 
                ON tb_customer_purchase_order.customer_id = tb_customer.customer_id 
                LEFT JOIN tb_customer_purchase_order_list 
                ON tb_customer_purchase_order.customer_purchase_order_id = tb_customer_purchase_order_list.customer_purchase_order_id 
                WHERE customer_purchase_order_list_id IN ( 
                    SELECT tb_customer_purchase_order_list.customer_purchase_order_list_id 
                    FROM tb_customer_purchase_order_list  
                    LEFT JOIN tb_invoice_customer_list ON  tb_customer_purchase_order_list.customer_purchase_order_list_id = tb_invoice_customer_list.customer_purchase_order_list_id  
                    GROUP BY tb_customer_purchase_order_list.customer_purchase_order_list_id 
                    HAVING IFNULL(SUM(invoice_customer_list_qty),0) < AVG(customer_purchase_order_list_qty)  
                ) 
                AND customer_purchase_order_finish = '0' 
                AND customer_purchase_order_code = '$customer_purchase_order_code' 
                GROUP BY tb_customer_purchase_order.customer_purchase_order_id
        ";
        $data;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            
        }
        return $data;
    }

    

    function getCustomerOrder(){

        $sql = "SELECT tb_customer.customer_id, customer_name_en , customer_name_th 
                FROM tb_customer 
                WHERE customer_id IN ( 
                    SELECT DISTINCT customer_id 
                    FROM tb_customer_purchase_order 
                    LEFT JOIN tb_customer_purchase_order_list 
                    ON tb_customer_purchase_order.customer_purchase_order_id = tb_customer_purchase_order_list.customer_purchase_order_id 
                    WHERE customer_purchase_order_list_id IN ( 
                        SELECT tb_customer_purchase_order_list.customer_purchase_order_list_id 
                        FROM tb_customer_purchase_order_list  
                        LEFT JOIN tb_invoice_customer_list ON  tb_customer_purchase_order_list.customer_purchase_order_list_id = tb_invoice_customer_list.customer_purchase_order_list_id  
                        GROUP BY tb_customer_purchase_order_list.customer_purchase_order_list_id 
                        HAVING IFNULL(SUM(invoice_customer_list_qty),0) < AVG(customer_purchase_order_list_qty)  
                    ) 
                    AND customer_purchase_order_finish = '0' 
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

    function generateInvoiceCustomerListByCustomerId(
    $customer_id, 
    $data = [],
    $search="",
    $customer_purchase_order_id=""){

        $str ='0';

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

        if($customer_purchase_order_id != ""){
            $str_po = "AND tb_customer_purchase_order.customer_purchase_order_id = '$customer_purchase_order_id' ";
        }

        $sql_customer = "SELECT tb2.product_id, 
        tb2.customer_purchase_order_list_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,  
        IFNULL(customer_purchase_order_list_qty 
        - IFNULL((
            SELECT SUM(invoice_customer_list_qty) 
            FROM tb_invoice_customer_list 
            WHERE customer_purchase_order_list_id = tb2.customer_purchase_order_list_id 
        ),0) ,0) as invoice_customer_list_qty,  
        IFNULL( (
                SELECT CASE WHEN (stock_group_id IS NULL OR stock_group_id = 0)
                            THEN stock_hold_id
                            ELSE stock_group_id 
                    END AS stock_group_id
                FROM tb_customer_purchase_order_list_detail 
                WHERE customer_purchase_order_list_id=tb2.customer_purchase_order_list_id 
                GROUP BY stock_group_id 
                LIMIT 0,1
        ),1) as stock_group_id,
        customer_purchase_order_product_name as invoice_customer_list_product_name,
        customer_purchase_order_product_detail as invoice_customer_list_product_detail,
        customer_purchase_order_list_price as invoice_customer_list_price, 
        customer_purchase_order_list_price_sum as invoice_customer_list_total, 
        customer_purchase_order_list_price_sum as invoice_customer_list_cost,
        stock_event,
        CONCAT('Order for customer PO : ',customer_purchase_order_code) as invoice_customer_list_remark 
        FROM tb_customer_purchase_order 
        LEFT JOIN tb_customer_purchase_order_list as tb2 ON tb_customer_purchase_order.customer_purchase_order_id = tb2.customer_purchase_order_id 
        LEFT JOIN tb_product ON tb2.product_id = tb_product.product_id 
        LEFT JOIN tb_product_category ON tb_product.product_category_id = tb_product_category.product_category_id 
        WHERE tb_customer_purchase_order.customer_id = '$customer_id' 
        $str_po 
        AND tb2.customer_purchase_order_list_id NOT IN ($str) 
        AND tb2.customer_purchase_order_list_id IN (
            SELECT tb_customer_purchase_order_list.customer_purchase_order_list_id 
            FROM tb_customer_purchase_order_list  
            LEFT JOIN tb_invoice_customer_list ON  tb_customer_purchase_order_list.customer_purchase_order_list_id = tb_invoice_customer_list.customer_purchase_order_list_id 
            GROUP BY tb_customer_purchase_order_list.customer_purchase_order_list_id 
            HAVING IFNULL(SUM(invoice_customer_list_qty),0) < AVG(customer_purchase_order_list_qty)  
        )  
        AND customer_purchase_order_finish = '0' 
        AND (product_name LIKE ('%$search%') OR customer_purchase_order_code LIKE ('%$search%') OR product_code LIKE ('%$search%')) ";

        // echo $sql_customer;
        $data = [];
        if ($result = mysqli_query(static::$db,$sql_customer, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }

        return $data;
    }

    function insertInvoiceCustomer($data = []){
        $sql = " INSERT INTO tb_invoice_customer (
            customer_id,
            employee_id,
            invoice_customer_code,
            invoice_customer_total_price,
            invoice_customer_vat,
            invoice_customer_vat_price,
            invoice_customer_net_price,
            invoice_customer_date,
            invoice_customer_name,
            invoice_customer_address,
            invoice_customer_tax,
            invoice_customer_branch,
            invoice_customer_term,
            invoice_customer_due,
            invoice_customer_due_day,
            invoice_customer_purchase,
            invoice_customer_begin,  
            vat_section,
            vat_section_add,
            invoice_customer_total_price_non,
            invoice_customer_vat_price_non,
            invoice_customer_total_non,
            invoice_customer_description,
            invoice_customer_remark,
            invoice_customer_print_line,
            invoice_customer_remark_top,
            invoice_customer_remark_bottom,
            addby,
            adddate,
            updateby,
            lastupdate) 
        VALUES ('".
        $data['customer_id']."','".
        $data['employee_id']."','".
        static::$db->real_escape_string($data['invoice_customer_code'])."','".
        $data['invoice_customer_total_price']."','".
        $data['invoice_customer_vat']."','".
        $data['invoice_customer_vat_price']."','".
        $data['invoice_customer_net_price']."','".
        static::$db->real_escape_string($data['invoice_customer_date'])."','".
        static::$db->real_escape_string($data['invoice_customer_name'])."','".
        static::$db->real_escape_string($data['invoice_customer_address'])."','".
        static::$db->real_escape_string($data['invoice_customer_tax'])."','".
        static::$db->real_escape_string($data['invoice_customer_branch'])."','".
        static::$db->real_escape_string($data['invoice_customer_term'])."','".
        static::$db->real_escape_string($data['invoice_customer_due'])."','".
        static::$db->real_escape_string($data['invoice_customer_due_day'])."','".
        static::$db->real_escape_string($data['invoice_customer_purchase'])."','".
        $data['invoice_customer_begin']."','".
        static::$db->real_escape_string($data['vat_section'])."','".
        static::$db->real_escape_string($data['vat_section_add'])."','".
        static::$db->real_escape_string($data['invoice_customer_total_price_non'])."','".
        static::$db->real_escape_string($data['invoice_customer_vat_price_non'])."','".
        static::$db->real_escape_string($data['invoice_customer_total_non'])."','".
        static::$db->real_escape_string($data['invoice_customer_description'])."','".
        static::$db->real_escape_string($data['invoice_customer_remark'])."','".
        static::$db->real_escape_string($data['invoice_customer_print_line'])."','".
        static::$db->real_escape_string($data['invoice_customer_print_top'])."','".
        static::$db->real_escape_string($data['invoice_customer_remark_bottom'])."','".
        $data['addby']."',".
        "NOW(),'".
        $data['addby'].
        "',NOW()); 
        ";


        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }

    function cancelInvoiceCustomerById($id){
        $sql = " UPDATE tb_invoice_customer SET 
        invoice_customer_close = '1', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE invoice_customer_id = '$id' 
        ";

        //echo $sql;

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function uncancelInvoiceCustomerById($id){
        $sql = " UPDATE tb_invoice_customer SET 
        invoice_customer_close = '0', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW()
        WHERE invoice_customer_id = '$id' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function deleteInvoiceCustomerByID($id){

        $sql = " DELETE FROM tb_invoice_customer_list WHERE invoice_customer_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_invoice_customer_cost WHERE invoice_customer_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_invoice_customer WHERE invoice_customer_id = '$id' ";
        
        if(mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)){
            return true;
        }else{
            return false;
        }

        

    }


}
?>