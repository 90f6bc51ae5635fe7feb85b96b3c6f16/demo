<?php
    require_once("BaseModel.php");
    class StatusServiceModel extends BaseModel{
        function __construct(){
            if(!static::$db){
                static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
            }
        }
        function getStatusServiceBy(){
            $sql = "SELECT * FROM tb_status_service  ";
            if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                $data = [];
                while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                    $data[] = $row;
                }
                $result->close();
                return $data;
            }
        }

        function getStatusServiceByID($id){
            $sql = " SELECT *
                FROM tb_status_service   
                WHERE status_service_id = '$id' 
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

    }
?>