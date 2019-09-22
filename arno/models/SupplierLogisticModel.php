<?php

require_once("BaseModel.php");
class SupplierLogisticModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getSupplierLogisticBy($supplier_id, $supplier_logistic_name = '', $supplier_logistic_detail = '', $supplier_logistic_lead_time = ''){
        $sql = " SELECT supplier_logistic_id, 
        supplier_logistic_name, 
        supplier_logistic_detail , 
        supplier_logistic_lead_time 
        FROM tb_supplier_logistic 
        WHERE supplier_id = $supplier_id
        AND (supplier_logistic_name LIKE ('%$supplier_logistic_name%') 
        OR supplier_logistic_detail LIKE ('%$supplier_logistic_detail%') 
        OR supplier_logistic_lead_time LIKE ('%$supplier_logistic_lead_time%') 
        )
        ORDER BY supplier_logistic_name
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

    function getSupplierLogisticByID($id){
        $sql = " SELECT * 
        FROM tb_supplier_logistic 
        WHERE supplier_logistic_id = '$id' 
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

    function updateSupplierLogisticByID($id,$data = []){
        $sql = " UPDATE tb_supplier_logistic SET 
        supplier_id = '".$data['supplier_id']."', 
        supplier_logistic_name = '".$data['supplier_logistic_name']."', 
        supplier_logistic_detail = '".$data['supplier_logistic_detail']."', 
        supplier_logistic_lead_time = '".$data['supplier_logistic_lead_time']."' 
        WHERE supplier_logistic_id = $id 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function insertSupplierLogistic($data = []){
        $sql = " INSERT INTO tb_supplier_logistic (
            supplier_id,
            supplier_logistic_name, 
            supplier_logistic_detail, 
            supplier_logistic_lead_time
        ) VALUES (
            '".$data['supplier_id']."', 
            '".$data['supplier_logistic_name']."', 
            '".$data['supplier_logistic_detail']."', 
            '".$data['supplier_logistic_lead_time']."'  
        ); 
        ";

        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }


    function deleteSupplierLogisticByID($id){
        $sql = "DELETE FROM tb_supplier_logistic WHERE supplier_logistic_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }
    
}
?>