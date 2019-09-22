<?php

require_once("BaseModel.php");
class StockMoveModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getStockMoveByDNS($id){
        $sql = "SELECT * FROM tb_stock_move WHERE delivery_note_supplier_send_id = '$id'" ;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }

    function getStockMoveByDNC($id){
        $sql = "SELECT * FROM tb_stock_move WHERE delivery_note_customer_id = '$id'" ;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }

    function getStockMoveBy($date_start  = '', $date_end  = '',$keyword = ""){

        if($date_start != "" && $date_end != ""){
            $str_date = "AND STR_TO_DATE(stock_move_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') AND STR_TO_DATE(stock_move_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";
        }else if ($date_start != ""){
            $str_date = "AND STR_TO_DATE(stock_move_date,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('$date_start','%d-%m-%Y %H:%i:%s') ";    
        }else if ($date_end != ""){
            $str_date = "AND STR_TO_DATE(stock_move_date,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('$date_end','%d-%m-%Y %H:%i:%s') ";  
        }

        $sql = " SELECT stock_move_id, 
        stock_group_id_out, 
        stock_group_id_in, 
        stock_move_code, 
        stock_move_date, 
        tb1.stock_group_name as move_group_name_out,
        tb2.stock_group_name as move_group_name_in,
        stock_move_remark, 
        tb_delivery_note_supplier_send.delivery_note_supplier_send_id,
        tb_delivery_note_supplier_send.delivery_note_supplier_send_code,
        tb_delivery_note_customer.delivery_note_customer_id,
        tb_delivery_note_customer.delivery_note_customer_code,
        IFNULL(CONCAT(user_name,' ',user_lastname),'-') as employee_name 
        FROM tb_stock_move 
        LEFT JOIN tb_user ON tb_stock_move.employee_id = tb_user.user_id 
        LEFT JOIN tb_stock_group as tb1 ON tb_stock_move.stock_group_id_out = tb1.stock_group_id 
        LEFT JOIN tb_stock_group as tb2 ON tb_stock_move.stock_group_id_in = tb2.stock_group_id 
        LEFT JOIN tb_delivery_note_customer ON tb_delivery_note_customer.delivery_note_customer_id = tb_stock_move.delivery_note_customer_id
        LEFT JOIN tb_delivery_note_supplier_send ON tb_delivery_note_supplier_send.delivery_note_supplier_send_id = tb_stock_move.delivery_note_supplier_send_id
        WHERE stock_move_code LIKE ('%$keyword%') 
        $str_date
        ORDER BY stock_move_code DESC 
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

    function getStockMoveByID($id){
        $sql = " SELECT stock_move_id, 
        tb_stock_move.employee_id, 
        stock_group_id_out, 
        stock_group_id_in, 
        stock_move_code, 
        stock_move_date, 
        tb1.stock_group_name as move_group_name_out,
        tb2.stock_group_name as move_group_name_in,
        stock_move_remark, 
        IFNULL(CONCAT(user_name,' ',user_lastname),'-') as employee_name 
        FROM tb_stock_move 
        LEFT JOIN tb_user ON tb_stock_move.employee_id = tb_user.user_id 
        LEFT JOIN tb_stock_group as tb1 ON tb_stock_move.stock_group_id_out = tb1.stock_group_id 
        LEFT JOIN tb_stock_group as tb2 ON tb_stock_move.stock_group_id_in = tb2.stock_group_id 
        WHERE stock_move_id = '$id' 
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

    function getStockMoveViewByID($id){
        $sql = " SELECT stock_move_id, 
        tb_stock_move.employee_id, 
        stock_group_id_out, 
        stock_group_id_in, 
        stock_move_code, 
        stock_move_date, 
        tb1.stock_group_name as move_group_name_out,
        tb2.stock_group_name as move_group_name_in,
        stock_move_remark, 
        IFNULL(CONCAT(user_name,' ',user_lastname),'-') as employee_name 
        FROM tb_stock_move 
        LEFT JOIN tb_user ON tb_stock_move.employee_id = tb_user.user_id 
        LEFT JOIN tb_stock_group as tb1 ON tb_stock_move.stock_group_id_out = tb1.stock_group_id 
        LEFT JOIN tb_stock_group as tb2 ON tb_stock_move.stock_group_id_in = tb2.stock_group_id 
        WHERE stock_move_id = '$id' 
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

    function getStockMoveLastID($id,$digit){

        $sql = "SELECT CONCAT('$id' , LPAD(IFNULL(MAX(CAST(RIGHT(stock_move_code,3) AS SIGNED)),0) + 1,$digit,'0' )) AS  stock_move_lastcode 
        FROM tb_stock_move 
        WHERE stock_move_code LIKE ('$id%') 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $row['stock_move_lastcode'];
        }

    }

   
    function updateStockMoveByID($id,$data = []){
        $sql = " UPDATE tb_stock_move SET 
        stock_group_id_out = '".$data['stock_group_id_out']."', 
        stock_group_id_in = '".$data['stock_group_id_in']."', 
        employee_id = '".$data['employee_id']."', 
        stock_move_code = '".$data['stock_move_code']."', 
        stock_move_date = '".$data['stock_move_date']."', 
        stock_move_remark = '".$data['stock_move_remark']."', 
        updateby = '".$data['updateby']."', 
        lastupdate = '".$data['lastupdate']."' 
        WHERE stock_move_id = '$id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }



    function insertStockMove($data = []){
        $sql = " INSERT INTO tb_stock_move (
            stock_group_id_out, 
            stock_group_id_in, 
            employee_id,
            stock_move_code,
            stock_move_date,
            stock_move_remark,
            addby,
            adddate,
            updateby,
            lastupdate) 
        VALUES ('".
        $data['stock_group_id_out']."','".
        $data['stock_group_id_in']."','".
        $data['employee_id']."','".
        $data['stock_move_code']."','".
        $data['stock_move_date']."','".
        $data['stock_move_remark']."','".
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

    function insertStockMoveDNC($data = []){
        $sql = " INSERT INTO tb_stock_move (
            stock_group_id_out, 
            stock_group_id_in, 
            employee_id,
            stock_move_code,
            stock_move_date,
            stock_move_remark,
            delivery_note_customer_id,
            addby,
            adddate,
            updateby,
            lastupdate) 
        VALUES ('".
        $data['stock_group_id_out']."','".
        $data['stock_group_id_in']."','".
        $data['employee_id']."','".
        $data['stock_move_code']."','".
        $data['stock_move_date']."','".
        $data['stock_move_remark']."','".
        $data['delivery_note_customer_id']."','".
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

    function insertStockMoveDNS($data = []){
        $sql = " INSERT INTO tb_stock_move (
            stock_group_id_out, 
            stock_group_id_in, 
            employee_id,
            stock_move_code,
            stock_move_date,
            stock_move_remark,
            delivery_note_supplier_send_id,
            addby,
            adddate,
            updateby,
            lastupdate) 
        VALUES ('".
        $data['stock_group_id_out']."','".
        $data['stock_group_id_in']."','".
        $data['employee_id']."','".
        $data['stock_move_code']."','".
        $data['stock_move_date']."','".
        $data['stock_move_remark']."','".
        $data['delivery_note_supplier_send_id']."','".
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



    function deleteStockMoveByID($id){
 
        $sql = " DELETE FROM tb_stock_move_list WHERE stock_move_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " DELETE FROM tb_stock_move WHERE stock_move_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteStockMoveByDNC($id){
        $sql = "SELECT stock_move_id FROM tb_stock_move WHERE delivery_note_customer_id = '$id'";
        
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
        }

        foreach($data as $row){
            $sql = " DELETE FROM tb_stock_move_list WHERE stock_move_id = '".$row['stock_move_id']."' ";
            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        }
        

        $sql = " DELETE FROM tb_stock_move WHERE delivery_note_customer_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
    function deleteStockMoveByDNS($id){
        $sql = "SELECT stock_move_id FROM tb_stock_move WHERE delivery_note_supplier_send_id = '$id'";
        
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
        }
        
        foreach($data as $row){
            $sql = " DELETE FROM tb_stock_move_list WHERE stock_move_id = '".$row['stock_move_id']."' ";
            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        }

        $sql = " DELETE FROM tb_stock_move WHERE delivery_note_supplier_send_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }


}
?>