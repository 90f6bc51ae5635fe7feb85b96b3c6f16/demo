<?php
    // tb_rate_service
    require_once("BaseModel.php");
    class RateServiceModel extends BaseModel{
        function __construct(){
            if(!static::$db){
                static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
            }
        }
        function getRateServiceBy(){
            $sql = "SELECT * FROM tb_rate_service  ";
            if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                $data = [];
                while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                    $data[] = $row;
                }
                $result->close();
                return $data;
            }
        }

        function getRateServiceByID($id){
            $sql = " SELECT *
                FROM tb_rate_service   
                WHERE rate_service_id = '$id' 
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