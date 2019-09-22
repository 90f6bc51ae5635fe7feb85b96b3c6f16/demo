<?php
ini_set('max_execution_time', 300); //300 seconds = 5 minutes
require_once("BaseModel.php");
class StockReportModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db,"utf8");
    }

    function getStockReportListBy($stock_group_id = '', $keyword = '',$status_qty = ''){
        
            if($status_qty == 1){

                $str_qty =" AND  stock_report_qty  > 0 " ;

            }elseif ($status_qty == 2) {

                $str_qty =" AND  stock_report_qty  < 0 " ;

           
            }elseif ($status_qty == 3) {

                $str_qty =" AND  stock_report_qty  = 0 " ;

            }else{

                $str_qty = "";

            }
              
        
        if($stock_group_id != "" && $stock_group_id != "ALL"){
            $str_stock = " AND tb_stock_report.stock_group_id = '$stock_group_id' ";
        }else if ($stock_group_id == "ALL"){
            $str_stock = "  ";
        }
        $sql = "    SELECT * 
                    FROM tb_product 
                    LEFT JOIN tb_stock_report ON tb_product.product_id = tb_stock_report.product_id 
                    LEFT JOIN tb_stock_group ON tb_stock_report.stock_group_id = tb_stock_group.stock_group_id  
                    LEFT JOIN tb_product_type ON tb_product.product_type = tb_product_type.product_type_id  
                    WHERE ( product_name LIKE ('%$keyword%') OR product_description LIKE ('%$keyword%') OR CONCAT(product_code_first,product_code) LIKE ('%$keyword%') )
                    $str_stock 
                    $str_qty
                    GROUP BY  tb_product.product_id, tb_stock_report.stock_group_id 
                    ORDER BY   stock_report_qty DESC,tb_stock_report.stock_group_id ,tb_product.product_id
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

    function getStockReportListMobileBy($stock_group_id = '', $keyword = ''){

        if(($stock_group_id == "" || $stock_group_id == "ALL" ) && $keyword == ''){
            $data =[];
            return $data;
        }else{ 
            if($stock_group_id != "" && $stock_group_id != "ALL"){
                $str_stock = " AND tb_stock_report.stock_group_id = '$stock_group_id' ";
            }else if ($stock_group_id == "ALL" || $stock_group_id == "" ){
                $str_stock = "  ";
            }
            if($keyword != "" ){
                $str_keyword = " AND ( product_name LIKE ('%$keyword%') OR product_description LIKE ('%$keyword%') OR CONCAT(product_code_first,product_code) LIKE ('%$keyword%') ) ";
            }else {
                $str_keyword = "  ";
            }
            $sql = "    SELECT tb_product.product_id ,
                            tb_stock_report.stock_group_id,
                            product_code,
                            product_name,
                            IFNULL( stock_report_qty , 0) as stock_report_qty , 
                            product_price_7 as stock_report_cost_avg , 
                            
                            product_price_7 * IFNULL(  stock_report_qty, 0) AS  stock_report_avg_total , 
                            IFNULL(  stock_group_name, 'ไม่เคยซื้อขาย') AS  stock_group_name
                        FROM tb_product 
                        LEFT JOIN tb_stock_report ON tb_product.product_id = tb_stock_report.product_id 
                        LEFT JOIN tb_stock_group ON tb_stock_report.stock_group_id = tb_stock_group.stock_group_id  
                        LEFT JOIN tb_product_type ON tb_product.product_type = tb_product_type.product_type_id  
                        WHERE 1 
                        $str_keyword
                        $str_stock 
                        GROUP BY  tb_product.product_id, tb_stock_report.stock_group_id 
                        ORDER BY   stock_report_qty DESC,tb_stock_report.stock_group_id ,tb_product.product_id
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

    
    function getStockReportProductByID($product_id){

        if($product_id != ""){
            $str_product = " tb_stock_report.product_id = '$product_id' ";
        }
        $status_qty = 1;
        if($status_qty == 1){

            $str_qty =" AND  stock_report_qty  > 0 " ;

        }elseif ($status_qty == 2) {

            $str_qty =" AND  stock_report_qty  < 0 " ;

        }else{

            $str_qty = "";

        } 

        $sql = " SELECT tb_stock_report.product_id ,tb_stock_group.stock_group_id ,product_type_name,product_group_name,product_unit_name,stock_report_qty,stock_group_name
        FROM tb_stock_report 
        LEFT JOIN tb_product ON tb_product.product_id = tb_stock_report.product_id 
        LEFT JOIN tb_stock_group ON tb_stock_report.stock_group_id = tb_stock_group.stock_group_id 
        LEFT JOIN tb_product_type ON tb_product.product_type = tb_product_type.product_type_id 
        LEFT JOIN tb_product_group ON tb_product.product_group = tb_product_group.product_group_id 
        LEFT JOIN tb_product_unit ON tb_product.product_unit = tb_product_unit.product_unit_id 
        LEFT JOIN tb_product_supplier ON tb_product_supplier.product_id = tb_stock_report.product_id
        LEFT JOIN tb_product_customer ON tb_product_customer.product_id = tb_stock_report.product_id
        LEFT JOIN tb_supplier ON tb_supplier.supplier_id = tb_product_supplier.supplier_id
        LEFT JOIN tb_product_customer_price ON tb_stock_report.product_id = tb_product_customer_price.product_id
        LEFT JOIN tb_customer ON tb_customer.customer_id = tb_product_customer_price.customer_id
        LEFT JOIN tb_currency ON tb_supplier.currency_id = tb_currency.currency_id      

        WHERE  
        $str_product
        $str_qty
        GROUP BY tb_stock_report.stock_group_id            
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

    function getStockReportByStockIDAndProductID($stock_group_id,$product_id){
        $sql = " SELECT * FROM tb_stock_group WHERE stock_group_id = '$stock_group_id'  "; 
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data_stock;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data_stock = $row;
            }
            $result->close();

            $table_name = $data_stock['table_name'];

            $sql = "SELECT * ,
            IFNULL((
                SELECT stock_date 
                FROM $table_name 
                WHERE invoice_supplier_list_id != '0'
                AND product_id = '$product_id' 
                ORDER BY stock_id DESC 
                LIMIT 0,1 
            ),'-') AS stock_date_buy ,
            IFNULL((
                SELECT stock_date 
                FROM $table_name 
                WHERE invoice_customer_list_id != '0' 
                AND product_id = '$product_id' 
                ORDER BY stock_id DESC 
                LIMIT 0,1 
            ),'-') AS stock_date_sale ,
            IFNULL((
                SELECT stock_date 
                FROM $table_name 
                WHERE stock_move_list_id != '0' 
                AND product_id = '$product_id' 
                ORDER BY stock_id DESC 
                LIMIT 0,1 
            ),'-') AS stock_date_move 
            FROM $table_name 
            WHERE  product_id = '$product_id' ORDER BY stock_id DESC LIMIT 0,1 "; 
            if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                $data;
                while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                    $data = $row;
                }
                $result->close();

            } 
        }

        return $data;
    } 

    function getStockReportProductPaperByID($product_id){

        
        if($product_id != "" ){
            $str_sup = " tb_invoice_supplier_list.product_id = '$product_id' ";
            $str_mov = " tb_stock_move_list.product_id = '$product_id' ";
            $str_cus =  " AND tb_invoice_customer_list.product_id = '$product_id' ";
        }

        $sql = "SELECT
                    product_id ,
                    tb_invoice_supplier.invoice_supplier_id AS paper_id,
                    tb_invoice_supplier.invoice_supplier_code_gen AS paper_code,
                    '1' AS paper_type,
                    'รับสินค้าเข้า' AS paper_type_name,
                    `invoice_supplier_date_recieve` AS paper_date,
                    -- tb_invoice_supplier_list.invoice_supplier_list_qty AS paper_qty,
                    SUM(tb_invoice_supplier_list.invoice_supplier_list_qty) AS paper_qty,
                    tb_stock_group.stock_group_name,
                    tb_stock_group.stock_group_id
                FROM `tb_invoice_supplier` 
                LEFT JOIN tb_invoice_supplier_list ON tb_invoice_supplier.invoice_supplier_id = tb_invoice_supplier_list.invoice_supplier_id 
                LEFT JOIN tb_stock_group ON tb_stock_group.stock_group_id = tb_invoice_supplier_list.stock_group_id
                WHERE   $str_sup
                group by tb_invoice_supplier.invoice_supplier_id


                UNION  SELECT
                        product_id ,
                        tb_stock_move.stock_move_id AS paper_id,
                        stock_move_code AS paper_code,
                        '2' AS paper_type,
                        'โอนคลังสินค้า' AS paper_type_name,
                        `stock_move_date` AS paper_date,
                        SUM(stock_move_list_qty) AS paper_qty,
                        tb_stock_group.stock_group_name,
                        tb_stock_group.stock_group_id
                    FROM `tb_stock_move` 
                   LEFT JOIN tb_stock_move_list ON tb_stock_move.stock_move_id = tb_stock_move_list.stock_move_id 
                   LEFT JOIN tb_stock_group ON tb_stock_group.stock_group_id = tb_stock_move.stock_group_id_in
                    WHERE   $str_mov
                    group by tb_stock_move.stock_move_id
                UNION SELECT
                        product_id ,
                        tb_invoice_customer.invoice_customer_id AS paper_id,
                        invoice_customer_code AS paper_code,
                        '3' AS paper_type,
                        'ขายสินค้า' AS paper_type_name,
                        `invoice_customer_date` AS paper_date,
                        SUM(invoice_customer_list_qty) AS paper_qty,
                        tb_stock_group.stock_group_name,
                        tb_stock_group.stock_group_id
                    FROM `tb_invoice_customer` 
                    LEFT JOIN tb_invoice_customer_list ON tb_invoice_customer.invoice_customer_id = tb_invoice_customer_list.invoice_customer_id 
                    LEFT JOIN tb_stock_group ON tb_stock_group.stock_group_id = tb_invoice_customer_list.stock_group_id
                    WHERE   invoice_customer_close = 0 
                    $str_cus 
                    group by tb_invoice_customer.invoice_customer_id
                ORDER BY STR_TO_DATE(paper_date,'%d-%m-%Y %H:%i:%s') DESC, paper_type
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

    function getStockReportBalanceBy($product_id,$table_name,$stock_date){ 
         
        $sql = "SELECT balance_qty,balance_stock_cost_avg,balance_stock_cost_avg_total 
        FROM $table_name
        WHERE stock_id = (SELECT MAX(stock_id)  
        FROM $table_name
        WHERE product_id = '$product_id' 
        AND STR_TO_DATE(stock_date,'%d-%m-%Y %H:%i:%s') < STR_TO_DATE('$stock_date','%d-%m-%Y %H:%i:%s') )"; 
        // echo $sql;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }
    }

    function updateCostWhenInsert($stock_group_id, $product_id, $qty, $cost){
        $stock_qty = 0;
        $stock_cost = 0.0;
        
        $new_qty = 0;
        $new_cost = 0.0;
        
        
        $str = "SELECT stock_report_qty , stock_report_cost_avg  
        FROM tb_stock_report
        WHERE stock_group_id = '$stock_group_id' 
        AND product_id = '$product_id' ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            
            if(count($data) > 0){


                $new_qty = $data['stock_report_qty'] + $qty;
                $new_cost = (($data['stock_report_qty'] * $data['stock_report_cost_avg']) + ($qty * $cost))/$new_qty;
                 
    
                $str = "UPDATE tb_stock_report SET
                stock_report_qty = '$new_qty' , 
                stock_report_cost_avg = '$new_cost' 
                WHERE stock_group_id = '$stock_group_id' 
                AND product_id = '$product_id' ";
    
                if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                    return true;
                }else {
                    return false;
                }
            }else{
                return false;
            }
        } 
    }

    function updateCostWhenUpdate($stock_group_id, $product_id, $qty_old, $cost_old, $qty_new, $cost_new  ){
        $stock_qty = 0;
        $stock_cost = 0.0;
        
        $new_qty = 0;
        $new_cost = 0.0;
        
        
        $str = "SELECT stock_report_qty , stock_report_cost_avg  
        FROM tb_stock_report
        WHERE stock_group_id = '$stock_group_id' 
        AND product_id = '$product_id' ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            
            if(count($data) > 0){


                $new_qty_out = $data['stock_report_qty'] - $qty_old;
                $new_cost = (($data['stock_report_qty'] * $data['stock_report_cost_avg']) - ($qty_old * $cost_old))/$new_qty_out;
                 
                $new_qty = $new_qty_out + $qty_new;
                $new_cost = (($new_qty_out * $new_cost) + ($qty_new * $cost_new))/$new_qty;
    
                $str = "UPDATE tb_stock_report SET
                stock_report_qty = '$new_qty' , 
                stock_report_cost_avg = '$new_cost' 
                WHERE stock_group_id = '$stock_group_id' 
                AND product_id = '$product_id' ";
    
                if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                    return true;
                }else {
                    return false;
                }
            }else{
                return false;
            }
        }    
    }

    function updateCostWhenDelete($stock_group_id, $product_id, $qty, $cost){
        $stock_qty = 0;
        $stock_cost = 0.0;
        
        $new_qty = 0;
        $new_cost = 0.0;
        
        
        $str = "SELECT stock_report_qty , stock_report_cost_avg  
        FROM tb_stock_report
        WHERE stock_group_id = '$stock_group_id' 
        AND product_id = '$product_id' ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            
            if(count($data) > 0){
                $new_qty = $data['stock_report_qty'] - $qty;
                $new_cost = (($data['stock_report_qty'] * $data['stock_report_cost_avg']) - ($qty * $cost))/$new_qty;
                 
    
                $str = "UPDATE tb_stock_report SET
                stock_report_qty = '$new_qty' , 
                stock_report_cost_avg = '$new_cost' 
                WHERE stock_group_id = '$stock_group_id' 
                AND product_id = '$product_id' ";
    
                if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                    return true;
                }else {
                    return false;
                }
            }else{
                return false;
            }
        }
    }

    function insertStockReportRow($stock_group_id, $product_id){

        $str = "SELECT *  
        FROM tb_stock_report
        WHERE stock_group_id = '$stock_group_id' 
        AND product_id = '$product_id' ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {

            

            if(mysqli_num_rows($result) == 0){
                $sql = " INSERT INTO tb_stock_report (
                    stock_group_id,
                    product_id
                ) 
                VALUES ('$stock_group_id','$product_id'); 
                ";
        
                //echo $sql;
                if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                    return mysqli_insert_id(static::$db);
                }else {
                    return 0;
                }
            }else{
                $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
                return $row['stock_report_id'];
            }

        }

    }
    //#####################################################################################################################
    //
    //
    //------------------------------------------ รายงานจุดสั่งซื้อ --------------------------------------------
    //
    //
    //#####################################################################################################################
    function getStockReportMinPointBy($product_start = "",$product_end = "",$product_type = "",$supplier_id = "",$product_qty = ""){
     
        $str_product = "";   
        $str_product_type = "";   
        $str_supplier_id = "";   
        $str_product_qty = "";   

        if($product_start != "" && $product_end != ""){
            $str_product = " AND tb_product.product_code >= '$product_start' AND tb_product.product_code <=  '$product_end' "; 
        }else if ($product_start != "" && $product_end == ""){
            $str_product = " AND CONCAT(tb_product.product_code_first,tb_product.product_code) LIKE ('%$product_start%') ";  
        } 

        if($product_type != ""){
            $str_product_type = " AND tb_product.product_type = '$product_type' "; 
        }  

        if($supplier_id != ""){
            $str_supplier_id = " AND tb_product_supplier.supplier_id = '$supplier_id' "; 
        }  
 

        if($product_qty != ""){
            if($product_qty == "low"){
                $str_product_qty = " HAVING stock_report_qty < minimum_stock "; 
            }else if($product_qty == "normal"){
                $str_product_qty = " HAVING stock_report_qty >= minimum_stock AND stock_report_qty <= safety_stock"; 
            }else if($product_qty == "high"){
                $str_product_qty = " HAVING stock_report_qty > safety_stock "; 
            }    
        }

        $sql =" SELECT tb_product.product_id,
        CONCAT(product_code_first,product_code) AS product_code, 
                product_name , 
                GROUP_CONCAT(DISTINCT supplier_name_en SEPARATOR ', ') AS supplier_name_en ,
                GROUP_CONCAT(DISTINCT customer_name_en SEPARATOR ', ') AS customer_name_en ,  
                SUM(minimum_stock) AS minimum_stock,
                SUM(safety_stock) AS safety_stock,
                SUM(maximum_stock) AS maximum_stock, 
                IFNULL(SUM(stock_report_qty),0) AS stock_report_qty ,
                (
                    (
                        SELECT SUM(safety_stock) 
                        FROM tb_product_customer 
                        WHERE product_id = tb_product.product_id 
                        AND product_status = 'Active'
                    ) -  IFNULL(
                        SUM(stock_report_qty)
                        ,0)
                ) AS product_buy  

                FROM tb_product 
                LEFT JOIN tb_stock_report ON tb_product.product_id = tb_stock_report.product_id   
                LEFT JOIN tb_product_customer ON tb_product.product_id = tb_product_customer.product_id   
                LEFT JOIN tb_customer ON tb_product_customer.customer_id = tb_customer.customer_id 
                LEFT JOIN tb_product_supplier ON tb_product.product_id = tb_product_supplier.product_id   
                LEFT JOIN tb_supplier ON tb_product_supplier.supplier_id = tb_supplier.supplier_id 
                WHERE tb_product_customer.product_id IS NOT NULL  
                AND tb_product.product_status = 'Active'  
                $str_product
                $str_product_type
                $str_supplier_id 
                GROUP BY tb_product.product_id , product_code , product_name , supplier_name_en  
                $str_product_qty 
                ORDER BY tb_product.product_code ASC    
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
    //#####################################################################################################################
    //
    //
    //------------------------------------------ รายงานรายละเอียดสินค้า --------------------------------------------
    //
    //
    //#####################################################################################################################
    function getStockReportProductDescriptionBy($product_start = "",$product_end = ""){
     
        $str_product = "";   

        if($product_start != "" && $product_end != ""){
            $str_product = " AND product_code >= '$product_start' AND product_code <=  '$product_end' "; 
        }else if ($product_start != "" && $product_end == ""){
            $str_product = " AND CONCAT(product_code_first,product_code) LIKE ('%$product_start%') ";  
        } 


        $sql =" SELECT product_code_first, product_code, product_name, product_barcode, product_description, product_category_name, product_group_name, product_type_name, product_unit_name,
                (SELECT account_name_th FROM tb_account WHERE account_id = tb_product.buy_account_id) AS buy_account_name ,
                (SELECT account_name_th FROM tb_account WHERE account_id = tb_product.sale_account_id) AS sale_account_name 
                FROM tb_product 
                LEFT JOIN tb_product_category ON tb_product.product_category_id = tb_product_category.product_category_id 
                LEFT JOIN tb_product_group ON tb_product.product_group = tb_product_group.product_group_id    
                LEFT JOIN tb_product_type ON tb_product.product_type = tb_product_type.product_type_id    
                LEFT JOIN tb_product_unit ON tb_product.product_unit = tb_product_unit.product_unit_id    
                LEFT JOIN tb_account ON tb_product.buy_account_id = tb_account.account_id   
                WHERE 1 
                $str_product
                ORDER BY product_code ASC    
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

   //#####################################################################################################################
    //
    //
    //------------------------------------------ รายงานสินค้าและวัตถุดิบ --------------------------------------------
    //
    //
    //#####################################################################################################################
    function getStockReportBy($stock_start = "",$stock_end = "",$product_start = "",$product_end = ""){
      
        $str_stock = "";  
        $str_product = "";  

        if($stock_start != "" && $stock_end != ""){
            $str_stock = " AND CAST(stock_group_code AS UNSIGNED) >= '$stock_start' AND CAST(stock_group_code AS UNSIGNED) <=  '$stock_end' "; 
        }else if ($stock_start != "" && $stock_end == ""){
            $str_stock = " AND stock_group_code = '$stock_start' ";  
        } 

        if($product_start != "" && $product_end != ""){
            $str_product = " AND product_code >= '$product_start' AND product_code <=  '$product_end' "; 
        }else if ($product_start != "" && $product_end == ""){
            $str_product = " AND CONCAT(product_code_first,product_code) LIKE ('%$product_start%') ";  
        } 


        $sql =" SELECT stock_group_name ,CONCAT(product_code_first,product_code) as product_code,product_name ,stock_report_qty,stock_report_cost_avg, (stock_report_qty*stock_report_cost_avg) AS  stock_report_total 
                FROM tb_product 
                LEFT JOIN tb_stock_report ON tb_product.product_id = tb_stock_report.product_id 
                LEFT JOIN tb_stock_group ON tb_stock_report.stock_group_id = tb_stock_group.stock_group_id 
                WHERE tb_product.product_id IS NOT NULL 
                $str_stock
                $str_product
                ORDER BY stock_group_name,product_code ASC
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
   //#####################################################################################################################
    //
    //
    //------------------------------------------ รายงานสินค้าคงเหลือ --------------------------------------------
    //
    //
    //#####################################################################################################################
    function getStockReportBalanceListBy($date_end = "", $stock_group_id = "" ,$product_start = "",$product_end = "" , $status_qty = "", $keyword=""){
       
        $str_product = "";  
        $str_date = "";
        $str_qty = "";  
        $str_keyword ="";
        $selectSun = "" ;
        $str_stock = ""; 
        

       // $sql = "SELECT * FROM tb_stock_group WHERE stock_group_id = '".$stock_group_id."' ";
       

        if($stock_group_id == "0" ){
             $str_stock = ""; 
        }else if ($stock_group_id != "0" && $stock_group_id!= "ALL"){ 
            $str_stock = " AND stock_group_id = '$stock_group_id' ";
              
        }
        
          if($keyword!= ""){
            $str_keyword ="AND ( tb_product.product_name LIKE ('%$keyword%') OR tb_product.product_code LIKE ('%$keyword%') )";
        }
            
            $sql ="SELECT * 
            FROM tb_stock_group 
            WHERE 1  
            $str_stock
            ";


            if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                 $data = [];
                 while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                    $data[] = $row;
                 }
                 $result->close(); 
            }
            $sql = '' ;

            // echo "<pre>";
            // print_r($data);
            // echo "</pre>";
       
        for($i = 0 ;$i<count($data)&&count($data)>0;$i++){

            if($status_qty == 1){

                $str_qty =" AND  stock_report_qty  > 0 " ;

            }elseif ($status_qty == 2) {

                $str_qty =" AND  stock_report_qty  < 0 " ;

            }else{

                $str_qty = "";

            }
          

            
    
            if($product_start != "" && $product_end != ""){
                $str_product = " AND product_code >= '$product_start' AND product_code <=  '$product_end' "; 
            }else if ($product_start != "" && $product_end == ""){
                $str_product = " AND CONCAT(product_code_first,product_code) LIKE ('%$product_start%') ";  
            } 

            if($i == 0){
                $sql .="SELECT *
                        FROM 
                ( 
                ";
            }

             $sql .="(  SELECT 
                            stock_group_code ,
                            stock_group_name ,
                            CONCAT(product_code_first,product_code) as product_code,
                            product_name ,
                            product_status, 
                            tb_product.product_id ,
                            IFNULL(
                                ( 
                                    SELECT balance_qty 
                                    FROM ".$data[$i]['table_name']." 
                                    WHERE product_id = tb1.product_id
                                    AND STR_TO_DATE(stock_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') 
                                    ORDER BY stock_id DESC 
                                    LIMIT 0,1 
                                ) 
                            , 0) as stock_report_qty , 
                            IFNULL(
                                ( 
                                    SELECT balance_stock_cost_avg 
                                    FROM ".$data[$i]['table_name']." 
                                    WHERE product_id = tb1.product_id
                                    AND STR_TO_DATE(stock_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') 
                                    ORDER BY stock_id DESC 
                                    LIMIT 0,1 
                                ) 
                            , 0 ) as stock_report_cost_avg , 
                            IFNULL(
                                ( 
                                    SELECT balance_stock_cost_avg_total 
                                    FROM ".$data[$i]['table_name']." 
                                    WHERE product_id = tb1.product_id
                                    AND STR_TO_DATE(stock_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') 
                                    ORDER BY stock_id DESC 
                                    LIMIT 0,1 
                            ) 
                            , 0 ) AS  stock_report_avg_total 
                        FROM tb_stock_report as tb1 
                        LEFT JOIN tb_stock_group ON tb1.stock_group_id = tb_stock_group.stock_group_id 
                        LEFT JOIN tb_product ON tb1.product_id = tb_product.product_id 
                        WHERE tb1.stock_group_id = ".$data[$i]['stock_group_id']." 
                        AND tb_product.product_id IS NOT NULL  
                        $str_product  
                        $str_qty
                        $str_keyword
                        GROUP BY tb1.stock_group_id , tb1.product_id 
                        HAVING stock_report_qty != 0 
                        ORDER BY stock_group_name,product_code ASC )
            "; 

                if(($i+1)<count($data)){
                    $sql .=" UNION";
                }
            }
            $sql .="  
            )
            AS tb_stock
            GROUP BY stock_group_code, product_code
            "; 

            //echo "<pre>".$sql."</pre>";
            // echo $sql;
            if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                $data = [];
                while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                    $data[] = $row;
                }
                $result->close();
                return $data;
            }  
            //    echo "<pre>";
            // print_r($data);
            // echo "</pre>";

    } 
    function getStockReportBalanceListByMobile($date_end = "", $stock_group_id = "" ,$keyword = ""  , $status_qty = ""){
       
        $str_product = "";  
        $str_date = "";
        $str_qty = "";  
        // $status_qty = 1;
        

       // $sql = "SELECT * FROM tb_stock_group WHERE stock_group_id = '".$stock_group_id."' ";
        $selectSun = "" ;

        if($stock_group_id != "" && $stock_group_id != "ALL"){
            $str_stock = " AND tb_stock_report.stock_group_id = '$stock_group_id' ";
        }else if ($stock_group_id == "ALL"){
            $str_stock = "  ";
        }

            $sql ="SELECT * 
            FROM tb_stock_group 
            WHERE 1 
            $str_stock
            ";


            if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                 $data = [];
                 while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                    $data[] = $row;
                 }
                 $result->close(); 
            }
            $sql = '' ;

        for($i = 0 ;$i<count($data)&&count($data)>0;$i++){

            if($status_qty == 1){

                $str_qty =" AND  stock_report_qty  > 0 " ;

            }elseif ($status_qty == 2) {

                $str_qty =" AND  stock_report_qty  < 0 " ;

            }else{

                $str_qty = "";

            } 
    
            // if($product_start != "" && $product_end != ""){
            //     $str_product = " AND product_code >= '$product_start' AND product_code <=  '$product_end' "; 
            // }else if ($product_start != "" && $product_end == ""){
            //     $str_product = " AND CONCAT(product_code_first,product_code) LIKE ('%$product_start%') ";  
            // } 
            // if($product_keyword != ""){
            //     $str_product = " AND CONCAT(product_code_first,product_code) LIKE ('%$product_keywordt%') ";
            // }
            // $stock_report_qty_having ="HAVING stock_report_qty != 0";
            $stock_report_qty_having ="";
            if($keyword != "" ){
                $str_product = " AND ( product_name LIKE ('%$keyword%') OR product_description LIKE ('%$keyword%') OR CONCAT(product_code_first,product_code) LIKE ('%$keyword%') ) ";
            }else {
                $str_product = "  ";
            }
            if($i == 0){
                $sql .="SELECT *
                        FROM 
                ( 
                ";
            }

             $sql .="(  SELECT 
                            stock_group_code ,
                            stock_group_name ,tb_product.product_id ,
                            CONCAT(product_code_first,product_code) as product_code,
                            product_name , 
                            IFNULL(
                                ( 
                                    SELECT balance_qty 
                                    FROM ".$data[$i]['table_name']." 
                                    WHERE product_id = tb1.product_id
                                    AND STR_TO_DATE(stock_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') 
                                    ORDER BY stock_id DESC 
                                    LIMIT 0,1 
                                ) 
                            , 0) as stock_report_qty , 
                            product_price_7 as stock_report_cost_avg , 
                            product_price_7 * IFNULL(
                                ( 
                                    SELECT balance_qty 
                                    FROM ".$data[$i]['table_name']." 
                                    WHERE product_id = tb1.product_id
                                    AND STR_TO_DATE(stock_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') 
                                    ORDER BY stock_id DESC 
                                    LIMIT 0,1 
                                ) 
                            , 0) AS  stock_report_avg_total 
                        FROM tb_stock_report as tb1 
                        LEFT JOIN tb_stock_group ON tb1.stock_group_id = tb_stock_group.stock_group_id 
                        LEFT JOIN tb_product ON tb1.product_id = tb_product.product_id 
                        WHERE tb1.stock_group_id = ".$data[$i]['stock_group_id']." 
                        AND tb_product.product_id IS NOT NULL  
                        $str_product  
                        $str_qty
                        GROUP BY tb1.product_id ,tb1.stock_group_id  
                        $stock_report_qty_having 
                        ORDER BY stock_group_name,product_code ASC )
            "; 

                if(($i+1)<count($data)){
                    $sql .=" UNION";
                }
            }
            $sql .="  
            )
            AS tb_stock
            GROUP BY  product_code, stock_group_code 
            ORDER BY stock_report_qty DESC
            "; 

            //echo "<pre>".$sql."</pre>";
            //echo $sql;
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

   //#####################################################################################################################
    //
    //
    //------------------------------------------ รายงานรายการประจำวันสินค้า --------------------------------------------
    //
    //
    //#####################################################################################################################
    function getStockReportProductMovementDayBy($date_target = "",$stock_start = "",$stock_end = "",$product_start = "",$product_end = "",$table_name = "",$group_by = "",$paper_code = ""){

        $str_stock = "";   
        $str_product = "";   
        $str_table_name = "";   
        $str_group_by = "";   
        $str_paper = "";   

        if($stock_start != "" && $stock_end != ""){
            $str_stock = " AND CAST(stock_group_code AS UNSIGNED) >= '$stock_start' AND CAST(stock_group_code AS UNSIGNED) <=  '$stock_end' "; 
        }else if ($stock_start != "" && $stock_end == ""){
            $str_stock = " AND stock_group_code = '$stock_start' ";  
        } 

        if($product_start != "" && $product_end != ""){
            $str_product = " AND product_code >= '$product_start' AND product_code <=  '$product_end' "; 
        }else if ($product_start != "" && $product_end == ""){
            $str_product = " AND CONCAT(product_code_first,product_code) LIKE ('%$product_start%') ";  
        } 

        if($paper_code != ""){  
            $str_paper = " AND paper_code LIKE ('%$paper_code%')  ";  
        }
        
        if($group_by != ""){
            $str_group_by = " GROUP BY $group_by "; 
        }
        
        

        $sql ="SELECT table_name 
                    FROM tb_stock_group 
                    WHERE 1 
                    $str_stock
                    ";


        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close(); 
        }

      
        
        $sql = '';
        for($i = 0 ;$i<count($data)&&count($data)>0;$i++){

            $str_date_target = "";
            $str_table_name = "";
            $str_paper_code = "";

            if($date_target != ""){
                $str_date_target = " AND STR_TO_DATE(".$data[$i]['table_name'].".stock_date,'%d-%m-%Y %H:%i:%s') = STR_TO_DATE('$date_target','%d-%m-%Y %H:%i:%s') "; 
            }

            if($table_name != ""){
                if($table_name =="invoice_supplier"){
                    $str_paper_code = " 
                    ".$table_name."_code_gen AS paper_code 
                    ";
                }else{
                    $str_paper_code = " 
                    ".$table_name."_code AS paper_code 
                    ";
                }
                
                $str_table_name = " 
                INNER JOIN tb_".$table_name."_list ON ".$data[$i]['table_name'].".".$table_name."_list_id = tb_".$table_name."_list.".$table_name."_list_id 
                INNER JOIN tb_".$table_name." ON tb_".$table_name."_list.".$table_name."_id = tb_".$table_name.".".$table_name."_id   
                "; 
            }else{
                $str_paper_code = " 
                IFNULL(
                        invoice_supplier_code_gen,
                    IFNULL(
                            invoice_customer_code,
                        IFNULL(
                                delivery_note_supplier_code,
                            IFNULL(
                                    delivery_note_customer_code,
                                IFNULL(
                                        stock_move_code,
                                    IFNULL(
                                            stock_product_set_out.stock_product_set_code,
                                        IFNULL(
                                                stock_product_set_in.stock_product_set_code,
                                            IFNULL(
                                                    stock_issue_code,
                                                IFNULL(
                                                        credit_note_code,
                                                    IFNULL(
                                                            credit_note_supplier_code,
                                                        IFNULL(
                                                                regrind_supplier_code,
                                                            IFNULL(
                                                                    stock_change_product_code,'initial' 
                                                            )
                                                        )
                                                    )
                                                )
                                            )
                                        )
                                    )
                                )
                            )
                        )
                    )
                ) AS paper_code ";
                $str_table_name = " 
                LEFT JOIN tb_delivery_note_supplier_list ON ".$data[$i]['table_name'].".delivery_note_supplier_list_id = tb_delivery_note_supplier_list.delivery_note_supplier_list_id 
                LEFT JOIN tb_delivery_note_supplier ON tb_delivery_note_supplier_list.delivery_note_supplier_id = tb_delivery_note_supplier.delivery_note_supplier_id  
                
                LEFT JOIN tb_delivery_note_customer_list ON ".$data[$i]['table_name'].".delivery_note_customer_list_id = tb_delivery_note_customer_list.delivery_note_customer_list_id 
                LEFT JOIN tb_delivery_note_customer ON tb_delivery_note_customer_list.delivery_note_customer_id = tb_delivery_note_customer.delivery_note_customer_id 
                
                LEFT JOIN tb_invoice_supplier_list ON ".$data[$i]['table_name'].".invoice_supplier_list_id = tb_invoice_supplier_list.invoice_supplier_list_id 
                LEFT JOIN tb_invoice_supplier ON tb_invoice_supplier_list.invoice_supplier_id = tb_invoice_supplier.invoice_supplier_id 
                
                LEFT JOIN tb_invoice_customer_list ON ".$data[$i]['table_name'].".invoice_customer_list_id = tb_invoice_customer_list.invoice_customer_list_id 
                LEFT JOIN tb_invoice_customer ON tb_invoice_customer_list.invoice_customer_id = tb_invoice_customer.invoice_customer_id 
                
                LEFT JOIN tb_stock_move_list ON ".$data[$i]['table_name'].".stock_move_list_id = tb_stock_move_list.stock_move_list_id 
                LEFT JOIN tb_stock_move ON tb_stock_move_list.stock_move_id = tb_stock_move.stock_move_id 

                LEFT JOIN tb_stock_product_set_list ON ".$data[$i]['table_name'].".stock_product_set_list_id = tb_stock_product_set_list.stock_product_set_list_id 
                LEFT JOIN tb_stock_product_set as stock_product_set_out ON tb_stock_product_set_list.stock_product_set_id = stock_product_set_out.stock_product_set_id 
                
                LEFT JOIN tb_stock_product_set as stock_product_set_in ON ".$data[$i]['table_name'].".stock_product_set_id = stock_product_set_in.stock_product_set_id 
  
                LEFT JOIN tb_stock_issue_list ON ".$data[$i]['table_name'].".stock_issue_list_id = tb_stock_issue_list.stock_issue_list_id 
                LEFT JOIN tb_stock_issue ON tb_stock_issue_list.stock_issue_id = tb_stock_issue.stock_issue_id 
                
                LEFT JOIN tb_credit_note_list ON ".$data[$i]['table_name'].".credit_note_list_id = tb_credit_note_list.credit_note_list_id 
                LEFT JOIN tb_credit_note ON tb_credit_note_list.credit_note_id = tb_credit_note.credit_note_id 

                LEFT JOIN tb_credit_note_supplier_list ON ".$data[$i]['table_name'].".credit_note_supplier_list_id = tb_credit_note_supplier_list.credit_note_supplier_list_id 
                LEFT JOIN tb_credit_note_supplier ON tb_credit_note_supplier_list.credit_note_supplier_id = tb_credit_note_supplier.credit_note_supplier_id 
                
                LEFT JOIN tb_regrind_supplier_list ON ".$data[$i]['table_name'].".regrind_supplier_list_id = tb_regrind_supplier_list.regrind_supplier_list_id 
                LEFT JOIN tb_regrind_supplier ON tb_regrind_supplier_list.regrind_supplier_id = tb_regrind_supplier.regrind_supplier_id 
                
                LEFT JOIN tb_stock_change_product_list ON ".$data[$i]['table_name'].".stock_change_product_list_id = tb_stock_change_product_list.stock_change_product_list_id 
                LEFT JOIN tb_stock_change_product ON tb_stock_change_product_list.stock_change_product_id = tb_stock_change_product.stock_change_product_id  
                "; 
            }

            if($i == 0){
                $sql .=" SELECT * FROM 
                ( 
                ";
            }
            $str_col ="";
            if($group_by == "product_code"){
                $str_col = "  
                CONCAT(product_code_first,product_code) as product_code ,
                product_name ,  
                $str_paper_code
                "; 
                $str_order_by = "ORDER BY product_code ASC ";
            }else if($group_by == "stock_group_code"){
                $str_col = " 
                (SELECT stock_group_name FROM tb_stock_group WHERE table_name = '".$data[$i]['table_name']."') AS stock_group_name ,
                (SELECT stock_group_code FROM tb_stock_group WHERE table_name = '".$data[$i]['table_name']."') AS stock_group_code ,
                $str_paper_code
                "; 
                $str_order_by = "ORDER BY stock_group_code ASC ";
            }else{
                $str_col = " 
                concat('".$data[$i]['table_name']."_',stock_id) AS from_stock ,
                ".$data[$i]['table_name'].".product_id ,
                CONCAT(product_code_first,product_code) as product_code ,
                product_name ,
                '".$data[$i]['table_name']."' AS table_name ,
                (SELECT stock_group_name FROM tb_stock_group WHERE table_name = '".$data[$i]['table_name']."') AS stock_group_name ,
                (SELECT stock_group_code FROM tb_stock_group WHERE table_name = '".$data[$i]['table_name']."') AS stock_group_code ,
                stock_type ,
                stock_date ,
                in_qty ,
                in_stock_cost_avg,
                in_stock_cost_avg_total,
                out_qty,
                out_stock_cost_avg,
                out_stock_cost_avg_total,
                balance_qty,
                balance_stock_cost_avg,
                balance_stock_cost_avg_total,
                $str_paper_code
                "; 
                $str_order_by = "ORDER BY  product_code,stock_group_code,STR_TO_DATE(stock_date,'%d-%m-%Y %H:%i:%s'),from_stock ASC ";
            }

            $sql .="(SELECT 
            $str_col
            FROM ".$data[$i]['table_name']." 
            LEFT JOIN tb_product ON ".$data[$i]['table_name'].".product_id = tb_product.product_id   
            $str_table_name
            WHERE 1 
            $str_date
            $str_date_target
            $str_product
            ) 
            ";
            if(($i+1)<count($data)){
                $sql .=" union";
            } 
        }  
        
        $sql .="  
        )
        AS tb_stock 
        WHERE 1 
        $str_paper
        $str_group_by
        $str_order_by
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


   //#####################################################################################################################
    //
    //
    //------------------------------------------ รายงานสินค้าที่ไม่เคลื่อนไหว --------------------------------------------
    //
    //
    //#####################################################################################################################
    function getStockReportProductNoMovementBy($order_by_stock,$date_start = "",$date_end = "",$stock_start = "",$stock_end = "",$product_start = "",$product_end = ""){

        $str_stock = "";   
        $str_product = "";   
        $str_order_by = "";   
        $str_group_by = " GROUP BY product_code";   

        if($stock_start != "" && $stock_end != ""){
            $str_stock = " AND CAST(stock_group_code AS UNSIGNED) >= '$stock_start' AND CAST(stock_group_code AS UNSIGNED) <=  '$stock_end' "; 
        }else if ($stock_start != "" && $stock_end == ""){
            $str_stock = " AND stock_group_code = '$stock_start' ";  
        } 

        if($product_start != "" && $product_end != ""){
            $str_product = " AND product_code >= '$product_start' AND product_code <=  '$product_end' "; 
        }else if ($product_start != "" && $product_end == ""){
            $str_product = " AND CONCAT(product_code_first,product_code) LIKE ('%$product_start%') ";  
        } 
        if($order_by_stock == "1"){
            $str_order_by = " stock_group_code ASC, product_code ASC ,product_group_id ASC "; 
        }
        else{
            $str_order_by = " product_group_id ASC ,stock_group_code ASC, product_code ASC";
        }

        $sql ="SELECT table_name 
                    FROM tb_stock_group 
                    WHERE 1 
                    $str_stock
                    ";


        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close(); 
        }

      
        
        $sql = '';
        for($i = 0 ;$i<count($data)&&count($data)>0;$i++){

            $str_date = "";

            if($date_start != "" && $date_end != ""){
                $str_date = " AND STR_TO_DATE(".$data[$i]['table_name'].".stock_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(".$data[$i]['table_name'].".stock_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
            }else if ($date_start != ""){
                $str_date = " AND STR_TO_DATE(".$data[$i]['table_name'].".stock_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
            }else if ($date_end != ""){
                $str_date = " AND STR_TO_DATE(".$data[$i]['table_name'].".stock_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
            }

            if($i == 0){
                $sql .=" SELECT product_code,product_name,stock_group_name,stock_group_code,product_group_id,product_group_name FROM 
                ( 
                ";
            }
            $sql .="(SELECT concat('".$data[$i]['table_name']."_',stock_id) AS from_stock ,tb_product_group.product_group_id,tb_product_group.product_group_name,
            ".$data[$i]['table_name'].".product_id ,
            CONCAT(product_code_first,product_code) as product_code ,product_name ,
            '".$data[$i]['table_name']."' AS table_name ,
            (SELECT stock_group_name FROM tb_stock_group WHERE table_name = '".$data[$i]['table_name']."') AS stock_group_name ,
            (SELECT stock_group_code FROM tb_stock_group WHERE table_name = '".$data[$i]['table_name']."') AS stock_group_code 
            FROM ".$data[$i]['table_name']." 
            LEFT JOIN tb_product ON ".$data[$i]['table_name'].".product_id = tb_product.product_id  
            LEFT JOIN tb_product_group ON tb_product.product_group = tb_product_group.product_group_id 
            WHERE ".$data[$i]['table_name'].".product_id NOT IN (
                SELECT  ".$data[$i]['table_name'].".product_id 
                FROM ".$data[$i]['table_name']." 
                LEFT JOIN tb_product ON ".$data[$i]['table_name'].".product_id = tb_product.product_id  
                WHERE 1 
                $str_date
                
                ) $str_product
                $str_group_by
            ORDER BY ".$data[$i]['table_name'].".product_id ,STR_TO_DATE(".$data[$i]['table_name'].".stock_date,'%d-%m-%Y %H:%i:%s'),from_stock  ASC ) 
            ";
            if(($i+1)<count($data)){
                $sql .=" union";
            }
            
        }  
        
        $sql .="  
        )
        AS tb_stock 
        ORDER BY $str_order_by  
        "; 

        /********************   */

        /** */
        // echo "<pre>";
        // print_r($sql) ;
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

    
   //#####################################################################################################################
    //
    //
    //------------------------------------------ รายงานความเคลื่อนไหวสินค้า --------------------------------------------
    //
    //
    //#####################################################################################################################
    function getStockReportProductMovementBy($date_start = "",$date_end = "",$stock_start = "",$stock_end = "",$product_start = "",$product_end = ""){

        $str_stock = "";   
        $str_product = "";   

        if($stock_start != "" && $stock_end != ""){
            $str_stock = " AND CAST(stock_group_code AS SIGNED) >= '$stock_start' AND CAST(stock_group_code AS SIGNED) <=  '$stock_end' "; 
        }else if ($stock_start != "" && $stock_end == ""){
            $str_stock = " AND stock_group_code = '$stock_start' ";  
        } 

        if($product_start != "" && $product_end != ""){
            $str_product = " AND product_code >= '$product_start' AND product_code <=  '$product_end' "; 
        }else if ($product_start != "" && $product_end == ""){
            $str_product = " AND CONCAT(product_code_first,product_code) LIKE ('%$product_start%') ";  
        }else if ($product_start == "" && $product_end != ""){
            $str_product = " AND CONCAT(product_code_first,product_code) LIKE ('%$product_end%') ";  
        } 

        $sql ="SELECT table_name 
                    FROM tb_stock_group 
                    WHERE 1 
                    $str_stock
                    ";


        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close(); 
        }

        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";

      
        
        $sql = '';
        for($i = 0 ;$i<count($data);$i++){

            $str_date = "";

            if($date_start != "" && $date_end != ""){
                $str_date = " AND STR_TO_DATE(".$data[$i]['table_name'].".stock_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(".$data[$i]['table_name'].".stock_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
            }else if ($date_start != ""){
                $str_date = " AND STR_TO_DATE(".$data[$i]['table_name'].".stock_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
            }else if ($date_end != ""){
                $str_date = " AND STR_TO_DATE(".$data[$i]['table_name'].".stock_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
            }

            if($i == 0){
                $sql .=" SELECT * FROM 
                ( 
                ";
            }
            $sql .="(SELECT concat('".$data[$i]['table_name']."_',stock_id) AS from_stock ,
            ".$data[$i]['table_name'].".product_id ,CONCAT(product_code_first,product_code) as product_code ,product_name ,
            '".$data[$i]['table_name']."' AS table_name ,
            (SELECT stock_group_name FROM tb_stock_group WHERE table_name = '".$data[$i]['table_name']."') AS stock_group_name ,
            (SELECT stock_group_code FROM tb_stock_group WHERE table_name = '".$data[$i]['table_name']."') AS stock_group_code ,
            stock_type ,
            stock_date ,
            in_qty ,
            in_stock_cost_avg,
            in_stock_cost_avg_total,
            out_qty,
            out_stock_cost_avg,
            out_stock_cost_avg_total,
            balance_qty,
            balance_stock_cost_avg,
            balance_stock_cost_avg_total,
            IFNULL(
                invoice_supplier_code_gen,
            IFNULL(
                    invoice_customer_code,
                IFNULL(
                        delivery_note_supplier_code,
                    IFNULL(
                            delivery_note_customer_code,
                        IFNULL(
                                stock_move_code,
                            IFNULL(
                                    stock_product_set_out.stock_product_set_code,
                                IFNULL(
                                        stock_product_set_in.stock_product_set_code,
                                    IFNULL(
                                            stock_issue_code,
                                        IFNULL(
                                                credit_note_code,
                                            IFNULL(
                                                    credit_note_supplier_code,
                                                IFNULL(
                                                        regrind_supplier_code,
                                                    IFNULL(
                                                            stock_change_product_code,'initial' 
                                                    )
                                                )
                                            )
                                        )
                                    )
                                )
                            )
                        )
                    )
                )
            )
        ) AS paper_code
            FROM ".$data[$i]['table_name']." 
            LEFT JOIN tb_product ON ".$data[$i]['table_name'].".product_id = tb_product.product_id  
            
            LEFT JOIN tb_delivery_note_supplier_list ON ".$data[$i]['table_name'].".delivery_note_supplier_list_id = tb_delivery_note_supplier_list.delivery_note_supplier_list_id 
            LEFT JOIN tb_delivery_note_supplier ON tb_delivery_note_supplier_list.delivery_note_supplier_id = tb_delivery_note_supplier.delivery_note_supplier_id  
            
            LEFT JOIN tb_delivery_note_customer_list ON ".$data[$i]['table_name'].".delivery_note_customer_list_id = tb_delivery_note_customer_list.delivery_note_customer_list_id 
            LEFT JOIN tb_delivery_note_customer ON tb_delivery_note_customer_list.delivery_note_customer_id = tb_delivery_note_customer.delivery_note_customer_id 
            
            LEFT JOIN tb_invoice_supplier_list ON ".$data[$i]['table_name'].".invoice_supplier_list_id = tb_invoice_supplier_list.invoice_supplier_list_id 
            LEFT JOIN tb_invoice_supplier ON tb_invoice_supplier_list.invoice_supplier_id = tb_invoice_supplier.invoice_supplier_id 
            
            LEFT JOIN tb_invoice_customer_list ON ".$data[$i]['table_name'].".invoice_customer_list_id = tb_invoice_customer_list.invoice_customer_list_id 
            LEFT JOIN tb_invoice_customer ON tb_invoice_customer_list.invoice_customer_id = tb_invoice_customer.invoice_customer_id 
            
            LEFT JOIN tb_stock_move_list ON ".$data[$i]['table_name'].".stock_move_list_id = tb_stock_move_list.stock_move_list_id 
            LEFT JOIN tb_stock_move ON tb_stock_move_list.stock_move_id = tb_stock_move.stock_move_id 
            
            LEFT JOIN tb_stock_product_set_list ON ".$data[$i]['table_name'].".stock_product_set_list_id = tb_stock_product_set_list.stock_product_set_list_id 
            LEFT JOIN tb_stock_product_set as stock_product_set_out ON tb_stock_product_set_list.stock_product_set_id = stock_product_set_out.stock_product_set_id 
            
            LEFT JOIN tb_stock_product_set as stock_product_set_in ON ".$data[$i]['table_name'].".stock_product_set_id = stock_product_set_in.stock_product_set_id 

            LEFT JOIN tb_stock_issue_list ON ".$data[$i]['table_name'].".stock_issue_list_id = tb_stock_issue_list.stock_issue_list_id 
            LEFT JOIN tb_stock_issue ON tb_stock_issue_list.stock_issue_id = tb_stock_issue.stock_issue_id 
            
            LEFT JOIN tb_credit_note_list ON ".$data[$i]['table_name'].".credit_note_list_id = tb_credit_note_list.credit_note_list_id 
            LEFT JOIN tb_credit_note ON tb_credit_note_list.credit_note_id = tb_credit_note.credit_note_id 

            LEFT JOIN tb_credit_note_supplier_list ON ".$data[$i]['table_name'].".credit_note_supplier_list_id = tb_credit_note_supplier_list.credit_note_supplier_list_id 
            LEFT JOIN tb_credit_note_supplier ON tb_credit_note_supplier_list.credit_note_supplier_id = tb_credit_note_supplier.credit_note_supplier_id 
            
            LEFT JOIN tb_regrind_supplier_list ON ".$data[$i]['table_name'].".regrind_supplier_list_id = tb_regrind_supplier_list.regrind_supplier_list_id 
            LEFT JOIN tb_regrind_supplier ON tb_regrind_supplier_list.regrind_supplier_id = tb_regrind_supplier.regrind_supplier_id 
            
            LEFT JOIN tb_stock_change_product_list ON ".$data[$i]['table_name'].".stock_change_product_list_id = tb_stock_change_product_list.stock_change_product_list_id 
            LEFT JOIN tb_stock_change_product ON tb_stock_change_product_list.stock_change_product_id = tb_stock_change_product.stock_change_product_id  
            WHERE 1 
            $str_date
            $str_product
            ORDER BY ".$data[$i]['table_name'].".product_id ,STR_TO_DATE(".$data[$i]['table_name'].".stock_date,'%d-%m-%Y %H:%i:%s'),from_stock  ASC ) 
            ";
            if(($i+1)<count($data)){
                $sql .=" union";
            }
            
        }  
        
        $sql .="  
        )
        AS tb_stock
        ORDER BY  product_code,stock_group_code,STR_TO_DATE(stock_date,'%d-%m-%Y %H:%i:%s'),from_stock ASC
        "; 
        //echo "<pre>".$sql."</pre>";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    } 

    function getStockReportProductBy($product_category_id = "", $product_type_id = "",$product_start = "",$product_end = ""){
          
        
        if($product_type_id != ""){
            $str_product_type = "AND tb_product.product_type = '$product_type_id' ";
        } 


        if($product_category_id != ""){
            $str_product_category = "AND tb_product.product_category_id = '$product_category_id' ";
        }  


        if($product_start != "" && $product_end != ""){

            $str_product = " AND tb_product.product_code  >= '$product_start' AND tb_product.product_code <=  '$product_end' "; 
        }else if ($product_start != "" && $product_end == ""){

            $str_product = " AND CONCAT(tb_product.product_code_first,tb_product.product_code) LIKE ('%$product_start%') ";   
        } 

        
        $sql = " SELECT tb_product.product_id, CONCAT(product_code_first,product_code) as product_code, product_name, product_type, 
        product_price_1, product_price_2, product_price_3, product_price_4, product_price_5, product_price_6, product_price_7 
        FROM tb_product 
        LEFT JOIN tb_product_category ON tb_product.product_category_id = tb_product_category.product_category_id 
        LEFT JOIN tb_product_type ON tb_product.product_type = tb_product_type.product_type_id  
        WHERE 1 
        $str_product_type
        $str_product_category 
        $str_product  
        GROUP BY tb_product.product_id
        ORDER BY product_code  
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



    //#####################################################################################################################
    //
    //
    //------------------------------------------ รายงานสินค้าเคลื่อนไหวที่มีปัญหา --------------------------------------------
    //
    //
    //#####################################################################################################################



    function getStockReportProblematicProductBy($stock_group_id = "" , $keyword= ""){
        $str_product = "";

        if($stock_group_id == "0"){
            $str_stock = ""; 

        }else if ($stock_group_id != "0"){
            $str_stock = " AND stock_group_id = '$stock_group_id' ";  
        } 

        if($keyword == ""){
            $str_product = ""; 
        }else if ($keyword != "" ){
            $str_product = " AND product_code LIKE ('%$keyword%') ";  
        } 

        $sql ="SELECT table_name 
                    FROM tb_stock_group 
                    WHERE 1 
                    $str_stock
                    ";


        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close(); 
        }

      
        
        $sql = '';
        for($i = 0 ;$i<count($data)&&count($data)>0;$i++){

            
      

            if($i == 0){
                $sql .=" SELECT * FROM 
                ( 
                ";
            }
            $sql .="(SELECT concat('".$data[$i]['table_name']."_',stock_id) AS from_stock ,
            ".$data[$i]['table_name'].".product_id ,CONCAT(product_code_first,product_code) as product_code ,product_name ,
            '".$data[$i]['table_name']."' AS table_name ,
            (SELECT stock_group_name FROM tb_stock_group WHERE table_name = '".$data[$i]['table_name']."') AS stock_group_name ,
            (SELECT stock_group_code FROM tb_stock_group WHERE table_name = '".$data[$i]['table_name']."') AS stock_group_code ,

            stock_type ,
            stock_date ,
            in_qty ,
            in_stock_cost_avg,
            in_stock_cost_avg_total,
            out_qty,
            out_stock_cost_avg,
            out_stock_cost_avg_total,
            balance_qty,
            balance_stock_cost_avg,
            balance_stock_cost_avg_total
           
           
            FROM ".$data[$i]['table_name']." 
            LEFT JOIN tb_product ON ".$data[$i]['table_name'].".product_id = tb_product.product_id 
            
            
            
            LEFT JOIN tb_delivery_note_supplier_list ON ".$data[$i]['table_name'].".delivery_note_supplier_list_id = tb_delivery_note_supplier_list.delivery_note_supplier_list_id 
            LEFT JOIN tb_delivery_note_supplier ON tb_delivery_note_supplier_list.delivery_note_supplier_id = tb_delivery_note_supplier.delivery_note_supplier_id  
            
            LEFT JOIN tb_delivery_note_customer_list ON ".$data[$i]['table_name'].".delivery_note_customer_list_id = tb_delivery_note_customer_list.delivery_note_customer_list_id 
            LEFT JOIN tb_delivery_note_customer ON tb_delivery_note_customer_list.delivery_note_customer_id = tb_delivery_note_customer.delivery_note_customer_id 
            
            LEFT JOIN tb_invoice_supplier_list ON ".$data[$i]['table_name'].".invoice_supplier_list_id = tb_invoice_supplier_list.invoice_supplier_list_id 
            LEFT JOIN tb_invoice_supplier ON tb_invoice_supplier_list.invoice_supplier_id = tb_invoice_supplier.invoice_supplier_id 
            
            LEFT JOIN tb_invoice_customer_list ON ".$data[$i]['table_name'].".invoice_customer_list_id = tb_invoice_customer_list.invoice_customer_list_id 
            LEFT JOIN tb_invoice_customer ON tb_invoice_customer_list.invoice_customer_id = tb_invoice_customer.invoice_customer_id 
            
            LEFT JOIN tb_stock_move_list ON ".$data[$i]['table_name'].".stock_move_list_id = tb_stock_move_list.stock_move_list_id 
            LEFT JOIN tb_stock_move ON tb_stock_move_list.stock_move_id = tb_stock_move.stock_move_id 
            
            LEFT JOIN tb_stock_issue_list ON ".$data[$i]['table_name'].".stock_issue_list_id = tb_stock_issue_list.stock_issue_list_id 
            LEFT JOIN tb_stock_issue ON tb_stock_issue_list.stock_issue_id = tb_stock_issue.stock_issue_id 
            
            LEFT JOIN tb_credit_note_list ON ".$data[$i]['table_name'].".credit_note_list_id = tb_credit_note_list.credit_note_list_id 
            LEFT JOIN tb_credit_note ON tb_credit_note_list.credit_note_id = tb_credit_note.credit_note_id 

            LEFT JOIN tb_credit_note_supplier_list ON ".$data[$i]['table_name'].".credit_note_supplier_list_id = tb_credit_note_supplier_list.credit_note_supplier_list_id 
            LEFT JOIN tb_credit_note_supplier ON tb_credit_note_supplier_list.credit_note_supplier_id = tb_credit_note_supplier.credit_note_supplier_id 
            
            LEFT JOIN tb_regrind_supplier_list ON ".$data[$i]['table_name'].".regrind_supplier_list_id = tb_regrind_supplier_list.regrind_supplier_list_id 
            LEFT JOIN tb_regrind_supplier ON tb_regrind_supplier_list.regrind_supplier_id = tb_regrind_supplier.regrind_supplier_id 
            
            
            WHERE 1 
            AND balance_qty < 0
            $str_product
            ORDER BY ".$data[$i]['table_name'].".product_id ,STR_TO_DATE(".$data[$i]['table_name'].".stock_date,'%d-%m-%Y %H:%i:%s'),from_stock ASC ) 
            ";

            if(($i+1)<count($data)){
                $sql .=" union ";
            }
            
        }  
        
        $sql .="  
        )
        AS tb_stock
        ORDER BY stock_group_name ASC , product_code,stock_group_code,STR_TO_DATE(stock_date,'%d-%m-%Y %H:%i:%s'), from_stock ASC 
        "; 
       //echo "<pre>".$sql."</pre>";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }




    }

     //#####################################################################################################################
    //
    //
    //------------------------------------------------------- มูลค่าตามคลังสินค้า --------------------------------------------
    //
    //
    //#####################################################################################################################
    function getStockCostBy($date_end = ""){
       
        $str_product = "";  
        $str_date = "";
        $str_qty = "";  
       
        

       // $sql = "SELECT * FROM tb_stock_group WHERE stock_group_id = '".$stock_group_id."' ";
    

            $sql ="SELECT * 
            FROM tb_stock_group 
            WHERE 1 
            
            ";


            if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                 $data = [];
                 while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                    $data[] = $row;
                 }
                 $result->close(); 
            }
            $sql = '' ;

        for($i = 0 ;$i<count($data)&&count($data)>0;$i++){

           
            // echo "<pre>";
            // print_r($data);
            // echo "</pre>";

            
    
            if($product_start != "" && $product_end != ""){
                $str_product = " AND product_code >= '$product_start' AND product_code <=  '$product_end' "; 
            }else if ($product_start != "" && $product_end == ""){
                $str_product = " AND CONCAT(product_code_first,product_code) LIKE ('%$product_start%') ";  
            } 

            if($i == 0){
                $sql .="SELECT SUM(stock_report_qty) AS stock_report_qty,
                               SUM(stock_report_avg_total) As stock_report_avg_total,
                                product_code ,  
                                product_name ,
                                stock_group_name,
                                stock_group_code
                        FROM 
                ( 
                ";
            }

             $sql .="( SELECT stock_group_name ,
             CONCAT(product_code_first,product_code) as product_code,
             product_name , 
             stock_group_code ,
             IF(balance_qty IS NULL , stock_report_qty ,balance_qty) as stock_report_qty , 
             IF(balance_stock_cost_avg IS NULL , 0, balance_stock_cost_avg ) as stock_report_cost_avg , 
             IF(balance_stock_cost_avg_total IS NULL , 0 ,balance_stock_cost_avg_total ) AS  stock_report_avg_total 
                FROM ( 
                    SELECT tb2.* 
                    FROM ".$data[$i]['table_name']." as tb2 
                    LEFT JOIN ".$data[$i]['table_name']." as tb3 ON (tb2.product_id = tb3.product_id AND tb2.stock_id < tb3.stock_id) 
                    WHERE  tb3.stock_id IS NULL AND STR_TO_DATE(tb2.stock_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s')
                ) as tb1
                LEFT JOIN tb_stock_report ON tb1.product_id = tb_stock_report.product_id
                LEFT JOIN tb_product ON tb_stock_report.product_id = tb_product.product_id 
                LEFT JOIN tb_stock_group ON tb_stock_report.stock_group_id = tb_stock_group.stock_group_id 
                  
                    WHERE tb_stock_report.stock_group_id = ".$data[$i]['stock_group_id']." 
                AND tb_product.product_id IS NOT NULL              
                GROUP BY tb_stock_report.stock_group_id , tb_stock_report.product_id 
                HAVING stock_report_qty != 0 
                ORDER BY stock_group_name,product_code ASC )
            "; 

                if(($i+1)<count($data)){
                    $sql .=" union";
                }
            }
            $sql .="  
            )
            AS tb_stock
            GROUP BY stock_group_code
            "; 

            // echo "<pre>".$sql."</pre>";
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



}



?>
