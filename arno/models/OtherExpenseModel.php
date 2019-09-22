<?php

require_once("BaseModel.php");
class OtherExpenseModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getOtherExpenseBy($date_start = "",$date_end = "",$supplier_id = "",$keyword = "",$user_id = ""){
        $str_supplier = "";
        $str_date = "";
        $str_user = "";

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(other_expense_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(other_expense_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(other_expense_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(other_expense_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        if($supplier_id != ""){
            $str_supplier = "AND tb2.supplier_id = '$supplier_id' ";
        }


        
        $sql = "   SELECT tb.other_expense_id,   
        other_expense_date, 
        other_expense_code,  
        other_expense_net, 
        IFNULL(CONCAT(tb1.user_name,' ',tb1.user_lastname),'-') as employee_name, 
        IFNULL(CONCAT(tb2.supplier_name_th,' (',tb2.supplier_name_th,')' ) ,'-') as supplier_name, 
        other_expense_remark  
        FROM tb_other_expense as tb  
        LEFT JOIN tb_user as tb1 ON tb.addby = tb1.user_id  
        LEFT JOIN tb_supplier as tb2 ON tb.supplier_id = tb2.supplier_id  
        LEFT JOIN tb_other_expense_list ON tb.other_expense_id =  tb_other_expense_list.other_expense_id  
        WHERE ( 
            CONCAT(tb1.user_name,' ',tb1.user_lastname) LIKE ('%$keyword%') 
            OR  other_expense_remark LIKE ('%$keyword%') 
            OR  other_expense_code LIKE ('%$keyword%')  
        ) 
        $str_supplier 
        $str_date 
        $str_user   
        GROUP BY tb.other_expense_id 
        ORDER BY STR_TO_DATE(other_expense_date,'%d-%m-%Y %H:%i:%s') , other_expense_code DESC  
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

    function getOtherExpenseByID($id){
        $sql = " SELECT * 
        FROM tb_other_expense 
        WHERE other_expense_id = '$id' 
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

    function getOtherExpenseViewByID($id){
        $sql = " SELECT *   
        FROM tb_other_expense 
        LEFT JOIN tb_user ON tb_other_expense.addby = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_supplier ON tb_other_expense.supplier_id = tb_supplier.supplier_id 
        WHERE other_expense_id = '$id' 
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

    function getOtherExpenseLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(SUBSTRING(other_expense_code,".(strlen($id)+1).",$digit) AS SIGNED)),0) + 1,$digit,'0' )) AS  other_expense_lastcode 
        FROM tb_other_expense 
        WHERE other_expense_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['other_expense_lastcode'];
        }

    }

   
    function updateOtherExpenseByID($id,$data = []){
        $sql = " UPDATE tb_other_expense SET 
        supplier_id = '".$data['supplier_id']."', 
        other_expense_code = '".$data['other_expense_code']."', 
        other_expense_date = '".$data['other_expense_date']."', 
        other_expense_vat_type = '".$data['other_expense_vat_type']."', 
        other_expense_bill_code = '".$data['other_expense_bill_code']."', 
        other_expense_bill_date = '".$data['other_expense_bill_date']."', 
        other_expense_remark = '".$data['other_expense_remark']."', 
        other_expense_total = '".$data['other_expense_total']."', 
        other_expense_vat = '".$data['other_expense_vat']."', 
        other_expense_vat_value = '".$data['other_expense_vat_value']."', 
        other_expense_net = '".$data['other_expense_net']."', 
        other_expense_interest = '".$data['other_expense_interest']."', 
        other_expense_cash = '".$data['other_expense_cash']."', 
        other_expense_other_pay = '".$data['other_expense_other_pay']."', 
        other_expense_vat_pay = '".$data['other_expense_vat_pay']."', 
        other_expense_discount_cash = '".$data['other_expense_discount_cash']."', 
        other_expense_pay = '".$data['other_expense_pay']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE other_expense_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    

    function insertOtherExpense($data = []){
        $sql = " INSERT INTO tb_other_expense (
            supplier_id,
            other_expense_code,
            other_expense_date,
            other_expense_vat_type,
            other_expense_bill_code,
            other_expense_bill_date,
            other_expense_remark,
            other_expense_total,
            other_expense_vat,
            other_expense_vat_value,
            other_expense_net,
            other_expense_interest,
            other_expense_cash,
            other_expense_other_pay,
            other_expense_vat_pay,
            other_expense_discount_cash,
            other_expense_pay,
            addby,
            adddate
        ) VALUES ('".
        $data['supplier_id']."','".
        $data['other_expense_code']."','".
        $data['other_expense_date']."','".
        $data['other_expense_vat_type']."','".
        $data['other_expense_bill_code']."','".
        $data['other_expense_bill_date']."','".
        $data['other_expense_remark']."','".
        $data['other_expense_total']."','".
        $data['other_expense_vat']."','".
        $data['other_expense_vat_value']."','".
        $data['other_expense_net']."','".
        $data['other_expense_interest']."','".
        $data['other_expense_cash']."','".
        $data['other_expense_other_pay']."','".
        $data['other_expense_vat_pay']."','".
        $data['other_expense_discount_cash']."','".
        $data['other_expense_pay']."','".
        $data['addby']."',".
        "NOW()); 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }


    function deleteOtherExpenseByID($id){
        $sql = " DELETE FROM tb_other_expense WHERE other_expense_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        $sql = " DELETE FROM tb_other_expense_list WHERE other_expense_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        $sql = " DELETE FROM tb_other_expense_pay WHERE other_expense_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }
}
?>