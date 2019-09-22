<?php
//ALTER TABLE `tb_stock_group` ADD `stock_group_code` VARCHAR(50) NOT NULL COMMENT 'หมายเลขคลังสินค้า' AFTER `stock_type_id`;

require_once("BaseModel.php");
class StockGroupModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
        mysqli_set_charset(static::$db,"utf8");
    }

    function getStockGroupBy($stock_type_id = ""){
        
        $str = "";
        if($stock_type_id != ""){
            $str = " WHERE tb_stock_type.stock_type_id = '$stock_type_id' ";
        }

        $sql = "  SELECT * 
                  FROM tb_stock_group 
                  LEFT JOIN tb_stock_type ON tb_stock_group.stock_type_id = tb_stock_type.stock_type_id 
                  $str 
                  ORDER BY stock_type_code, stock_group_code ";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }

    function getStockGroupByProductID($product_id = ""){
         

        $sql = "  SELECT * 
                  FROM tb_stock_group 
                  LEFT JOIN tb_stock_type ON tb_stock_group.stock_type_id = tb_stock_type.stock_type_id 
                  LEFT JOIN tb_stock_report ON tb_stock_group.stock_group_id = tb_stock_report.stock_group_id 
                  WHERE tb_stock_report.product_id = '$product_id' 
                  AND stock_report_qty > 0 
                  GROUP BY tb_stock_group.stock_group_id ";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
        else{
            return $sql;
        }

    }
    function getStockGroupByEmployeeID($employee_id = ""){ 

        $sql = "  SELECT stock_group_id,stock_group_name,employee_id 
                  FROM tb_stock_group 
                  LEFT JOIN tb_user ON tb_stock_group.employee_id = tb_user.user_id  
                  WHERE tb_stock_group.employee_id = '$employee_id' ";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
        else{
            return $sql;
        }

    }

    function getStockGroupByID($stock_group_id){
        $sql = "SELECT * FROM tb_stock_group WHERE stock_group_id = $stock_group_id ";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $result->close();
            return $data;
        }

    }


    function getQtyBy($stock_group_id,$product_id){
        $sql = "SELECT * FROM tb_stock_report WHERE stock_group_id = '$stock_group_id' AND product_id = '$product_id'  "; 

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        } 

    }


    function updateTableName($stock_group_id,$table_name){
        $sql = " UPDATE tb_stock_group SET 
        table_name = '".$table_name."' 
        WHERE stock_group_id = $stock_group_id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }

    function setPrimaryByID($stock_type_id,$stock_group_id){

        $sql = " UPDATE tb_stock_group SET 
        stock_group_primary = '0' 
        WHERE stock_type_id = '$stock_type_id' 
        ";

        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        $sql = " UPDATE tb_stock_group SET 
        stock_group_primary = '1'  
        WHERE stock_group_id = '$stock_group_id'  
        ";
        
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function updateStockGroupByID($id,$data = []){
        $sql = " UPDATE tb_stock_group SET 
        stock_type_id= '".$data['stock_type_id']."' , 
        employee_id = '".$data['employee_id']."' , 
        stock_group_code = '".$data['stock_group_code']."' , 
        stock_group_name = '".$data['stock_group_name']."' ,  
        stock_group_detail = '".$data['stock_group_detail']."' , 
        stock_group_notification = '".$data['stock_group_notification']."' , 
        stock_group_day = '".$data['stock_group_day']."' , 
        updateby = '".$data['updateby']."',  
        lastupdate = NOW() 
        WHERE stock_group_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function insertStockGroup($data = []){
        $sql = " INSERT INTO tb_stock_group ( 
            stock_type_id,
            employee_id, 
            stock_group_code,  
            stock_group_name,  
            stock_group_detail, 
            stock_group_notification, 
            stock_group_day, 
            table_name, 
            addby,
            adddate
        ) VALUES (  
            '".$data['stock_type_id']."', 
            '".$data['employee_id']."', 
            '".$data['stock_group_code']."', 
            '".$data['stock_group_name']."', 
            '".$data['stock_group_detail']."', 
            '".$data['stock_group_notification']."', 
            '".$data['stock_group_day']."', 
            '".$data['table_name']."', 
            '".$data['addby']."', 
            NOW()  
        ); 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }


    function deleteStockGroupByID($id){
        $sql = " DELETE FROM tb_stock_group WHERE stock_group_id = '$id' ";
        if(mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)){
            return true;
        }else{
            return false;
        }
    }

    
    function getStockGroupByCode($stock_group_code = ""){
        
        $str = "";
        if($stock_group_code != ""){
            $str = " WHERE tb_stock_group.stock_group_code = '$stock_group_code' ";
        }

        $sql = "  SELECT * 
                  FROM tb_stock_group 
                  LEFT JOIN tb_stock_type ON tb_stock_group.stock_type_id = tb_stock_type.stock_type_id 
                  $str ";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }

    }

}
?>