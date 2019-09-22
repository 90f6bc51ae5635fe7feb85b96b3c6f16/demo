<?php

require_once("BaseModel.php");
class DataLogModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getDataLogBy($date_start='',$date_end='',$data_log_type='',$data_log_user=''){
        $str_date = "";
        $str_user = "";
        $str_type = "";
        
        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(data_log_date,'%Y-%m-%d %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(data_log_date,'%Y-%m-%d %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(data_log_date,'%Y-%m-%d %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(data_log_date,'%Y-%m-%d %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($data_log_user != ""){
            $str_user = "AND data_log_user = '$data_log_user' ";
        }

        if($data_log_type != ""){
            $str_type = "AND data_log_type = '$data_log_type' ";
        }

        $sql = " SELECT  * 
        FROM tb_data_log 
        LEFT JOIN tb_user ON tb_data_log.data_log_user = tb_user.user_id  
        WHERE 1
        $str_type
        $str_user
        $str_date
        ORDER BY  data_log_id DESC
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


    function insertDataLog($data = []){
        $sql = " INSERT INTO tb_data_log (
            data_log_date,
            data_log_type,
            data_log_subject,
            data_log_sql,
            data_log_text,
            data_log_user
        ) VALUES (
            NOW(), 
            '".$data['data_log_type']."', 
            '".$data['data_log_subject']."', 
            '".$data['data_log_sql']."', 
            '".$data['data_log_text']."', 
            '".$data['data_log_user']."' 
        ); 
        ";

        //echo $sql;

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }

    
    function updateDataLogByID($data_log_id,$data = []){ 
        $sql = " UPDATE tb_data_log SET  
        data_log_type = '".$data['data_log_type']."', 
        data_log_subject = '".$data['data_log_subject']."', 
        data_log_sql = '".$data['data_log_sql']."', 
        data_log_text = '".$data['data_log_text']."', 
        data_log_user = '".$data['data_log_user']."', 
        data_log_date = NOW()  
        WHERE data_log_id = '$data_log_id' 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        } 
    }
  
    function deleteDataLogByID($id){
        $sql = "DELETE FROM tb_data_log WHERE data_log_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 
    }

    function deleteDataLogByType($data_log_type){
        $sql = "DELETE FROM tb_data_log WHERE data_log_type = '$data_log_type' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 
    }
}
?>