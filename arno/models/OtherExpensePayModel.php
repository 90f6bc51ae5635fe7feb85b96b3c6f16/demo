<?php

require_once("BaseModel.php");
class OtherExpensePayModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getOtherExpensePayBy($other_expense_id){
        $sql = " SELECT 
        other_expense_pay_id, 
        other_expense_pay_by,
        other_expense_pay_date,
        other_expense_pay_bank,
        other_expense_pay_value,
        other_expense_pay_balance,
        other_expense_pay_total
        FROM tb_other_expense_pay 
        WHERE other_expense_id = '$other_expense_id' 
        ORDER BY other_expense_pay_id 
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


    function insertOtherExpensePay($data = []){
        $sql = " INSERT INTO tb_other_expense_pay (
            other_expense_id, 
            other_expense_pay_by,
            other_expense_pay_date,
            other_expense_pay_bank,
            other_expense_pay_value,
            other_expense_pay_balance,
            other_expense_pay_total,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['other_expense_id']."', 
            '".$data['other_expense_pay_by']."', 
            '".$data['other_expense_pay_date']."', 
            '".$data['other_expense_pay_bank']."', 
            '".$data['other_expense_pay_value']."', 
            '".$data['other_expense_pay_balance']."', 
            '".$data['other_expense_pay_total']."', 
            '".$data['addby']."', 
            NOW(), 
            '".$data['updateby']."', 
            NOW() 
        ); 
        ";

        //echo $sql."<br><br>";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }

    function updateOtherExpensePayById($data,$id){

        $sql = " UPDATE tb_other_expense_pay 
            SET other_expense_pay_by = '".$data['other_expense_pay_by']."', 
            other_expense_pay_date = '".$data['other_expense_pay_date']."',
            other_expense_pay_bank = '".$data['other_expense_pay_bank']."',
            other_expense_pay_value = '".$data['other_expense_pay_value']."',
            other_expense_pay_balance = '".$data['other_expense_pay_balance']."',
            other_expense_pay_total = '".$data['other_expense_pay_total']."'     
            WHERE other_expense_pay_id = '$id' 
        ";

        //echo $sql."<br><br>";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function deleteOtherExpensePayByID($id){
        $sql = "DELETE FROM tb_other_expense_pay WHERE other_expense_pay_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteOtherExpensePayByOtherExpenseID($id){
        $sql = "DELETE FROM tb_other_expense_pay WHERE other_expense_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteOtherExpensePayByOtherExpensePayIDNotIN($id,$data){
        $str ='';
        if(is_array($data)){ 
            for($i=0; $i < count($data) ;$i++){
                if($data[$i] != ""){
                    $str .= $data[$i];
                    if($i + 1 < count($data)){
                        $str .= ',';
                    }
                }
            }
        }else if ($data != ''){
            $str = $data;
        }else{
            $str='0';
        }

        if( $str==''){
            $str='0';
        }

        $sql = "DELETE FROM tb_other_expense_pay WHERE other_expense_id = '$id' AND other_expense_pay_id NOT IN ($str) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>