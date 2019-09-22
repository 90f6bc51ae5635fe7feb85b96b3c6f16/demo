<?php

require_once("BaseModel.php");
class UserModel extends BaseModel{

    function __construct(){
       
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);        
        }
        mysqli_set_charset(static::$db,"utf8");
    }

    function getLogin($username, $password){
        $username = static::$db->real_escape_string($username);
        $password = static::$db->real_escape_string($password);

        if ($result = mysqli_query(static::$db,"SELECT * 
        FROM tb_user
        WHERE user_username = '$username' 
        AND user_password = '$password' ", MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }

    }


    function getUserLicenseByID($user_id){
        $user_id = static::$db->real_escape_string($user_id); 

        if ($result = mysqli_query(static::$db,"SELECT * 
        FROM tb_user LEFT JOIN tb_license ON tb_user.license_id = tb_license.license_id 
        WHERE user_id = '$user_id' ", MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getUserBy($name = '', $position = '', $email = '', $mobile  = ''){
        $sql = " SELECT user_id, user_code, user_image ,
        CONCAT(tb_user.user_name,' ',tb_user.user_lastname) as name , user_mobile, user_email, user_position_name, user_status_name  
        FROM tb_user LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_user_status ON tb_user.user_status_id = tb_user_status.user_status_id 
        WHERE CONCAT(tb_user.user_name,' ',tb_user.user_lastname) LIKE ('%$name%') 
        AND user_position_name LIKE ('%$position%') 
        AND user_email LIKE ('%$email%') 
        AND user_mobile LIKE ('%$mobile%') 
        ORDER BY CONCAT(tb_user.user_name,' ',tb_user.user_lastname) 
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

    function getUserByIDArray($id = ''){
        $sql = " SELECT user_id, user_code, user_image , CONCAT(tb_user.user_name,' ',tb_user.user_lastname) as name , user_mobile, user_email, user_position_name, user_status_name  
        FROM tb_user LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_user_status ON tb_user.user_status_id = tb_user_status.user_status_id 
        WHERE user_id = '$id'
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

    function checkPlayer($id){
        $sql = " SELECT `player_id`, `user_id` FROM `tb_player` WHERE player_id = '$id'";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }

    }
    function getUserByID($id){
        $sql = " SELECT * 
        FROM tb_user 
        LEFT JOIN tb_license ON tb_user.license_id = tb_license.license_id 
        WHERE user_id = '$id' 
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

    function getUserByCode($code){
        $sql = " SELECT * 
        FROM tb_user 
        WHERE user_code = '$code' 
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

    function updateUserByID($id,$data = []){
        $sql = " UPDATE tb_user SET 
        
        user_image = '".static::$db->real_escape_string($data['user_image'])."',  
        user_code = '".static::$db->real_escape_string($data['user_code'])."',  
        user_prefix = '".static::$db->real_escape_string($data['user_prefix'])."', 
        user_name = '".static::$db->real_escape_string($data['user_name'])."', 
        user_lastname = '".static::$db->real_escape_string($data['user_lastname'])."', 
        user_mobile = '".static::$db->real_escape_string($data['user_mobile'])."', 
        user_email = '".static::$db->real_escape_string($data['user_email'])."', 
        user_username = '".static::$db->real_escape_string($data['user_username'])."', 
        user_password = '".static::$db->real_escape_string($data['user_password'])."', 
        user_address = '".static::$db->real_escape_string($data['user_address'])."', 
        user_province = '".static::$db->real_escape_string($data['user_province'])."', 
        user_amphur = '".static::$db->real_escape_string($data['user_amphur'])."', 
        user_district = '".static::$db->real_escape_string($data['user_district'])."', 
        user_zipcode = '".static::$db->real_escape_string($data['user_zipcode'])."', 
        user_position_id = '".static::$db->real_escape_string($data['user_position_id'])."',
        license_id = '".static::$db->real_escape_string($data['license_id'])."', 
        user_status_id = '".static::$db->real_escape_string($data['user_status_id'])."' 
        WHERE user_id = '".static::$db->real_escape_string($id)."'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function updateUserProfileByID($id,$data = []){
        $sql = " UPDATE tb_user SET 
        
        user_image = '".static::$db->real_escape_string($data['user_image'])."',  
        user_prefix = '".static::$db->real_escape_string($data['user_prefix'])."', 
        user_name = '".static::$db->real_escape_string($data['user_name'])."', 
        user_lastname = '".static::$db->real_escape_string($data['user_lastname'])."', 
        user_mobile = '".static::$db->real_escape_string($data['user_mobile'])."', 
        user_email = '".static::$db->real_escape_string($data['user_email'])."', 
        user_username = '".static::$db->real_escape_string($data['user_username'])."', 
        user_password = '".static::$db->real_escape_string($data['user_password'])."', 
        user_address = '".static::$db->real_escape_string($data['user_address'])."', 
        user_province = '".static::$db->real_escape_string($data['user_province'])."', 
        user_amphur = '".static::$db->real_escape_string($data['user_amphur'])."', 
        user_district = '".static::$db->real_escape_string($data['user_district'])."', 
        user_zipcode = '".static::$db->real_escape_string($data['user_zipcode'])."'
        WHERE user_id = '".static::$db->real_escape_string($id)."'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    
    function updateUserProfileNoIMGByID($id,$data = []){
        $sql = " UPDATE tb_user SET 
        
        user_prefix = '".static::$db->real_escape_string($data['user_prefix'])."', 
        user_name = '".static::$db->real_escape_string($data['user_name'])."', 
        user_lastname = '".static::$db->real_escape_string($data['user_lastname'])."', 
        user_mobile = '".static::$db->real_escape_string($data['user_mobile'])."', 
        user_email = '".static::$db->real_escape_string($data['user_email'])."', 
        user_username = '".static::$db->real_escape_string($data['user_username'])."', 
        user_password = '".static::$db->real_escape_string($data['user_password'])."', 
        user_address = '".static::$db->real_escape_string($data['user_address'])."', 
        user_province = '".static::$db->real_escape_string($data['user_province'])."', 
        user_amphur = '".static::$db->real_escape_string($data['user_amphur'])."', 
        user_district = '".static::$db->real_escape_string($data['user_district'])."', 
        user_zipcode = '".static::$db->real_escape_string($data['user_zipcode'])."'
        WHERE user_id = '".static::$db->real_escape_string($id)."'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function updateUserSignatureByID($id,$data = []){
        $sql = " UPDATE tb_user SET 
        user_signature = '".$data['user_signature']."' 
        WHERE user_id = '$id'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function updatePlayerIDByID($id,$user_player_id){
        $sql = " UPDATE tb_user SET 
        user_player_id = '".$user_player_id."' 
        WHERE user_id = '$id' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function updateTbPlayer($player_id, $user_id){
        $sql = " UPDATE tb_player SET 
        `user_id` = '".$user_id."' ,
        `player_type` = '1'
        
        WHERE player_id = '$player_id' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function insertUser($data = []){
        $sql = " INSERT INTO tb_user ( 
            user_code,
            user_prefix,
            user_name,
            user_image,
            user_lastname,
            user_mobile,
            user_email,
            user_username,
            user_password,
            user_address,
            user_province,
            user_amphur,
            user_district,
            user_zipcode,
            user_position_id,
            license_id,
            user_status_id
            )  VALUES ('". 
            $data['user_code']."','".
            $data['user_prefix']."','".
            $data['user_name']."','".
            $data['user_image']."','".
            $data['user_lastname']."','".
            $data['user_mobile']."','".
            $data['user_email']."','".
            $data['user_username']."','".
            $data['user_password']."','".
            $data['user_address']."','".
            $data['user_province']."','".
            $data['user_amphur']."','".
            $data['user_district']."','".
            $data['user_zipcode']."','".
            $data['user_position_id']."','".
            $data['license_id']."','".
            $data['user_status_id']."'); 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return '';
        }

    }

    function insertPlayer($player_id, $user_id){
        $sql = " INSERT INTO tb_player ( 
            `player_id`,
            `user_id`,
            `player_type`
            )  VALUES ('". 
            $player_id."','". 
            $user_id."',
            '1'
        ); 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        } 
    }

 

    function deleteUserByID($id){
        $sql = " DELETE FROM tb_user WHERE user_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deletePlayer($id){
        $sql = " DELETE FROM tb_player WHERE player_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
    ////--------------------------------------------------------------------- App Mobile --------------------------------------------------------------------------------///////

    function updateAppUserSignatureByID($id , $data=""){
        $sql = " UPDATE tb_user SET 
        user_signature = '".$data."' 
        WHERE user_id = '$id'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
      
    }
    function updateAppUserPhoneByID($id , $data=""){
        $sql = " UPDATE tb_user SET 
        user_mobile = '".$data."' 
        WHERE user_id = '$id'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
      
    }
    function updateAppUserEmailByID($id , $data){
        $sql = " UPDATE tb_user SET 
        user_email = '".$data."' 
        WHERE user_id = '$id'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
      
    }
    function updateUserImageByID($id , $data){
        $sql = " UPDATE tb_user SET 
        user_image = '".$data."' 
        WHERE user_id = '$id'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
      
    }
    function updateAppUserPasswordByID($id , $data){
        $sql = " UPDATE tb_user SET 
        user_password = '".$data."' 
        WHERE user_id = '$id'
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
      
      
    }

}
?>