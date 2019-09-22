<?php

require_once("BaseModel.php");
class JobOperationProcessModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getJobOperationProcessBy($job_operation_id){
        $sql = " SELECT *
        FROM tb_job_operation_process 
        WHERE job_operation_id = '$job_operation_id' 
        ORDER BY job_operation_process_no 
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


    function insertJobOperationProcess($data = []){
        $sql = " INSERT INTO tb_job_operation_process (
            job_operation_id,
            job_operation_process_no,
            job_operation_process_name,
            job_operation_process_remark,
            job_operation_process_drawing, 
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['job_operation_id']."',  
            '".$data['job_operation_process_no']."',  
            '".$data['job_operation_process_name']."',  
            '".$data['job_operation_process_remark']."',  
            '".$data['job_operation_process_drawing']."', 
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

    function updateJobOperationProcessById($data,$id){

        $sql = " UPDATE tb_job_operation_process 
            SET job_operation_process_no = '".$data['job_operation_process_no']."', 
            job_operation_process_name = '".$data['job_operation_process_name']."',
            job_operation_process_remark = '".$data['job_operation_process_remark']."', 
            job_operation_process_drawing = '".$data['job_operation_process_drawing']."' 
            WHERE job_operation_process_id = '$id'
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function deleteJobOperationProcessByID($id){
        $sql = "DELETE FROM tb_job_operation_process WHERE job_operation_process_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteJobOperationProcessByQuotationID($id){
        $sql = "DELETE FROM tb_job_operation_process WHERE job_operation_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteJobOperationProcessByQuotationIDNotIN($id,$data){
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

        $sql = "DELETE FROM tb_job_operation_process WHERE job_operation_id = '$id' AND job_operation_process_id NOT IN ($str) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>