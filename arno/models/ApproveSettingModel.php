<?php

require_once("BaseModel.php");
class ApproveSettingModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }


    function getApproveSettingBy(){

        $sql = "SELECT * 
        FROM tb_approve_setting 
        LEFT JOIN tb_approve_setting_type ON tb_approve_setting.approve_setting_type_id = tb_approve_setting_type.approve_setting_type_id
        LEFT JOIN tb_approve_setting_license ON tb_approve_setting.approve_setting_license_id = tb_approve_setting_license.approve_setting_license_id
        LEFT JOIN tb_currency ON tb_approve_setting.currency_id = tb_currency.currency_id
        ORDER BY approve_setting_type_name, currency_name, approve_setting_value_min, approve_setting_value_max, approve_setting_license_name, approve_setting_license_value 
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
    

    function getApproveSettingByID($id){
        $sql = " SELECT * 
        FROM tb_approve_setting 
        WHERE approve_setting_id = '$id' 
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

    function getApproveSettingByType($approve_setting_type_id){
        $sql = "SELECT * 
        FROM tb_approve_setting 
        LEFT JOIN tb_approve_setting_type ON tb_approve_setting.approve_setting_type_id = tb_approve_setting_type.approve_setting_type_id
        LEFT JOIN tb_approve_setting_license ON tb_approve_setting.approve_setting_license_id = tb_approve_setting_license.approve_setting_license_id
        LEFT JOIN tb_currency ON tb_approve_setting.currency_id = tb_currency.currency_id
        WHERE tb_approve_setting.approve_setting_type_id = '$approve_setting_type_id' 
        ORDER BY approve_setting_type_name, currency_name, approve_setting_value_min, approve_setting_value_max, approve_setting_license_name, approve_setting_license_value 
        
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

    function getLicenseAndValueBy($approve_setting_type_id,$value,$currency_id = ''){
        $str_currency = "";
        if($currency_id != ''){
            $str_currency = "AND currency_id = '$currency_id' ";
        }
        $sql = " SELECT * 
        FROM tb_approve_setting 
        WHERE approve_setting_type_id = '$approve_setting_type_id' 
        $str_currency  
        AND approve_setting_value_min <= '$value' AND '$value' <= approve_setting_value_max 
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


    function checkCanApproveBy($approve_setting_type_id , $currency_id, $approve_setting_license, $approve_setting_license_value, $value){
        $str_currency = "";
        if($currency_id != ''){
            $str_currency = "AND currency_id = '$currency_id' ";
        }

        $sql = " SELECT * 
        FROM tb_approve_setting 
        WHERE approve_setting_type_id = '$approve_setting_type_id' 
        $str_currency 
        AND approve_setting_license = '$approve_setting_license' AND approve_setting_license_value = '$approve_setting_license_value' 
        AND approve_setting_value_min <= '$value' AND '$value' <= approve_setting_value_max 
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
 

    function updateApproveSettingByID($id,$data = []){
        $sql = " UPDATE tb_approve_setting SET 
        approve_setting_type_id = '".$data['approve_setting_type_id']."',   
        currency_id = '".$data['currency_id']."',   
        approve_setting_value_min	 = '".$data['approve_setting_value_min']."', 
        approve_setting_value_max	 = '".$data['approve_setting_value_max']."', 
        approve_setting_license_id	 = '".$data['approve_setting_license_id']."', 
        approve_setting_license_value = '".$data['approve_setting_license_value']."', 
        approve_setting_notification = '".$data['approve_setting_notification']."'  
        WHERE approve_setting_id = $id 
        ";
        //echo $sql;

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function insertApproveSetting($data = []){
        $sql = " INSERT INTO tb_approve_setting ( 
            approve_setting_type_id , 
            currency_id , 
            approve_setting_value_min , 
            approve_setting_value_max , 
            approve_setting_license_id , 
            approve_setting_license_value , 
            approve_setting_notification  
        ) VALUES (
            '".$data['approve_setting_type_id']."', 
            '".$data['currency_id']."', 
            '".$data['approve_setting_value_min']."', 
            '".$data['approve_setting_value_max']."', 
            '".$data['approve_setting_license_id']."', 
            '".$data['approve_setting_license_value']."', 
            '".$data['approve_setting_notification']."' 
        ); 
        ";

        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }


    function deleteApproveSettingByID($id){
        $sql = " DELETE FROM tb_approve_setting WHERE approve_setting_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>