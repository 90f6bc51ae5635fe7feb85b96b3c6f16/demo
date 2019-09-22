<?php

require_once("BaseModel.php");
class JobOperationProcessToolModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getJobOperationProcessToolBy($job_operation_process_id){
        $sql = " SELECT *
        FROM tb_job_operation_process_tool 
        WHERE job_operation_process_id = '$job_operation_process_id' 
        ORDER BY job_operation_process_tool_toollife 
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


    function insertJobOperationProcessTool($data = []){
        $sql = " INSERT INTO tb_job_operation_process_tool (
            job_operation_process_id,
            product_id,
            job_operation_process_tool_toollife,
            job_operation_process_tool_name,
            job_operation_process_tool_remark,
            job_operation_process_tool_status, 
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['job_operation_process_id']."',  
            '".$data['product_id']."',   
            '".$data['job_operation_process_tool_toollife']."',  
            '".$data['job_operation_process_tool_name']."',  
            '".$data['job_operation_process_tool_remark']."',  
            '".$data['job_operation_process_tool_status']."', 
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

    function updateJobOperationProcessToolById($data,$id){

        $sql = " UPDATE tb_job_operation_process_tool 
            SET product_id = '".$data['product_id']."', 
            job_operation_process_tool_toollife = '".$data['job_operation_process_tool_toollife']."', 
            job_operation_process_tool_name = '".$data['job_operation_process_tool_name']."',
            job_operation_process_tool_remark = '".$data['job_operation_process_tool_remark']."', 
            job_operation_process_tool_active = '".$data['job_operation_process_tool_active']."' 
            WHERE job_operation_process_tool_id = '$id'
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function activeJobOperationProcessToolByID($id){
        $sql = " UPDATE tb_job_operation_process_tool SET 
        job_operation_process_tool_active = '1', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE job_operation_process_tool_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function inactiveJobOperationProcessToolByID($id){
        $sql = " UPDATE tb_job_operation_process_tool SET 
        job_operation_process_tool_active = '0', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE job_operation_process_tool_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function deleteJobOperationProcessToolByID($id){
        $sql = "DELETE FROM tb_job_operation_process_tool WHERE job_operation_process_tool_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteJobOperationProcessToolByQuotationID($id){
        $sql = "DELETE FROM tb_job_operation_process_tool WHERE job_operation_process_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteJobOperationProcessToolByQuotationIDNotIN($id,$data){
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

        $sql = "DELETE FROM tb_job_operation_process_tool WHERE job_operation_process_id = '$id' AND job_operation_process_tool_id NOT IN ($str) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>