<?php

require_once("BaseModel.php");
class StockIssueModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getStockIssueBy($date_start  = '', $date_end  = '',$customer_id = '',$keyword =''){

        $str_customer = "";
        $str_date = ""; 
        $str_lock = "";

        if($lock_1 == "1" && $lock_2 == "1"){
            $str_lock = "AND (paper_lock_1 = '0' OR paper_lock_2 = '0')";
        }else if ($lock_1 == "1") {
            $str_lock = "AND paper_lock_1 = '0' ";
        }else if($lock_2 == "1"){
            $str_lock = "AND paper_lock_2 = '0' ";
        }

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(stock_issue_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(stock_issue_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(stock_issue_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(stock_issue_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($user_id != ""){
            $str_user = "AND employee_id = '$user_id' ";
        } 

        $sql = " SELECT stock_issue_id, 
        tb_stock_issue.invoice_customer_list_id, 
        tb_stock_issue.invoice_customer_id, 
        invoice_customer_list_qty,
        invoice_customer_list_total,
        invoice_customer_code,
        product_code,
        product_name,
        product_unit_name,
        stock_issue_code, 
        stock_issue_date,  
        stock_issue_name,  
        stock_issue_address,  
        stock_issue_tax,  
        stock_issue_branch,  
        stock_issue_remark, 
        stock_issue_total_cost, 
        stock_issue_invoice_cost, 
        stock_issue_profit, 
        stock_issue_profit_percent, 
        invoice_customer_list_total,
        IFNULL(CONCAT(user_name,' ',user_lastname),'-') as employee_name 
        FROM tb_stock_issue 
        LEFT JOIN tb_user ON tb_stock_issue.employee_id = tb_user.user_id 
        LEFT JOIN tb_invoice_customer_list ON tb_stock_issue.invoice_customer_list_id = tb_invoice_customer_list.invoice_customer_list_id  
        LEFT JOIN tb_product ON tb_invoice_customer_list.product_id = tb_product.product_id  
        LEFT JOIN tb_product_unit ON tb_product.product_unit = tb_product_unit.product_unit_id  
        LEFT JOIN tb_invoice_customer ON tb_stock_issue.invoice_customer_id = tb_invoice_customer.invoice_customer_id  
        LEFT JOIN tb_paper_lock ON SUBSTRING(tb_stock_issue.stock_issue_date,3,9)=SUBSTRING(tb_paper_lock.paper_lock_date,3,9) 
        WHERE ( 
            CONCAT(tb_user.user_name,' ',tb_user.user_lastname) LIKE ('%$keyword%')  
            OR  stock_issue_code LIKE ('%$keyword%') 
        ) 
        $str_date
        ORDER BY stock_issue_code DESC 
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

    function getStockIssueByID($id){
        $sql = " SELECT tb_stock_issue.stock_issue_id, 
        tb_stock_issue.invoice_customer_list_id, 
        tb_stock_issue.invoice_customer_id, 
        tb_stock_issue.employee_id,  
        invoice_customer_code,
        tb_stock_issue.stock_issue_code, 
        tb_stock_issue.stock_issue_date,  
        tb_stock_issue.stock_issue_remark, 
        stock_issue_total_cost, 
        stock_issue_invoice_cost, 
        stock_issue_profit, 
        stock_issue_profit_percent, 
        stock_issue_name,  
        stock_issue_address,  
        stock_issue_tax,  
        stock_issue_branch, 
        invoice_customer_list_total,
        IFNULL(CONCAT(tb_user.user_name,' ',tb_user.user_lastname),'-') as employee_name,
        IFNULL(CONCAT(tb.user_name,' ',tb.user_lastname),'-') as invoice_employee_name  
        FROM tb_stock_issue 
        LEFT JOIN tb_user ON tb_stock_issue.employee_id = tb_user.user_id 
        LEFT JOIN tb_invoice_customer_list ON tb_stock_issue.invoice_customer_list_id = tb_invoice_customer_list.invoice_customer_list_id  
        LEFT JOIN tb_invoice_customer ON tb_stock_issue.invoice_customer_id = tb_invoice_customer.invoice_customer_id  
        LEFT JOIN tb_user as tb ON tb_invoice_customer.employee_id = tb.user_id  
        WHERE stock_issue_id = '$id' 
        ";

        //echo $sql;

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getStockIssueViewByID($id){
        $sql = "SELECT tb_stock_issue.stock_issue_id, 
        tb_stock_issue.invoice_customer_list_id, 
        tb_stock_issue.invoice_customer_id, 
        tb_stock_issue.employee_id,  
        IF(tb_stock_issue.invoice_customer_list_id!=0,invoice_customer_list_qty,1) as invoice_customer_list_qty,
        IF(tb_stock_issue.invoice_customer_list_id!=0,invoice_customer_list_total,invoice_customer_total_price) as invoice_customer_list_total,
        invoice_customer_code,
        IFNULL(product_code,'-') as product_code,
        IFNULL(product_name,'-') as product_name,
        IFNULL(product_unit_name,'PC') as product_unit_name,
        tb_stock_issue.stock_issue_code, 
        tb_stock_issue.stock_issue_date,  
        tb_stock_issue.stock_issue_remark, 
        stock_issue_total_cost, 
        stock_issue_invoice_cost, 
        stock_issue_profit, 
        stock_issue_profit_percent, 
        stock_issue_name,  
        stock_issue_address,  
        stock_issue_tax,  
        stock_issue_branch,  
        tb_user.user_signature,
        IFNULL(CONCAT(tb_user.user_name,' ',tb_user.user_lastname),'-') as employee_name,
        IFNULL(CONCAT(tb.user_name,' ',tb.user_lastname),'-') as invoice_employee_name  
        FROM tb_stock_issue 
        LEFT JOIN tb_user ON tb_stock_issue.employee_id = tb_user.user_id 
        LEFT JOIN tb_invoice_customer_list ON tb_stock_issue.invoice_customer_list_id = tb_invoice_customer_list.invoice_customer_list_id  
        LEFT JOIN tb_product ON tb_invoice_customer_list.product_id = tb_product.product_id  
        LEFT JOIN tb_product_unit ON tb_product.product_unit = tb_product_unit.product_unit_id  
        LEFT JOIN tb_invoice_customer ON tb_stock_issue.invoice_customer_id = tb_invoice_customer.invoice_customer_id  
        LEFT JOIN tb_user as tb ON tb_invoice_customer.employee_id = tb.user_id  
        WHERE stock_issue_id = '$id' 
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

    function getStockIssueLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(stock_issue_code,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  stock_issue_lastcode 
        FROM tb_stock_issue
        WHERE stock_issue_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['stock_issue_lastcode'];
        }

    }

   
    function updateStockIssueByID($id,$data = []){
        $sql = " UPDATE tb_stock_issue SET 
        invoice_customer_list_id = '".$data['invoice_customer_list_id']."',  
        invoice_customer_id = '".$data['invoice_customer_id']."',  
        employee_id = '".$data['employee_id']."', 
        stock_issue_code = '".$data['stock_issue_code']."', 
        stock_issue_date = '".$data['stock_issue_date']."', 
        stock_issue_name = '".$data['stock_issue_name']."', 
        stock_issue_address = '".$data['stock_issue_address']."', 
        stock_issue_tax = '".$data['stock_issue_tax']."', 
        stock_issue_branch = '".$data['stock_issue_branch']."', 
        stock_issue_remark = '".$data['stock_issue_remark']."', 
        stock_issue_total_cost = '".$data['stock_issue_total_cost']."', 
        stock_issue_invoice_cost = '".$data['stock_issue_invoice_cost']."', 
        stock_issue_profit = '".$data['stock_issue_profit']."', 
        stock_issue_profit_percent = '".$data['stock_issue_profit_percent']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE stock_issue_id = $id 
        ";

        //echo "<pre>".$sql."</pre>";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }



    function insertStockIssue($data = []){
        $sql = " INSERT INTO tb_stock_issue (
            invoice_customer_list_id,  
            invoice_customer_id,  
            employee_id,
            stock_issue_code,
            stock_issue_date,
            stock_issue_name,  
            stock_issue_address,  
            stock_issue_tax,  
            stock_issue_branch, 
            stock_issue_remark,
            stock_issue_total_cost,
            stock_issue_invoice_cost,
            stock_issue_profit,
            stock_issue_profit_percent,
            addby,
            adddate,
            updateby,
            lastupdate) 
        VALUES ('".
        $data['invoice_customer_list_id']."','". 
        $data['invoice_customer_id']."','". 
        $data['employee_id']."','".
        $data['stock_issue_code']."','".
        $data['stock_issue_date']."','".
        $data['stock_issue_name']."','".
        $data['stock_issue_address']."','".
        $data['stock_issue_tax']."','".
        $data['stock_issue_branch']."','".
        $data['stock_issue_remark']."','".
        $data['stock_issue_total_cost']."','".
        $data['stock_issue_invoice_cost']."','".
        $data['stock_issue_profit']."','".
        $data['stock_issue_profit_percent']."','".
        $data['addby']."',".
        "NOW(),'".
        $data['addby'].
        "',NOW()); 
        ";

        echo "<pre>".$sql."</pre>";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }



    function deleteStockIssueByID($id){
 

        $sql = " DELETE FROM tb_stock_issue_list WHERE stock_issue_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_stock_issue WHERE stock_issue_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }


}
?>