<?php

require_once("BaseModel.php");
class AssetAccountGroupModel extends BaseModel{

    function __construct(){
       
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);        
        }
        mysqli_set_charset(static::$db,"utf8");
    }
    function getAssetAccountGroupBy($name = '', $position = '', $email = '', $mobile  = ''){
        $sql = " SELECT asset_account_group_id, asset_account_group_code, asset_account_group_image , CONCAT(tb_asset_account_group.asset_account_group_name,' ',tb_asset_account_group.asset_account_group_lastname) as name , asset_account_group_mobile, asset_account_group_email, asset_account_group_position_name, asset_account_group_status_name  
        FROM tb_asset_account_group LEFT JOIN tb_asset_account_group_position ON tb_asset_account_group.asset_account_group_position_id = tb_asset_account_group_position.asset_account_group_position_id 
        LEFT JOIN tb_asset_account_group_status ON tb_asset_account_group.asset_account_group_status_id = tb_asset_account_group_status.asset_account_group_status_id 
        WHERE CONCAT(tb_asset_account_group.asset_account_group_name,' ',tb_asset_account_group.asset_account_group_lastname) LIKE ('%$name%') 
        AND asset_account_group_position_name LIKE ('%$position%') 
        AND asset_account_group_email LIKE ('%$email%') 
        AND asset_account_group_mobile LIKE ('%$mobile%') 
        ORDER BY CONCAT(tb_asset_account_group.asset_account_group_name,' ',tb_asset_account_group.asset_account_group_lastname) 
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

    function getAssetAccountGroupByAll(){
        $sql = " SELECT * 
        FROM tb_asset_account_group  
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
    function getAssetAccountGroupByID($id){
        $sql = " SELECT * 
        FROM tb_asset_account_group 
        WHERE asset_account_group_id = '$id' 
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


    function getAssetAccountGroupByCode($code){
        $sql = " SELECT * 
        FROM tb_asset_account_group 
        WHERE asset_account_group_code = '$code' 
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

    function updateAssetAccountGroupByID($id,$data = []){
        $sql = " UPDATE tb_asset_account_group SET 
        
        asset_account_group_code = '".static::$db->real_escape_string($data['asset_account_group_code'])."',  
        asset_account_group_name_th = '".static::$db->real_escape_string($data['asset_account_group_name_th'])."',  
        asset_account_group_name_en = '".static::$db->real_escape_string($data['asset_account_group_name_en'])."'
        WHERE asset_account_group_id = '".static::$db->real_escape_string($id)."'
        ";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }
    function insertAssetAccountGroup($data = []){
        $sql = " INSERT INTO tb_asset_account_group ( 
            asset_account_group_code,
            asset_account_group_name_th,
            asset_account_group_name_en
            )  VALUES ('". 
            static::$db->real_escape_string($data['asset_account_group_code'])."','".
            static::$db->real_escape_string($data['asset_account_group_name_th'])."','".
            static::$db->real_escape_string($data['asset_account_group_name_en'])."'); 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return '';
        }

    }


    function deleteAssetAccountGroupByID($id){
        $sql = " DELETE FROM tb_asset_account_group WHERE asset_account_group_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>