
<?php

require_once("BaseModel.php");
class RegrindSupplierSettingModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getRegrindSupplierSettingBy(){
        $sql = " SELECT *     
        FROM tb_regrind_supplier_setting 
        LEFT JOIN tb_product ON (tb_regrind_supplier_setting.product_id = tb_product.product_id) 
        LEFT JOIN tb_customer ON (tb_regrind_supplier_setting.customer_id = tb_customer.customer_id)  
        LEFT JOIN tb_supplier ON (tb_regrind_supplier_setting.supplier_id = tb_supplier.supplier_id)  
        LEFT JOIN tb_user ON (tb_regrind_supplier_setting.employee_id = tb_user.user_id)  
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
    function getRegrindSupplierSettingByID($product_id,$customer_id,$supplier_id,$employee_id ){
    
        $sql = " SELECT * 
        FROM tb_regrind_supplier_setting 
        WHERE product_id = '$product_id' AND customer_id = '$customer_id' AND supplier_id = '$supplier_id' AND employee_id = '$employee_id'
        "; 
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data =[];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }
    function getRegrindSupplierSettingViewBy($customer_id='',$supplier_id='',$employee_id='',$product_id ='' ){
        $str_product ='';
        // if($customer_id != ""){
        //     $str_customer = " AND customer_id = '$customer_id' ";
        // }
        // if($supplier_id != ""){
        //     $str_supplier = " AND supplier_id = '$supplier_id' ";
        // }
        // if($supplier_id != ""){
        //     $str_supplier = " AND supplier_id = '$supplier_id' ";
        // }
        // if(is_array($data)){ 
        //     for($i=0; $i < count($data) ;$i++){
        //         $str .= $data[$i];
        //         if($i + 1 < count($data)){
        //             $str .= ',';
        //         }
        //     }
        // }else if ($data != ''){
        //     $str = $data;
        // }else{
        //     $str='0';
        // }

        // if($product_id != ""){
        //     $str_product = " AND product_id = '$product_id' ";
        // }
    
        $sql = " SELECT 
                    tb_regrind_supplier_setting.product_id,
                    product_code,
                    product_name ,
                    customer_id
                FROM tb_regrind_supplier_setting 
                LEFT JOIN tb_product ON tb_regrind_supplier_setting.product_id = tb_product.product_id 
                WHERE 
                customer_id = '$customer_id'
                AND supplier_id = '$supplier_id' 
                AND employee_id = '$employee_id'
                $str_product
                "; 
        $data =[];
        // return $sql;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) { 
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close(); 
        } 
        return $data;
    }
    function getRegrindSupplierSettingByProductID($customer_id='',$supplier_id='',$employee_id='',$product_id ='' ){
        $str_product =''; 
        $sql = " SELECT *
        FROM tb_regrind_supplier_setting  
        WHERE 
        customer_id = '$customer_id'
        AND supplier_id = '$supplier_id' 
        AND employee_id = '$employee_id'
        AND product_id = '$product_id' 
        "; 
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data =[];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function updateRegrindSupplierSettingByID($data = []){
        $sql = " UPDATE tb_regrind_supplier_setting SET     
        employee_id = '".$data['employee_id']."' 
        WHERE product_id = '".$data['product_id']."' AND customer_id = '".$data['customer_id']."' AND supplier_id = '".$data['supplier_id']."' AND employee_id = '".$data['employee_id']."' 
         
        ";
 
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function insertRegrindSupplierSetting($data = []){ 
        $sql = " INSERT INTO tb_regrind_supplier_setting (
            product_id,
            customer_id,
            supplier_id,
            employee_id
        ) VALUES (
            '".$data['product_id']."', 
            '".$data['customer_id']."', 
            '".$data['supplier_id']."', 
            '".$data['employee_id']."' 
        ); 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }


    function deleteRegrindSupplierSettingByID($customer_id,$supplier_id, $employee_id){
        $sql = " DELETE FROM tb_regrind_supplier_setting  WHERE customer_id = '$customer_id' AND supplier_id = '$supplier_id' AND employee_id = '$employee_id' ";
        // mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
         }else {
             return false;
         }
    }
}
