<?php

require_once("BaseModel.php");
class CurrencyModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getCurrencyBy($name_th = '',$name_en = ''){
        $sql = "SELECT * 
        FROM tb_currency 
        WHERE  (currency_name LIKE ('%$name_th%') OR currency_country LIKE ('%$name_en%')) 

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
    function getCurrencyByFavorite(){
        $sql = "SELECT * 
        FROM tb_currency 
        ORDER BY currency_favorite DESC, currency_name 
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

    function getCurrencyByID($id){
        $sql = " SELECT * 
        FROM tb_currency 
        WHERE currency_id = '$id' 
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

    function updateCurrencyByID($id,$data = []){
        $sql = " UPDATE tb_currency SET 
        currency_country = '".$data['currency_country']."' ,  
        currency_name = '".$data['currency_name']."' ,  
        currency_sign = '".$data['currency_sign']."' ,  
        currency_code  = '".$data['currency_code']."' ,  
        currency_thousand  = '".$data['currency_thousand']."' , 
        currency_decimal  = '".$data['currency_decimal']."' 
        WHERE currency_id = $id 
        ";

        //echo $sql;

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function insertCurrency($data = []){
        $sql = " INSERT INTO tb_currency (
            currency_country , 
            currency_name , 
            currency_sign ,
            currency_code,
            currency_thousand,
            currency_decimal
        ) VALUES (
            '".$data['currency_country']."', 
            '".$data['currency_name']."', 
            '".$data['currency_sign']."', 
            '".$data['currency_code']."', 
            '".$data['currency_thousand']."', 
            '".$data['currency_decimal']."'
        ); 
        ";

        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }


    function deleteCurrencyByID($id){
        $sql = " DELETE FROM tb_currency WHERE currency_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>