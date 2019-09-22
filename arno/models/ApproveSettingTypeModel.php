<?php

require_once("BaseModel.php");
class ApproveSettingTypeModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getApproveSettingTypeBy($name = ''){
        $sql = "SELECT * FROM tb_approve_setting_type WHERE  approve_setting_type_name LIKE ('%$name%') 
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

    function getApproveSettingTypeByID($id){
        $sql = " SELECT * 
        FROM tb_approve_setting_type 
        WHERE approve_setting_type_id = '$id' 
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

    function updateApproveSettingTypeByID($id,$data = []){
        $sql = " SELECT * 
        FROM tb_approve_setting_type 
        WHERE approve_setting_type_id = '$id' 
        ";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_NUM);
            $result->close();
            return $row;
        }

    }


    function deleteApproveSettingTypeByID($id){
        $sql = " DELETE FROM tb_approve_setting_type WHERE approve_setting_type_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>