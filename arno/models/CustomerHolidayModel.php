<?php

require_once("BaseModel.php");
class CustomerHolidayModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getCustomerHolidayBy($customer_id){
        $sql = " SELECT *   
        FROM tb_customer_holiday LEFT JOIN tb_holiday ON tb_customer_holiday.holiday_id = tb_holiday.holiday_id
        WHERE customer_id = '$customer_id' 
        ORDER BY tb_customer_holiday.holiday_id  
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

    function getCustomerHolidayByID($id){
        $sql = " SELECT * 
        FROM tb_customer_holiday 
        WHERE customer_holiday_id = '$id' 
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

    function updateCustomerHolidayByID($id,$data = []){
        $sql = " UPDATE tb_customer_holiday SET     
        customer_id = '".$data['customer_id']."', 
        holiday_id = '".$data['holiday_id']."', 
        customer_holiday_date = '".$data['customer_holiday_date']."', 
        customer_holiday_name = '".$data['customer_holiday_name']."' 
        WHERE customer_holiday_id = $id 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    

    function insertCustomerHoliday($data = []){
        $sql = " INSERT INTO tb_customer_holiday (
            customer_id,
            holiday_id,
            customer_holiday_date,
            customer_holiday_name
        ) VALUES (
            '".$data['customer_id']."', 
            '".$data['holiday_id']."', 
            '".$data['customer_holiday_date']."', 
            '".$data['customer_holiday_name']."'
        ); 
        ";

        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }


    function deleteCustomerHolidayByID($id){
        $sql = " DELETE FROM tb_customer_holiday WHERE customer_holiday_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>