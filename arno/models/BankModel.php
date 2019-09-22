<?php

require_once("BaseModel.php");
class BankModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getBankBy($bank_name = ''){
        $sql = " SELECT *   
        FROM tb_bank  
        WHERE bank_name LIKE ('%$bank_name%') 
        ORDER BY bank_name  
        ";
        if ($result = mysqli_query( static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getBankByID($id){
        $sql = " SELECT * 
        FROM tb_bank  
        WHERE bank_id = '$id' 
        ";

        if ($result = mysqli_query( static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }

    }

    function updateBankByID($id,$data = []){
        $sql = " UPDATE tb_bank SET     
        bank_code = '".$data['bank_code']."', 
        bank_name = '".$data['bank_name']."', 
        bank_detail = '".$data['bank_detail']."'
        WHERE bank_id = $id 
        ";


        if (mysqli_query( static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function insertBank($data = []){
        $sql = " INSERT INTO tb_bank (
            bank_code,
            bank_name,
            bank_detail
        ) VALUES (
            '".$data['bank_code']."', 
            '".$data['bank_name']."', 
            '".$data['bank_detail']."' 
        ); 
        ";

        //echo $sql;
        if (mysqli_query( static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }


    function deleteBankByID($id){
        $sql = " DELETE FROM tb_bank WHERE bank_id = '$id' ";
        mysqli_query( static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>