<?php

require_once("BaseModel.php");
class DashboardModel extends BaseModel{

    private $maintenance_stock;

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
            
        }
        mysqli_set_charset(static::$db,"utf8");

        // $this->maintenance_stock =  new MaintenanceStockModel;
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
        ORDER BY invoice_customer_code ASC 
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

    function getNetPriceGroupByDate($year='2019'){
        $sql = " SELECT DATE_FORMAT(STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s'), '%Y-%m') AS invoice_date ,
        SUM(invoice_customer_total_price) AS 'net_price' 
        FROM `tb_invoice_customer` 
        LEFT JOIN tb_customer ON tb_invoice_customer.customer_id = tb_customer.customer_id 
        WHERE DATE_FORMAT(STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s'), '%Y') = '$year'
        GROUP BY invoice_date
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
    function getNetPriceGroupByCustomer($year='2019'){
         
        $sql = "SELECT 
                    tb_customer.customer_id,
                    tb_customer.customer_code,
                    tb_customer.customer_name_en as 'customer_name',
                    invoice_date,SUM(net_price) as 'net_price'
                FROM
                    ( 
                        (   
                        SELECT  tb_invoice_customer.customer_id as 'customer_id',  
                                DATE_FORMAT(STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s'), '%Y-%m') AS invoice_date ,
                                SUM(invoice_customer_total_price) AS 'net_price' 
                        FROM `tb_invoice_customer`  
                        WHERE DATE_FORMAT(STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s'), '%Y') = '$year'
                        AND invoice_customer_close = 0 
                        AND invoice_customer_begin = 0 
                        GROUP BY tb_invoice_customer.customer_id 
                        ) UNION ALL (
                        SELECT  tb_credit_note.customer_id as 'customer_id',  
                                DATE_FORMAT(STR_TO_DATE(credit_note_date,'%d-%m-%Y %H:%i:%s'), '%Y-%m') AS invoice_date ,
                                -SUM(credit_note_total_price) AS 'net_price' 
                        FROM `tb_credit_note`  
                        WHERE DATE_FORMAT(STR_TO_DATE(credit_note_date,'%d-%m-%Y %H:%i:%s'), '%Y') = '$year'
                        AND credit_note_close = 0 
                        GROUP BY tb_credit_note.customer_id 
                        ) UNION ALL (
                        SELECT  tb_debit_note.customer_id as 'customer_id',  
                                DATE_FORMAT(STR_TO_DATE(debit_note_date,'%d-%m-%Y %H:%i:%s'), '%Y-%m') AS invoice_date ,
                                SUM(debit_note_total_price) AS 'net_price' 
                        FROM `tb_debit_note`  
                        WHERE DATE_FORMAT(STR_TO_DATE(debit_note_date,'%d-%m-%Y %H:%i:%s'), '%Y') = '$year'
                        AND debit_note_close = 0 
                        GROUP BY tb_debit_note.customer_id 
                        )
                ) AS tb_data 
                LEFT JOIN tb_customer  ON (tb_data.customer_id = tb_customer.customer_id) 
                GROUP BY tb_data.customer_id
                ORDER BY (net_price) DESC
        ";
        // return $sql;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getJournalSalesReportShowAllListBy($date_start="", $date_end = "",$product_category_id = "",$order_by_type = ''){
        
        $str_date = ""; 
        $str_category = '';
        $str_order_by = "";



        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        } 

        if($product_category_id != ""){
            $str_category = " AND tb_product.product_category_id = '$product_category_id' ";
        }


        if($order_by_type == "sale_customer"){
            $str_order_by = " ORDER BY employee_id , invoice_customer_code , invoice_customer_list_no ASC ";

        }else{
            $str_order_by = " ORDER BY employee_id, customer_code , invoice_customer_code , invoice_customer_list_no ASC ";
        }
        $sql =" SELECT * 
                FROM
                (
                    (
                        SELECT 
                            '1_invoice' as paper_type,
                            tb_invoice_customer.invoice_customer_id,
                            invoice_customer_code,
                            invoice_customer_date,
                            employee_id,
                            customer_id,
                            invoice_customer_close,
                            product_id,
                            invoice_customer_list_id,
                            invoice_customer_list_no,
                            invoice_customer_list_qty,
                            invoice_customer_list_price,
                            invoice_customer_list_total   
                        FROM tb_invoice_customer 
                        LEFT JOIN tb_invoice_customer_list ON tb_invoice_customer.invoice_customer_id = tb_invoice_customer_list.invoice_customer_id 
                        WHERE invoice_customer_close = 0
                        AND invoice_customer_begin = 0    
                    ) UNION ALL (
                        SELECT 
                            '2_credit_note' as paper_type,
                            tb_credit_note.credit_note_id as invoice_customer_id,
                            credit_note_code as invoice_customer_code,
                            credit_note_date as invoice_customer_date,
                            IFNULL((SELECT employee_id FROM tb_invoice_customer WHERE invoice_customer_id = tb_credit_note.invoice_customer_id),employee_id) as employee_id,
                            customer_id,
                            credit_note_close as invoice_customer_close,
                            product_id,
                            credit_note_list_id as invoice_customer_list_id,
                            credit_note_list_no as invoice_customer_list_no,
                            credit_note_list_qty as invoice_customer_list_qty,
                             - credit_note_list_price as invoice_customer_list_price,
                             - credit_note_list_total as invoice_customer_list_total  
                        FROM tb_credit_note 
                        LEFT JOIN tb_credit_note_list ON tb_credit_note.credit_note_id = tb_credit_note_list.credit_note_id 
                        WHERE credit_note_close = 0  
                        AND tb_credit_note_list.credit_note_id IS NOT NULL
                    ) UNION ALL (
                        SELECT 
                            '3_debit_note' as paper_type,
                            tb_debit_note.debit_note_id as invoice_customer_id,
                            debit_note_code as invoice_customer_code,
                            debit_note_date as invoice_customer_date,
                            IFNULL((SELECT employee_id FROM tb_invoice_customer WHERE invoice_customer_id = tb_debit_note.invoice_customer_id),employee_id) as employee_id,
                            customer_id,
                            debit_note_close as invoice_customer_close,
                            product_id,
                            debit_note_list_id as invoice_customer_list_id,
                            debit_note_list_no as invoice_customer_list_no,
                            debit_note_list_qty as invoice_customer_list_qty,
                            debit_note_list_price as invoice_customer_list_price,
                            debit_note_list_total as invoice_customer_list_total  
                        FROM tb_debit_note 
                        LEFT JOIN tb_debit_note_list ON tb_debit_note.debit_note_id = tb_debit_note_list.debit_note_id 
                        WHERE debit_note_close = 0  
                        AND tb_debit_note_list.debit_note_id IS NOT NULL
                    )
                ) AS tb_data 
                LEFT JOIN tb_product ON tb_data.product_id = tb_product.product_id 
                LEFT JOIN tb_customer  ON (tb_data.customer_id = tb_customer.customer_id) 
                LEFT JOIN tb_user ON (tb_data.employee_id = tb_user.user_id) 
                WHERE invoice_customer_close = 0  
                $str_category
                $str_date 
                $str_order_by 
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
 
    function getNetPriceGroupByCustomerLimit($page_start,$page_end,$year='2019'){
        $sql = "SELECT 
                    tb_data.customer_id,
                    tb_customer.customer_code, 
                    tb_customer.customer_name_en AS customer_name, 
                    invoice_date,SUM(net_price) as 'net_price'
                FROM
                    ( 
                        (   
                        SELECT  tb_invoice_customer.customer_id as 'customer_id', 
                                DATE_FORMAT(STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s'), '%Y-%m') AS invoice_date ,
                                SUM(invoice_customer_total_price) AS 'net_price' 
                        FROM `tb_invoice_customer`  
                        WHERE DATE_FORMAT(STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s'), '%Y') = '$year'
                        AND invoice_customer_close = 0
                        AND invoice_customer_begin = 0  
                        GROUP BY tb_invoice_customer.customer_id 
                        ) UNION ALL (
                        SELECT  tb_credit_note.customer_id as 'customer_id', 
                                DATE_FORMAT(STR_TO_DATE(credit_note_date,'%d-%m-%Y %H:%i:%s'), '%Y-%m') AS invoice_date ,
                                -SUM(credit_note_total_price) AS 'net_price' 
                        FROM `tb_credit_note`  
                        WHERE DATE_FORMAT(STR_TO_DATE(credit_note_date,'%d-%m-%Y %H:%i:%s'), '%Y') = '$year'
                        AND credit_note_close = 0 
                        GROUP BY tb_credit_note.customer_id 
                        ) UNION ALL (
                        SELECT  tb_debit_note.customer_id as 'customer_id', 
                                DATE_FORMAT(STR_TO_DATE(debit_note_date,'%d-%m-%Y %H:%i:%s'), '%Y-%m') AS invoice_date ,
                                SUM(debit_note_total_price) AS 'net_price' 
                        FROM `tb_debit_note`  
                        WHERE DATE_FORMAT(STR_TO_DATE(debit_note_date,'%d-%m-%Y %H:%i:%s'), '%Y') = '$year'
                        AND debit_note_close = 0 
                        GROUP BY tb_debit_note.customer_id 
                        )
                    ) AS tb_data 
                    LEFT JOIN tb_customer  ON (tb_data.customer_id = tb_customer.customer_id) 
                    GROUP BY tb_data.customer_id
                    ORDER BY (net_price) DESC LIMIT $page_start,$page_end 
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
    function getBarChart($user_id='',$year='2019'){
        $sql = " SELECT 
        
                    tb_customer.customer_code AS code ,
                    tb_customer.customer_name_en AS customer_name,
                    customer_name_en, 
                    SUM(net_price) as 'net_price'
                FROM 
                    ( 
                        ( SELECT 
                                customer_id,
                                SUM(invoice_customer_total_price) AS 'net_price' 
                            FROM `tb_invoice_customer`  
                            WHERE  tb_invoice_customer.employee_id ='$user_id'
                            AND invoice_customer_close = 0 
                            AND invoice_customer_begin = 0 
                            AND DATE_FORMAT(STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s'), '%Y') = '$year'
                            GROUP BY tb_invoice_customer.customer_id 
                        ) UNION ALL
                        ( SELECT  
                                customer_id,
                                -SUM(credit_note_total_price) AS 'net_price' 
                            FROM `tb_credit_note`  
                            WHERE  tb_credit_note.employee_id ='$user_id'
                            AND credit_note_close = 0 
                            AND DATE_FORMAT(STR_TO_DATE(credit_note_date,'%d-%m-%Y %H:%i:%s'), '%Y') = '$year'
                            GROUP BY tb_credit_note.customer_id 
                        ) UNION ALL
                        ( SELECT  
                                customer_id,
                                SUM(debit_note_total_price) AS 'net_price' 
                            FROM `tb_debit_note`  
                            WHERE  tb_debit_note.employee_id ='$user_id'
                            AND debit_note_close = 0 
                            AND DATE_FORMAT(STR_TO_DATE(debit_note_date,'%d-%m-%Y %H:%i:%s'), '%Y') = '$year'
                            GROUP BY tb_debit_note.customer_id 
                        ) 
                    ) as tb
                    LEFT JOIN  tb_customer  ON  tb.customer_id = tb_customer.customer_id
                    GROUP BY tb_customer.customer_id
                    ORDER BY net_price DESC
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
    function getBarChartAllCustomer($year ='2019'){
        
        $sql = "SELECT  
                    tb_customer.customer_code AS code ,
                    tb_customer.customer_name_en AS customer_name,
                    customer_name_en, 
                    SUM(net_price) as 'net_price' 
                FROM ( 
                        (
                        SELECT 
                            customer_id, 
                            SUM(invoice_customer_total_price) AS 'net_price' 
                        FROM `tb_invoice_customer`  
                        WHERE  1
                        AND invoice_customer_close = 0 
                        AND invoice_customer_begin = 0 
                        AND DATE_FORMAT(STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s'), '%Y') = '$year'
                        GROUP BY tb_invoice_customer.customer_id 
                        ) UNION ALL
                        (
                        SELECT 
                            customer_id, 
                            -SUM(credit_note_total_price) AS 'net_price' 
                        FROM `tb_credit_note`  
                        WHERE  1
                        AND credit_note_close = 0 
                        AND DATE_FORMAT(STR_TO_DATE(credit_note_date,'%d-%m-%Y %H:%i:%s'), '%Y') = '$year'
                        GROUP BY tb_credit_note.customer_id 
                        ) UNION ALL
                        (
                        SELECT 
                            customer_id, 
                            SUM(debit_note_total_price) AS 'net_price' 
                        FROM `tb_debit_note`  
                        WHERE  1
                        AND debit_note_close = 0 
                        AND DATE_FORMAT(STR_TO_DATE(debit_note_date,'%d-%m-%Y %H:%i:%s'), '%Y') = '$year'
                        GROUP BY tb_debit_note.customer_id 
                        )
                ) as tb 
                LEFT JOIN  tb_customer  ON  tb.customer_id = tb_customer.customer_id
                GROUP BY tb_customer.customer_id
                ORDER BY net_price DESC
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
    
    function getNetPriceGroupByAllSales($year='2019'){
        $sql = "SELECT tb_user.user_id,CONCAT(tb_user.user_name,'  ',tb_user.user_lastname) AS sales_name , 
        DATE_FORMAT(STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s'), '%Y-%m') AS invoice_date ,
        SUM(invoice_customer_total_price) AS 'net_price' 
        FROM `tb_invoice_customer` 
        LEFT JOIN tb_customer ON tb_invoice_customer.customer_id = tb_customer.customer_id 
        LEFT JOIN tb_user ON tb_invoice_customer.employee_id = tb_user.user_id 
        WHERE DATE_FORMAT(STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s'), '%Y') = '$year'
        GROUP BY tb_user.user_id  
        ORDER BY SUM(invoice_customer_total_price) DESC 
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
    function getNetPriceGroupBySales($sales=''){
         
        $sql = "SELECT 
                tb_user.user_id,
                tb_user.user_name AS 'sales_name',
                invoice_date,
                SUM(net_price) as 'net_price' 
        FROM(
            (SELECT   
                customer_id,
                employee_id,
                DATE_FORMAT(STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s'), '%Y') AS invoice_date ,
                SUM(invoice_customer_total_price) AS 'net_price' 
            FROM `tb_invoice_customer`  
            WHERE  
            tb_invoice_customer.employee_id = '$sales' 
            AND invoice_customer_close = 0 
            AND invoice_customer_begin = 0 
            GROUP BY invoice_date 
            ORDER BY invoice_date
            )UNION ALL (
                SELECT
                customer_id,
                employee_id, 
                DATE_FORMAT(STR_TO_DATE(credit_note_date,'%d-%m-%Y %H:%i:%s'), '%Y') AS invoice_date ,
                -SUM(credit_note_total_price) AS 'net_price' 
            FROM `tb_credit_note`  
            WHERE  
            tb_credit_note.employee_id = '$sales' 
            AND credit_note_close = 0 
            GROUP BY invoice_date 
            ORDER BY invoice_date
            )UNION ALL (
                SELECT
                customer_id,
                employee_id, 
                DATE_FORMAT(STR_TO_DATE(debit_note_date,'%d-%m-%Y %H:%i:%s'), '%Y') AS invoice_date ,
                SUM(debit_note_total_price) AS 'net_price' 
            FROM `tb_debit_note`  
            WHERE  
            tb_debit_note.employee_id = '$sales' 
            AND debit_note_close = 0 
            GROUP BY invoice_date 
            ORDER BY invoice_date
            )
        ) as tb
        LEFT JOIN tb_customer ON tb.customer_id = tb_customer.customer_id 
        LEFT JOIN tb_user ON tb.employee_id = tb_user.user_id 
        GROUP BY invoice_date 
        ORDER BY invoice_date
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
    function getNetPriceBySales($sales){
        $str = "";
        if($sales !=""){
            $str = " tb_invoice_customer.employee_id = '$sales' " ;
        }
        $sql = "SELECT tb_user.user_id,tb_user.user_name AS 'sales_name' , 
        DATE_FORMAT(STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s'), '%Y-%m') AS invoice_date ,
        SUM(invoice_customer_total_price) AS 'net_price' 
        FROM `tb_invoice_customer` 
        LEFT JOIN tb_customer ON tb_invoice_customer.customer_id = tb_customer.customer_id 
        LEFT JOIN tb_user ON tb_invoice_customer.employee_id = tb_user.user_id 
        WHERE $str
        GROUP BY invoice_date
        ORDER BY invoice_date 
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
    function getCustomerAll(){
        $sql = "SELECT * 
        FROM `tb_customer` 
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
    ///---------------------------------------- App Mobile-----------------------------------------///
    function getNetPriceGroupByYear($year = '2019'){

        $sql = " SELECT DATE_FORMAT(STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s'), '%m') AS invoice_date ,
        SUM(invoice_customer_total_price) AS 'net_price' 
        FROM `tb_invoice_customer` 
        LEFT JOIN tb_customer ON tb_invoice_customer.customer_id = tb_customer.customer_id 
        WHERE DATE_FORMAT(STR_TO_DATE(invoice_customer_total_price,'%d-%m-%Y %H:%i:%s'), '%Y') = '$year'
        GROUP BY invoice_date
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

    ///---------------------------------------- App Mobile-----------------------------------------///

    function getNetPriceGroupBy($year = '2019'){ 

        $sql = " SELECT  
        invoice_date,
        invoice_date_M,
        SUM(net_price) as 'net_price'
            FROM (
                    ( 
                    SELECT
                        customer_id,
                        DATE_FORMAT( STR_TO_DATE( invoice_customer_date, '%d-%m-%Y %H:%i:%s' ), '%Y-%m' ) AS invoice_date,
                        DATE_FORMAT( STR_TO_DATE(  invoice_customer_date, '%d-%m-%Y %H:%i:%s'  ),  '%m' ) AS invoice_date_M,
                        SUM(invoice_customer_total_price) AS 'net_price'
                    FROM  `tb_invoice_customer` 
                    WHERE 1 
                    AND invoice_customer_close = 0 
                    AND invoice_customer_begin = 0 
                    AND DATE_FORMAT(STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s'), '%Y') = '$year'
                    GROUP BY  invoice_date
                    ORDER BY invoice_date DESC 
                    ) UNION ALL
                    ( 
                    SELECT
                        customer_id,
                        DATE_FORMAT( STR_TO_DATE( credit_note_date, '%d-%m-%Y %H:%i:%s' ), '%Y-%m' ) AS invoice_date,
                        DATE_FORMAT( STR_TO_DATE(  credit_note_date, '%d-%m-%Y %H:%i:%s'  ),  '%m' ) AS invoice_date_M,
                        -SUM(credit_note_total_price) AS 'net_price'
                    FROM  `tb_credit_note` 
                    WHERE 1
                            AND credit_note_close = 0 
                    AND DATE_FORMAT(STR_TO_DATE(credit_note_date,'%d-%m-%Y %H:%i:%s'), '%Y') = '$year'
                    GROUP BY  invoice_date
                    ORDER BY invoice_date DESC 
                    ) UNION ALL
                    ( 
                    SELECT
                        customer_id,
                        DATE_FORMAT( STR_TO_DATE( debit_note_date, '%d-%m-%Y %H:%i:%s' ), '%Y-%m' ) AS invoice_date,
                        DATE_FORMAT( STR_TO_DATE(  debit_note_date, '%d-%m-%Y %H:%i:%s'  ),  '%m' ) AS invoice_date_M,
                        SUM(debit_note_total_price) AS 'net_price'
                    FROM  `tb_debit_note` 
                    WHERE 1
                            AND debit_note_close = 0 
                    AND DATE_FORMAT(STR_TO_DATE(debit_note_date,'%d-%m-%Y %H:%i:%s'), '%Y') = '$year'
                    GROUP BY  invoice_date
                    ORDER BY invoice_date DESC 
                    )
            ) as tb 
            LEFT JOIN  tb_customer  ON  tb.customer_id = tb_customer.customer_id
            GROUP BY invoice_date_M
            ORDER BY invoice_date
        ";
        $data = [];
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) { 
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }
    function getProductBestSeller($user_id='',$customer_id='',$year="2019"){
        $str_user = "";
        if($user_id !=""){
            $str_user = " AND tb_invoice_customer.employee_id ='$user_id' " ;
        }
        $sql = " SELECT product_code , product_name ,tb_product.product_id,
                        SUM(invoice_customer_list_total) as 'sale_total',
                        SUM(invoice_customer_list_qty) as 'qty_total', 
                        MAX(STR_TO_DATE(  invoice_customer_date, '%d-%m-%Y %H:%i:%s'  )) as 'last_sell' 
            FROM  `tb_invoice_customer`
            LEFT JOIN  tb_invoice_customer_list  ON  tb_invoice_customer.invoice_customer_id = tb_invoice_customer_list.invoice_customer_id 
            LEFT JOIN  tb_product  ON  tb_invoice_customer_list.product_id = tb_product.product_id  
            WHERE 
                tb_invoice_customer.customer_id ='$customer_id' 
                $str_user
                AND tb_product.product_id IS NOT NULL 
                AND DATE_FORMAT(STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s'), '%Y') = '$year'

            GROUP BY tb_invoice_customer_list.product_id
            ORDER BY SUM(invoice_customer_list_total) DESC
        ";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
            // 
        }

    }

    function getCustomerDashBoardByID($user_id='',$customer_id='',$year="2019"){
        $str_user = "";
        $str_user_credit = "";
        $str_user_debit = "";
        if($user_id !=""){
            $str_user = " AND tb_invoice_customer.employee_id ='$user_id' " ;
            $str_user_credit = " AND tb_credit_note.employee_id ='$user_id' " ;
            $str_user_debit = " AND tb_debit_note.employee_id ='$user_id' " ;
        }
        $sql = "SELECT  
                        invoice_date,
                        invoice_date_M,
                        SUM(net_price) as 'net_price'
                FROM 
                    ( 
                        ( SELECT 
                                customer_id,
                        DATE_FORMAT( STR_TO_DATE( invoice_customer_date, '%d-%m-%Y %H:%i:%s' ), '%Y-%m' ) AS invoice_date,
                        DATE_FORMAT( STR_TO_DATE(  invoice_customer_date, '%d-%m-%Y %H:%i:%s'  ),  '%m' ) AS invoice_date_M,
                                SUM(invoice_customer_total_price) AS 'net_price' 
                            FROM `tb_invoice_customer`  
                            WHERE 1 
                            $str_user
                            AND customer_id = '$customer_id'
                            AND invoice_customer_close = 0 
                            AND invoice_customer_begin = 0 
                            AND DATE_FORMAT(STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s'), '%Y') = '$year'
                            GROUP BY  invoice_date
                        ) UNION ALL
                        ( SELECT  
                                customer_id,
                        DATE_FORMAT( STR_TO_DATE(credit_note_date, '%d-%m-%Y %H:%i:%s' ), '%Y-%m' ) AS invoice_date,
                        DATE_FORMAT( STR_TO_DATE(  credit_note_date, '%d-%m-%Y %H:%i:%s'  ),  '%m' ) AS invoice_date_M,
                                -SUM(credit_note_total_price) AS 'net_price' 
                            FROM `tb_credit_note`  
                            WHERE 1 
                            $str_user_credit 
                            AND customer_id = '$customer_id'
                            AND credit_note_close = 0 
                            AND DATE_FORMAT(STR_TO_DATE(credit_note_date,'%d-%m-%Y %H:%i:%s'), '%Y') = '$year'
                            GROUP BY  invoice_date
                        ) UNION ALL
                        ( SELECT  
                                customer_id,
                        DATE_FORMAT( STR_TO_DATE(debit_note_date, '%d-%m-%Y %H:%i:%s' ), '%Y-%m' ) AS invoice_date,
                        DATE_FORMAT( STR_TO_DATE( debit_note_date, '%d-%m-%Y %H:%i:%s'  ),  '%m' ) AS invoice_date_M,
                                SUM(debit_note_total_price) AS 'net_price' 
                            FROM `tb_debit_note`  
                            WHERE 1 
                            $str_user_debit
                            AND customer_id = '$customer_id'
                            AND debit_note_close = 0 
                            AND DATE_FORMAT(STR_TO_DATE(debit_note_date,'%d-%m-%Y %H:%i:%s'), '%Y') = '$year'
                            GROUP BY  invoice_date
                        ) 
                    ) as tb
                    LEFT JOIN  tb_customer  ON  tb.customer_id = tb_customer.customer_id
                    GROUP BY  invoice_date
                    ORDER BY invoice_date ASC
        ";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
            // 
        }

    }
    function getSUMCustomerDashBoardByID($user_id='',$customer_id='',$year="2019"){
        $str_user = "";
        $str_user_credit = "";
        $str_user_debit = "";
        if($user_id !=""){
            $str_user = " AND tb_invoice_customer.employee_id ='$user_id' " ;
            $str_user_credit = " AND tb_credit_note.employee_id ='$user_id' " ;
            $str_user_debit = " AND tb_debit_note.employee_id ='$user_id' " ;
        }
        $sql = "SELECT  
                        invoice_date,
                        invoice_date_M,
                        SUM(net_price) as 'net_price'
                FROM 
                    ( 
                        ( SELECT 
                                customer_id,
                        DATE_FORMAT( STR_TO_DATE( invoice_customer_date, '%d-%m-%Y %H:%i:%s' ), '%Y-%m' ) AS invoice_date,
                        DATE_FORMAT( STR_TO_DATE(  invoice_customer_date, '%d-%m-%Y %H:%i:%s'  ),  '%m' ) AS invoice_date_M,
                                SUM(invoice_customer_total_price) AS 'net_price' 
                            FROM `tb_invoice_customer`  
                            WHERE 1 
                            $str_user
                            AND customer_id = '$customer_id'
                            AND invoice_customer_close = 0 
                            AND invoice_customer_begin = 0 
                            AND DATE_FORMAT(STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s'), '%Y') = '$year'
                            GROUP BY  invoice_date
                        ) UNION ALL
                        ( SELECT  
                                customer_id,
                        DATE_FORMAT( STR_TO_DATE(credit_note_date, '%d-%m-%Y %H:%i:%s' ), '%Y-%m' ) AS invoice_date,
                        DATE_FORMAT( STR_TO_DATE(  credit_note_date, '%d-%m-%Y %H:%i:%s'  ),  '%m' ) AS invoice_date_M,
                                -SUM(credit_note_total_price) AS 'net_price' 
                            FROM `tb_credit_note`  
                            WHERE 1 
                            $str_user_credit
                            AND customer_id = '$customer_id'
                            AND credit_note_close = 0 
                            AND DATE_FORMAT(STR_TO_DATE(credit_note_date,'%d-%m-%Y %H:%i:%s'), '%Y') = '$year'
                            GROUP BY  invoice_date
                        ) UNION ALL
                        ( SELECT  
                                customer_id,
                        DATE_FORMAT( STR_TO_DATE(debit_note_date, '%d-%m-%Y %H:%i:%s' ), '%Y-%m' ) AS invoice_date,
                        DATE_FORMAT( STR_TO_DATE( debit_note_date, '%d-%m-%Y %H:%i:%s'  ),  '%m' ) AS invoice_date_M,
                                SUM(debit_note_total_price) AS 'net_price' 
                            FROM `tb_debit_note`  
                            WHERE 1 
                            $str_user_debit
                            AND customer_id = '$customer_id'
                            AND debit_note_close = 0 
                            AND DATE_FORMAT(STR_TO_DATE(debit_note_date,'%d-%m-%Y %H:%i:%s'), '%Y') = '$year'
                            GROUP BY  invoice_date
                        ) 
                    ) as tb
                    LEFT JOIN  tb_customer  ON  tb.customer_id = tb_customer.customer_id
                    GROUP BY  tb_customer.customer_id
                    ORDER BY invoice_date DESC
        ";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
            // 
        }

    }

    function getNetPriceGroupByCustomerBySale($user_id){

        $sql = " SELECT 
                    SUM(invoice_customer_total_price) AS 'net_price' ,customer_name_en
                FROM  `tb_invoice_customer`
                LEFT JOIN  tb_customer  ON  tb_invoice_customer.customer_id = tb_customer.customer_id
                WHERE  
                tb_invoice_customer.employee_id ='$user_id'
                GROUP BY tb_invoice_customer.customer_id
                ORDER BY SUM(invoice_customer_total_price) DESC
                ";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
            // 
        }

    }
    function getDashBoardLineChart($sales='',$year = '2019'){ 

        $sql = " SELECT  
                    invoice_date ,
                    invoice_date_M,
                    SUM(net_price) as 'net_price'
                    FROM (
                        ( 
                            SELECT 
                            customer_id,
                                DATE_FORMAT( STR_TO_DATE( invoice_customer_date, '%d-%m-%Y %H:%i:%s' ), '%Y-%m' ) AS invoice_date,
                                DATE_FORMAT( STR_TO_DATE( invoice_customer_date, '%d-%m-%Y %H:%i:%s' ), '%m' ) AS invoice_date_M,
                                SUM(invoice_customer_total_price) AS 'net_price'
                            FROM `tb_invoice_customer` 
                            WHERE
                            tb_invoice_customer.employee_id = '$sales' 
                            AND invoice_customer_close = 0 
                            AND invoice_customer_begin = 0 
                            AND DATE_FORMAT(STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s'), '%Y') = '$year'
                            GROUP BY invoice_date
                            ORDER BY invoice_date DESC LIMIT 0 , 12
                        )UNION ALL 
                        ( 
                            SELECT 
                            customer_id,
                                DATE_FORMAT( STR_TO_DATE( credit_note_date, '%d-%m-%Y %H:%i:%s' ), '%Y-%m' ) AS invoice_date,
                                DATE_FORMAT( STR_TO_DATE( credit_note_date, '%d-%m-%Y %H:%i:%s' ), '%m' ) AS invoice_date_M,
                                    -SUM(credit_note_total_price) AS 'net_price'
                            FROM `tb_credit_note` 
                            WHERE
                            tb_credit_note.employee_id = '$sales' 
                            AND credit_note_close = 0 
                            AND DATE_FORMAT(STR_TO_DATE(credit_note_date,'%d-%m-%Y %H:%i:%s'), '%Y') = '$year'
                            GROUP BY invoice_date
                            ORDER BY invoice_date DESC LIMIT 0 , 12
                        ) UNION ALL 
                        ( 
                            SELECT 
                            customer_id,
                                DATE_FORMAT( STR_TO_DATE( debit_note_date, '%d-%m-%Y %H:%i:%s' ), '%Y-%m' ) AS invoice_date,
                                DATE_FORMAT( STR_TO_DATE( debit_note_date, '%d-%m-%Y %H:%i:%s' ), '%m' ) AS invoice_date_M,
                                    SUM(debit_note_total_price) AS 'net_price'
                            FROM `tb_debit_note` 
                            WHERE
                            tb_debit_note.employee_id = '$sales' 
                            AND debit_note_close = 0 
                            AND DATE_FORMAT(STR_TO_DATE(debit_note_date,'%d-%m-%Y %H:%i:%s'), '%Y') = '$year'
                            GROUP BY invoice_date
                            ORDER BY invoice_date DESC LIMIT 0 , 12
                        )  

                )   as tb
                 LEFT JOIN tb_customer ON tb.customer_id = tb_customer.customer_id
                 GROUP BY invoice_date_M
                 ORDER BY invoice_date
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
    function getAllTotalPriceGroupByEmployeeIDEachMount($employee_id='',$year = '2019'){ 

        $sql = " SELECT invoice_date,invoice_date_M,SUM(net_price) as 'net_price' FROM (
                            (
                            SELECT 
                                    DATE_FORMAT( STR_TO_DATE( invoice_customer_date, '%d-%m-%Y %H:%i:%s' ), '%Y-%m' ) AS invoice_date,
                                    DATE_FORMAT( STR_TO_DATE( invoice_customer_date, '%d-%m-%Y %H:%i:%s' ), '%m' ) AS invoice_date_M,
                                    SUM(invoice_customer_total_price) AS 'net_price'
                            FROM `tb_invoice_customer` 
                            WHERE 
                            tb_invoice_customer.employee_id = '$employee_id' 
                            AND DATE_FORMAT(STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s'), '%Y') = '$year'
                            AND invoice_customer_close = 0
                            AND invoice_customer_begin = 0 
                            GROUP BY invoice_date
                            ORDER BY invoice_date DESC 
                            ) UNION ALL
                            (
                                SELECT 
                                        DATE_FORMAT( STR_TO_DATE( credit_note_date, '%d-%m-%Y %H:%i:%s' ), '%Y-%m' ) AS invoice_date,
                                        DATE_FORMAT( STR_TO_DATE( credit_note_date, '%d-%m-%Y %H:%i:%s' ), '%m' ) AS invoice_date_M,
                                         -SUM(credit_note_total_price) AS 'net_price'
                                FROM `tb_credit_note` 
                                WHERE 
                                tb_credit_note.employee_id = '$employee_id' 
                                AND DATE_FORMAT(STR_TO_DATE(credit_note_date,'%d-%m-%Y %H:%i:%s'), '%Y') = '$year'
                                AND credit_note_close = 0
                                GROUP BY invoice_date
                                ORDER BY invoice_date DESC 
                            ) UNION ALL
                            (
                                SELECT 
                                        DATE_FORMAT( STR_TO_DATE( debit_note_date, '%d-%m-%Y %H:%i:%s' ), '%Y-%m' ) AS invoice_date,
                                        DATE_FORMAT( STR_TO_DATE( debit_note_date, '%d-%m-%Y %H:%i:%s' ), '%m' ) AS invoice_date_M,
                                        SUM(debit_note_total_price) AS 'net_price'
                                FROM `tb_debit_note` 
                                WHERE 
                                tb_debit_note.employee_id = '$employee_id' 
                                AND DATE_FORMAT(STR_TO_DATE(debit_note_date,'%d-%m-%Y %H:%i:%s'), '%Y') = '$year'
                                AND debit_note_close = 0
                                GROUP BY invoice_date
                                ORDER BY invoice_date DESC 
                            ) 
                ) as tb 
                GROUP BY invoice_date
                ORDER BY invoice_date
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
    function getSumAllTotalPriceGroupByEmployeeID($employee_id='',$year = '2019'){ 
        $sql = "  SELECT 
                         SUM(net_price) as 'net_price' 
                    FROM (
                                (
                                SELECT 
                                        DATE_FORMAT( STR_TO_DATE( invoice_customer_date, '%d-%m-%Y %H:%i:%s' ), '%Y-%m' ) AS invoice_date,
                                        DATE_FORMAT( STR_TO_DATE( invoice_customer_date, '%d-%m-%Y %H:%i:%s' ), '%m' ) AS invoice_date_M,
                                        SUM(invoice_customer_total_price) AS 'net_price'
                                FROM `tb_invoice_customer` 
                                WHERE 
                                tb_invoice_customer.employee_id = '$employee_id' 
                                AND DATE_FORMAT(STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s'), '%Y') = '$year'
                                AND invoice_customer_close = 0
                                AND invoice_customer_begin = 0 
                                GROUP BY invoice_date
                                ORDER BY invoice_date DESC 
                                ) UNION ALL
                                (
                                    SELECT 
                                            DATE_FORMAT( STR_TO_DATE( credit_note_date, '%d-%m-%Y %H:%i:%s' ), '%Y-%m' ) AS invoice_date,
                                            DATE_FORMAT( STR_TO_DATE( credit_note_date, '%d-%m-%Y %H:%i:%s' ), '%m' ) AS invoice_date_M,
                                            -SUM(credit_note_total_price) AS 'net_price'
                                    FROM `tb_credit_note` 
                                    WHERE 
                                    tb_credit_note.employee_id = '$employee_id' 
                                    AND DATE_FORMAT(STR_TO_DATE(credit_note_date,'%d-%m-%Y %H:%i:%s'), '%Y') = '$year'
                                    AND credit_note_close = 0
                                    GROUP BY invoice_date
                                    ORDER BY invoice_date DESC 
                                ) UNION ALL
                                (
                                    SELECT 
                                            DATE_FORMAT( STR_TO_DATE( debit_note_date, '%d-%m-%Y %H:%i:%s' ), '%Y-%m' ) AS invoice_date,
                                            DATE_FORMAT( STR_TO_DATE( debit_note_date, '%d-%m-%Y %H:%i:%s' ), '%m' ) AS invoice_date_M,
                                            SUM(debit_note_total_price) AS 'net_price'
                                    FROM `tb_debit_note` 
                                    WHERE 
                                    tb_debit_note.employee_id = '$employee_id' 
                                    AND DATE_FORMAT(STR_TO_DATE(debit_note_date,'%d-%m-%Y %H:%i:%s'), '%Y') = '$year'
                                    AND debit_note_close = 0
                                    GROUP BY invoice_date
                                    ORDER BY invoice_date DESC 
                                ) 
                    ) as tb  
                    ORDER BY invoice_date
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

    ///---------------------------------------- App Mobile-----------------------------------------///
    
    function getNetPriceGroupByAllSalesApp(){
        $sql = "SELECT tb_user.user_id,CONCAT(tb_user.user_name,'  ',tb_user.user_lastname) AS sales_name , 
        DATE_FORMAT(STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s'), '%Y-%m') AS invoice_date ,
        SUM(invoice_customer_total_price) AS 'net_price' 
        FROM `tb_invoice_customer` 
        LEFT JOIN tb_customer ON tb_invoice_customer.customer_id = tb_customer.customer_id 
        LEFT JOIN tb_user ON tb_invoice_customer.employee_id = tb_user.user_id 
        WHERE DATE_FORMAT(STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s'), '%Y') = '2018'
        GROUP BY tb_user.user_id  
        ORDER BY SUM(invoice_customer_total_price) DESC 
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



}
?>