<?php

require_once("BaseModel.php");
class AddressModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

   

    function getProvinceByID($id = ''){
        $sql = " SELECT * FROM tb_province ORDER BY PROVINCE_NAME";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getAmphurByProviceID($id = ''){
        $sql = " SELECT * FROM tb_amphur ";
        if ($id != ''){
            $sql = " SELECT * FROM tb_amphur , tb_province WHERE tb_amphur.PROVINCE_ID = tb_province.PROVINCE_ID AND  PROVINCE_NAME = '$id' ";
        }

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getDistricByAmphurID($id = ''){
        $sql = " SELECT * FROM tb_district ";
        if ($id != ''){
            $sql = " SELECT * FROM tb_district , tb_amphur WHERE tb_district.AMPHUR_ID = tb_amphur.AMPHUR_ID AND  AMPHUR_NAME = '$id' ";
        }

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getZipcodeByAmphurID($id = ''){
        $sql = " SELECT * FROM tb_amphur ";
        if ($id != ''){
            $sql .= " WHERE AMPHUR_NAME = '$id' ";
        }

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