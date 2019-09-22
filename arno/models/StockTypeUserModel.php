<?php

require_once("BaseModel.php");
class StockTypeUserModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getStockTypeUserBy($stock_type_id){
        $sql = " SELECT  * 
        FROM tb_stock_type_user 
        LEFT JOIN tb_user ON tb_stock_type_user.employee_id = tb_user.user_id 
        LEFT JOIN tb_user_position ON tb_user.user_position_id = tb_user_position.user_position_id 
        LEFT JOIN tb_stock_type ON tb_stock_type_user.stock_type_id = tb_stock_type.stock_type_id 
        WHERE tb_stock_type.stock_type_id = '$stock_type_id' 
        ORDER BY  user_name , user_lastname 
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


    function insertStockTypeUser($data = []){
        $sql = " INSERT INTO tb_stock_type_user (
            stock_type_id,
            employee_id
        ) VALUES (
            '".$data['stock_type_id']."', 
            '".$data['employee_id']."' 
        ); 
        ";

        //echo $sql;

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }

    function updatePurchaseRquestListById($data,$id){

        $sql = " UPDATE tb_stock_type_user 
            SET stock_type_id = '".$data['stock_type_id']."', 
            employee_id = '".$data['employee_id']."' 
            WHERE stock_type_user_id = '$id'
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function deleteStockTypeUserByID($id){
        $sql = "DELETE FROM tb_stock_type_user WHERE stock_type_user_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteStockTypeUserByStockTypeID($id){
        $sql = "DELETE FROM tb_stock_type_user WHERE stock_type_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteStockTypeUserByEmployeeIDNotIN($id,$data){
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

        $sql = "DELETE FROM tb_stock_type_user WHERE  stock_type_user_id NOT IN ($str) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>