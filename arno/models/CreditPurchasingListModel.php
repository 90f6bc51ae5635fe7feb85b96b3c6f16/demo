<?php

require_once("BaseModel.php");
class CreditPurchasingListModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getCreditPurchasingListBy($credit_purchasing_id){
        $sql = " SELECT credit_purchasing_list_id,  
        credit_purchasing_list_code, 
        credit_purchasing_list_name, 
        stock_group_id, 
        credit_purchasing_list_qty, 
        credit_purchasing_list_unit, 
        credit_purchasing_list_price, 
        credit_purchasing_list_discount,
        credit_purchasing_list_discount_type,
        credit_purchasing_list_total 
        FROM tb_credit_purchasing_list 
        WHERE credit_purchasing_id = '$credit_purchasing_id' 
        ORDER BY credit_purchasing_list_id 
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


    function insertCreditPurchasingList($data = []){
        $sql = " INSERT INTO tb_credit_purchasing_list (
            credit_purchasing_id,
            credit_purchasing_list_code,
            credit_purchasing_list_name, 
            stock_group_id, 
            credit_purchasing_list_qty, 
            credit_purchasing_list_unit, 
            credit_purchasing_list_price, 
            credit_purchasing_list_discount,
            credit_purchasing_list_discount_type,
            credit_purchasing_list_total, 
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['credit_purchasing_id']."',  
            '".$data['credit_purchasing_list_code']."',  
            '".$data['credit_purchasing_list_name']."',  
            '".$data['stock_group_id']."',  
            '".$data['credit_purchasing_list_qty']."', 
            '".$data['credit_purchasing_list_unit']."', 
            '".$data['credit_purchasing_list_price']."', 
            '".$data['credit_purchasing_list_discount']."', 
            '".$data['credit_purchasing_list_discount_type']."', 
            '".$data['credit_purchasing_list_total']."', 
            '".$data['addby']."', 
            NOW(), 
            '".$data['updateby']."', 
            NOW() 
        ); 
        ";

        echo $sql."<br><br>";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }

    function updateCreditPurchasingListById($data,$id){

        $sql = " UPDATE tb_credit_purchasing_list 
            SET credit_purchasing_list_code = '".$data['credit_purchasing_list_code']."', 
            credit_purchasing_list_name = '".$data['credit_purchasing_list_name']."',
            stock_group_id = '".$data['stock_group_id']."', 
            credit_purchasing_list_qty = '".$data['credit_purchasing_list_qty']."', 
            credit_purchasing_list_unit = '".$data['credit_purchasing_list_unit']."', 
            credit_purchasing_list_price = '".$data['credit_purchasing_list_price']."', 
            credit_purchasing_list_discount = '".$data['credit_purchasing_list_discount']."', 
            credit_purchasing_list_discount_type = '".$data['credit_purchasing_list_discount_type']."', 
            credit_purchasing_list_total = '".$data['credit_purchasing_list_total']."'  
            WHERE credit_purchasing_list_id = '$id'
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function deleteCreditPurchasingListByID($id){
        $sql = "DELETE FROM tb_credit_purchasing_list WHERE credit_purchasing_list_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteCreditPurchasingListByCreditPurchasingID($id){
        $sql = "DELETE FROM tb_credit_purchasing_list WHERE credit_purchasing_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteCreditPurchasingListByCreditPurchasingListIDNotIN($id,$data){
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

        $sql = "DELETE FROM tb_credit_purchasing_list WHERE credit_purchasing_id = '$id' AND credit_purchasing_list_id NOT IN ($str) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>