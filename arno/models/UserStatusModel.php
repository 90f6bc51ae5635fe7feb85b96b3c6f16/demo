<?php

require_once("BaseModel.php");
class UserStatusModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getUserStatusBy($name = ''){
        $sql = "SELECT * FROM tb_user_status WHERE  user_status_name LIKE ('%$name%') 
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

    function getUserStatusByID($id){
        $sql = " SELECT * 
        FROM tb_user_status 
        WHERE user_status_id = '$id' 
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

    function updateUserStatusByID($id,$data = []){
        $sql = " SELECT * 
        FROM tb_user_status 
        WHERE user_status_id = '$id' 
        ";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_NUM);
            $result->close();
            return $row;
        }

    }


    function deleteUserStatusByID($id){
        $sql = " DELETE FROM tb_user_status WHERE user_status_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>