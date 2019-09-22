<?php

require_once("BaseModel.php");
class UserPositionModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getUserPositionBy($name = ''){
        $sql = "SELECT * FROM tb_user_position WHERE  user_position_name LIKE ('%$name%') 
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

    function getUserPositionByID($id){
        $sql = " SELECT * 
        FROM tb_user_position 
        WHERE user_position_id = '$id' 
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

    function updateUserPositionByID($id,$data = []){
        $sql = " SELECT * 
        FROM tb_user_position 
        WHERE user_position_id = '$id' 
        ";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_NUM);
            $result->close();
            return $row;
        }

    }


    function deleteUserPositionByID($id){
        $sql = " DELETE FROM tb_user_position WHERE user_position_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>