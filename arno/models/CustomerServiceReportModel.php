<?php
    // tb_customer_service_report
    require_once("BaseModel.php");
    class CustomerServiceReportModel extends BaseModel{
        function __construct(){
            if(!static::$db){
                static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
            }
        }
        function getCustomerServiceReportBy(){
            $sql = "SELECT * FROM tb_customer_service_report  ";
            if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                $data = [];
                while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                    $data[] = $row;
                }
                $result->close();
                return $data;
            }
        }

        function getCustomerServiceReportByID($id){
            $sql = " SELECT *
                FROM tb_customer_service_report    
                WHERE customer_service_report_id = '$id' 
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
        function getCustomerServiceReportViewByID($id){
            $sql = " SELECT *
                FROM tb_customer_service_report   
                LEFT JOIN tb_customer ON tb_customer_service_report.customer_id = tb_customer.customer_id
                LEFT JOIN tb_user ON tb_customer_service_report.addby = tb_user.user_id
                LEFT JOIN tb_status_service ON tb_customer_service_report.status_after_service_id = tb_status_service.status_service_id
                LEFT JOIN tb_rate_service ON tb_customer_service_report.rate_service_id = tb_rate_service.rate_service_id
                WHERE customer_service_report_id = '$id' 
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

        function updateCustomerServiceReportByID($id,$data=[]){
            $set = '';
            $i=1;
            foreach($data as $key=>$value){
                $set = $set." $key = " .'"'.$value.'"';
                if($i!=count($data)){
                    $set = $set." , ";
                }
                $i++;
            }
            $sql = 
            "   UPDATE tb_customer_service_report 
                SET $set
                WHERE customer_service_report_id = '$id'
            ";
             if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                return true;
             }else {
                 return false;
             }

        }

        function insertFirstFunction(){
            $sql = "INSERT INTO tb_customer_service_report (adddate) 
            VALUE (NOW())";
            if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                return mysqli_insert_id(static::$db);
             }else {
                 return 0;
             }
        }

        function getCustomerServiceReportByColumn($date_start='',$date_end='',$keyword='',$data = []){
            if($date_start != "" && $date_end != ""){
                $str_date = " AND STR_TO_DATE(customer_service_report_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(customer_service_report_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
            }else if ($date_start != ""){
                $str_date = " AND STR_TO_DATE(customer_service_report_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
            }else if ($date_end != ""){
                $str_date = " AND STR_TO_DATE(customer_service_report_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
            }
            $where = "";
            $i=0;
            foreach($data as $key=>$value){
                $where = $where . " AND ";
                $where = $where . " $key = '$value' ";
            }
            $sql = "SELECT tb_customer_service_report.*,
                tb_customer.customer_code,tb_customer.customer_name_th,tb_customer.customer_name_en,
                tb_rate_service.rate_service_name,
                tb_status_service.status_service_name,
                tb_user.user_prefix,tb_user.user_name,tb_user.user_lastname
                FROM tb_customer_service_report 
                LEFT JOIN tb_customer on tb_customer_service_report.customer_id = tb_customer.customer_id
                LEFT JOIN tb_rate_service on tb_rate_service.rate_service_id = tb_customer_service_report.rate_service_id
                LEFT JOIN tb_status_service on tb_status_service.status_service_id = tb_customer_service_report.status_after_service_id
                LEFT JOIN tb_user on tb_user.user_id = tb_customer_service_report.updateby
                WHERE tb_customer_service_report.customer_service_report_no LIKE ('%$keyword%')
                $str_date
                $where"; 
            if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                $data = [];
                while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                    $data[] = $row;
                }
                $result->close();
                return $data;
            }
        }
        function getCustomerServiceReportByApp($date_start = "",$date_end = "",$keyword = "",$user_id = ""){
            $str_customer = "";
            $str_date = "";
            $str_user = "";
            $str_status = "";

            if($date_start != "" && $date_end != ""){
                $str_date = "AND STR_TO_DATE(customer_service_report_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(customer_service_report_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
            }else if ($date_start != ""){
                $str_date = "AND STR_TO_DATE(customer_service_report_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
            }else if ($date_end != ""){
                $str_date = "AND STR_TO_DATE(customer_service_report_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
            }


            $sql = "SELECT tb_customer_service_report.*,
                tb_customer.customer_code,tb_customer.customer_name_th,tb_customer.customer_name_en,
                tb_rate_service.rate_service_name,
                tb_status_service.status_service_name,
                tb_user.user_prefix,tb_user.user_name,tb_user.user_lastname
                FROM tb_customer_service_report 
                LEFT JOIN tb_customer on tb_customer_service_report.customer_id = tb_customer.customer_id
                LEFT JOIN tb_rate_service on tb_rate_service.rate_service_id = tb_customer_service_report.rate_service_id
                LEFT JOIN tb_status_service on tb_status_service.status_service_id = tb_customer_service_report.status_after_service_id
                LEFT JOIN tb_user on tb_user.user_id = tb_customer_service_report.updateby
                WHERE ( 
                        CONCAT(tb_user.user_name,' ',tb_user.user_lastname) LIKE ('%$keyword%') 
                        OR  customer_service_report_no LIKE ('%$keyword%') 
                        OR  tb_customer.customer_name_en LIKE ('%$keyword%') 
                    )
                $str_date 
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

        function getCustomerServiceReportLastID($id,$digit){

            $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(customer_service_report_no,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  customer_service_report_lastcode 
            FROM tb_customer_service_report
            WHERE customer_service_report_no LIKE ('$id%') 
            ";
    
            if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
                $result->close();
                return $row['customer_service_report_lastcode'];
            }
    
        }

        function insertCustomerServiceReport($data=[]){
            $column = ' ';
            $value = ' ';
            $i=1;
            foreach($data as $key => $val){
                $column = $column . " $key ";
                $value = $value . ' "'.$val.'" ';
                if($i!=count($data)){
                    $column = $column . " , ";
                    $value = $value . " , ";
                }
                $i++;
            }
            $sql = "INSERT INTO tb_customer_service_report ( $column )
                    VALUE ( $value ) ";
            if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
                return mysqli_insert_id(static::$db);
            }else {
                return 0;
            }
        }

        function newUpdateCustomerServiceReportByID($id,$data = []){
            $sql = " UPDATE tb_customer_service_report SET ";  
            foreach($data as $column => $val){ 
                $sql .=" ".$column." = '$val',"; 
            } 
            $sql .="
            lastupdate = NOW() 
            WHERE customer_service_report_id = '$id' 
            ";  
            if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
               return true;
            }else {
                return false;
            }
        }

    }

?>