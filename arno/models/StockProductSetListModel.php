<?php

require_once("BaseModel.php"); 
class StockProductSetListModel extends BaseModel{ 
    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        } 
    }

    function getStockProductSetListBy($stock_product_set_id){
        $sql = " SELECT 
        tb_stock_product_set_list.product_set_id, 
        tb_stock_product_set_list.stock_group_id, 
        CONCAT(product_code_first,product_code) as product_code,  
        product_name,   
        stock_group_code,   
        stock_group_name,   
        stock_product_set_list_id, 
        stock_product_set_list_qty, 
        stock_product_set_list_cost, 
        stock_product_set_list_cost_total  
        FROM tb_stock_product_set_list 
        LEFT JOIN tb_product ON tb_stock_product_set_list.product_set_id = tb_product.product_id 
        LEFT JOIN tb_stock_group ON tb_stock_product_set_list.stock_group_id = tb_stock_group.stock_group_id 
        WHERE stock_product_set_id = '$stock_product_set_id' 
        ORDER BY stock_product_set_list_id 
        ";

        //echo $sql . "<br><br>";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }


    function insertStockProductSetList($data = []){
        $sql = " INSERT INTO tb_stock_product_set_list (
            stock_product_set_id,
            product_set_id,
            stock_group_id, 
            stock_product_set_list_qty,
            stock_product_set_list_cost,
            stock_product_set_list_cost_total,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['stock_product_set_id']."', 
            '".$data['product_set_id']."', 
            '".$data['stock_group_id']."', 
            '".$data['stock_product_set_list_qty']."',
            '".$data['stock_product_set_list_cost']."',
            '".$data['stock_product_set_list_cost_total']."',
            '".$data['addby']."', 
            NOW(), 
            '".$data['updateby']."', 
            NOW() 
        ); 
        ";

        //echo $sql . "<br><br>";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {

            $id = mysqli_insert_id(static::$db);
            return $id; 
        }else {
            return 0;
        }

    }

    

    function updateStockProductSetListById($data,$id){

        $sql = " UPDATE tb_stock_product_set_list 
            SET product_set_id = '".$data['product_set_id']."', 
            stock_product_set_id = '".$data['stock_product_set_id']."',  
            stock_group_id = '".$data['stock_group_id']."',  
            stock_product_set_list_qty = '".$data['stock_product_set_list_qty']."',  
            stock_product_set_list_cost = '".$data['stock_product_set_list_cost']."',  
            stock_product_set_list_cost_total = '".$data['stock_product_set_list_cost_total']."',  
            updateby = '".$data['updateby']."',
            lastupdate = NOW() 
            WHERE stock_product_set_list_id = '$id' 
        ";

        //echo $sql . "<br><br>";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
          
           return true;
        }else {
            return false;
        }
    }




    function deleteStockProductSetListByID($id){
        $sql = "DELETE FROM tb_stock_product_set_list WHERE stock_product_set_list_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteStockProductSetListByStockProductSetID($id){


        $sql = "DELETE FROM tb_stock_product_set_list WHERE stock_product_set_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteStockProductSetListByStockProductSetIDNotIN($id,$data){
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

        $sql = "DELETE FROM tb_stock_product_set_list WHERE stock_product_set_id = '$id' AND stock_product_set_list_id NOT IN ($str) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
 
    }
}
?>