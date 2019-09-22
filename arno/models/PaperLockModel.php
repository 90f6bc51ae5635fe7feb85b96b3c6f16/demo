<?php

require_once("BaseModel.php");

class PaperLockModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getPaperLock(){
        $sql = "SELECT * FROM tb_paper_lock ";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }

    function checkPaperLockByDate($date, $lock_1 = "0", $lock_2 = "0" ){

        $str_lock = "";

        if($lock_1 == "1" && $lock_2 == "1"){
            $str_lock = "AND (paper_lock_1 = '0' OR paper_lock_2 = '0')";
        }else if ($lock_1 == "1") {
            $str_lock = "AND paper_lock_1 = '0' ";
        }else if($lock_2 == "1"){
            $str_lock = "AND paper_lock_2 = '0' ";
        }

        $sql = "SELECT * 
                FROM tb_paper_lock 
                WHERE SUBSTRING(tb_paper_lock.paper_lock_date,3,9) = SUBSTRING('$date',3,9)  
                $str_lock ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();

            if(count($data) == 0){
                return true;
            }else{
                return false;
            }
            
        }else{
            return false;
        }
    }

    function generatePaperLock($date_start){
        date_default_timezone_set('asia/bangkok');
        $sql = "TRUNCATE  tb_paper_lock ;";
 
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $date_str = explode("-",$date_start);
        $time = strtotime($date_str[2]."-".$date_str[1]."-01" );

        for($i=0; $i < 24; $i++){
        
            $date_current = date("t-m-Y", strtotime("+".$i." month", $time));

        
            $sql = " INSERT INTO tb_paper_lock ( 
                paper_lock_1 , 
                paper_lock_2,
                paper_lock_date 
            ) VALUES (
                '0', 
                '0', 
                '".$date_current."' 
            ); 
            ";

           //echo $date_str[2].".".$date_str[1].".".$date_str[0]."-->".$sql."<br><br>";
            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        } 
    }

    function setPaperLock1($id){
        $sql = " UPDATE tb_paper_lock SET  
        paper_lock_1 = '1' 
        WHERE paper_lock_id = '$id' 
        ";
 
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function setPaperLock2($id){
        $sql = " UPDATE tb_paper_lock SET  
        paper_lock_2 = '1' 
        WHERE paper_lock_id = '$id' 
        ";

        //echo $sql."<br><br>";
 
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        } 
    }

    function clearPaperLock1(){
        $sql = " UPDATE tb_paper_lock SET  
        paper_lock_1 = '0'  
        ";

        //echo $sql."<br><br>";
 
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        } 
    }

    function clearPaperLock2(){
        $sql = " UPDATE tb_paper_lock SET  
        paper_lock_2 = '0'  
        ";
 
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        } 
    }



    

}
?>