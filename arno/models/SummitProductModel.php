<?php

require_once("BaseModel.php");
class SummitProductModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getSummitProductBy($product_id = '',$stock_group_id = ''){
        $str_product  = '';
        $str_stock  = '';

        if($product_id != ''){
            $str_product = "AND tb_summit_product.product_id = '$product_id' ";
        }

        if($stock_group_id != ''){
            $str_stock = "AND tb_summit_product.stock_group_id = '$stock_group_id' ";
        }

        $sql = " SELECT * 
        FROM tb_summit_product 
        LEFT JOIN tb_product ON tb_summit_product.product_id = tb_product.product_id 
        LEFT JOIN tb_stock_group ON tb_summit_product.stock_group_id = tb_stock_group.stock_group_id 
        WHERE 1 = 1 
        $str_product 
        $str_stock 
        ORDER BY product_name, stock_group_name  
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

    function getSummitProductByID($id){
        $sql = " SELECT * 
        FROM tb_summit_product 
        LEFT JOIN tb_product ON tb_summit_product.product_id = tb_product.product_id 
        LEFT JOIN tb_stock_group ON tb_summit_product.stock_group_id = tb_stock_group.stock_group_id 
        WHERE summit_product_id = '$id' 
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


    function updateSummitProductByID($id,$data = []){
        $sql = " UPDATE tb_summit_product SET      
        summit_product_qty = '".$data['summit_product_qty']."', 
        summit_product_cost = '".$data['summit_product_cost']."', 
        summit_product_total = '".$data['summit_product_total']."',
        updateby = '".$data['updateby']."' , 
        lastupdate = NOW() 
        WHERE summit_product_id = $id 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    

    function insertSummitProduct($data = []){
        $sql = " INSERT INTO tb_summit_product (
            product_id,
            stock_group_id,
            summit_product_qty, 
            summit_product_cost, 
            summit_product_total,
            addby,
            adddate 
        ) VALUES (
            '".$data['product_id']."', 
            '".$data['stock_group_id']."', 
            '".$data['summit_product_qty']."', 
            '".$data['summit_product_cost']."', 
            '".$data['summit_product_total']."', 
            '".$data['addby']."',
            NOW() 
        ); 
        ";

        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {

            $id = mysqli_insert_id(static::$db);

            $sql = "
            CALL insert_stock_summit('".
            $data['stock_group_id']."','".
            $id."','".
            $data['product_id']."','".
            $data['summit_product_qty']."','".
            $data['stock_date']."','".
            $data['summit_product_cost']."'".
            ");
        ";

            //echo $sql . "<br><br>";

            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

            return $id; 
        }else {
            return 0;
        }

    }


    function deleteSummitProductByID($id){

        $sql = "    SELECT * 
                    FROM  tb_summit_product
                    WHERE summit_product_id = '$id' ";   

        $sql_delete=[];
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $sql_delete [] = "
                    CALL delete_stock_summit('".
                    $row['stock_group_id']."','".
                    $row['summit_product_id']."','".
                    $row['product_id']."','".
                    $row['summit_product_qty']."','".
                    $row['summit_product_cost']."'".
                    ");
                ";
               
            }
            $result->close();
        }

        for($i = 0 ; $i < count($sql_delete); $i++){
            mysqli_query(static::$db,$sql_delete[$i], MYSQLI_USE_RESULT);
        }
        
        $sql = " DELETE FROM tb_summit_product WHERE summit_product_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);





    }
}
?>