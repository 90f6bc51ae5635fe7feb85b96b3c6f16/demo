<?php

require_once("BaseModel.php");
class JobOperationModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getJobOperationBy($job_id){
        $sql = " SELECT *
        FROM tb_job_operation 
        WHERE job_id = '$job_id' 
        ORDER BY job_operation_no 
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


    function insertJobOperation($data = []){
        $sql = " INSERT INTO tb_job_operation (
            job_id,
            job_operation_no,
            job_operation_name,
            job_operation_remark,
            job_operation_drawing, 
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['job_id']."',  
            '".$data['job_operation_no']."',  
            '".$data['job_operation_name']."',  
            '".$data['job_operation_remark']."',  
            '".$data['job_operation_drawing']."', 
            '".$data['addby']."', 
            NOW(), 
            '".$data['updateby']."', 
            NOW() 
        ); 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }

    function updateJobOperationById($data,$id){

        $sql = " UPDATE tb_job_operation 
            SET job_operation_no = '".$data['job_operation_no']."', 
            job_operation_name = '".$data['job_operation_name']."',
            job_operation_remark = '".$data['job_operation_remark']."', 
            job_operation_drawing = '".$data['job_operation_drawing']."' 
            WHERE job_operation_id = '$id'
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function deleteJobOperationByID($id){
        $sql = "DELETE FROM tb_job_operation WHERE job_operation_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteJobOperationByQuotationID($id){
        $sql = "DELETE FROM tb_job_operation WHERE job_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteJobOperationByQuotationIDNotIN($id,$data){
        $str ='';
        if(is_array($data)){ 
            for($i=0; $i < count($data) ;$i++){
                $str .= $data[$i];
                if($i + 1 < count($data)){
                    $str .= ',';
                }
            }
        }else if ($data != ''){
            $str = $data;
        }else{
            $str='0';
        }

        $sql = "DELETE FROM tb_job_operation WHERE job_id = '$id' AND job_operation_id NOT IN ($str) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>