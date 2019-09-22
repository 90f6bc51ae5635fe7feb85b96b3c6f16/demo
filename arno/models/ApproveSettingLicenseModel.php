<?php

require_once("BaseModel.php");
class ApproveSettingLicenseModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getApproveSettingLicenseBy($name = ''){
        $sql = "SELECT * FROM tb_approve_setting_license WHERE  approve_setting_license_name LIKE ('%$name%') 
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

    function getApproveSettingLicenseByID($id){
        $sql = " SELECT * 
        FROM tb_approve_setting_license 
        WHERE approve_setting_license_id = '$id' 
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

    function updateApproveSettingLicenseByID($id,$data = []){
        $sql = " SELECT * 
        FROM tb_approve_setting_license 
        WHERE approve_setting_license_id = '$id' 
        ";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_NUM);
            $result->close();
            return $row;
        }

    }


    function deleteApproveSettingLicenseByID($id){
        $sql = " DELETE FROM tb_approve_setting_license WHERE approve_setting_license_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>