<?php

require_once("BaseModel.php");
class AssetModel extends BaseModel{

    function __construct(){
       
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);        
        }
        mysqli_set_charset(static::$db,"utf8");
    }

    function getLogin($assetname, $password){
        $assetname = static::$db->real_escape_string($assetname);
        $password = static::$db->real_escape_string($password);

        if ($result = mysqli_query(static::$db,"SELECT * 
        FROM tb_asset LEFT JOIN tb_license ON tb_asset.license_id = tb_license.license_id 
        WHERE asset_assetname = '$assetname' 
        AND asset_password = '$password' ", MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getAssetBy($name = '', $position = '', $email = '', $mobile  = ''){
        $sql = " SELECT asset_id, asset_code, asset_image , CONCAT(tb_asset.asset_name,' ',tb_asset.asset_lastname) as name , asset_mobile, asset_email, asset_position_name, asset_status_name  
        FROM tb_asset LEFT JOIN tb_asset_position ON tb_asset.asset_position_id = tb_asset_position.asset_position_id 
        LEFT JOIN tb_asset_status ON tb_asset.asset_status_id = tb_asset_status.asset_status_id 
        WHERE CONCAT(tb_asset.asset_name,' ',tb_asset.asset_lastname) LIKE ('%$name%') 
        AND asset_position_name LIKE ('%$position%') 
        AND asset_email LIKE ('%$email%') 
        AND asset_mobile LIKE ('%$mobile%') 
        ORDER BY CONCAT(tb_asset.asset_name,' ',tb_asset.asset_lastname) 
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

    function getAssetByAll(){
        $sql = " SELECT * 
        FROM tb_asset  
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
    function getAssetByID($id){
        $sql = " SELECT * 
        FROM tb_asset 
        WHERE asset_id = '$id' 
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


    function getAssetByCode($code){
        $sql = " SELECT * 
        FROM tb_asset 
        WHERE asset_code = '$code' 
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

    function updateAssetByID($id,$data = []){
        $sql = " UPDATE tb_asset SET 
        
        asset_code = '".static::$db->real_escape_string($data['asset_code'])."',  
        asset_name_th = '".static::$db->real_escape_string($data['asset_name_th'])."',  
        asset_name_en = '".static::$db->real_escape_string($data['asset_name_en'])."', 
        asset_category_id = '".static::$db->real_escape_string($data['asset_category_id'])."', 
        asset_account_group_id = '".static::$db->real_escape_string($data['asset_account_group_id'])."', 
        asset_registration_no = '".static::$db->real_escape_string($data['asset_registration_no'])."', 
        asset_department_id = '".static::$db->real_escape_string($data['asset_department_id'])."', 
        asset_depreciate = '".static::$db->real_escape_string($data['asset_depreciate'])."', 
        asset_buy_date = '".static::$db->real_escape_string($data['asset_buy_date'])."', 
        asset_use_date = '".static::$db->real_escape_string($data['asset_use_date'])."', 
        asset_cost_price = '".static::$db->real_escape_string($data['asset_cost_price'])."', 
        asset_scrap_price = '".static::$db->real_escape_string($data['asset_scrap_price'])."', 
        asset_expire = '".static::$db->real_escape_string($data['asset_expire'])."', 
        asset_rate = '".static::$db->real_escape_string($data['asset_rate'])."', 
        asset_depreciate_transfer = '".static::$db->real_escape_string($data['asset_depreciate_transfer'])."', 
        asset_depreciate_manual = '".static::$db->real_escape_string($data['asset_depreciate_manual'])."' ,
        asset_depreciate_initial = '".static::$db->real_escape_string($data['asset_depreciate_initial'])."' ,
        asset_depreciate_manual = '".static::$db->real_escape_string($data['asset_depreciate_manual'])."' ,
        asset_manual_date = '".static::$db->real_escape_string($data['asset_manual_date'])."' ,
        asset_sale_date = '".static::$db->real_escape_string($data['asset_sale_date'])."' ,
        asset_price = '".static::$db->real_escape_string($data['asset_price'])."' ,
        asset_income = '".static::$db->real_escape_string($data['asset_income'])."' ,
        asset_remark = '".static::$db->real_escape_string($data['asset_remark'])."' 
        WHERE asset_id = '".static::$db->real_escape_string($id)."'
        ";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function updateAssetProfileByID($id,$data = []){
        $sql = " UPDATE tb_asset SET 
        
        asset_image = '".static::$db->real_escape_string($data['asset_image'])."',  
        asset_prefix = '".static::$db->real_escape_string($data['asset_prefix'])."', 
        asset_name = '".static::$db->real_escape_string($data['asset_name'])."', 
        asset_lastname = '".static::$db->real_escape_string($data['asset_lastname'])."', 
        asset_mobile = '".static::$db->real_escape_string($data['asset_mobile'])."', 
        asset_email = '".static::$db->real_escape_string($data['asset_email'])."', 
        asset_assetname = '".static::$db->real_escape_string($data['asset_assetname'])."', 
        asset_password = '".static::$db->real_escape_string($data['asset_password'])."', 
        asset_address = '".static::$db->real_escape_string($data['asset_address'])."', 
        asset_province = '".static::$db->real_escape_string($data['asset_province'])."', 
        asset_amphur = '".static::$db->real_escape_string($data['asset_amphur'])."', 
        asset_district = '".static::$db->real_escape_string($data['asset_district'])."', 
        asset_zipcode = '".static::$db->real_escape_string($data['asset_zipcode'])."'
        WHERE asset_id = '".static::$db->real_escape_string($id)."'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }  
    function insertAsset($data = []){
        $sql = " INSERT INTO tb_asset ( 
            asset_code,
            asset_name_th,
            asset_name_en,
            asset_category_id,
            asset_account_group_id,
            asset_registration_no,
            asset_department_id,
            asset_depreciate,
            asset_buy_date,
            asset_use_date,
            asset_cost_price,
            asset_scrap_price,
            asset_expire,
            asset_rate,
            asset_depreciate_transfer,
            asset_depreciate_manual,
            asset_depreciate_initial,
            asset_manual_date,
            asset_sale_date,
            asset_price,
            asset_income,
            asset_remark
            )  VALUES ('". 
            $data['asset_code']."','".
            $data['asset_name_th']."','".
            $data['asset_name_en']."','".
            $data['asset_category_id']."','".
            $data['asset_account_group_id']."','".
            $data['asset_registration_no']."','".
            $data['asset_department_id']."','".
            $data['asset_depreciate']."','".
            $data['asset_buy_date']."','".
            $data['asset_use_date']."','".
            $data['asset_cost_price']."','".
            $data['asset_scrap_price']."','".
            $data['asset_expire']."','".
            $data['asset_rate']."','".
            $data['asset_depreciate_transfer']."','".
            $data['asset_depreciate_manual']."','".
            $data['asset_depreciate_initial']."','".
            $data['asset_manual_date']."','".
            $data['asset_sale_date']."','".
            $data['asset_price']."','".
            $data['asset_income']."','".
            $data['asset_remark']."'); 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return '';
        }

    }
    function deleteAssetByID($id){
        $sql = " DELETE FROM tb_asset WHERE asset_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>