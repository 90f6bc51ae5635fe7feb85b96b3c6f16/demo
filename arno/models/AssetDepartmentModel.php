<?php

require_once("BaseModel.php");
class AssetDepartmentModel extends BaseModel{

    function __construct(){
       
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);        
        }
        mysqli_set_charset(static::$db,"utf8");
    }
    function getAssetDepartmentBy($name = '', $position = '', $email = '', $mobile  = ''){
        $sql = " SELECT asset_department_id, asset_department_code, asset_department_image , CONCAT(tb_asset_department.asset_department_name,' ',tb_asset_department.asset_department_lastname) as name , asset_department_mobile, asset_department_email, asset_department_position_name, asset_department_status_name  
        FROM tb_asset_department LEFT JOIN tb_asset_department_position ON tb_asset_department.asset_department_position_id = tb_asset_department_position.asset_department_position_id 
        LEFT JOIN tb_asset_department_status ON tb_asset_department.asset_department_status_id = tb_asset_department_status.asset_department_status_id 
        WHERE CONCAT(tb_asset_department.asset_department_name,' ',tb_asset_department.asset_department_lastname) LIKE ('%$name%') 
        AND asset_department_position_name LIKE ('%$position%') 
        AND asset_department_email LIKE ('%$email%') 
        AND asset_department_mobile LIKE ('%$mobile%') 
        ORDER BY CONCAT(tb_asset_department.asset_department_name,' ',tb_asset_department.asset_department_lastname) 
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

    function getAssetDepartmentByAll(){
        $sql = " SELECT * 
        FROM tb_asset_department  
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
    function getAssetDepartmentByID($id){
        $sql = " SELECT * 
        FROM tb_asset_department 
        WHERE asset_department_id = '$id' 
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


    function getAssetDepartmentByCode($code){
        $sql = " SELECT * 
        FROM tb_asset_department 
        WHERE asset_department_code = '$code' 
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

    function updateAssetDepartmentByID($id,$data = []){
        $sql = " UPDATE tb_asset_department SET 
        
        asset_department_code = '".static::$db->real_escape_string($data['asset_department_code'])."',  
        asset_department_name_th = '".static::$db->real_escape_string($data['asset_department_name_th'])."',  
        asset_department_name_en = '".static::$db->real_escape_string($data['asset_department_name_en'])."'
        WHERE asset_department_id = '".static::$db->real_escape_string($id)."'
        ";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }
    function insertAssetDepartment($data = []){
        $sql = " INSERT INTO tb_asset_department ( 
            asset_department_code,
            asset_department_name_th,
            asset_department_name_en
            )  VALUES ('". 
            static::$db->real_escape_string($data['asset_department_code'])."','".
            static::$db->real_escape_string($data['asset_department_name_th'])."','".
            static::$db->real_escape_string($data['asset_department_name_en'])."'); 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return '';
        }

    }


    function deleteAssetDepartmentByID($id){
        $sql = " DELETE FROM tb_asset_department WHERE asset_department_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>