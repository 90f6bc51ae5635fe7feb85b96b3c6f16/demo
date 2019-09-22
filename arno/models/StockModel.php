<?php

require_once("BaseModel.php");
class StockModel extends BaseModel{

    private $table_name = "";
    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db,"utf8");
    }

    function setTableName($table_name = "tb_stock"){
        $this->table_name = $table_name;
    }

    function createStockTable(){
        $sql = "
            CREATE TABLE `".$this->table_name."` ( 
                `stock_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงคลังสินค้า', 
                `stock_type` varchar(10) NOT NULL COMMENT 'ประเภท รับ หรือ ออก', 
                `product_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงสินค้า', 
                `stock_date` varchar(50) NOT NULL COMMENT 'วันที่ดำเนินการ', 
                `customer_id` int(11) NOT NULL COMMENT 'รหัสลูกค้า', 
                `supplier_id` int(11) NOT NULL COMMENT 'รหัสผู้ขาย', 
                `in_qty` int(11) NOT NULL COMMENT 'จำนวน (เข้า)', 
                `in_stock_cost_avg` double	 NOT NULL COMMENT 'ราคาต่อชิ้น (เข้า)', 
                `in_stock_cost_avg_total` double	 NOT NULL COMMENT 'ราคารวม (เข้า)', 
                `out_qty` int(11) NOT NULL COMMENT 'จำนวน (ออก)', 
                `out_stock_cost_avg` double	 NOT NULL COMMENT 'ราคาต่อชิ้น (ออก)', 
                `out_stock_cost_avg_total` double	 NOT NULL COMMENT 'ราคารวม (ออก)', 
                `balance_qty` int(11) NOT NULL COMMENT 'จำนวน (คงเหลือ)', 
                `balance_stock_cost_avg` double	 NOT NULL COMMENT 'ราคาต่อชิ้น (คงเหลือ)', 
                `balance_stock_cost_avg_total` double	 NOT NULL COMMENT 'ราคารวม (คงเหลือ)', 
                `delivery_note_supplier_list_id` int(11) NOT NULL DEFAULT '0' COMMENT 'รหัสอ้างอิงรายการยืมเข้า', 
                `delivery_note_customer_list_id` int(11) DEFAULT '0' COMMENT 'รหัสอ้างอิงรายการยืมออก', 
                `invoice_supplier_list_id` int(11) NOT NULL DEFAULT '0' COMMENT 'รหัสอ้างอิงรายการซื้อเข้า', 
                `invoice_customer_list_id` int(11) NOT NULL DEFAULT '0' COMMENT 'รหัสอ้างอิงรายการขายออก', 
                `stock_move_list_id` int(11) NOT NULL DEFAULT '0' COMMENT 'รหัสอ้างอิงรายการย้ายคลังสินค้า', 
                `stock_issue_list_id` int(11) NOT NULL DEFAULT '0' COMMENT 'รหัสอ้างอิงรายการตัดคลังสินค้า', 
                `credit_note_list_id` int(11) NOT NULL DEFAULT '0' COMMENT 'รหัสอ้างอิงรายการใบลดหนี้', 
                `credit_note_supplier_list_id` int(11) NOT NULL DEFAULT '0' COMMENT 'รหัสอ้างอิงรายการใบลดหนี้ซัพพลายเออร์', 
                `regrind_supplier_list_id` int(11) NOT NULL DEFAULT '0' COMMENT 'รหัสอ้างอิงรายการใบ Regrind', 
                `summit_product_id` int(11) NOT NULL DEFAULT '0' COMMENT 'รหัสรายการสินค้ายกยอด', 
                `stock_change_product_list_id` int(11) NOT NULL DEFAULT '0' COMMENT 'รหัสรายการสินค้าเปลี่ยนชื่อ', 
                `stock_product_set_list_id` int(11) NOT NULL DEFAULT '0' COMMENT 'รหัสรายการรวมสินค้า', 
                `stock_product_set_id` int(11) NOT NULL DEFAULT '0' COMMENT 'รหัสใบรวมสินค้า', 
                `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มสินค้า', 
                `adddate` varchar(50) NOT NULL COMMENT 'เวลาเพิ่มสินค้า', 
                `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขสิ้นค้า', 
                `lastupdate` varchar(50) NOT NULL COMMENT 'เวลาแก้ไขสินค้า' 
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;  
        ";
            
        if (mysqli_query(static::$db,$sql)) {
            $sql = "
                ALTER TABLE `".$this->table_name."` 
                    ADD PRIMARY KEY (`stock_id`), 
                    ADD KEY `invoice_code` (`invoice_customer_list_id`,`invoice_supplier_list_id`); 
            ";
            if (mysqli_query(static::$db,$sql)) {
                $sql = "
                  ALTER TABLE `".$this->table_name."` 
                    MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงคลังสินค้า'; 
                ";
                if (mysqli_query(static::$db,$sql)) {return true;}
                else {return false;}
            }else {return false;}
        }else {return false;}
    }

    function deleteStockTable(){
        $sql = "DROP TABLE IF EXISTS ".$this->table_name." ;";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }

    }

    function getStockBy(){
        $sql = "  SELECT * FROM $table_name WHERE 1 ";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getStockLogListByDate($date_start = '', $date_end = '', $stock_group_id = '', $keyword = ''){
        $str = " AND STR_TO_DATE(stock_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%Y-%m-%d %H:%i:%s') AND STR_TO_DATE(stock_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%Y-%m-%d %H:%i:%s') ";

        $sql_old_in = "SELECT SUM(in_qty) 
        FROM ".$this->table_name."  
        WHERE ".$this->table_name.".product_id = tb.product_id 
        AND stock_type = 'in' 
        AND STR_TO_DATE(stock_date,'%d-%m-%Y %H:%i:%s') < STR_TO_DATE('$date_start','%Y-%m-%d %H:%i:%s')";


        $sql_old_out = "SELECT SUM(out_qty) 
        FROM ".$this->table_name."  
        WHERE ".$this->table_name.".product_id = tb.product_id 
        AND stock_type = 'out' 
        AND STR_TO_DATE(stock_date,'%d-%m-%Y %H:%i:%s') < STR_TO_DATE('$date_start','%Y-%m-%d %H:%i:%s')";


        $sql_in = "SELECT SUM(in_qty) 
        FROM ".$this->table_name."  
        WHERE ".$this->table_name.".product_id = tb.product_id 
        AND stock_type = 'in' ".$str;

        $sql_out = "SELECT SUM(out_qty) 
        FROM ".$this->table_name." 
        WHERE ".$this->table_name.".product_id = tb.product_id 
        AND stock_type = 'out' ".$str;

        $sql_borrow_in = "SELECT SUM(in_qty) 
        FROM ".$this->table_name." 
        WHERE ".$this->table_name.".product_id = tb.product_id 
        AND stock_type = 'borrow_in' ".$str;

        $sql_borrow_out = "SELECT SUM(out_qty) 
        FROM ".$this->table_name." 
        WHERE ".$this->table_name.".product_id = tb.product_id 
        AND stock_type = 'borrow_out' ".$str;

        $sql_minimum = "SELECT SUM(minimum_stock) 
        FROM tb_product_customer 
        WHERE tb_product_customer.product_id = tb.product_id 
        AND product_status = 'Active' ";

        $sql_safety = "SELECT SUM(safety_stock) 
        FROM tb_product_customer 
        WHERE tb_product_customer.product_id = tb.product_id 
        AND product_status = 'Active' ";

        $sql = "SELECT tb.product_id,  CONCAT(product_code_first,product_code) as product_code, 
        product_name, product_type, product_status ,
        (IFNULL(($sql_old_in),0) - IFNULL(($sql_old_out),0)) as stock_old,
        IFNULL(($sql_in),0) as stock_in,
        IFNULL(($sql_out),0) as stock_out,
        IFNULL(($sql_borrow_in),0) as stock_borrow_in,
        IFNULL(($sql_borrow_out),0) as stock_borrow_out,
        IFNULL(($sql_safety),0) as stock_safety,
        IFNULL(($sql_minimum),0) as stock_minimum 
        FROM tb_stock_report 
        LEFT JOIN tb_product as  tb ON tb_stock_report.product_id = tb.product_id  
        WHERE stock_group_id = '$stock_group_id' 
        AND (
                CONCAT(product_code_first,product_code) LIKE ('%$keyword%') 
                OR  product_name LIKE ('%$keyword%') 
        ) 
        ORDER BY CONCAT(product_code_first,product_code) 
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

    function getStockInByDate($date_start = '', $date_end = ''){
        $sql = "SELECT stock_id, stock_date, ".$this->table_name.".product_id,  CONCAT(product_code_first,product_code) as product_code, 
        product_name, product_type, product_status , in_qty , supplier_name_th, supplier_name_en 
        FROM ".$this->table_name." 
        LEFT JOIN tb_product ON ".$this->table_name.".product_id = tb_product.product_id 
        LEFT JOIN tb_invoice_supplier_list ON ".$this->table_name.".invoice_supplier_list_id = tb_invoice_supplier_list.invoice_supplier_list_id 
        LEFT JOIN tb_invoice_supplier ON tb_invoice_supplier_list.invoice_supplier_id = tb_invoice_supplier.invoice_supplier_id 
        LEFT JOIN tb_supplier ON tb_invoice_supplier.supplier_id = tb_supplier.supplier_id 
        WHERE stock_type = 'in' 
        AND STR_TO_DATE(stock_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%Y-%m-%d %H:%i:%s') 
        AND STR_TO_DATE(stock_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%Y-%m-%d %H:%i:%s') 
        ORDER BY STR_TO_DATE(stock_date,'%d-%m-%Y %H:%i:%s') 
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

    function getStockOutByDate($date_start = '', $date_end = ''){
        $sql = "SELECT stock_id, stock_date, ".$this->table_name.".product_id,  CONCAT(product_code_first,product_code) as product_code, 
        product_name, product_type, product_status , out_qty , customer_name_th, customer_name_en 
        FROM ".$this->table_name." 
        LEFT JOIN tb_product ON ".$this->table_name.".product_id = tb_product.product_id 
        LEFT JOIN tb_invoice_customer_list ON ".$this->table_name.".invoice_customer_list_id = tb_invoice_customer_list.invoice_customer_list_id 
        LEFT JOIN tb_invoice_customer ON tb_invoice_customer_list.invoice_customer_id = tb_invoice_customer.invoice_customer_id 
        LEFT JOIN tb_customer ON tb_invoice_customer.customer_id = tb_customer.customer_id 
        WHERE stock_type = 'out' 
        AND STR_TO_DATE(stock_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%Y-%m-%d %H:%i:%s') 
        AND STR_TO_DATE(stock_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%Y-%m-%d %H:%i:%s') 
        ORDER BY STR_TO_DATE(stock_date,'%d-%m-%Y %H:%i:%s') 
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


    function updateStockByID($id,$data = []){
        $sql = " UPDATE $table_name SET 
        stock_type = '".$data['stock_type']."' , 
        product_id = '".$data['product_id']."' , 
        stock_date = '".$data['stock_date']."' , 
        customer_id = '".$data['customer_id']."' , 
        supplier_id = '".$data['supplier_id']."' , 
        in_qty = '".$data['in_qty']."' , 
        in_price = '".$data['in_price']."' , 
        in_total = '".$data['in_total']."' , 
        out_qty = '".$data['out_qty']."' , 
        out_price = '".$data['out_price']."' , 
        out_total = '".$data['out_total']."' , 
        balance_qty = '".$data['balance_qty']."' , 
        balance_price = '".$data['balance_price']."' , 
        balance_total = '".$data['balance_total']."' , 
        delivery_note_supplier_list_id = '".$data['delivery_note_supplier_list_id']."' , 
        delivery_note_customer_list_id = '".$data['delivery_note_customer_list_id']."' , 
        invoice_supplier_list_id = '".$data['invoice_supplier_list_id']."' , 
        invoice_customer_list_id = '".$data['invoice_customer_list_id']."' , 
        stock_move_list_id = '".$data['stock_move_list_id']."' , 
        regrind_supplier_list_id = '".$data['regrind_supplier_list_id']."' , 
        updateby = '".$data['updateby']."',  
        lastupdate = NOW() 
        WHERE stock_id = $id ;
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function insertStock($data = []){
        $sql = " INSERT INTO tb_stock_group (
            stock_type, 
            product_id, 
            stock_date, 
            customer_id, 
            supplier_id, 
            in_qty, 
            in_price, 
            in_total,  
            out_qty, 
            out_price, 
            out_total,  
            balance_qty, 
            balance_price, 
            balance_total,  
            delivery_note_supplier_list_id, 
            delivery_note_customer_list_id, 
            invoice_supplier_list_id, 
            invoice_customer_list_id, 
            regrind_supplier_list_id,
            stock_move_list_id, 
            addby,
            adddate
        ) VALUES (  
            '".$data['stock_type']."', 
            '".$data['product_id']."', 
            '".$data['stock_date']."', 
            '".$data['customer_id']."', 
            '".$data['supplier_id']."', 
            '".$data['in_qty']."', 
            '".$data['in_price']."', 
            '".$data['in_total']."', 
            '".$data['out_qty']."', 
            '".$data['out_price']."', 
            '".$data['out_total']."', 
            '".$data['balance_qty']."', 
            '".$data['balance_price']."', 
            '".$data['balance_total']."', 
            '".$data['delivery_note_supplier_list_id']."', 
            '".$data['delivery_note_customer_list_id']."', 
            '".$data['invoice_supplier_list_id']."', 
            '".$data['invoice_customer_list_id']."', 
            '".$data['regrind_supplier_list_id']."', 
            '".$data['stock_move_list_id']."', 
            '".$data['addby']."', 
            NOW()  
        ); 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }


    function deleteStockByID($id){
        $sql = " DELETE FROM $table_name WHERE stock_id = '$id' ;";
        if(mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)){
            return true;
        }else{
            return false;
        }
    }

}
?>
