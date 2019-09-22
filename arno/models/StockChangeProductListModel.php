<?php

require_once("BaseModel.php");
class StockChangeProductListModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getStockChangeProductListBy($stock_change_product_id){
        $sql = " SELECT 
        stock_group_id_new,
        stock_group_id_old,
        tb3.stock_group_name as stock_group_name_new,
        tb4.stock_group_name stock_group_name_old,
        tb_stock_change_product_list.product_id_old, 
        CONCAT(tb1.product_code_first,tb1.product_code) as product_code_old,
        tb1.product_name as product_name_old,   
        tb_stock_change_product_list.product_id_new, 
        CONCAT(tb2.product_code_first,tb2.product_code) as product_code_new, 
        tb2.product_name as product_name_new,
        stock_change_product_list_id, 
        stock_change_product_list_qty,
        stock_change_product_list_price,
        stock_change_product_list_total,
        stock_change_product_list_remark 
        FROM tb_stock_change_product_list 
        LEFT JOIN tb_product as tb1 ON tb_stock_change_product_list.product_id_old = tb1.product_id 
        LEFT JOIN tb_product as tb2 ON tb_stock_change_product_list.product_id_new = tb2.product_id 
        LEFT JOIN tb_stock_group as tb3 ON tb_stock_change_product_list.stock_group_id_new = tb3.stock_group_id 
        LEFT JOIN tb_stock_group as tb4 ON tb_stock_change_product_list.stock_group_id_old = tb4.stock_group_id 
        WHERE stock_change_product_id = '$stock_change_product_id' 
        ORDER BY stock_change_product_list_id 
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


    function insertStockChangeProductList($data = []){
        $sql = " INSERT INTO tb_stock_change_product_list (
            stock_change_product_id,
            product_id_new,
            product_id_old,
            stock_group_id_new,
            stock_group_id_old,
            stock_change_product_list_qty, 
            stock_change_product_list_price, 
            stock_change_product_list_total, 
            stock_change_product_list_remark,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['stock_change_product_id']."', 
            '".$data['product_id_new']."', 
            '".$data['product_id_old']."', 
            '".$data['stock_group_id_new']."', 
            '".$data['stock_group_id_old']."', 
            '".$data['stock_change_product_list_qty']."', 
            '".$data['stock_change_product_list_price']."', 
            '".$data['stock_change_product_list_total']."', 
            '".$data['stock_change_product_list_remark']."',
            '".$data['addby']."', 
            NOW(), 
            '".$data['updateby']."', 
            NOW() 
        ); 
        ";

        //echo $sql . "<br><br>";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {

            $id = mysqli_insert_id(static::$db);
        /*------------------------------ คำนวนต้นทุนสินค้าในกรณีเพิ่ม ---------------------------*/



        /*------------------------------ สิ้นสุด คำนวนต้นทุนสินค้าในกรณีเพิ่ม ----------------------*/

            return $id; 
        }else {
            return 0;
        }

    }

    

    function updateStockChangeProductListById($data,$id){

        $sql = " UPDATE tb_stock_change_product_list 
            SET product_id_new = '".$data['product_id_new']."', 
            product_id_old = '".$data['product_id_old']."',  
            stock_group_id_new = '".$data['stock_group_id_new']."',  
            stock_group_id_old = '".$data['stock_group_id_old']."',  
            stock_change_product_id = '".$data['stock_change_product_id']."',  
            stock_change_product_list_qty = '".$data['stock_change_product_list_qty']."',  
            stock_change_product_list_price = '".$data['stock_change_product_list_price']."',  
            stock_change_product_list_total = '".$data['stock_change_product_list_total']."',  
            stock_change_product_list_remark = '".$data['stock_change_product_list_remark']."' 
            WHERE stock_change_product_list_id = '$id' 
        ";

        //echo $sql . "<br><br>";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
        /*------------------------------ คำนวนต้นทุนสินค้าในกรณีแก้ไข ---------------------------*/



        /*------------------------------ สิ้นสุด คำนวนต้นทุนสินค้าในกรณีแก้ไข----------------------*/

           return true;
        }else {
            return false;
        }
    }




    function deleteStockChangeProductListByID($id){
        $sql = "DELETE FROM tb_stock_change_product_list WHERE stock_change_product_list_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteStockChangeProductListByStockChangeProductID($id){


        $sql = "DELETE FROM tb_stock_change_product_list WHERE stock_change_product_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteStockChangeProductListByStockChangeProductIDNotIN($id,$data){
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
        /*------------------------------ คำนวนต้นทุนสินค้าในกรณีลบ ---------------------------*/



        /*------------------------------ สิ้นสุด คำนวนต้นทุนสินค้าในกรณีลบ ----------------------*/
        $sql = "DELETE FROM tb_stock_change_product_list WHERE stock_change_product_id = '$id' AND stock_change_product_list_id NOT IN ($str) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT); 

        

    }
}
?>