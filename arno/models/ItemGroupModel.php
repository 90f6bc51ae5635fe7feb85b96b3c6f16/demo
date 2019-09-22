<?php

require_once("BaseModel.php");
class ItemGroupModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getItemGroupBy($name = ''){
        $sql = "SELECT * FROM tb_item_group WHERE  item_group_name LIKE ('%$name%') 
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

    function getItemGroupByID($id){
        $sql = " SELECT * 
        FROM tb_item_group 
        WHERE item_group_id = '$id' 
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

    function updateItemGroupByID($id,$data = []){
        $sql = " SELECT * 
        FROM tb_item_group 
        WHERE item_group_id = '$id' 
        ";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_NUM);
            $result->close();
            return $row;
        }

    }


    function deleteItemGroupByID($id){
        $sql = " DELETE FROM tb_item_group WHERE item_group_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>