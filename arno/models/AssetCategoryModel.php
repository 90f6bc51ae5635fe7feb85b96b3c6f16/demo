<?php

require_once("BaseModel.php");
class AssetCategoryModel extends BaseModel{

    function __construct(){
       
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);        
        }
        mysqli_set_charset(static::$db,"utf8");
    }
    function getAssetCategoryBy($name = '', $position = '', $email = '', $mobile  = ''){
        $sql = " SELECT asset_category_id, asset_category_code, asset_category_image , CONCAT(tb_asset_category.asset_category_name,' ',tb_asset_category.asset_category_lastname) as name , asset_category_mobile, asset_category_email, asset_category_position_name, asset_category_status_name  
        FROM tb_asset_category LEFT JOIN tb_asset_category_position ON tb_asset_category.asset_category_position_id = tb_asset_category_position.asset_category_position_id 
        LEFT JOIN tb_asset_category_status ON tb_asset_category.asset_category_status_id = tb_asset_category_status.asset_category_status_id 
        WHERE CONCAT(tb_asset_category.asset_category_name,' ',tb_asset_category.asset_category_lastname) LIKE ('%$name%') 
        AND asset_category_position_name LIKE ('%$position%') 
        AND asset_category_email LIKE ('%$email%') 
        AND asset_category_mobile LIKE ('%$mobile%') 
        ORDER BY CONCAT(tb_asset_category.asset_category_name,' ',tb_asset_category.asset_category_lastname) 
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

    function getAssetCategoryByAll(){
        $sql = " SELECT * 
        FROM tb_asset_category  
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
    function getAssetCategoryByID($id){
        $sql = " SELECT * 
        FROM tb_asset_category 
        WHERE asset_category_id = '$id' 
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


    function getAssetCategoryByCode($code){
        $sql = " SELECT * 
        FROM tb_asset_category 
        WHERE asset_category_code = '$code' 
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

    function updateAssetCategoryByID($id,$data = []){
        $sql = " UPDATE tb_asset_category SET 
        
        asset_category_code = '".static::$db->real_escape_string($data['asset_category_code'])."',  
        asset_category_name_th = '".static::$db->real_escape_string($data['asset_category_name_th'])."',  
        asset_category_name_en = '".static::$db->real_escape_string($data['asset_category_name_en'])."'
        WHERE asset_category_id = '".static::$db->real_escape_string($id)."'
        ";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }
    function insertAssetCategory($data = []){
        $sql = " INSERT INTO tb_asset_category ( 
            asset_category_code,
            asset_category_name_th,
            asset_category_name_en
            )  VALUES ('". 
            static::$db->real_escape_string($data['asset_category_code'])."','".
            static::$db->real_escape_string($data['asset_category_name_th'])."','".
            static::$db->real_escape_string($data['asset_category_name_en'])."'); 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return '';
        }

    }


    function deleteAssetCategoryByID($id){
        $sql = " DELETE FROM tb_asset_category WHERE asset_category_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>