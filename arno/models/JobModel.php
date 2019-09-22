<?php

require_once("BaseModel.php");
class JobModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getJobBy($job_name = '', $job_code = '',  $job_status  = ''){
        $sql = " SELECT *   
        FROM tb_job     
        LEFT JOIN tb_customer ON tb_job.customer_id = tb_customer.customer_id 
        ORDER BY job_name  
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

    function getJobByID($id){
        $sql = " SELECT * 
        FROM tb_job      
        LEFT JOIN tb_customer ON tb_job.customer_id = tb_customer.customer_id 
        WHERE job_id = '$id' 
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

    
    function activeJobByID($id){
        $sql = " UPDATE tb_job SET 
        job_active = '1', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE job_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function inactiveJobByID($id){
        $sql = " UPDATE tb_job SET 
        job_active = '0', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE job_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    
    

    function updateJobByID($id,$data = []){
        $sql = " UPDATE tb_job SET  
        customer_id = '".$data['customer_id']."', 
        job_code = '".$data['job_code']."', 
        job_name = '".$data['job_name']."', 
        job_cost = '".$data['job_cost']."', 
        job_price = '".$data['job_price']."', 
        job_production = '".$data['job_production']."', 
        job_remark = '".$data['job_remark']."', 
        job_drawing = '".$data['job_drawing']."', 
        job_start = '".$data['job_start']."', 
        job_end = '".$data['job_end']."', 
        job_active = '".$data['job_active']."' 
        WHERE job_id = $id 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }


    function insertJob($data = []){
        $sql = " INSERT INTO tb_job (
            customer_id, 
            job_code,
            job_name,
            job_cost,
            job_price,
            job_production,
            job_remark,
            job_drawing,
            job_start,
            job_end,
            job_active
        ) VALUES (
            '".$data['customer_id']."', 
            '".$data['job_code']."', 
            '".$data['job_name']."', 
            '".$data['job_cost']."', 
            '".$data['job_price']."', 
            '".$data['job_production']."', 
            '".$data['job_remark']."', 
            '".$data['job_drawing']."', 
            '".$data['job_start']."', 
            '".$data['job_end']."', 
            '".$data['job_active']."' 
        ); 
        ";

        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }




    function deleteJobByID($id){
        $sql = " DELETE FROM tb_job WHERE job_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>