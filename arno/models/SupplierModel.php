<?php

require_once("BaseModel.php");
class SupplierModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db,"utf8");
    }


    function getSupplierByKeyword($keyword=''){
        $str_domestic = ""; 
        $sql = " SELECT supplier_id, supplier_code, supplier_name_th, supplier_name_en , supplier_tax , supplier_tel, supplier_email   
        FROM tb_supplier 
        WHERE ( 
                supplier_code LIKE ('%$keyword%') 
                OR supplier_name_en LIKE ('%$keyword%') 
                OR supplier_name_th LIKE ('%$keyword%')  
              ) 
        ORDER BY supplier_code 
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


    function getSupplierBy($supplier_domestic="",$keyword=''){
        $str_domestic = "";
        if($supplier_domestic != ""){
            $str_domestic = "AND supplier_domestic = '$supplier_domestic' ";
        }
        $sql = " SELECT supplier_id, supplier_code, supplier_name_th, supplier_name_en , supplier_tax , supplier_tel, supplier_email   
        FROM tb_supplier 
        WHERE ( 
                supplier_code LIKE ('%$keyword%') 
                OR supplier_name_en LIKE ('%$keyword%') 
                OR supplier_name_th LIKE ('%$keyword%')  
              )
              $str_domestic
        ORDER BY supplier_name_en 
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
    
    function getSupplierProductBy($supplier_id){
        $sql = " SELECT * 
        FROM `tb_product_supplier` 
        LEFT JOIN tb_product ON tb_product_supplier.product_id = tb_product.product_id
        LEFT JOIN tb_invoice_supplier ON tb_product_supplier.supplier_id = tb_invoice_supplier.supplier_id
        LEFT JOIN tb_user ON tb_invoice_supplier.employee_id = tb_user.user_id

        WHERE tb_product_supplier.supplier_id = $supplier_id
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
    
    function getSupplierProductCusBy($supplier_id){
        $sql = " SELECT * 
        FROM `tb_product_supplier` 
        LEFT JOIN tb_product ON tb_product_supplier.product_id = tb_product.product_id
        LEFT JOIN  tb_purchase_order ON tb_product_supplier.supplier_id = tb_purchase_order.supplier_id
        LEFT JOIN tb_user ON tb_purchase_order.employee_id = tb_user.user_id

        WHERE tb_product_supplier.supplier_id = $supplier_id
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



    function getSupplierCodeIndexByChar($char){
        $sql = " SELECT IFNULL(MAX(CAST(RIGHT(supplier_code,3) AS SIGNED )),0) as supplier_code  
        FROM tb_supplier 
        WHERE supplier_code LIKE '$char%' 
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

    function getSupplierByID($id){
        $sql = " SELECT * 
        FROM tb_supplier 
        LEFT JOIN tb_currency ON tb_supplier.currency_id = tb_currency.currency_id 
        WHERE supplier_id = '$id' 
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

    function getSupplierByCode($code){
        $sql = " SELECT * 
        FROM tb_supplier 
        LEFT JOIN tb_currency ON tb_supplier.currency_id = tb_currency.currency_id 
        WHERE supplier_code = '$code' 
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

    function updateSupplierByID($id,$data = []){
        $sql = " UPDATE tb_supplier SET  
        supplier_code = '".$data['supplier_code']."', 
        supplier_name_th = '".$data['supplier_name_th']."', 
        supplier_name_en = '".$data['supplier_name_en']."', 
        supplier_type = '".$data['supplier_type']."', 
        supplier_tax = '".$data['supplier_tax']."', 
        supplier_address_1 = '".$data['supplier_address_1']."', 
        supplier_address_2 = '".$data['supplier_address_2']."', 
        supplier_address_3 = '".$data['supplier_address_3']."', 
        supplier_zipcode = '".$data['supplier_zipcode']."', 
        supplier_tel = '".$data['supplier_tel']."', 
        supplier_fax = '".$data['supplier_fax']."', 
        supplier_email = '".$data['supplier_email']."', 
        supplier_email_2 = '".$data['supplier_email_2']."', 
        supplier_email_3 = '".$data['supplier_email_3']."', 
        supplier_domestic = '".$data['supplier_domestic']."', 
        supplier_remark = '".$data['supplier_remark']."', 
        supplier_branch = '".$data['supplier_branch']."', 
        supplier_zone = '".$data['supplier_zone']."', 
        credit_day = '".$data['credit_day']."', 
        condition_pay = '".$data['condition_pay']."', 
        pay_limit = '".$data['pay_limit']."' , 
        account_id = '".$data['account_id']."', 
        vat_type = '".$data['vat_type']."', 
        vat = '".$data['vat']."', 
        currency_id = '".$data['currency_id']."' , 
        supplier_logo = '".$data['supplier_logo']."' , 
        updateby = '".$data['updateby']."',  
        lastupdate = NOW() 
        WHERE supplier_id = '$id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function updateSupplierAcceptByID($id,$data = []){
        $sql = " UPDATE tb_supplier SET 
        supplier_accept_status = '".$data['supplier_accept_status']."', 
        supplier_accept_by = '".$data['supplier_accept_by']."', 
        supplier_accept_date = NOW(),  
        updateby = '".$data['updateby']."', 
        lastupdate = NOW() 
        WHERE supplier_id = '$id' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function insertSupplier($data = []){
        $sql = " INSERT INTO tb_supplier ( 
            supplier_code,
            supplier_name_th,
            supplier_name_en,
            supplier_type,
            supplier_tax,
            supplier_address_1,
            supplier_address_2,
            supplier_address_3,
            supplier_zipcode,
            supplier_tel, 
            supplier_fax, 
            supplier_email, 
            supplier_email_2, 
            supplier_email_3, 
            supplier_domestic, 
            supplier_remark, 
            supplier_branch, 
            supplier_zone, 
            credit_day, 
            condition_pay,  
            pay_limit, 
            account_id, 
            vat_type, 
            vat,  
            currency_id,
            supplier_logo,
            addby,
            adddate
        ) VALUES ( 
            '".$data['supplier_code']."', 
            '".$data['supplier_name_th']."', 
            '".$data['supplier_name_en']."', 
            '".$data['supplier_type']."', 
            '".$data['supplier_tax']."', 
            '".$data['supplier_address_1']."', 
            '".$data['supplier_address_2']."', 
            '".$data['supplier_address_3']."', 
            '".$data['supplier_zipcode']."', 
            '".$data['supplier_tel']."', 
            '".$data['supplier_fax']."', 
            '".$data['supplier_email']."', 
            '".$data['supplier_email_2']."', 
            '".$data['supplier_email_3']."', 
            '".$data['supplier_domestic']."', 
            '".$data['supplier_remark']."', 
            '".$data['supplier_branch']."', 
            '".$data['supplier_zone']."', 
            '".$data['credit_day']."', 
            '".$data['condition_pay']."',  
            '".$data['pay_limit']."', 
            '".$data['account_id']."', 
            '".$data['vat_type']."', 
            '".$data['vat']."',  
            '".$data['currency_id']."', 
            '".$data['supplier_logo']."',    
            '".$data['addby']."', 
            NOW()  
        ); 
        ";

        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }


    function deleteSupplierByID($id){
        $sql = " DELETE FROM tb_supplier WHERE supplier_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

}
?>