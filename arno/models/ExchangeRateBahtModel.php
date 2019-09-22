<?php

require_once("BaseModel.php");
class ExchangeRateBahtModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getExchangeRateBahtByDate($date_start = '', $date_end = '', $lock_1 = "0", $lock_2 = "0"){
        $str_lock = "";

        if($lock_1 == "1" && $lock_2 == "1"){
            $str_lock = "AND (paper_lock_1 = '0' OR paper_lock_2 = '0') ";
        }else if ($lock_1 == "1") {
            $str_lock = "AND paper_lock_1 = '0' ";
        }else if($lock_2 == "1"){
            $str_lock = "AND paper_lock_2 = '0' ";
        }

        $sql = "SELECT * 
        FROM tb_exchange_rate_baht 
        LEFT JOIN tb_currency ON tb_exchange_rate_baht.currency_id = tb_currency.currency_id   
        LEFT JOIN tb_paper_lock ON SUBSTRING(tb_exchange_rate_baht.exchange_rate_baht_date,3,9)=SUBSTRING(tb_paper_lock.paper_lock_date,3,9) 
        WHERE   STR_TO_DATE(exchange_rate_baht_date,'%d-%m-%Y') >= STR_TO_DATE('$date_start','%d-%m-%Y') 
        AND STR_TO_DATE(exchange_rate_baht_date,'%d-%m-%Y') <= STR_TO_DATE('$date_end','%d-%m-%Y') 
        $str_lock
        ORDER BY STR_TO_DATE(exchange_rate_baht_date,'%d-%m-%Y')  
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


    function getExchangeRateBahtByID($id){
        $sql = " SELECT * 
        FROM tb_exchange_rate_baht  
        WHERE exchange_rate_baht_id = '$id' 
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

    function getExchangeRateBahtByCurrncyID($date,$currency_id){
        $sql = " SELECT * 
        FROM tb_exchange_rate_baht 
        LEFT JOIN tb_currency ON tb_exchange_rate_baht.currency_id = tb_currency.currency_id  
        WHERE tb_exchange_rate_baht.currency_id = '$currency_id' 
        AND tb_exchange_rate_baht.exchange_rate_baht_date ='$date'  
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


    function updateExchangeRateBahtByID($id,$data = []){
        $sql = " UPDATE tb_exchange_rate_baht SET 
        currency_id = '".$data['currency_id']."',  
        exchange_rate_baht_date = '".$data['exchange_rate_baht_date']."', 
        exchange_rate_baht_value = '".$data['exchange_rate_baht_value']."' 
        WHERE exchange_rate_baht_id = $id 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
        }else {
            return false;
        }


    }



    function insertExchangeRateBaht($data = []){
        $sql = " INSERT INTO tb_exchange_rate_baht (
            currency_id,
            exchange_rate_baht_date,
            exchange_rate_baht_value  
        ) VALUES (
            '".$data['currency_id']."', 
            '".$data['exchange_rate_baht_date']."', 
            '".$data['exchange_rate_baht_value']."' 
        ); 
        ";

        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }



    function deleteExchangeRateBahtByID($id){
        $sql = " DELETE FROM tb_exchange_rate_baht WHERE exchange_rate_baht_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    

}
?>