<?php

require_once("BaseModel.php"); 
require_once("MaintenanceStockModel.php"); 
class InvoiceSupplierModel extends BaseModel{

    private $maintenance_stock;

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db,"utf8");
        $this->maintenance_stock =  new MaintenanceStockModel;
    }

    function getInvoiceSupplierBy($date_start = "",$date_end = "",$supplier_id = "",$keyword = "",$user_id = "",$begin = '0', $lock_1 = "0", $lock_2 = "0",$supplier_domestic = "" , $sort='DESC'){

        $str_supplier = "";
        $str_date = "";
        $str_user = "";
        $str_lock = "";
        $str_domestic = "";

        if($supplier_domestic != ""){

            $str_domestic = "AND tb2.supplier_domestic = '$supplier_domestic' ";
        }

        if($lock_1 == "1" && $lock_2 == "1"){
            $str_lock = "AND (paper_lock_1 = '0' OR paper_lock_2 = '0')";
        }else if ($lock_1 == "1") {
            $str_lock = "AND paper_lock_1 = '0' ";
        }else if($lock_2 == "1"){
            $str_lock = "AND paper_lock_2 = '0' ";
        }

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(invoice_supplier_date_recieve,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(invoice_supplier_date_recieve,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(invoice_supplier_date_recieve,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(invoice_supplier_date_recieve,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
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
        
        $sql = " SELECT invoice_supplier_id, 
        invoice_supplier_code, 
        invoice_supplier_code_gen, 
        invoice_supplier_date, 
        invoice_supplier_date_recieve,  
        invoice_supplier_total_price, 
        invoice_supplier_vat_price, 
        invoice_supplier_net_price,  
        import_duty, 
        freight_in, 
        supplier_domestic, 
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        invoice_supplier_term, 
        invoice_supplier_due, 
        invoice_supplier_name, 
        invoice_supplier_close,
        IFNULL(tb2.supplier_name_en,'-') as supplier_name  
        FROM tb_invoice_supplier 
        LEFT JOIN tb_user as tb1 ON tb_invoice_supplier.employee_id = tb1.user_id 
        LEFT JOIN tb_supplier as tb2 ON tb_invoice_supplier.supplier_id = tb2.supplier_id 
        LEFT JOIN tb_paper_lock ON SUBSTRING(tb_invoice_supplier.invoice_supplier_date_recieve,3,9)=SUBSTRING(tb_paper_lock.paper_lock_date,3,9) 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%')  
            OR  invoice_supplier_code LIKE ('%$keyword%') 
            OR  invoice_supplier_code_gen LIKE ('%$keyword%') 
        )  
        $str_domestic
        AND invoice_supplier_begin = '$begin' 
        $str_lock 
        $str_supplier 
        $str_date 
        $str_user  
        ORDER BY  invoice_supplier_code_gen $sort 
        ";

        //  echo $sql;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getInvoiceSupplierByKeyword( $keyword = "" ){
  
        
        $sql = " SELECT *
        FROM tb_invoice_supplier 
        LEFT JOIN tb_user  ON tb_invoice_supplier.employee_id = tb_user.user_id 
        LEFT JOIN tb_supplier  ON tb_invoice_supplier.supplier_id = tb_supplier.supplier_id  
        WHERE  invoice_supplier_code_gen LIKE ('%$keyword%')   
        AND invoice_supplier_begin = '0' 
        ORDER BY  invoice_supplier_code_gen ASC 
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

    function getInvoiceSupplierByID($id){
        $sql = " SELECT * 
        FROM tb_invoice_supplier 
        WHERE invoice_supplier_id = '$id' 
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

    function getInvoiceSupplierByCode($invoice_supplier_code){
        $sql = " SELECT * 
        FROM tb_invoice_supplier  
        LEFT JOIN tb_supplier ON tb_invoice_supplier.supplier_id = tb_supplier.supplier_id
        WHERE invoice_supplier_code = '$invoice_supplier_code' 
        AND invoice_supplier_close = '0' 
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
    function getInvoiceSupplierByCodeGen($invoice_supplier_code){
        $sql = " SELECT * 
        FROM tb_invoice_supplier  
        LEFT JOIN tb_supplier ON tb_invoice_supplier.supplier_id = tb_supplier.supplier_id
        WHERE invoice_supplier_code_gen = '$invoice_supplier_code' 
        AND invoice_supplier_close = '0' 
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

    function getInvoiceSupplierViewByID($id){
        $sql = " SELECT *   
        FROM tb_invoice_supplier 
        LEFT JOIN tb_user ON tb_invoice_supplier.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_supplier ON tb_invoice_supplier.supplier_id = tb_supplier.supplier_id         
        LEFT JOIN tb_currency ON tb_supplier.currency_id = tb_currency.currency_id 
        WHERE invoice_supplier_id = '$id' 
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

    function getInvoiceSupplierViewListByjournalGeneralID($id){
        $sql = " SELECT *   
        FROM tb_journal_general_list 
        LEFT JOIN tb_invoice_supplier ON tb_journal_general_list.journal_invoice_supplier_id = tb_invoice_supplier.invoice_supplier_id
        LEFT JOIN tb_user ON tb_invoice_supplier.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_supplier ON tb_invoice_supplier.supplier_id = tb_supplier.supplier_id 
        WHERE journal_general_id = '$id' AND tb_journal_general_list.journal_invoice_supplier_id > 0 
        AND invoice_supplier_close = '0' 
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

    function getInvoiceSupplierViewListByjournalPaymentID($id){
        $sql = " SELECT *   
        FROM tb_journal_cash_payment_list 
        LEFT JOIN tb_invoice_supplier ON tb_journal_cash_payment_list.journal_invoice_supplier_id = tb_invoice_supplier.invoice_supplier_id
        LEFT JOIN tb_user ON tb_invoice_supplier.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_supplier ON tb_invoice_supplier.supplier_id = tb_supplier.supplier_id 
        WHERE journal_cash_payment_id = '$id' AND tb_journal_cash_payment_list.journal_invoice_supplier_id > 0 
        AND invoice_supplier_close = '0' 
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

    function getInvoiceSupplierViewListByjournalReceiptID($id){
        $sql = " SELECT *   
        FROM tb_journal_cash_receipt_list 
        LEFT JOIN tb_invoice_supplier ON tb_journal_cash_receipt_list.journal_invoice_supplier_id = tb_invoice_supplier.invoice_supplier_id
        LEFT JOIN tb_user ON tb_invoice_supplier.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_supplier ON tb_invoice_supplier.supplier_id = tb_supplier.supplier_id 
        WHERE journal_cash_receipt_id = '$id' AND tb_journal_cash_receipt_list.journal_invoice_supplier_id > 0 
        AND invoice_supplier_close = '0' 
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

    function getInvoiceSupplierViewListByjournalPurchaseID($id){
        $sql = " SELECT *   
        FROM tb_journal_purchase_list 
        LEFT JOIN tb_invoice_supplier ON tb_journal_purchase_list.journal_invoice_supplier_id = tb_invoice_supplier.invoice_supplier_id
        LEFT JOIN tb_user ON tb_invoice_supplier.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_supplier ON tb_invoice_supplier.supplier_id = tb_supplier.supplier_id 
        WHERE journal_purchase_id = '$id' AND tb_journal_purchase_list.journal_invoice_supplier_id > 0 
        AND invoice_supplier_close = '0' 
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

    function getInvoiceSupplierViewListByjournalSaleID($id){
        $sql = " SELECT *   
        FROM tb_journal_sale_list 
        LEFT JOIN tb_invoice_supplier ON tb_journal_sale_list.journal_invoice_supplier_id = tb_invoice_supplier.invoice_supplier_id
        LEFT JOIN tb_user ON tb_invoice_supplier.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_supplier ON tb_invoice_supplier.supplier_id = tb_supplier.supplier_id 
        WHERE journal_sale_id = '$id' AND tb_journal_sale_list.journal_invoice_supplier_id > 0 
        AND invoice_supplier_close = '0' 
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


    function getInvoiceSupplierViewListByjournalSaleReturnID($id){
        $sql = " SELECT *   
        FROM tb_journal_sale_return_list 
        LEFT JOIN tb_invoice_supplier ON tb_journal_sale_return_list.journal_invoice_supplier_id = tb_invoice_supplier.invoice_supplier_id
        LEFT JOIN tb_user ON tb_invoice_supplier.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_supplier ON tb_invoice_supplier.supplier_id = tb_supplier.supplier_id 
        WHERE journal_sale_return_id = '$id' AND tb_journal_sale_return_list.journal_invoice_supplier_id > 0 
        AND invoice_supplier_close = '0' 
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

   
    function updateInvoiceSupplierByID($id,$data = []){ 
        $sql = " UPDATE tb_invoice_supplier SET  
        supplier_id = '".$data['supplier_id']."', 
        employee_id = '".$data['employee_id']."', 
        invoice_supplier_code = '".static::$db->real_escape_string($data['invoice_supplier_code'])."', 
        invoice_supplier_code_gen = '".static::$db->real_escape_string($data['invoice_supplier_code_gen'])."', 
        invoice_supplier_total_price = '".$data['invoice_supplier_total_price']."', 
        invoice_supplier_currency_total = '".$data['invoice_supplier_currency_total']."', 
        invoice_supplier_cost_total = '".$data['invoice_supplier_cost_total']."', 
        invoice_supplier_total_stock_price = '".$data['invoice_supplier_total_stock_price']."', 
        invoice_supplier_currency_stock_total = '".$data['invoice_supplier_currency_stock_total']."', 
        invoice_supplier_cost_stock_total = '".$data['invoice_supplier_cost_stock_total']."',  
        invoice_supplier_vat = '".$data['invoice_supplier_vat']."', 
        invoice_supplier_vat_type = '".$data['invoice_supplier_vat_type']."', 
        invoice_supplier_vat_price = '".$data['invoice_supplier_vat_price']."', 
        invoice_supplier_net_price = '".$data['invoice_supplier_net_price']."', 
        invoice_supplier_date = '".static::$db->real_escape_string($data['invoice_supplier_date'])."', 
        invoice_supplier_date_recieve = '".static::$db->real_escape_string($data['invoice_supplier_date_recieve'])."', 
        invoice_supplier_name = '".static::$db->real_escape_string($data['invoice_supplier_name'])."', 
        invoice_supplier_address = '".static::$db->real_escape_string($data['invoice_supplier_address'])."', 
        invoice_supplier_tax = '".static::$db->real_escape_string($data['invoice_supplier_tax'])."', 
        invoice_supplier_branch = '".static::$db->real_escape_string($data['invoice_supplier_branch'])."', 
        invoice_supplier_term = '".static::$db->real_escape_string($data['invoice_supplier_term'])."', 
        invoice_supplier_due = '".static::$db->real_escape_string($data['invoice_supplier_due'])."',  
        invoice_supplier_due_day = '".static::$db->real_escape_string($data['invoice_supplier_due_day'])."',  
        invoice_supplier_begin = '".$data['invoice_supplier_begin']."', 
        import_duty = '".$data['import_duty']."', 
        freight_in = '".$data['freight_in']."', 
        vat_section = '".static::$db->real_escape_string($data['vat_section'])."', 
        vat_section_add = '".static::$db->real_escape_string($data['vat_section_add'])."', 
        invoice_supplier_total_price_non = '".$data['invoice_supplier_total_price_non']."', 
        invoice_supplier_vat_price_non = '".$data['invoice_supplier_vat_price_non']."', 
        invoice_supplier_total_non = '".$data['invoice_supplier_total_non']."', 
        invoice_supplier_description = '".static::$db->real_escape_string($data['invoice_supplier_description'])."', 
        invoice_supplier_remark = '".static::$db->real_escape_string($data['invoice_supplier_remark'])."', 
        invoice_supplier_stock = '".static::$db->real_escape_string($data['invoice_supplier_stock'])."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW()  
        WHERE invoice_supplier_id = '$id' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }


    
    function updateInvoiceSupplierCostByID($id,$data = []){


        $sql = " UPDATE tb_invoice_supplier SET   
        invoice_supplier_total_stock_price = '".$data['invoice_supplier_total_stock_price']."', 
        invoice_supplier_currency_stock_total = '".$data['invoice_supplier_currency_stock_total']."', 
        invoice_supplier_cost_stock_total = '".$data['invoice_supplier_cost_stock_total']."',  
        import_duty = '".$data['import_duty']."', 
        freight_in = '".$data['freight_in']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE invoice_supplier_id = '$id' 
        ";

        // echo "<pre>";
        // print_r($sql);
        // echo "</pre>";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function getPurchaseOrder($type = "ภายในประเทศ",$keyword = ""){

        $sql = "    SELECT tb_purchase_order.purchase_order_id , purchase_order_code, tb_purchase_order.supplier_id, supplier_name_en, supplier_name_th 
                    FROM tb_purchase_order 
                    LEFT JOIN tb_supplier ON tb_purchase_order.supplier_id = tb_supplier.supplier_id
                    LEFT JOIN tb_purchase_order_list ON tb_purchase_order.purchase_order_id = tb_purchase_order_list.purchase_order_id
                    WHERE purchase_order_list_id IN ( 
                        SELECT tb_purchase_order_list.purchase_order_list_id 
                        FROM tb_purchase_order_list  
                        LEFT JOIN tb_invoice_supplier_list ON  tb_purchase_order_list.purchase_order_list_id = tb_invoice_supplier_list.purchase_order_list_id 
                        LEFT JOIN tb_invoice_supplier ON  tb_invoice_supplier_list.invoice_supplier_id = tb_invoice_supplier.invoice_supplier_id 
                        WHERE (invoice_supplier_close = '0' OR invoice_supplier_close IS NULL)
                        GROUP BY tb_purchase_order_list.purchase_order_list_id 
                        HAVING IFNULL(SUM(invoice_supplier_list_qty),0) < AVG(purchase_order_list_qty)  
                    ) 
                    AND purchase_order_status = 'Confirm' 
                    AND supplier_domestic = '$type' 
                    AND purchase_order_code LIKE('%$keyword%') 
                    GROUP BY tb_purchase_order.purchase_order_id 
                    ORDER BY STR_TO_DATE(purchase_order_date,'%d-%m-%Y %H:%i:%s') DESC
                
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


    function checkPurchaseOrder($purchase_order_id = ""){

        $sql = "    SELECT COUNT(*) AS recieve_status
                    FROM tb_purchase_order 
                    LEFT JOIN tb_supplier ON tb_purchase_order.supplier_id = tb_supplier.supplier_id
                    LEFT JOIN tb_purchase_order_list ON tb_purchase_order.purchase_order_id = tb_purchase_order_list.purchase_order_id
                    WHERE purchase_order_list_id IN ( 
                        SELECT tb_purchase_order_list.purchase_order_list_id 
                        FROM tb_purchase_order_list  
                        LEFT JOIN tb_invoice_supplier_list ON  tb_purchase_order_list.purchase_order_list_id = tb_invoice_supplier_list.purchase_order_list_id 
                        LEFT JOIN tb_invoice_supplier ON  tb_invoice_supplier_list.invoice_supplier_id = tb_invoice_supplier.invoice_supplier_id 
                        WHERE (invoice_supplier_close = '0' OR invoice_supplier_close IS NULL )
                        GROUP BY tb_purchase_order_list.purchase_order_list_id 
                        HAVING IFNULL(SUM(invoice_supplier_list_qty),0) < AVG(purchase_order_list_qty)  
                    ) 
                    AND tb_purchase_order.purchase_order_id = '$purchase_order_id' 
                    GROUP BY tb_purchase_order.purchase_order_id 
                
        ";

        //echo $sql;
        $data = [];
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            
        }
        return $row['recieve_status'];
    }


    function getPurchaseOrderByCode($type = "ภายในประเทศ",$keyword = ""){

        $sql = "    SELECT tb_purchase_order.purchase_order_id , purchase_order_code, tb_purchase_order.supplier_id, supplier_name_en, supplier_name_th 
                    FROM tb_purchase_order 
                    LEFT JOIN tb_supplier ON tb_purchase_order.supplier_id = tb_supplier.supplier_id
                    LEFT JOIN tb_purchase_order_list ON tb_purchase_order.purchase_order_id = tb_purchase_order_list.purchase_order_id
                    WHERE purchase_order_list_id IN ( 
                        SELECT tb_purchase_order_list.purchase_order_list_id 
                        FROM tb_purchase_order_list  
                        LEFT JOIN tb_invoice_supplier_list ON  tb_purchase_order_list.purchase_order_list_id = tb_invoice_supplier_list.purchase_order_list_id 
                        LEFT JOIN tb_invoice_supplier ON  tb_invoice_supplier_list.invoice_supplier_id = tb_invoice_supplier.invoice_supplier_id 
                        WHERE invoice_supplier_close = '0' 
                        GROUP BY tb_purchase_order_list.purchase_order_list_id 
                        HAVING IFNULL(SUM(invoice_supplier_list_qty),0) < AVG(purchase_order_list_qty)  
                    ) 
                    AND purchase_order_status = 'Confirm' 
                    AND supplier_domestic = '$type' 
                    AND purchase_order_code = '$keyword' 
                    GROUP BY tb_purchase_order.purchase_order_id 
                
        ";

        //echo $sql;
        $data ;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            
        }
        return $data;
    }
    function getInvoiceSupplierByAutoComplete($keyword){
        $sql = " SELECT invoice_supplier_code_gen,invoice_supplier_date_recieve 
        FROM tb_invoice_supplier  
        WHERE invoice_supplier_begin = '0' 
        AND invoice_supplier_close = '0'  
        AND invoice_supplier_code_gen LIKE('%$keyword%')  
    
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
    function getSupplierOrder($type = "ภายในประเทศ"){

        $sql = "SELECT tb_supplier.supplier_id, supplier_name_en , supplier_name_th 
                FROM tb_supplier 
                WHERE supplier_id IN ( 
                    SELECT DISTINCT supplier_id 
                    FROM tb_purchase_order 
                    LEFT JOIN tb_purchase_order_list ON tb_purchase_order.purchase_order_id = tb_purchase_order_list.purchase_order_id
                    WHERE purchase_order_list_id IN ( 
                        SELECT tb_purchase_order_list.purchase_order_list_id 
                        FROM tb_purchase_order_list  
                        LEFT JOIN tb_invoice_supplier_list ON  tb_purchase_order_list.purchase_order_list_id = tb_invoice_supplier_list.purchase_order_list_id 
                        LEFT JOIN tb_invoice_supplier ON  tb_invoice_supplier_list.invoice_supplier_id = tb_invoice_supplier.invoice_supplier_id 
                        WHERE (invoice_supplier_close = '0' || invoice_supplier_close IS NULL) 
                        GROUP BY tb_purchase_order_list.purchase_order_list_id 
                        HAVING IFNULL(SUM(invoice_supplier_list_qty),0) < AVG(purchase_order_list_qty)  
                    ) 
                    AND purchase_order_status = 'Confirm'
                ) 
                AND supplier_domestic = '$type' 
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


    function getInvoiceSupplierLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(invoice_supplier_code_gen,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  invoice_supplier_lastcode 
        FROM tb_invoice_supplier
        WHERE invoice_supplier_code_gen LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['invoice_supplier_lastcode'];
        }

    }

    function generateInvoiceSupplierListBySupplierId(
        $supplier_id, 
        $data = [],
        $data_qty = [],
        $search = "", 
        $purchase_order_id="",
        $invoice_supplier_id=""
        ){
       
        $data_buf = [];
        $str_invoice_supplier_id ="";
        if($invoice_supplier_id != ''){
            $str_invoice_supplier_id =" AND tb_invoice_supplier_list.invoice_supplier_id != '$invoice_supplier_id' ";
        }
        for($i=0; $i < count($data) ;$i++){
            $j = 0;
            for(;$j<count($data_buf);$j++){

                if($data[$i]==$data_buf[$j]['id']){
                    $data_buf[$j]['qty'] +=$data_qty[$i];
                    // break;
                }
            }
            if($j==count($data_buf)){
                $data_buf[]=array(
                    'id'=>$data[$i],
                    'qty'=>$data_qty[$i]
                );
            }
        }
        $str ='0';
        if(is_array($data)){ 
            for($i=0; $i < count($data) ;$i++){
                $purchase_order_list_id =  $data_buf[$i]['id'];
                $invoice_supplier_list_qty =  $data_buf[$i]['qty'];
                if($invoice_supplier_list_qty == ''){
                    $invoice_supplier_list_qty = 0;
                }


                $sql = " SELECT tb_purchase_order_list.purchase_order_list_id , 
                            MAX(purchase_order_list_qty) AS 'MAX_qty',
                            IFNULL(
                                (
                                    SELECT SUM(invoice_supplier_list_qty) 
                                    FROM tb_invoice_supplier_list 
                                    LEFT JOIN tb_invoice_supplier ON  tb_invoice_supplier_list.invoice_supplier_id = tb_invoice_supplier.invoice_supplier_id 
                                    WHERE  purchase_order_list_id = '$purchase_order_list_id' 
                                    AND invoice_supplier_close = '0' 
                                    $str_invoice_supplier_id
                                )
                            ,0)+$invoice_supplier_list_qty  AS 'use_qty'
                        FROM tb_purchase_order_list  
                        WHERE tb_purchase_order_list.purchase_order_list_id = '$purchase_order_list_id'";

                //  echo $sql."\r\n\n";

                $data_sub; 
                if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                    
                    while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                        $data_sub = $row;
                    }
                    $result->close();
                    
                }

                if($data_sub['MAX_qty']<=$data_sub['use_qty']){
                    if($str != '0'){
                        $str .= $data[$i];
                        $str .= ',';
                    }       
                    else{
                        $str = $data[$i];
                        $str .= ',';
                    }             
                    

                }else{
                    $data_buf[$i]['qty'] = $data_sub['MAX_qty']-$data_sub['use_qty'];
                }
            }
            //
            if($str !=''){
                $str = rtrim($str,',');
            }else{
                $str = '0';
            }
        }else if ($data != ''){
            $str = $data;
        }else{
            $str='0';
        }
        $str_po = "";

        if($purchase_order_id != ""){
            $str_po = "AND tb_purchase_order.purchase_order_id = '$purchase_order_id' ";
        }

        $sql_customer = "SELECT tb2.product_id, 
        tb2.purchase_order_list_id, 
        '' as regrind_supplier_list_id,
        CONCAT(product_code_first,product_code) as product_code, 
        stock_group_id,
        product_name,  
        IFNULL(purchase_order_list_qty 
        - IFNULL((
            SELECT SUM(invoice_supplier_list_qty) 
            FROM tb_invoice_supplier_list 
            LEFT JOIN tb_invoice_supplier ON  tb_invoice_supplier_list.invoice_supplier_id = tb_invoice_supplier.invoice_supplier_id 
            WHERE purchase_order_list_id = tb2.purchase_order_list_id 
            AND invoice_supplier_close = '0' 
        ),0) ,0) as invoice_supplier_list_qty, 
        purchase_order_list_price,
        purchase_order_list_price_sum,
        purchase_order_list_price as invoice_supplier_list_price, 
        '0' as invoice_supplier_list_total,
        '0' as invoice_supplier_list_cost, 
        stock_event,
        CONCAT('PO : ',purchase_order_code) as invoice_supplier_list_remark  
        FROM tb_purchase_order_list as tb2
        LEFT JOIN tb_purchase_order  ON tb2.purchase_order_id = tb_purchase_order.purchase_order_id  
        LEFT JOIN tb_product ON tb2.product_id = tb_product.product_id 
        LEFT JOIN tb_product_category ON tb_product.product_category_id = tb_product_category.product_category_id 
        WHERE tb_purchase_order.supplier_id = '$supplier_id' 
        $str_po 
        AND tb2.purchase_order_list_id NOT IN ($str) 
        AND tb2.purchase_order_list_id IN ( 
            SELECT tb_purchase_order_list.purchase_order_list_id 
            FROM tb_purchase_order_list  
            LEFT JOIN tb_invoice_supplier_list ON  tb_purchase_order_list.purchase_order_list_id = tb_invoice_supplier_list.purchase_order_list_id 
            LEFT JOIN tb_invoice_supplier ON  tb_invoice_supplier_list.invoice_supplier_id = tb_invoice_supplier.invoice_supplier_id 
            WHERE (invoice_supplier_close = '0' || invoice_supplier_close IS NULL )
            GROUP BY tb_purchase_order_list.purchase_order_list_id 
            HAVING IFNULL(SUM(invoice_supplier_list_qty),0) < MAX(purchase_order_list_qty)  
        ) 
        AND (product_name LIKE ('%$search%') OR purchase_order_code LIKE ('%$search%') OR product_code LIKE ('%$search%')) 
        AND purchase_order_status = 'Confirm'
        GROUP BY purchase_order_list_id 
        ORDER BY purchase_order_code , purchase_order_list_no  ";

        // echo $sql_customer;

        $data = [];
        if ($result = mysqli_query(static::$db,$sql_customer, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            
        }

        for($i=0;$i<count($data);$i++){
            for($j=0;$j<count($data_buf);$j++){
                if($data_buf[$j]['id']==$data[$i]['purchase_order_list_id']){
                    $data[$i]['invoice_supplier_list_qty'] = $data_buf[$j]['qty'];
                    break;
                }
                
            }
        }



        return $data;
    }


    
    function generateInvoiceSupplierListImportBySupplierId(
        $supplier_id, 
        $invoice_supplier_list_id = [],  
        $invoice_supplier_list_qty = [],  
        $jsondata = [] 
        ){
         
        $sql_data = [];
        for($i = 0 ; $i < count($jsondata); $i++){
            $purchase_order_code = $jsondata[$i]->purchase_order_code;
            $product_code = $jsondata[$i]->product_code;

            $sql = "SELECT tb2.product_id, 
            tb2.purchase_order_list_id, 
            tb2.purchase_order_list_no, 
            '' as regrind_supplier_list_id,
            CONCAT(product_code_first,product_code) as product_code, 
            tb2.stock_group_id,
            product_name,  
            stock_group_name,  
            IFNULL(purchase_order_list_qty 
            - IFNULL((
                SELECT SUM(invoice_supplier_list_qty) 
                FROM tb_invoice_supplier_list 
                LEFT JOIN tb_invoice_supplier ON  tb_invoice_supplier_list.invoice_supplier_id = tb_invoice_supplier.invoice_supplier_id 
                WHERE invoice_supplier_close = '0' 
                AND purchase_order_list_id = tb2.purchase_order_list_id 
            ),0) ,0) as invoice_supplier_list_qty, 
            purchase_order_list_price,
            purchase_order_list_price_sum,
            purchase_order_list_price as invoice_supplier_list_price, 
            '0' as invoice_supplier_list_total,
            '0' as invoice_supplier_list_cost, 
            purchase_order_code,
            CONCAT('PO : ',purchase_order_code) as invoice_supplier_list_remark 
            FROM tb_purchase_order 
            LEFT JOIN tb_purchase_order_list as tb2 ON tb_purchase_order.purchase_order_id = tb2.purchase_order_id  
            LEFT JOIN tb_product ON tb2.product_id = tb_product.product_id 
            LEFT JOIN tb_stock_group ON tb2.stock_group_id = tb_stock_group.stock_group_id 
            WHERE tb_purchase_order.supplier_id = '$supplier_id' 
            AND tb2.purchase_order_list_id IN ( 
                SELECT tb_purchase_order_list.purchase_order_list_id 
                FROM tb_purchase_order_list  
                LEFT JOIN tb_invoice_supplier_list ON  tb_purchase_order_list.purchase_order_list_id = tb_invoice_supplier_list.purchase_order_list_id 
                LEFT JOIN tb_invoice_supplier ON  tb_invoice_supplier_list.invoice_supplier_id = tb_invoice_supplier.invoice_supplier_id 
                WHERE (invoice_supplier_close = '0' OR invoice_supplier_close IS NULL)
                GROUP BY tb_purchase_order_list.purchase_order_list_id 
                HAVING IFNULL(SUM(invoice_supplier_list_qty),0) < MAX(purchase_order_list_qty)  
            ) 
            AND purchase_order_code = '$purchase_order_code' 
            AND product_code = '$product_code' 
            AND purchase_order_status = 'Confirm' 
            ORDER BY purchase_order_code , purchase_order_list_no  ";  
            $data = [];
            if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                        
                while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                    $data[] = $row;
                }
                $result->close();
                
            }
            $jsondata[$i]->invoice_supplier_lists = $data;
            $jsondata[$i]->sql = $sql;
        } 

        return $jsondata;
    }


    function insertInvoiceSupplier($data = []){
        $sql = " INSERT INTO tb_invoice_supplier ( 
            supplier_id,
            employee_id,
            invoice_supplier_code,
            invoice_supplier_code_gen,
            invoice_supplier_total_price,
            invoice_supplier_currency_total,
            invoice_supplier_cost_total,
            invoice_supplier_total_stock_price,
            invoice_supplier_currency_stock_total,
            invoice_supplier_cost_stock_total,
            invoice_supplier_vat,
            invoice_supplier_vat_type,
            invoice_supplier_vat_price,
            invoice_supplier_net_price,
            invoice_supplier_date,
            invoice_supplier_date_recieve,
            invoice_supplier_name,
            invoice_supplier_address,
            invoice_supplier_tax,
            invoice_supplier_branch,
            invoice_supplier_term,
            invoice_supplier_due, 
            invoice_supplier_due_day, 
            invoice_supplier_begin,
            import_duty, 
            freight_in, 
            vat_section,
            vat_section_add,
            invoice_supplier_total_price_non,
            invoice_supplier_vat_price_non,
            invoice_supplier_total_non,
            invoice_supplier_description,
            invoice_supplier_remark,
            invoice_supplier_stock,
            addby,
            adddate,
            updateby,
            lastupdate) 
        VALUES ('". 
        $data['supplier_id']."','".
        $data['employee_id']."','".
        static::$db->real_escape_string($data['invoice_supplier_code'])."','".
        static::$db->real_escape_string($data['invoice_supplier_code_gen'])."','".
        $data['invoice_supplier_total_price']."','".
        $data['invoice_supplier_currency_total']."','".
        $data['invoice_supplier_cost_total']."','".
        $data['invoice_supplier_total_stock_price']."','".
        $data['invoice_supplier_currency_stock_total']."','".
        $data['invoice_supplier_cost_stock_total']."','".
        $data['invoice_supplier_vat']."','".
        $data['invoice_supplier_vat_type']."','".
        $data['invoice_supplier_vat_price']."','".
        $data['invoice_supplier_net_price']."','".
        static::$db->real_escape_string($data['invoice_supplier_date'])."','".
        static::$db->real_escape_string($data['invoice_supplier_date_recieve'])."','".
        static::$db->real_escape_string($data['invoice_supplier_name'])."','".
        static::$db->real_escape_string($data['invoice_supplier_address'])."','".
        static::$db->real_escape_string($data['invoice_supplier_tax'])."','".
        static::$db->real_escape_string($data['invoice_supplier_branch'])."','".
        static::$db->real_escape_string($data['invoice_supplier_term'])."','".
        static::$db->real_escape_string($data['invoice_supplier_due'])."','".  
        static::$db->real_escape_string($data['invoice_supplier_due_day'])."','".  
        $data['invoice_supplier_begin']."','". 
        $data['import_duty']."','".
        $data['freight_in']."','".
        static::$db->real_escape_string($data['vat_section'])."','".
        static::$db->real_escape_string($data['vat_section_add'])."','".
        $data['invoice_supplier_total_price_non']."','".
        $data['invoice_supplier_vat_price_non']."','".
        $data['invoice_supplier_total_non']."','".
        static::$db->real_escape_string($data['invoice_supplier_description'])."','".
        static::$db->real_escape_string($data['invoice_supplier_remark'])."','".
        static::$db->real_escape_string($data['invoice_supplier_stock'])."','".
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


    function updateSupplierByInvoiceID($id,$data = []){
        $sql = " UPDATE tb_invoice_supplier SET 
        supplier_id = '".$data['supplier_id']."',  
        invoice_supplier_name = '".static::$db->real_escape_string($data['invoice_supplier_name'])."', 
        invoice_supplier_address = '".static::$db->real_escape_string($data['invoice_supplier_address'])."', 
        invoice_supplier_tax = '".static::$db->real_escape_string($data['invoice_supplier_tax'])."', 
        invoice_supplier_branch = '".static::$db->real_escape_string($data['invoice_supplier_branch'])."', 
        invoice_supplier_term = '".static::$db->real_escape_string($data['invoice_supplier_term'])."',  
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE invoice_supplier_id = '$id' 
        ";

        //echo $sql;

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }


    function deleteInvoiceSupplierByID($id){
        $sql = " DELETE FROM tb_invoice_supplier_freight_in_list WHERE invoice_supplier_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_invoice_supplier_import_duty_list WHERE invoice_supplier_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_invoice_supplier_list WHERE invoice_supplier_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_invoice_supplier WHERE invoice_supplier_id = '$id' ";
        if(mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)){
            return true;
        }else{
            return false;
        }



    }
    function getPurchaseOrderByInvoiceSupplierId($invoice_supplier_id){

        $sql =  "   SELECT tb_purchase_order.purchase_order_id,purchase_order_code
                    FROM  tb_invoice_supplier_list
                    LEFT JOIN tb_purchase_order_list ON tb_invoice_supplier_list.purchase_order_list_id = tb_purchase_order_list.purchase_order_list_id    
                    LEFT JOIN tb_purchase_order ON tb_purchase_order_list.purchase_order_id = tb_purchase_order.purchase_order_id
                    LEFT JOIN tb_invoice_supplier ON  tb_invoice_supplier_list.invoice_supplier_id = tb_invoice_supplier.invoice_supplier_id 
                    WHERE invoice_supplier_close = '0' 
                    AND tb_invoice_supplier_list.invoice_supplier_id  = '$invoice_supplier_id' 
                    GROUP BY purchase_order_code 
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

    function updateInvoiceSupplierCodeByInvoiceID($id,$data = []){
        //print_r($data);
        $sql = " UPDATE tb_invoice_supplier SET  
        invoice_supplier_date = '".static::$db->real_escape_string($data['invoice_supplier_date'])."', 
        invoice_supplier_code = '".static::$db->real_escape_string($data['invoice_supplier_code'])."',
        invoice_supplier_tax = '".static::$db->real_escape_string($data['invoice_supplier_tax'])."',
        invoice_supplier_branch = '".static::$db->real_escape_string($data['invoice_supplier_branch'])."'
        WHERE invoice_supplier_id = '$id' 
        ";
        // echo $sql .'<br>';
       
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }


    
    function cancelInvoiceSupplierById($id){
        $sql = " UPDATE tb_invoice_supplier SET 
        invoice_supplier_close = '1', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE invoice_supplier_id = '$id' 
        ";

        //echo $sql;

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function uncancelInvoiceSupplierById($id){
        $sql = " UPDATE tb_invoice_supplier SET 
        invoice_supplier_close = '0', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW()
        WHERE invoice_supplier_id = '$id' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }



}
?>