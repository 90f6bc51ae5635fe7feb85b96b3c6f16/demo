<?php

require_once("BaseModel.php");
class StockIssueListModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getStockIssueListBy($stock_issue_id){
        $sql = " SELECT 
        tb_stock_issue_list.product_id, 
        tb_stock_issue_list.stock_group_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,   
        stock_group_code,   
        stock_group_name,   
        product_name,   
        product_unit_name,   
        stock_issue_list_id, 
        stock_issue_list_qty,
        stock_issue_list_price,
        stock_issue_list_total,
        stock_issue_list_remark 
        FROM tb_stock_issue_list 
        LEFT JOIN tb_product ON tb_stock_issue_list.product_id = tb_product.product_id 
        LEFT JOIN tb_product_unit ON tb_product.product_unit = tb_product_unit.product_unit_id 
        LEFT JOIN tb_stock_group ON tb_stock_issue_list.stock_group_id = tb_stock_group.stock_group_id 
        WHERE stock_issue_id = '$stock_issue_id' 
        ORDER BY stock_issue_list_id 
        ";

        //echo $sql;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }


    function insertStockIssueList($data = []){
        $sql = " INSERT INTO tb_stock_issue_list (
            stock_issue_id,
            product_id,
            stock_group_id,
            stock_issue_list_qty, 
            stock_issue_list_price,
            stock_issue_list_total,
            stock_issue_list_remark,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['stock_issue_id']."', 
            '".$data['product_id']."', 
            '".$data['stock_group_id']."', 
            '".$data['stock_issue_list_qty']."', 
            '".$data['stock_issue_list_price']."', 
            '".$data['stock_issue_list_total']."', 
            '".$data['stock_issue_list_remark']."',
            '".$data['addby']."', 
            NOW(), 
            '".$data['updateby']."', 
            NOW() 
        ); 
        ";
 
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {

            $id = mysqli_insert_id(static::$db); 

            return $id; 
        }else {
            return 0;
        }

    }

    

    function updateStockIssueListById($data,$id){

        $sql = " UPDATE tb_stock_issue_list 
            SET product_id = '".$data['product_id']."', 
            stock_issue_id = '".$data['stock_issue_id']."',  
            stock_group_id = '".$data['stock_group_id']."',  
            stock_issue_list_qty = '".$data['stock_issue_list_qty']."',  
            stock_issue_list_price = '".$data['stock_issue_list_price']."', 
            stock_issue_list_total = '".$data['stock_issue_list_total']."',
            stock_issue_list_remark = '".$data['stock_issue_list_remark']."' 
            WHERE stock_issue_list_id = '$id' 
        "; 
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) { 

           return true;
        }else {
            return false;
        }
    }




    function deleteStockIssueListByID($id){
        $sql = "DELETE FROM tb_stock_issue_list WHERE stock_issue_list_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteStockIssueListByStockIssueID($id){


        $sql = "DELETE FROM tb_stock_issue_list WHERE stock_issue_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteStockIssueListByStockIssueIDNotIN($id,$data){
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


        $sql = "DELETE FROM tb_stock_issue_list WHERE stock_issue_id = '$id' AND stock_issue_list_id NOT IN ($str) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        

    }
}
?>