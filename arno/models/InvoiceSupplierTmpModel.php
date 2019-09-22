<?php

require_once("BaseModel.php"); 
require_once("MaintenanceStockModel.php"); 
class InvoiceSupplierTmpModel extends BaseModel{

    private $maintenance_stock;

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db,"utf8");
        $this->maintenance_stock =  new MaintenanceStockModel;
    }

    function getInvoiceSupplierTmpBy($date_start = "",$date_end = "",$supplier_id = "",$keyword = "",$user_id = "",$begin = '0', $lock_1 = "0", $lock_2 = "0",$supplier_domestic = "" , $sort='DESC'){

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
            $str_date = "AND STR_TO_DATE(invoice_supplier_tmp_date_recieve,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(invoice_supplier_tmp_date_recieve,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(invoice_supplier_tmp_date_recieve,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(invoice_supplier_tmp_date_recieve,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
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
        
        $sql = " SELECT invoice_supplier_tmp_id, 
        invoice_supplier_tmp_code, 
        invoice_supplier_tmp_code_gen, 
        invoice_supplier_tmp_date, 
        invoice_supplier_tmp_date_recieve,  
        invoice_supplier_tmp_total_price, 
        invoice_supplier_tmp_vat_price, 
        invoice_supplier_tmp_net_price,
        invoice_supplier_tmp_file,  
        import_duty, 
        freight_in, 
        supplier_domestic, 
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        invoice_supplier_tmp_term, 
        invoice_supplier_tmp_due, 
        invoice_supplier_tmp_name, 
        invoice_supplier_tmp_close,
        IFNULL(tb2.supplier_name_en,'-') as supplier_name  
        FROM tb_invoice_supplier_tmp 
        LEFT JOIN tb_user as tb1 ON tb_invoice_supplier_tmp.employee_id = tb1.user_id 
        LEFT JOIN tb_supplier as tb2 ON tb_invoice_supplier_tmp.supplier_id = tb2.supplier_id 
        LEFT JOIN tb_paper_lock ON SUBSTRING(tb_invoice_supplier_tmp.invoice_supplier_tmp_date_recieve,3,9)=SUBSTRING(tb_paper_lock.paper_lock_date,3,9) 
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%')  
            OR  invoice_supplier_tmp_code LIKE ('%$keyword%') 
            OR  invoice_supplier_tmp_code_gen LIKE ('%$keyword%') 
            OR  supplier_name_en LIKE ('%$keyword%') 
            OR  invoice_supplier_tmp_name LIKE ('%$keyword%') 
        )  
        $str_domestic
        AND invoice_supplier_tmp_begin = '$begin' 
        $str_lock 
        $str_supplier 
        $str_date 
        $str_user  
        ORDER BY  invoice_supplier_tmp_code_gen $sort 
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

    function getInvoiceSupplierTmpByKeyword( $keyword = "" ){
  
        
        $sql = " SELECT *
        FROM tb_invoice_supplier_tmp 
        LEFT JOIN tb_user  ON tb_invoice_supplier_tmp.employee_id = tb_user.user_id 
        LEFT JOIN tb_supplier  ON tb_invoice_supplier_tmp.supplier_id = tb_supplier.supplier_id  
        WHERE  invoice_supplier_tmp_code_gen LIKE ('%$keyword%')   
        AND invoice_supplier_tmp_begin = '0' 
        ORDER BY  invoice_supplier_tmp_code_gen ASC 
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

    function getInvoiceSupplierTmpByID($id){
        $sql = " SELECT * 
        FROM tb_invoice_supplier_tmp 
        LEFT JOIN tb_supplier ON tb_invoice_supplier_tmp.supplier_id = tb_supplier.supplier_id
        WHERE invoice_supplier_tmp_id = '$id' 
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

    function getInvoiceSupplierTmpByCode($invoice_supplier_tmp_code){
        $sql = " SELECT * 
        FROM tb_invoice_supplier_tmp  
        LEFT JOIN tb_supplier ON tb_invoice_supplier_tmp.supplier_id = tb_supplier.supplier_id
        WHERE invoice_supplier_tmp_code = '$invoice_supplier_tmp_code' 
        AND invoice_supplier_tmp_close = '0' 
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
    
    function getInvoiceSupplierTmpByCodeGen($invoice_supplier_tmp_code){
        $sql = " SELECT * 
        FROM tb_invoice_supplier_tmp  
        LEFT JOIN tb_supplier ON tb_invoice_supplier_tmp.supplier_id = tb_supplier.supplier_id
        WHERE invoice_supplier_tmp_code_gen = '$invoice_supplier_tmp_code' 
        AND invoice_supplier_tmp_close = '0' 
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

    function getInvoiceSupplierTmpViewByID($id ){
        $sql = " SELECT *   
        FROM tb_invoice_supplier_tmp 
        LEFT JOIN tb_user ON tb_invoice_supplier_tmp.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_supplier ON tb_invoice_supplier_tmp.supplier_id = tb_supplier.supplier_id         
        LEFT JOIN tb_currency ON tb_supplier.currency_id = tb_currency.currency_id 
        WHERE invoice_supplier_tmp_id = '$id' 
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


   
    function updateInvoiceSupplierTmpByID($id,$data = []){ 
        $sql = " UPDATE tb_invoice_supplier_tmp SET  
        supplier_id = '".$data['supplier_id']."', 
        employee_id = '".$data['employee_id']."', 
        invoice_supplier_tmp_code = '".static::$db->real_escape_string($data['invoice_supplier_tmp_code'])."', 
        invoice_supplier_tmp_code_gen = '".static::$db->real_escape_string($data['invoice_supplier_tmp_code_gen'])."', 
        invoice_supplier_tmp_file = '".static::$db->real_escape_string($data['invoice_supplier_tmp_file'])."', 
        invoice_supplier_tmp_date = '".static::$db->real_escape_string($data['invoice_supplier_tmp_date'])."', 
        invoice_supplier_tmp_date_recieve = '".static::$db->real_escape_string($data['invoice_supplier_tmp_date_recieve'])."', 
        invoice_supplier_tmp_name = '".static::$db->real_escape_string($data['invoice_supplier_tmp_name'])."', 
        invoice_supplier_tmp_address = '".static::$db->real_escape_string($data['invoice_supplier_tmp_address'])."', 
        invoice_supplier_tmp_tax = '".static::$db->real_escape_string($data['invoice_supplier_tmp_tax'])."', 
        invoice_supplier_tmp_branch = '".static::$db->real_escape_string($data['invoice_supplier_tmp_branch'])."', 
        invoice_supplier_tmp_term = '".static::$db->real_escape_string($data['invoice_supplier_tmp_term'])."', 
        invoice_supplier_tmp_due = '".static::$db->real_escape_string($data['invoice_supplier_tmp_due'])."',  
        invoice_supplier_tmp_due_day = '".static::$db->real_escape_string($data['invoice_supplier_tmp_due_day'])."',  
        invoice_supplier_tmp_begin = '".$data['invoice_supplier_tmp_begin']."', 
        import_duty = '".$data['import_duty']."', 
       
        invoice_supplier_tmp_remark = '".static::$db->real_escape_string($data['invoice_supplier_tmp_remark'])."', 
        invoice_supplier_tmp_stock = '".static::$db->real_escape_string($data['invoice_supplier_tmp_stock'])."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW()  
        WHERE invoice_supplier_tmp_id = '$id' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }


     

    function getInvoiceSupplierTmpByAutoComplete($keyword){
        $sql = " SELECT invoice_supplier_tmp_code_gen,invoice_supplier_tmp_date_recieve 
        FROM tb_invoice_supplier_tmp  
        WHERE invoice_supplier_tmp_begin = '0' 
        AND invoice_supplier_tmp_close = '0'  
        AND invoice_supplier_tmp_code_gen LIKE('%$keyword%')  
    
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


    function getInvoiceSupplierTmpLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(invoice_supplier_tmp_code_gen,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  invoice_supplier_tmp_lastcode 
        FROM tb_invoice_supplier_tmp
        WHERE invoice_supplier_tmp_code_gen LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['invoice_supplier_tmp_lastcode'];
        }

    }



    
    function insertInvoiceSupplierTmp($data = []){
        $sql = " INSERT INTO tb_invoice_supplier_tmp ( 
            supplier_id,
            employee_id,
            invoice_supplier_tmp_code,
            invoice_supplier_tmp_code_gen,
            invoice_supplier_tmp_date,
            invoice_supplier_tmp_date_recieve,
            invoice_supplier_tmp_name,
            invoice_supplier_tmp_address,
            invoice_supplier_tmp_tax, 
            invoice_supplier_tmp_remark, 
            invoice_supplier_tmp_file,
            addby,
            adddate,
            updateby,
            lastupdate) 
        VALUES ('". 
        $data['supplier_id']."','".
        $data['employee_id']."','".
        static::$db->real_escape_string($data['invoice_supplier_tmp_code'])."','".
        static::$db->real_escape_string($data['invoice_supplier_tmp_code_gen'])."','". 
        static::$db->real_escape_string($data['invoice_supplier_tmp_date'])."','".
        static::$db->real_escape_string($data['invoice_supplier_tmp_date_recieve'])."','".
        static::$db->real_escape_string($data['invoice_supplier_tmp_name'])."','".
        static::$db->real_escape_string($data['invoice_supplier_tmp_address'])."','".
        static::$db->real_escape_string($data['invoice_supplier_tmp_tax'])."','". 
        static::$db->real_escape_string($data['invoice_supplier_tmp_remark'])."','". 
        static::$db->real_escape_string($data['invoice_supplier_tmp_file'])."','".
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
        $sql = " UPDATE tb_invoice_supplier_tmp SET 
        supplier_id = '".$data['supplier_id']."',  
        invoice_supplier_tmp_name = '".static::$db->real_escape_string($data['invoice_supplier_tmp_name'])."', 
        invoice_supplier_tmp_address = '".static::$db->real_escape_string($data['invoice_supplier_tmp_address'])."', 
        invoice_supplier_tmp_tax = '".static::$db->real_escape_string($data['invoice_supplier_tmp_tax'])."',  
        invoice_supplier_tmp_term = '".static::$db->real_escape_string($data['invoice_supplier_tmp_term'])."',  
        invoice_supplier_tmp_file = '".static::$db->real_escape_string($data['invoice_supplier_tmp_file'])."',  
        updateby = '".$data['updateby']."', 
        lastupdate = NOW()
        WHERE invoice_supplier_tmp_id = '$id' 
        ";

        //echo $sql;

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }


    function deleteInvoiceSupplierTmpByID($id){ 
        $sql = " DELETE FROM tb_invoice_supplier_tmp WHERE invoice_supplier_tmp_id = '$id' ";
        if(mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)){
            return true;
        }else{
            return false;
        }



    }
    

    function updateInvoiceSupplierTmpCodeByInvoiceID($id,$data = []){
        //print_r($data);
        $sql = " UPDATE tb_invoice_supplier_tmp SET  
        invoice_supplier_tmp_date = '".static::$db->real_escape_string($data['invoice_supplier_tmp_date'])."', 
        invoice_supplier_tmp_code = '".static::$db->real_escape_string($data['invoice_supplier_tmp_code'])."',
        invoice_supplier_tmp_tax = '".static::$db->real_escape_string($data['invoice_supplier_tmp_tax'])."',
        invoice_supplier_tmp_branch = '".static::$db->real_escape_string($data['invoice_supplier_tmp_branch'])."'
        WHERE invoice_supplier_tmp_id = '$id' 
        ";
        // echo $sql .'<br>';
       
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }


    
    function cancelInvoiceSupplierTmpById($id){
        $sql = " UPDATE tb_invoice_supplier_tmp SET 
        invoice_supplier_tmp_close = '1', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE invoice_supplier_tmp_id = '$id' 
        ";

        //echo $sql;

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function uncancelInvoiceSupplierTmpById($id){
        $sql = " UPDATE tb_invoice_supplier_tmp SET 
        invoice_supplier_tmp_close = '0', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW()
        WHERE invoice_supplier_tmp_id = '$id' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }



}
?>