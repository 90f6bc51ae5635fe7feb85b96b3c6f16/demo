<?php

require_once("BaseModel.php");
class MaintenanceAccountModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db,"utf8");
    }

    function runMaintenance(){

    // 1. ล้างคลังสินค้าทั้งหมด
    // 2. ทำการเอาสินค้ายกยอดมาเพิ่มเข้าไปในคลังสินค้า tb_stock และ tb_stock_report
    // 3. วนรอบตามลำดับวันเริ่มต้นระบบ จนถึง วันล่าสุด
    //      3.1. อัพเดทต้นทุน และจำนวน จากการรับสินค้า RR/RF อ้างอิงวันที่จาก invoice_supplier_date_recieve
    //      3.2. อัพเดทต้นทุน และจำนวน จากการโอนคลังสินค้า Stock move อ้างอิงวันที่จาก stock_move_date
    //      3.3. อัพเดทต้นทุน และจำนวน จากการโอยย้ายสินค้า ไปยัง สินค้าชื่อใหม่
    //      3.4. อัพเดทต้นทุน และจำนวน จากการขายสินค้า Invoice ขาย อ้างอิงวันที่จาก invoice_customer_date

    } 
}
?>