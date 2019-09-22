<?php

require_once("BaseModel.php");

class MaintenanceStockModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db,"utf8");
    }

    //***************************************************************************************************************** */
    //
    //
    //  Function ใช้ภายในระบบ Summit Porduct, Invoice Supplier, Stock Move
    //
    //
    //***************************************************************************************************************** */


    function runMaintenance($start_date = ''){

        // 1-1. ถ้าเป็นการซ่อมแซมระบบทั้งหมด 
        if($start_date == ''){
            // 1-1.1 ล้างข้อมูลทั้งหมดในตาราง รายงานคลังสินค้า
            $sql = "TRUNCATE TABLE tb_stock_report";
            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

            // 1-1.2 ค้นหาคลังสินค้าทั้งหมด แล้วล้างประวัติทั้งหมด
            $sql = "SELECT * FROM tb_stock_group ";
            $data = [];
            if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                while( $row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                    $data[] = $row;
                }
                $result->close(); 

                //1-1.2.1 วนรอบล้างประวัติคลังสินค้า 
                for($i = 0; $i < count($data); $i++){
                    $sql = "TRUNCATE TABLE ".$data[$i]['table_name'];
                    mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
                }
            }

            // 1-1.3 ทำการนำสินค้าตั้งต้น เข้าสู่คลังสินค้าต่างๆ 
            $sql = "SELECT * FROM tb_summit_product ORDER BY stock_group_id , product_id";
            $data = [];
            if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                while( $row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                    $data[] = $row;
                }
                $result->close(); 

                // 1-1.3.1 วนรอบปรับต้นทุนต่างๆ ในคลังสินค้า
                for($i = 0; $i < count($data); $i++){   
                    $this->addSummitProduct($data[$i]['summit_product_date'], $data[$i]['stock_group_id'], $data[$i]['summit_product_id'], $data[$i]['product_id'], $data[$i]['summit_product_qty'], $data[$i]['summit_product_cost'], $data[$i]['summit_product_total']);
                }
            }

            $str_invoice_supplier = " ";
            $str_move = " ";
            $str_change = " ";
            $str_invoice_customer = " ";
            $str_issue = " ";
        }

        // 1-2. ถ้าเป็นเป็นการซ่อมแซมข้อมูล ณ วันที่นั้นๆ

        else{
            // 1-2.1 ล้างข้อมูลทั้งหมดในตาราง รายงานคลังสินค้า
            $sql = "TRUNCATE TABLE tb_stock_report";
            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

            // 1-2.2 ลบข้อมูลในคลังสินค้าทุกคลังที่มีการเคลื่อนไหวตั้งแต่วันที่ทำการแก้ไข และ ปรับ Index ของทุกคลังสินค้าใหม่ ให้เท่ากับ index ล่าสุด
            $sql = "SELECT * FROM tb_stock_group ";
            $data = [];
            if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                while( $row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                    $data[] = $row;
                }
                $result->close(); 

                
                for($i = 0; $i < count($data); $i++){

                    //1-2.2.1 วนรอบล้างประวัติคลังสินค้า ตั้งแต่วันที่ทำการแก้ไข 
                    $sql = "DELETE FROM ".$data[$i]['table_name']." WHERE STR_TO_DATE(stock_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$start_date','%d-%m-%Y %H:%i:%s')";
                    mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

                    // 1-2.3 ปรับ Index ของทุกคลังสินค้าใหม่ ให้เท่ากับ index ล่าสุด
                    $sql = "ALTER TABLE ".$data[$i]['table_name']." AUTO_INCREMENT = (SELECT MAX(stock_id) + 1 FROM ".$data[$i]['table_name']." )";
                    mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);


                    $sql = "SELECT * FROM ".$data[$i]['table_name']." 
                    WHERE stock_id IN ( 
                        SELECT MAX(stock_id) 
                        FROM ".$data[$i]['table_name']."  
                        GROUP BY product_id 
                    ) ";


                    $data_transaction = [];
                    if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                        while( $row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                            $data_transaction[] = $row;
                        }
                        $result->close(); 


                        for($i_tran = 0 ; $i_tran < count($data_transaction) ; $i_tran ++){
                            $sql = "INSERT INTO tb_stock_report (
                                stock_group_id,
                                product_id,
                                stock_report_qty,
                                stock_report_cost_avg,
                                stock_report_cost_avg_total
                            ) VALUES ('".
                            $data[$i]['stock_group_id'] . "','".
                            $data_transaction[$i_tran]['product_id'] . "','".
                            $data_transaction[$i_tran]['balance_qty'] . "','".
                            $data_transaction[$i_tran]['balance_stock_cost_avg'] . "','".
                            $data_transaction[$i_tran]['balance_stock_cost_avg_total'] . "'".
                            "); "; 
            
                            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
                        }

                    }



                }
            }


            $str_invoice_supplier = " AND STR_TO_DATE(invoice_supplier_date_recieve,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$start_date','%d-%m-%Y %H:%i:%s') ";
            $str_move = " AND STR_TO_DATE(stock_move_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$start_date','%d-%m-%Y %H:%i:%s') ";
            $str_set = " AND STR_TO_DATE(stock_product_set_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$start_date','%d-%m-%Y %H:%i:%s') ";
            $str_change = " AND STR_TO_DATE(stock_change_product_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$start_date','%d-%m-%Y %H:%i:%s') ";
            $str_invoice_customer = " AND STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$start_date','%d-%m-%Y %H:%i:%s') ";
            $str_issue = " AND STR_TO_DATE(stock_issue_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$start_date','%d-%m-%Y %H:%i:%s') ";
            $str_credit = " AND STR_TO_DATE(credit_note_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$start_date','%d-%m-%Y %H:%i:%s') ";
            $str_credit_supplier = " AND STR_TO_DATE(credit_note_supplier_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$start_date','%d-%m-%Y %H:%i:%s') ";

             
        }



        
        //2. ดึงข้อมูลการรับสินค้าเข้าเรียงตามวันที่
        $sql_purchase="SELECT 
        invoice_supplier_code_gen as transaction_code,  
        invoice_supplier_list_no as transaction_no,  
        invoice_supplier_date_recieve as stock_date, 
        '1_purchase' as transaction_type, 
        stock_group_id,   
        '0' as stock_group_id_out, 
        '0' as stock_group_id_in, 
        invoice_supplier_list_id,  
        '0' as stock_move_list_id, 
        '0' as stock_change_product_list_id, 
        '0' as invoice_customer_list_id,   
        '0' as stock_issue_list_id,  
        '0' as stock_product_set_list_id,  
        '0' as stock_product_set_id,  
        '0' as credit_note_list_id,  
        '0' as credit_note_supplier_list_id, 
        tb_invoice_supplier_list.product_id as product_id,  
        '0' as product_id_old, 
        '0' as product_id_new, 
        invoice_supplier_list_qty as qty, 
        invoice_supplier_list_cost as cost, 
        invoice_supplier_list_cost_total as total 
        FROM tb_invoice_supplier 
        LEFT JOIN tb_invoice_supplier_list ON tb_invoice_supplier.invoice_supplier_id = tb_invoice_supplier_list.invoice_supplier_id 
        LEFT JOIN tb_product ON tb_invoice_supplier_list.product_id = tb_product.product_id 
        LEFT JOIN tb_product_category ON tb_product.product_category_id = tb_product_category.product_category_id  
        WHERE invoice_supplier_begin = '0' AND stock_event = '1'  AND invoice_supplier_close = '0' 
        AND invoice_supplier_list_id IS NOT NULL 
        $str_invoice_supplier
        GROUP BY invoice_supplier_list_id 
        ORDER BY STR_TO_DATE(invoice_supplier_date_recieve,'%d-%m-%Y %H:%i:%s') , invoice_supplier_code_gen  ";


////รับของเข้า

        //ดึงข้อมูลการรับสินค้าเข้าเรียงตามวันที่
        $sql_credit="SELECT 
        credit_note_code as transaction_code, 
        '0' as transaction_no, 
        credit_note_date as stock_date, 
        '2_credit' as transaction_type, 
        stock_group_id,  
        '0' as stock_group_id_out, 
        '0' as stock_group_id_in, 
        '0' as invoice_supplier_list_id, 
        '0' as stock_move_list_id, 
        '0' as stock_change_product_list_id, 
        '0' as invoice_customer_list_id,   
        '0' as stock_issue_list_id,  
        '0' as stock_product_set_list_id,  
        '0' as stock_product_set_id,   
        credit_note_list_id,  
        '0' as credit_note_supplier_list_id,   
        product_id, 
        '0' as product_id_old, 
        '0' as product_id_new, 
        credit_note_list_qty as qty, 
        credit_note_list_price as cost, 
        credit_note_list_cost as total 
        FROM tb_credit_note 
        LEFT JOIN tb_credit_note_list ON tb_credit_note.credit_note_id = tb_credit_note_list.credit_note_id 
        WHERE credit_note_list_id IS NOT NULL 
        AND credit_note_type_id = '1' 
        $str_credit 
        GROUP BY credit_note_list_id 
        ORDER BY STR_TO_DATE(credit_note_date,'%d-%m-%Y %H:%i:%s') , credit_note_code  ";


        //ดึงข้อมูลการย้ายคลังสินค้าเข้าเรียงตามวันที่
        $sql_move="SELECT 
        stock_move_code as transaction_code,  
        '0' as transaction_no, 
        stock_move_date as stock_date,  
        '3_move' as transaction_type, 
        '0' as stock_group_id,   
        stock_group_id_out, 
        stock_group_id_in, 
        '0' as invoice_supplier_list_id, 
        stock_move_list_id, 
        '0' as stock_change_product_list_id, 
        '0' as invoice_customer_list_id,   
        '0' as stock_issue_list_id,  
        '0' as stock_product_set_list_id,  
        '0' as stock_product_set_id,  
        '0' as credit_note_list_id,  
        '0' as credit_note_supplier_list_id, 
        tb_stock_move_list.product_id as product_id,  
        '0' as product_id_old, 
        '0' as product_id_new, 
        stock_move_list_qty as qty, 
        '0' as cost, 
        '0' as total 
        FROM tb_stock_move 
        LEFT JOIN tb_stock_move_list ON tb_stock_move.stock_move_id = tb_stock_move_list.stock_move_id 
        WHERE stock_move_list_id IS NOT NULL 
        $str_move 
        GROUP BY stock_move_list_id 
        ORDER BY STR_TO_DATE(stock_move_date,'%d-%m-%Y %H:%i:%s') , stock_move_code ";



        //ดึงข้อมูลการการตัดคลังสินค้าเมื่อเกิดการรวมชุดเรียงตามวันที่
        $sql_set_1 ="SELECT 
        stock_product_set_code as transaction_code,  
        '0' as transaction_no, 
        stock_product_set_date as stock_date,  
        '4_set' as transaction_type, 
        tb_stock_product_set_list.stock_group_id as stock_group_id,   
        '0' as stock_group_id_out, 
        '0' as stock_group_id_in, 
        '0' as invoice_supplier_list_id, 
        '0' as stock_move_list_id,  
        '0' as stock_change_product_list_id, 
        '0' as invoice_customer_list_id,   
        '0' as stock_issue_list_id,  
        stock_product_set_list_id, 
        '0' as stock_product_set_id,
        '0' as credit_note_list_id,  
        '0' as credit_note_supplier_list_id, 
        tb_stock_product_set_list.product_set_id as product_id,  
        '0' as product_id_old, 
        '0' as product_id_new, 
        stock_product_set_list_qty as qty, 
        '0' as cost, 
        '0' as total 
        FROM tb_stock_product_set 
        LEFT JOIN tb_stock_product_set_list ON tb_stock_product_set.stock_product_set_id = tb_stock_product_set_list.stock_product_set_id 
        WHERE stock_product_set_list_id IS NOT NULL 
        $str_set 
        GROUP BY stock_product_set_list_id 
        ORDER BY STR_TO_DATE(stock_product_set_date,'%d-%m-%Y %H:%i:%s') , stock_product_set_code ";


        //ดึงข้อมูลการการเพิ่มสินค้าเข้าคลังสินค้าเมื่อเกิดการรวมชุดเรียงตามวันที่
        $sql_set_2 ="SELECT 
        stock_product_set_code as transaction_code,  
        '0' as transaction_no, 
        stock_product_set_date as stock_date,  
        '5_set' as transaction_type, 
        tb_stock_product_set.stock_group_id as stock_group_id,   
        '0' as stock_group_id_out, 
        '0' as stock_group_id_in, 
        '0' as invoice_supplier_list_id, 
        '0' as stock_move_list_id,  
        '0' as stock_change_product_list_id, 
        '0' as invoice_customer_list_id,   
        '0' as stock_issue_list_id,  
        '0' as stock_product_set_list_id, 
        stock_product_set_id,
        '0' as credit_note_list_id,  
        '0' as credit_note_supplier_list_id, 
        tb_stock_product_set.product_id as product_id,  
        '0' as product_id_old, 
        '0' as product_id_new, 
        stock_product_set_qty as qty, 
        '0' as cost, 
        '0' as total 
        FROM tb_stock_product_set  
        WHERE stock_product_set_id IS NOT NULL 
        $str_set 
        GROUP BY stock_product_set_id 
        ORDER BY STR_TO_DATE(stock_product_set_date,'%d-%m-%Y %H:%i:%s') , stock_product_set_code ";


        //ดึงข้อมูลการย้ายจำนวนจากสินค้าชื่อนึง ไป ยังสินค้าชื่อนึง เข้าเรียงตามวันที่
        $sql_rename = "SELECT 
        stock_change_product_code as transaction_code,  
        '0' as transaction_no, 
        stock_change_product_date as stock_date,  
        '6_rename' as transaction_type, 
        '0' as stock_group_id,   
        stock_group_id_old as stock_group_id_out, 
        stock_group_id_new as stock_group_id_in, 
        '0' as invoice_supplier_list_id, 
        '0' as stock_move_list_id, 
        stock_change_product_list_id, 
        '0' as invoice_customer_list_id,   
        '0' as stock_issue_list_id,  
        '0' as stock_product_set_list_id,  
        '0' as stock_product_set_id,  
        '0' as credit_note_list_id,  
        '0' as credit_note_supplier_list_id, 
        '0' as product_id, 
        product_id_old, 
        product_id_new, 
        stock_change_product_list_qty as qty, 
        stock_change_product_list_price as cost, 
        (stock_change_product_list_qty * stock_change_product_list_price) as total
        FROM tb_stock_change_product 
        LEFT JOIN tb_stock_change_product_list ON tb_stock_change_product.stock_change_product_id = tb_stock_change_product_list.stock_change_product_id 
        WHERE stock_change_product_list_id IS NOT NULL 
        $str_change 
        GROUP BY stock_change_product_list_id 
        ORDER BY STR_TO_DATE(stock_change_product_date,'%d-%m-%Y %H:%i:%s') , stock_change_product_code";




        

        //ดึงข้อมูลการรับสินค้าเข้าเรียงตามวันที่
        $sql_sale="SELECT 
        invoice_customer_code as transaction_code,  
        invoice_customer_list_no as transaction_no, 
        invoice_customer_date as stock_date, 
        '7_sale' as transaction_type, 
        stock_group_id,  
        '0' as stock_group_id_out, 
        '0' as stock_group_id_in, 
        '0' as invoice_supplier_list_id, 
        '0' as stock_move_list_id, 
        '0' as stock_change_product_list_id, 
        invoice_customer_list_id,   
        '0' as stock_issue_list_id,  
        '0' as stock_product_set_list_id,  
        '0' as stock_product_set_id,  
        '0' as credit_note_list_id,  
        '0' as credit_note_supplier_list_id, 
        tb_invoice_customer_list.product_id as product_id, 
        '0' as product_id_old, 
        '0' as product_id_new, 
        invoice_customer_list_qty as qty, 
        invoice_customer_list_price as cost,
        (invoice_customer_list_qty * invoice_customer_list_price) as total 
        FROM tb_invoice_customer 
        LEFT JOIN tb_invoice_customer_list ON tb_invoice_customer.invoice_customer_id = tb_invoice_customer_list.invoice_customer_id 
        LEFT JOIN tb_product ON tb_invoice_customer_list.product_id = tb_product.product_id 
        LEFT JOIN tb_product_category ON tb_product.product_category_id = tb_product_category.product_category_id 
        WHERE invoice_customer_begin = '0' AND stock_event = '1' AND invoice_customer_close = '0'
        AND invoice_customer_list_id IS NOT NULL  
        $str_invoice_customer 
        GROUP BY invoice_customer_list_id 
        ORDER BY STR_TO_DATE(invoice_customer_date,'%d-%m-%Y %H:%i:%s') , invoice_customer_code  ";





        //ดึงข้อมูลการรับสินค้าเข้าเรียงตามวันที่
        $sql_issue="SELECT 
        stock_issue_code as transaction_code, 
        '0' as transaction_no, 
        stock_issue_date as stock_date, 
        '8_issue' as transaction_type, 
        stock_group_id,  
        '0' as stock_group_id_out, 
        '0' as stock_group_id_in, 
        '0' as invoice_supplier_list_id, 
        '0' as stock_move_list_id, 
        '0' as stock_change_product_list_id, 
        '0' as invoice_customer_list_id,   
        stock_issue_list_id,  
        '0' as stock_product_set_list_id,  
        '0' as stock_product_set_id,  
        '0' as credit_note_list_id,  
        '0' as credit_note_supplier_list_id,  
        product_id, 
        '0' as product_id_old, 
        '0' as product_id_new, 
        stock_issue_list_qty as qty, 
        '0' as cost, 
        '0' as total 
        FROM tb_stock_issue 
        LEFT JOIN tb_stock_issue_list ON tb_stock_issue.stock_issue_id = tb_stock_issue_list.stock_issue_id 
        WHERE stock_issue_list_id IS NOT NULL 
        $str_issue 
        GROUP BY stock_issue_list_id 
        ORDER BY STR_TO_DATE(stock_issue_date,'%d-%m-%Y %H:%i:%s') , stock_issue_code  ";




        //ดึงข้อมูลการลดหนี้คืนสินค้าเข้าเรียงตามวันที่
        $sql_credit_supplier="SELECT 
        credit_note_supplier_code as transaction_code, 
        '0' as transaction_no, 
        credit_note_supplier_date as stock_date, 
        '9_credit_supplier' as transaction_type, 
        stock_group_id,  
        '0' as stock_group_id_out, 
        '0' as stock_group_id_in, 
        '0' as invoice_supplier_list_id, 
        '0' as stock_move_list_id, 
        '0' as stock_change_product_list_id, 
        '0' as invoice_customer_list_id,   
        '0' as stock_issue_list_id,   
        '0' as stock_product_set_list_id,  
        '0' as stock_product_set_id,  
        '0' as credit_note_list_id,   
        credit_note_supplier_list_id,  
        product_id, 
        '0' as product_id_old, 
        '0' as product_id_new, 
        credit_note_supplier_list_qty as qty, 
        credit_note_supplier_list_price as cost, 
        credit_note_supplier_list_cost as total 
        FROM tb_credit_note_supplier 
        LEFT JOIN tb_credit_note_supplier_list ON tb_credit_note_supplier.credit_note_supplier_id = tb_credit_note_supplier_list.credit_note_supplier_id 
        WHERE credit_note_supplier_list_id IS NOT NULL 
        AND credit_note_supplier_type_id = '1' 
        $str_credit_supplier 
        GROUP BY credit_note_supplier_list_id 
        ORDER BY STR_TO_DATE(credit_note_supplier_date,'%d-%m-%Y %H:%i:%s') , credit_note_supplier_code  ";


        // ดึงข้อมูล Transaction รวมทั้งหมด
        $sql = "SELECT 
        transaction_code,  
        stock_date, 
        transaction_type, 
        stock_group_id, 
        stock_group_id_out, 
        stock_group_id_in, 
        invoice_supplier_list_id, 
        stock_move_list_id,
        invoice_customer_list_id, 
        stock_change_product_list_id, 
        invoice_customer_list_id, 
        stock_issue_list_id, 
        stock_product_set_list_id,  
        stock_product_set_id,  
        credit_note_list_id,
        credit_note_supplier_list_id,
        product_id,  
        product_id_old,  
        product_id_new,  
        qty, 
        cost,
        total  
        FROM    (
                    ($sql_purchase) 
                    UNION ALL  ($sql_move) 
                    UNION ALL  ($sql_set_1) 
                    UNION ALL  ($sql_set_2) 
                    UNION ALL  ($sql_rename) 
                    UNION ALL  ($sql_sale) 
                    UNION ALL  ($sql_credit) 
                    UNION ALL  ($sql_credit_supplier) 
                    UNION ALL  ($sql_issue)
                ) as tb_transaction  
        ORDER BY STR_TO_DATE(stock_date,'%d-%m-%Y %H:%i:%s'), transaction_type , transaction_code, transaction_no ASC 
        "; 
        

        // echo "<pre>";
        // print_r ($sql);
        // echo "</pre>";
        $data = [];
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            while( $row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            for($i = 0 ; $i < count($data) ; $i++){ 
                if($data[$i]['transaction_type'] == '1_purchase'){ // คำนวนคลังสินค้าในรูปแบบของการซื้อ
                    $this->addPurchase($data[$i]['stock_date'], $data[$i]['stock_group_id'], $data[$i]['invoice_supplier_list_id'], $data[$i]['product_id'], $data[$i]['qty'], $data[$i]['cost'], $data[$i]['total']);
                }else if($data[$i]['transaction_type'] == '3_move'){// คำนวนคลังสินค้าในรูปแบบของการย้ายคลังสินค้า 
                    $this->addMoveStock($data[$i]['stock_date'], $data[$i]['stock_group_id_out'],$data[$i]['stock_group_id_in'],$data[$i]['stock_move_list_id'], $data[$i]['product_id'], $data[$i]['qty']);
                }else if($data[$i]['transaction_type'] == '4_set'){// คำนวนคลังสินค้าในรูปแบบของการรวมสินค้า-ตัดสินค้าออกจากคลังสินค้า 
 
                    $this->addStockProductSetList($data[$i]['stock_date'], $data[$i]['stock_group_id'], $data[$i]['stock_product_set_list_id'], $data[$i]['product_id'], $data[$i]['qty']);
                }else if($data[$i]['transaction_type'] == '5_set'){// คำนวนคลังสินค้าในรูปแบบของการรวมสินค้า-เพิ่มสินค้าเข้าคลังสินค้า  
                    // echo "<pre>";
                    // print_r($data[$i]);
                    // echo "</pre>";
                    $sql_sub ="SELECT SUM(stock_product_set_list_cost_total) as cost_total FROM tb_stock_product_set_list WHERE stock_product_set_id = '".$data[$i]['stock_product_set_id']."'";
                    $total = 0;
                    $cost = 0;
                    if ($result_sub = mysqli_query(static::$db,$sql_sub, MYSQLI_USE_RESULT)) {
                        $row_sub = mysqli_fetch_array($result_sub,MYSQLI_ASSOC);
                        $result_sub->close();
                        $total = $row_sub['cost_total'];
                        $cost = round($total/$data[$i]['qty'],2);
                    }
                    $this->addStockProductSet($data[$i]['stock_date'], $data[$i]['stock_group_id'], $data[$i]['stock_product_set_id'], $data[$i]['product_id'], $data[$i]['qty'], $cost, $total);
                }else if($data[$i]['transaction_type'] == '6_rename'){// คำนวนคลังสินค้าในรูปแบบของการย้ายสินค้าไปยังสินค้าชื่ออื่น 
                    $this->addStockChangeProduct($data[$i]['stock_date'],$data[$i]['stock_group_id_out'],$data[$i]['stock_group_id_in'] ,$data[$i]['stock_change_product_list_id'], $data[$i]['product_id_old'], $data[$i]['product_id_new'], $data[$i]['qty']);
                }else if($data[$i]['transaction_type'] == '7_sale'){// คำนวนคลังสินค้าในรูปแบบของการขาย
                    $this->addSaleStock($data[$i]['stock_date'], $data[$i]['stock_group_id'], $data[$i]['invoice_customer_list_id'], $data[$i]['product_id'], $data[$i]['qty']);
                }else if($data[$i]['transaction_type'] == '8_issue'){// คำนวนคลังสินค้าในรูปแบบของการตัดสินค้า Tool Management
                    $this->addIssueStock($data[$i]['stock_date'], $data[$i]['stock_group_id'], $data[$i]['stock_issue_list_id'], $data[$i]['product_id'], $data[$i]['qty']);
                }else if($data[$i]['transaction_type']=='2_credit'){
                    $this->addCreditNote($data[$i]['stock_date'], $data[$i]['stock_group_id'], $data[$i]['credit_note_list_id'], $data[$i]['product_id'], $data[$i]['qty'], round($data[$i]['total']/$data[$i]['qty'],2), $data[$i]['total']);
                }else if($data[$i]['transaction_type']=='9_credit_supplier'){
                    $this->addCreditNoteSupplier($data[$i]['stock_date'], $data[$i]['stock_group_id'], $data[$i]['credit_note_supplier_list_id'], $data[$i]['product_id'], $data[$i]['qty']);
                }
            }
        }
        

        // ทำการดึงข้อมูล การซื้อ การย้ายคลังสินค้า การขาย 

        $sql = "SELECT tb_stock_issue.*, SUM(stock_issue_list_total) as stock_issue_list_total 
        FROM tb_stock_issue 
        LEFT JOIN tb_stock_issue_list ON tb_stock_issue.stock_issue_id = tb_stock_issue_list.stock_issue_id
        WHERE STR_TO_DATE(stock_issue_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$start_date','%d-%m-%Y %H:%i:%s') 
        GROUP BY tb_stock_issue.stock_issue_id
        
        ";
        $data = [];
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            while( $row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            $str = ""; 
            for($i=0;$i<count($data);$i++){
                $stock_issue_profit=$data[$i]['stock_issue_invoice_cost'] - $data[$i]['stock_issue_list_total'];
                if($data[$i]['stock_issue_list_total'] != 0){
                    $stock_issue_profit_percent = round(($stock_issue_profit/$data[$i]['stock_issue_list_total'])*100,2);
                }else{
                    $stock_issue_profit_percent = round(($stock_issue_profit/1)*100,2);

                }
                

                $str="UPDATE tb_stock_issue SET 
                stock_issue_total_cost ='".$data[$i]['stock_issue_list_total']."',
                stock_issue_profit ='".$stock_issue_profit."',
                stock_issue_profit_percent ='".$stock_issue_profit_percent."'
                WHERE stock_issue_id = '".$data[$i]['stock_issue_id']."'
                ";
                
                mysqli_query(static::$db,$str, MYSQLI_USE_RESULT); 
            }

        }
        
        
        

        

    }

    
    //##########################################################################################################
    //
    //####################################### ตรวจสอบและสร้างรายการรายงานคลังสินค้า #################################
    //
    //##########################################################################################################

    function createRowStockReport($stock_group_id,$product_id){
        $sql = "SELECT COUNT(*) as check_row 
        FROM tb_stock_report 
        WHERE tb_stock_report.stock_group_id = '$stock_group_id' 
        AND tb_stock_report.product_id = '$product_id' ;";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $stock_report = mysqli_fetch_array($result,MYSQLI_ASSOC) ;
            $result->close();

            
            if($stock_report['check_row'] == 0){
                $sql = "INSERT INTO tb_stock_report (
                    stock_group_id,
                    product_id
                ) VALUES ('".
                $stock_group_id . "','".
                $product_id . "'".
                "); "; 

                mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
            }
        }

    }






    //##########################################################################################################
    //
    //############################# คำนวนจำนวน และ ต้นทุนสินค้าใหม่เมือเพิ่มรายรับสินค้าเข้า ###############################
    //
    //##########################################################################################################

    function calculatePurchaseCostIn($stock_group_id, $product_id, $qty, $cost,$total=0){
        $sql = "SELECT stock_report_qty , stock_report_cost_avg, stock_report_cost_avg_total  
        FROM tb_stock_report
        WHERE stock_group_id = '$stock_group_id' 
        AND product_id = '$product_id' ;"; 
       

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $stock_report = mysqli_fetch_array($result,MYSQLI_ASSOC) ;
            $result->close();


            $stock_qty = $stock_report['stock_report_qty'];
            $stock_cost = round($stock_report['stock_report_cost_avg'],2);
            $stock_cost_total = round($stock_report['stock_report_cost_avg_total'],2);



            $new_qty = $stock_qty + $qty;
            $new_cost_total = round($stock_cost_total + $total,2);
            if($new_qty > 0){
                $new_cost = round($new_cost_total / $new_qty,2);
            }else{
                $new_cost = 0;
                $new_cost_total = 0;
            }
            
 
            $sql = "UPDATE tb_stock_report 
                    SET stock_report_qty = '$new_qty', 
                        stock_report_cost_avg = '$new_cost', 
                        stock_report_cost_avg_total = '$new_cost_total' 
                    WHERE stock_group_id = '$stock_group_id' 
                    AND product_id = '$product_id' ; "; 
 

            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 

            $stock_report['stock_report_qty'] = $new_qty;
            $stock_report['stock_report_cost_avg'] = $new_cost;
            $stock_report['stock_report_cost_avg_total'] = $new_cost_total;
            return $stock_report;
        }
    }






    //##########################################################################################################
    //
    //############################# คำนวนจำนวน และ ต้นทุนสินค้าใหม่เมือลบรายรับสินค้าเข้า ###############################
    //
    //##########################################################################################################

    function calculatePurchaseCostOut($stock_group_id, $product_id, $qty, $cost){
        $sql = "SELECT stock_report_qty , stock_report_cost_avg  
        FROM tb_stock_report
        WHERE stock_group_id = '$stock_group_id' 
        AND product_id = '$product_id' ;";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $stock_report = mysqli_fetch_array($result,MYSQLI_ASSOC) ;
            $result->close();

            $stock_qty = $stock_report['stock_report_qty'];
            $stock_cost = round($stock_report['stock_report_cost_avg'],2);

            $new_qty = $stock_qty - $qty;

            if($new_qty == 0){
                $new_cost = 0;
            }else{
                $new_cost = round((($stock_qty * $stock_cost) - ($qty * $cost))/$new_qty,2);
            }
            
 
            $sql = "UPDATE tb_stock_report 
                    SET stock_report_qty = '$new_qty', 
                        stock_report_cost_avg = '$new_cost' 
                    WHERE stock_group_id = '$stock_group_id' 
                    AND product_id = '$product_id' ; "; 

            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 
            
        }
    }





    //##########################################################################################################
    //
    //############################ คำนวนจำนวน และ ต้นทุนสินค้าใหม่เมือเพิ่มรายการขายสินค้า ###############################
    //
    //##########################################################################################################

    function calculateSaleCostIn($stock_group_id, $product_id, $qty){
        $sql = "SELECT stock_report_qty , stock_report_cost_avg  , stock_report_cost_avg_total 
        FROM tb_stock_report
        WHERE stock_group_id = '$stock_group_id' 
        AND product_id = '$product_id' ;";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $stock_report = mysqli_fetch_array($result,MYSQLI_ASSOC) ;
            $result->close();

            $stock_qty = $stock_report['stock_report_qty'];
            $stock_cost =round($stock_report['stock_report_cost_avg'],2);
            $stock_cost_total = round($stock_report['stock_report_cost_avg_total'],2);


            $new_qty = $stock_qty - $qty; 
            

            if($new_qty < 1){

                $cost_total_out = round($stock_cost_total,2) ;
                $cost_out = round($stock_cost,2) ;

                $new_cost_total = 0 ;
                $new_cost = 0 ;

            }else{

                $cost_total_out = round(($stock_cost * $qty),2);
                $cost_out = round($stock_cost,2) ;

                $new_cost_total = round($stock_cost_total -  $cost_total_out,2);
                $new_cost = round($new_cost_total / $new_qty,2) ;

            }


            $sql = "UPDATE tb_stock_report 
                    SET stock_report_qty = '$new_qty', 
                        stock_report_cost_avg = '$new_cost',
                        stock_report_cost_avg_total = '$new_cost_total' 
                    WHERE stock_group_id = '$stock_group_id' 
                    AND product_id = '$product_id' ; "; 


            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 

            $stock_report['stock_report_qty'] = $new_qty;
            $stock_report['stock_report_cost_avg_out'] = round($cost_out,2);  
            $stock_report['stock_report_cost_avg_total_out'] = round($cost_total_out,2); 
            $stock_report['stock_report_cost_avg'] = round($new_cost,2);  
            $stock_report['stock_report_cost_avg_total'] = round($new_cost_total,2); 

            return $stock_report;
        }else{
            return 0;
        }
    }





    //##########################################################################################################
    //
    //############################ คำนวนจำนวน และ ต้นทุนสินค้าใหม่เมือลบรายการขายสินค้า ###############################
    //
    //##########################################################################################################

    function calculateSaleCostOut($stock_group_id, $product_id, $qty, $cost){
        $sql = "SELECT stock_report_qty , stock_report_cost_avg  
        FROM tb_stock_report
        WHERE stock_group_id = '$stock_group_id' 
        AND product_id = '$product_id' ;";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $stock_report = mysqli_fetch_array($result,MYSQLI_ASSOC) ;
            $result->close();

            $stock_qty = round($stock_report['stock_report_qty'],2);
            $stock_cost = round($stock_report['stock_report_cost_avg'],2);

            $new_qty = $stock_qty + $qty;
            if($new_qty == 0){
                $new_cost = 0 ;
            }else{
                $new_cost = round((($stock_qty * $stock_cost) + ($qty * $cost))/$new_qty,2);
            }
            
 
            $sql = "UPDATE tb_stock_report 
                    SET stock_report_qty = '$new_qty', 
                        stock_report_cost_avg = '$new_cost' 
                    WHERE stock_group_id = '$stock_group_id' 
                    AND product_id = '$product_id' ; "; 

            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 
        }
    }









    //##########################################################################################################
    //
    //##################################### ดึงข้อมูลคลังสินค้าจาก stock_group_id ###################################
    //
    //##########################################################################################################

    function getStockGroupTable($stock_group_id){
        if($stock_group_id != 0){
            $sql ="SELECT `table_name`,`stock_group_id`  
            FROM tb_stock_group 
            WHERE tb_stock_group.stock_group_id = '$stock_group_id' ";
        }else{
            $sql ="SELECT `table_name`,`stock_group_id`  
            FROM tb_stock_group 
            WHERE tb_stock_group.stock_group_primary = '1' ";
        }
 

        $stock = [];
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $stock = mysqli_fetch_array($result,MYSQLI_ASSOC) ;
            $result->close();

            return $stock;
        }else{

        }
    }






    //##########################################################################################################
    //
    //################################## ทำการคำณวนต้นทุนเมื่อเพิ่มรายการสินค้ายกยอดมา ################################
    //
    //##########################################################################################################

    function addSummitProduct($stock_date, $stock_group_id = 0, $summit_product_id, $product_id, $qty, $cost, $total){

        $stock = $this->getStockGroupTable($stock_group_id);

        $this->createRowStockReport($stock['stock_group_id'],$product_id);
        $stock_report = $this->calculatePurchaseCostIn($stock['stock_group_id'], $product_id, $qty, $cost,$total);
 
        $sql = "INSERT INTO ". $stock['table_name'] ." (
            stock_type,
            product_id, 
            stock_date,
            in_qty, 
            in_stock_cost_avg,
            in_stock_cost_avg_total,
            out_qty, 
            out_stock_cost_avg,
            out_stock_cost_avg_total,
            balance_qty, 
            balance_stock_cost_avg,
            balance_stock_cost_avg_total,
            summit_product_id
        ) VALUE ('".
        "in"."','".
        $product_id."','".
        $stock_date."','".
        $qty."','".
        $cost."','".
        $total."','".
        (0)."','".
        (0)."','".
        (0)."','".
        ($stock_report['stock_report_qty'])."','".
        ($stock_report['stock_report_cost_avg'])."','".
        ($stock_report['stock_report_cost_avg_total'])."','".
        $summit_product_id."'); ";   
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }  







    //##########################################################################################################
    //
    //################################## ทำการคำณวนต้นทุนเมื่อลบรายการสินค้ายกยอดมา ################################
    //
    //##########################################################################################################

    function removeSummitProduct($stock_group_id, $summit_product_id, $product_id, $qty, $cost){
        $stock = $this->getStockGroupTable($stock_group_id); 

        $this->createRowStockReport($stock['stock_group_id'],$product_id);
        $stock_report = $this->calculatePurchaseCostOut($stock['stock_group_id'], $product_id, $qty, $cost);

        $sql = "DELETE FROM ".$stock['table_name']." WHERE summit_product_id ='".$summit_product_id."'";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 
    }  







    //##########################################################################################################
    //
    //###################################### ทำการคำณวนต้นทุนเมื่อเพิ่มรายการรับสินค้า #################################
    //
    //##########################################################################################################

    function addPurchase($stock_date, $stock_group_id = 0, $invoice_supplier_list_id, $product_id, $qty, $cost, $total){

        $stock = $this->getStockGroupTable($stock_group_id);

        $this->createRowStockReport($stock['stock_group_id'],$product_id);
        $stock_report = $this->calculatePurchaseCostIn($stock['stock_group_id'], $product_id, $qty, $cost, $total);
 
        $sql = "INSERT INTO ". $stock['table_name'] ." (
            stock_type,
            product_id, 
            stock_date,
            in_qty, 
            in_stock_cost_avg,
            in_stock_cost_avg_total,
            out_qty, 
            out_stock_cost_avg,
            out_stock_cost_avg_total,
            balance_qty, 
            balance_stock_cost_avg,
            balance_stock_cost_avg_total,
            invoice_supplier_list_id
            ) VALUE ('".
        "in"."','".
        $product_id."','".
        $stock_date."','".
        $qty."','".
        $cost."','".
        $total."','".
        (0)."','".
        (0)."','".
        (0)."','".
        ($stock_report['stock_report_qty'])."','".
        ($stock_report['stock_report_cost_avg'])."','".
        ($stock_report['stock_report_cost_avg_total'])."','".
        $invoice_supplier_list_id."'); "; 

        

        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
 
    }   


    
    //##########################################################################################################
    //
    //###################################### ทำการคำณวนต้นทุนเมื่อเพิ่มรายการรับสินค้า #################################
    //
    //##########################################################################################################

    function addStockProductSet($stock_date, $stock_group_id = 0, $stock_product_set_id, $product_id, $qty, $cost, $total){
        // echo "<pre>".$qty."</pre>";
        $stock = $this->getStockGroupTable($stock_group_id); 
        $this->createRowStockReport($stock['stock_group_id'],$product_id);
        $stock_report = $this->calculatePurchaseCostIn($stock['stock_group_id'], $product_id, $qty, $cost, $total);
 
        $sql = "INSERT INTO ". $stock['table_name'] ." (
            stock_type,
            product_id, 
            stock_date,
            in_qty, 
            in_stock_cost_avg,
            in_stock_cost_avg_total,
            out_qty, 
            out_stock_cost_avg,
            out_stock_cost_avg_total,
            balance_qty, 
            balance_stock_cost_avg,
            balance_stock_cost_avg_total,
            stock_product_set_id
            ) VALUE ('".
        "in"."','".
        $product_id."','".
        $stock_date."','".
        $qty."','".
        $cost."','".
        $total."','".
        (0)."','".
        (0)."','".
        (0)."','".
        ($stock_report['stock_report_qty'])."','".
        ($stock_report['stock_report_cost_avg'])."','".
        ($stock_report['stock_report_cost_avg_total'])."','".
        $stock_product_set_id."'); "; 

        // echo "<pre>".$sql."</pre>";

        

        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
 
    }   






    //##########################################################################################################
    //
    //###################################### ทำการคำณวนต้นทุนเมื่อลบรายการรับสินค้า #################################
    //
    //##########################################################################################################

    function removePurchase($stock_group_id, $invoice_supplier_list_id, $product_id, $qty, $cost){
        $stock = $this->getStockGroupTable($stock_group_id); 

        $this->createRowStockReport($stock['stock_group_id'],$product_id);
        $stock_report = $this->calculatePurchaseCostOut($stock['stock_group_id'], $product_id, $qty, $cost);

        $sql = "DELETE FROM ".$stock['table_name']." WHERE invoice_supplier_list_id ='".$invoice_supplier_list_id."'";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);  

    } 







    //##########################################################################################################
    //
    //###################################### ทำการคำณวนต้นทุนเมื่อเพิ่มรายการขายสินค้า #################################
    //
    //##########################################################################################################

    function addSaleStock($stock_date, $stock_group_id, $invoice_customer_list_id, $product_id, $qty){ 
        $stock = $this->getStockGroupTable($stock_group_id); 
        $this->createRowStockReport($stock['stock_group_id'],$product_id);
        $stock_report = $this->calculateSaleCostIn($stock['stock_group_id'],$product_id,$qty); 
 
        $sql = "INSERT INTO ". $stock['table_name'] ." (
            stock_type,
            product_id, 
            stock_date,
            in_qty, 
            in_stock_cost_avg,
            in_stock_cost_avg_total,
            out_qty, 
            out_stock_cost_avg,
            out_stock_cost_avg_total,
            balance_qty, 
            balance_stock_cost_avg,
            balance_stock_cost_avg_total,
            invoice_customer_list_id
            ) VALUE ('".
        "out"."','".
        $product_id."','".
        $stock_date."','".
        (0)."','".
        (0)."','".
        (0)."','".
        $qty."','".
        $stock_report['stock_report_cost_avg_out']."','".
        $stock_report['stock_report_cost_avg_total_out']."','".
        ($stock_report['stock_report_qty'])."','".
        ($stock_report['stock_report_cost_avg'])."','".
        ($stock_report['stock_report_cost_avg_total'])."','".
        $invoice_customer_list_id."'); "; 
        // echo $sql."<br><br>";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    } 

    //##########################################################################################################
    //
    //###################################### ทำการคำณวนต้นทุนเมื่อ  ทำงานใบนำออกสินค้า #################################
    //
    //##########################################################################################################

    function addIssueStock($stock_date, $stock_group_id, $stock_issue_list_id, $product_id, $qty){ 
        $stock = $this->getStockGroupTable($stock_group_id); 
        $this->createRowStockReport($stock['stock_group_id'],$product_id);
        $stock_report = $this->calculateSaleCostIn($stock['stock_group_id'],$product_id,$qty); 
 
        $sql = "INSERT INTO ". $stock['table_name'] ." (
            stock_type,
            product_id, 
            stock_date,
            in_qty, 
            in_stock_cost_avg,
            in_stock_cost_avg_total,
            out_qty, 
            out_stock_cost_avg,
            out_stock_cost_avg_total,
            balance_qty, 
            balance_stock_cost_avg,
            balance_stock_cost_avg_total,
            stock_issue_list_id
            ) VALUE ('".
        "out"."','".
        $product_id."','".
        $stock_date."','".
        (0)."','".
        (0)."','".
        (0)."','".
        $qty."','".
        $stock_report['stock_report_cost_avg_out']."','".
        $stock_report['stock_report_cost_avg_total_out']."','".
        ($stock_report['stock_report_qty'])."','".
        ($stock_report['stock_report_cost_avg'])."','".
        ($stock_report['stock_report_cost_avg_total'])."','".
        $stock_issue_list_id."'); "; 
        //echo $sql."<br><br>";
        
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        
        $sql = "UPDATE tb_stock_issue_list ".
        "SET stock_issue_list_price = '".$stock_report['stock_report_cost_avg_out']."', ".
        "stock_issue_list_total = '".$stock_report['stock_report_cost_avg_total_out']."' ".
        "WHERE stock_issue_list_id = '$stock_issue_list_id' ";

        //echo "<pre>".$sql."</pre>";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    } 

    //##########################################################################################################
    //
    //###################################### ทำการคำณวนต้นทุนเมื่อ  ทำงานใบลดหนี้ซัพพลายเออร์ #################################
    //
    //##########################################################################################################

    function addStockProductSetList($stock_date, $stock_group_id, $stock_product_set_list_id, $product_id, $qty){ 
        $stock = $this->getStockGroupTable($stock_group_id); 
        $this->createRowStockReport($stock['stock_group_id'],$product_id);
        $stock_report = $this->calculateSaleCostIn($stock['stock_group_id'],$product_id,$qty); 
 
        $sql = "INSERT INTO ". $stock['table_name'] ." (
            stock_type,
            product_id, 
            stock_date,
            in_qty, 
            in_stock_cost_avg,
            in_stock_cost_avg_total,
            out_qty, 
            out_stock_cost_avg,
            out_stock_cost_avg_total,
            balance_qty, 
            balance_stock_cost_avg,
            balance_stock_cost_avg_total,
            stock_product_set_list_id
            ) VALUE ('".
        "out"."','".
        $product_id."','".
        $stock_date."','".
        (0)."','".
        (0)."','".
        (0)."','".
        $qty."','".
        $stock_report['stock_report_cost_avg_out']."','".
        $stock_report['stock_report_cost_avg_total_out']."','".
        ($stock_report['stock_report_qty'])."','".
        ($stock_report['stock_report_cost_avg'])."','".
        ($stock_report['stock_report_cost_avg_total'])."','".
        $stock_product_set_list_id."'); "; 
        //  echo $sql."<br><br>";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);


        $sql = "UPDATE tb_stock_product_set_list ".
        "SET stock_product_set_list_cost = '".$stock_report['stock_report_cost_avg_out']."', ".
        "stock_product_set_list_cost_total = '".$stock_report['stock_report_cost_avg_total_out']."' ".
        "WHERE stock_product_set_list_id = '$stock_product_set_list_id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        
    }  

    //##########################################################################################################
    //
    //###################################### ทำการคำณวนต้นทุนเมื่อ  ทำงานใบลดหนี้ซัพพลายเออร์ #################################
    //
    //##########################################################################################################

    function addCreditNoteSupplier($stock_date, $stock_group_id, $credit_note_supplier_list_id, $product_id, $qty){ 
        $stock = $this->getStockGroupTable($stock_group_id); 
        $this->createRowStockReport($stock['stock_group_id'],$product_id);
        $stock_report = $this->calculateSaleCostIn($stock['stock_group_id'],$product_id,$qty); 
 
        $sql = "INSERT INTO ". $stock['table_name'] ." (
            stock_type,
            product_id, 
            stock_date,
            in_qty, 
            in_stock_cost_avg,
            in_stock_cost_avg_total,
            out_qty, 
            out_stock_cost_avg,
            out_stock_cost_avg_total,
            balance_qty, 
            balance_stock_cost_avg,
            balance_stock_cost_avg_total,
            credit_note_supplier_list_id
            ) VALUE ('".
        "out"."','".
        $product_id."','".
        $stock_date."','".
        (0)."','".
        (0)."','".
        (0)."','".
        $qty."','".
        $stock_report['stock_report_cost_avg_out']."','".
        $stock_report['stock_report_cost_avg_total_out']."','".
        ($stock_report['stock_report_qty'])."','".
        ($stock_report['stock_report_cost_avg'])."','".
        ($stock_report['stock_report_cost_avg_total'])."','".
        $credit_note_supplier_list_id."'); "; 
        //  echo $sql."<br><br>";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);


        $sql = "UPDATE tb_credit_note_supplier_list ".
        "SET credit_note_supplier_list_cost = '".$stock_report['stock_report_cost_avg_out']."', ".
        "credit_note_supplier_list_cost_total = '".$stock_report['stock_report_cost_avg_total_out']."' ".
        "WHERE credit_note_supplier_list_id = '$credit_note_supplier_list_id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }  

    //##########################################################################################################
    //
    //###################################### ทำการคำณวนต้นทุนเมื่อลบรายการขายสินค้า #################################
    //
    //##########################################################################################################

    function removeSaleStock($stock_group_id,$invoice_customer_list_id, $product_id, $qty, $cost){
        $stock = $this->getStockGroupTable($stock_group_id); 

        $this->createRowStockReport($stock['stock_group_id'],$product_id);
        $stock_report = $this->calculateSaleCostOut($stock['stock_group_id'], $product_id, $qty, $cost);

        $sql = "DELETE FROM ".$stock['table_name']." WHERE invoice_customer_list_id ='".$invoice_customer_list_id."'";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);  

    }








    //##########################################################################################################
    //
    //###################################### ทำการคำณวนต้นทุนเมื่อเพิ่มรายการย้ายสินค้า #################################
    //
    //##########################################################################################################

    function addMoveStock($stock_date,$stock_group_id_out,$stock_group_id_in,$stock_move_list_id, $product_id, $qty){


        //------------------------------------------------ คำณวนต้นทุนของคลังสินค้าขาออก -------------------------------------------
        $stock_out = $this->getStockGroupTable($stock_group_id_out);

        $this->createRowStockReport($stock_out['stock_group_id'],$product_id);

        $stock_report = $this->calculateSaleCostIn($stock_out['stock_group_id'],$product_id,$qty); 
 
        $sql = "INSERT INTO ". $stock_out['table_name'] ." (
            stock_type,
            product_id, 
            stock_date,
            in_qty, 
            in_stock_cost_avg,
            in_stock_cost_avg_total,
            out_qty, 
            out_stock_cost_avg,
            out_stock_cost_avg_total,
            balance_qty, 
            balance_stock_cost_avg,
            balance_stock_cost_avg_total,
            stock_move_list_id
            ) VALUE ('".
        "out"."','".
        $product_id."','".
        $stock_date."','".
        (0)."','".
        (0)."','".
        (0)."','".
        $qty."','".
        $stock_report['stock_report_cost_avg_out']."','".
        ($stock_report['stock_report_cost_avg_total_out'])."','".
        ($stock_report['stock_report_qty'])."','".
        ($stock_report['stock_report_cost_avg'])."','".
        ($stock_report['stock_report_cost_avg_total'])."','".
        $stock_move_list_id."'); "; 

        //echo "<br><br><b>SQL OUT : </b>".$sql;

        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);




        //------------------------------------------------ คำณวนต้นทุนของคลังสินค้าขาเข้า -------------------------------------------
        $cost = $stock_report['stock_report_cost_avg_out'];
        $total = $stock_report['stock_report_cost_avg_total_out'];
        $stock_in = $this->getStockGroupTable($stock_group_id_in);
        $this->createRowStockReport($stock_in['stock_group_id'],$product_id);
        $stock_report = $this->calculatePurchaseCostIn($stock_in['stock_group_id'], $product_id, $qty, $cost,$total);
 
        $sql = "INSERT INTO ". $stock_in['table_name'] ." (
            stock_type,
            product_id, 
            stock_date,
            in_qty, 
            in_stock_cost_avg,
            in_stock_cost_avg_total,
            out_qty, 
            out_stock_cost_avg,
            out_stock_cost_avg_total,
            balance_qty, 
            balance_stock_cost_avg,
            balance_stock_cost_avg_total,
            stock_move_list_id
            ) VALUE ('".
        "in"."','".
        $product_id."','".
        $stock_date."','".
        $qty."','".
        $cost."','".
        ($total)."','".
        (0)."','".
        (0)."','".
        (0)."','".
        ($stock_report['stock_report_qty'])."','".
        ($stock_report['stock_report_cost_avg'])."','".
        ($stock_report['stock_report_cost_avg_total'])."','".
        $stock_move_list_id."'); "; 

        //echo "<br><br><b>SQL IN : </b>".$sql;

        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);


    } 








    //##########################################################################################################
    //
    //###################################### ทำการคำณวนต้นทุนเมื่อเพิ่มรายการย้ายสินค้า #################################
    //
    //##########################################################################################################

    function removeMoveStock($stock_group_id_out,$stock_group_id_in,$stock_move_list_id, $product_id, $qty, $cost){
        //------------------------------------------------ คำณวนต้นทุนของคลังสินค้าขาออก -------------------------------------------
        $stock_out = $this->getStockGroupTable($stock_group_id_out); 

        $this->createRowStockReport($stock_out['stock_group_id'],$product_id);
        $stock_report = $this->calculateSaleCostOut($stock['stock_group_id'], $product_id, $qty, $cost);

        $sql = "DELETE FROM ".$stock_out['table_name']." WHERE stock_move_list_id ='".$stock_move_list_id."'";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 


        //------------------------------------------------ คำณวนต้นทุนของคลังสินค้าขาเข้า -------------------------------------------
        $stock_in = $this->getStockGroupTable($stock_group_id_in); 

        $this->createRowStockReport($stock_in['stock_group_id'],$product_id);
        $stock_report = $this->calculatePurchaseCostOut($stock_in['stock_group_id'], $product_id, $qty, $cost);

        $sql = "DELETE FROM ".$stock_in['table_name']." WHERE stock_move_list_id ='".$stock_move_list_id."'";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);  



    }



    //##########################################################################################################
    //
    //###################################### ทำการคำณวนต้นทุนเมื่อเพิ่มรายการย้ายสินค้า #################################
    //
    //##########################################################################################################

    function addStockChangeProduct($stock_date,$stock_group_id_out,$stock_group_id_in ,$stock_change_product_list_id, $product_id_old, $product_id_new, $qty){



        //------------------------------------------------ คำณวนต้นทุนของคลังสินค้าขาออก -------------------------------------------
        $stock_out = $this->getStockGroupTable($stock_group_id_out);

        $this->createRowStockReport($stock_out['stock_group_id'],$product_id_old);

        $stock_report = $this->calculateSaleCostIn($stock_out['stock_group_id'],$product_id_old,$qty); 
 
        $sql = "INSERT INTO ". $stock_out['table_name'] ." (
            stock_type,
            product_id, 
            stock_date,
            in_qty, 
            in_stock_cost_avg,
            in_stock_cost_avg_total,
            out_qty, 
            out_stock_cost_avg,
            out_stock_cost_avg_total,
            balance_qty, 
            balance_stock_cost_avg,
            balance_stock_cost_avg_total,
            stock_change_product_list_id
            ) VALUE ('".
        "out"."','".
        $product_id_old."','".
        $stock_date."','".
        (0)."','".
        (0)."','".
        (0)."','".
        $qty."','".
        $stock_report['stock_report_cost_avg_out']."','".
        ($stock_report['stock_report_cost_avg_total_out'])."','".
        ($stock_report['stock_report_qty'])."','".
        ($stock_report['stock_report_cost_avg'])."','".
        ($stock_report['stock_report_cost_avg_total'])."','".
        $stock_change_product_list_id."'); "; 

        //echo $sql ."<br><br>";

        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);


        //------------------------------------------------ คำณวนต้นทุนของคลังสินค้าขาเข้า -------------------------------------------
        $cost = $stock_report['stock_report_cost_avg_out'];
        $total = $stock_report['stock_report_cost_avg_total_out'];
        $stock_in = $this->getStockGroupTable($stock_group_id_in);
        $this->createRowStockReport($stock_in['stock_group_id'],$product_id_new);

        //echo $product_id_new ."<br><br>";
        //echo $qty ."<br><br>";
        //echo $cost ."<br><br>";

        $stock_report = $this->calculatePurchaseCostIn($stock_in['stock_group_id'], $product_id_new, $qty, $cost,$total);
 
        $sql = "INSERT INTO ". $stock_in['table_name'] ." (
            stock_type,
            product_id, 
            stock_date,
            in_qty, 
            in_stock_cost_avg,
            in_stock_cost_avg_total,
            out_qty, 
            out_stock_cost_avg,
            out_stock_cost_avg_total,
            balance_qty, 
            balance_stock_cost_avg,
            balance_stock_cost_avg_total,
            stock_change_product_list_id
            ) VALUE ('".
        "in"."','".
        $product_id_new."','".
        $stock_date."','".
        $qty."','".
        $cost."','".
        $total."','".
        (0)."','".
        (0)."','".
        (0)."','".
        ($stock_report['stock_report_qty'])."','".
        ($stock_report['stock_report_cost_avg'])."','".
        ($stock_report['stock_report_qty'] * $stock_report['stock_report_cost_avg'])."','".
        $stock_change_product_list_id."'); "; 

        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);



        $sql = "UPDATE tb_stock_change_product_list ".
                "SET stock_change_product_list_price = '".$cost."', ".
                "stock_change_product_list_total = '".$total."' ".
                "WHERE stock_change_product_list_id = '$stock_change_product_list_id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        //echo $sql ."<br><br><br><br>";


    } 








    //##########################################################################################################
    //
    //###################################### ทำการคำณวนต้นทุนเมื่อเพิ่มรายการย้ายสินค้า #################################
    //
    //##########################################################################################################

    function removeStockChangeProduct($stock_group_id,$stock_change_product_list_id,  $product_id_old, $product_id_new, $qty, $cost){
        //------------------------------------------------ คำณวนต้นทุนของคลังสินค้าขาออก -------------------------------------------
        $stock_out = $this->getStockGroupTable($stock_group_id); 

        $this->createRowStockReport($stock_out['stock_group_id'],$product_id_old);
        $stock_report = $this->calculateSaleCostOut($stock['stock_group_id'], $product_id_old, $qty, $cost);

        $sql = "DELETE FROM ".$stock_out['table_name']." WHERE stock_change_product_list_id ='".$stock_change_product_list_id."'";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 


        //------------------------------------------------ คำณวนต้นทุนของคลังสินค้าขาเข้า -------------------------------------------
        $stock_in = $this->getStockGroupTable($stock_group_id); 

        $this->createRowStockReport($stock_in['stock_group_id'],$product_id_new);
        $stock_report = $this->calculatePurchaseCostOut($stock_in['stock_group_id'], $product_id_new, $qty, $cost);

        $sql = "DELETE FROM ".$stock_in['table_name']." WHERE stock_change_product_list_id ='".$stock_change_product_list_id."'";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);  



    }



    //##########################################################################################################
    //
    //###################################### ทำการคำณวนต้นทุนเมื่อเพิ่มรายการรับสินค้า #################################
    //
    //##########################################################################################################

    function addCreditNote($stock_date, $stock_group_id = 0, $invoice_supplier_list_id, $product_id, $qty, $cost, $total){

        $stock = $this->getStockGroupTable($stock_group_id);

        $this->createRowStockReport($stock['stock_group_id'],$product_id);
        $stock_report = $this->calculatePurchaseCostIn($stock['stock_group_id'], $product_id, $qty, $cost, $total);
 
        $sql = "INSERT INTO ". $stock['table_name'] ." (
            stock_type,
            product_id, 
            stock_date,
            in_qty, 
            in_stock_cost_avg,
            in_stock_cost_avg_total,
            out_qty, 
            out_stock_cost_avg,
            out_stock_cost_avg_total,
            balance_qty, 
            balance_stock_cost_avg,
            balance_stock_cost_avg_total,
            credit_note_list_id
            ) VALUE ('".
        "in"."','".
        $product_id."','".
        $stock_date."','".
        $qty."','".
        $cost."','".
        $total."','".
        (0)."','".
        (0)."','".
        (0)."','".
        ($stock_report['stock_report_qty'])."','".
        ($stock_report['stock_report_cost_avg'])."','".
        ($stock_report['stock_report_cost_avg_total'])."','".
        $invoice_supplier_list_id."'); "; 

        

        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
 
    }   



}
?>