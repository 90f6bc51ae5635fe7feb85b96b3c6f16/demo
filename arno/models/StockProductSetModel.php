<?php

require_once("BaseModel.php");
class StockProductSetModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getStockProductSetBy($date_start  = '', $date_end  = '',$keyword = ""){

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(stock_product_set_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(stock_product_set_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(stock_product_set_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(stock_product_set_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        $sql = " SELECT stock_product_set_id, 
        tb_stock_product_set.stock_group_id, 
        tb_stock_product_set.product_id, 
        stock_product_set_code, 
        stock_product_set_date, 
        product_code, 
        product_name, 
        stock_group_name,  
        stock_product_set_remark, 
        user_name, 
        user_lastname  
        FROM tb_stock_product_set 
        LEFT JOIN tb_user ON tb_stock_product_set.employee_id = tb_user.user_id 
        LEFT JOIN tb_product ON tb_stock_product_set.product_id = tb_product.product_id 
        LEFT JOIN tb_stock_group ON tb_stock_product_set.stock_group_id = tb_stock_group.stock_group_id 
        WHERE stock_product_set_code LIKE ('%$keyword%') 
        $str_date
        ORDER BY stock_product_set_code DESC 
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

    function getStockProductSetByID($id){
        $sql = " SELECT stock_product_set_id, 
        tb_stock_product_set.employee_id, 
        tb_stock_product_set.product_id, 
        tb_stock_product_set.stock_group_id, 
        stock_product_set_code, 
        stock_product_set_date, 
        stock_product_set_qty, 
        stock_product_set_remark, 
        product_code, 
        product_name, 
        stock_group_name,  
        user_name, 
        user_lastname  
        FROM tb_stock_product_set 
        LEFT JOIN tb_user ON tb_stock_product_set.employee_id = tb_user.user_id 
        LEFT JOIN tb_product ON tb_stock_product_set.product_id = tb_product.product_id 
        LEFT JOIN tb_stock_group ON tb_stock_product_set.stock_group_id = tb_stock_group.stock_group_id 
        WHERE stock_product_set_id = '$id' 
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

    function getStockProductSetViewByID($id){
        $sql = " SELECT stock_product_set_id, 
        tb_stock_product_set.employee_id, 
        tb_stock_product_set.product_id, 
        tb_stock_product_set.stock_group_id, 
        stock_product_set_code, 
        stock_product_set_date, 
        stock_product_set_qty, 
        stock_product_set_remark, 
        product_code, 
        product_name, 
        stock_group_name,  
        user_name, 
        user_lastname  
        FROM tb_stock_product_set 
        LEFT JOIN tb_user ON tb_stock_product_set.employee_id = tb_user.user_id 
        LEFT JOIN tb_product ON tb_stock_product_set.product_id = tb_product.product_id 
        LEFT JOIN tb_stock_group ON tb_stock_product_set.stock_group_id = tb_stock_group.stock_group_id 
        WHERE stock_product_set_id = '$id' 
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

    function getStockProductSetLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(RIGHT(stock_product_set_code,3) AS SIGNED)),0) + 1,$digit,'0' )) AS  stock_product_set_lastcode 
        FROM tb_stock_product_set 
        WHERE stock_product_set_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['stock_product_set_lastcode'];
        }

    }

   
    function updateStockProductSetByID($id,$data = []){
        $sql = " UPDATE tb_stock_product_set SET 
        product_id = '".$data['product_id']."', 
        stock_group_id = '".$data['stock_group_id']."', 
        employee_id = '".$data['employee_id']."', 
        stock_product_set_code = '".$data['stock_product_set_code']."', 
        stock_product_set_date = '".$data['stock_product_set_date']."', 
        stock_product_set_remark = '".$data['stock_product_set_remark']."', 
        stock_product_set_qty = '".$data['stock_product_set_qty']."', 
        stock_product_set_cost = '".$data['stock_product_set_cost']."', 
        stock_product_set_cost_total = '".$data['stock_product_set_cost_total']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = NOW()  
        WHERE stock_product_set_id = '$id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }



    function insertStockProductSet($data = []){
        $sql = " INSERT INTO tb_stock_product_set (
            product_id, 
            stock_group_id, 
            employee_id,
            stock_product_set_code,
            stock_product_set_date,
            stock_product_set_remark,
            stock_product_set_qty,
            stock_product_set_cost,
            stock_product_set_cost_total,
            addby,
            adddate,
            updateby,
            lastupdate) 
        VALUES ('".
        $data['product_id']."','".
        $data['stock_group_id']."','".
        $data['employee_id']."','".
        $data['stock_product_set_code']."','".
        $data['stock_product_set_date']."','".
        $data['stock_product_set_remark']."','".
        $data['stock_product_set_qty']."','".
        $data['stock_product_set_cost']."','".
        $data['stock_product_set_cost_total']."','".
        $data['addby']."',".
        "NOW(),'".
        $data['addby'].
        "',NOW()); 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }



    function deleteStockProductSetByID($id){
 
        $sql = " DELETE FROM tb_stock_product_set_list WHERE stock_product_set_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_stock_product_set WHERE stock_product_set_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }


}
?>