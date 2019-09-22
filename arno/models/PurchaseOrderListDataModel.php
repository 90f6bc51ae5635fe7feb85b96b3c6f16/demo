<?php

require_once("BaseModel.php");
class PurchaseOrderListDataModel extends BaseModel{

    function __construct(){
 
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getPurchaseOrderListDataBy($customer_purchase_order_list_detail_id = ""){
        $str = "1";
        if($customer_purchase_order_list_detail_id != ""){
            $str = "customer_purchase_order_list_id = '$customer_purchase_order_list_detail_id'";
        }
        $sql = "    SELECT tb_1.stock_group_name,  
                    WHERE $str ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }
    function insertPurchaseOrderListData($data = []){
        $sql = " INSERT INTO tb_purchase_order_list_data ( 
            purchase_order_list_id,
            customer_purchase_order_list_detail_id
        ) VALUES ( 
            '".$data['purchase_order_list_id']."', 
            '".$data['customer_purchase_order_list_detail_id']."'
        ); 
        ";

//echo $sql."<br><br>";
        //echo $sql;
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return '';
        }

    }

    function deletePurchaseOrderListDataByPurchaseOrderListIDNotIN($id,$data=[]){
        $str ='';
        if(is_array($data)){ 
            if(count($data)>0){
                for($i=0; $i < count($data) ;$i++){
                    $str .= " '".$data[$i]."' ";
                    if($i + 1 < count($data)){
                        $str .= ',';
                    }
                }
            }else{
                $str='0';
            }
        }else if ($data != ''){
            $str = $data;
        }else{
            $str='0';
        }

        $sql = "DELETE FROM  tb_purchase_order_list_data  WHERE purchase_order_list_id IN ( 
            SELECT purchase_order_list_id 
            FROM tb_purchase_order_list  
            WHERE  purchase_order_id = '$id' AND purchase_order_list_id NOT IN ($str)
            ) ";     
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }

    function deletePurchaseOrderListDataByCPO_List_Detail_IDNotIN($id,$data=[]){
        $str ='';
        if(is_array($data)){ 
            if(count($data)>0){
                for($i=0; $i < count($data) ;$i++){
                    $str .= " '".$data[$i]."' ";
                    if($i + 1 < count($data)){
                        $str .= ',';
                    }
                }
            }else{
                $str='0';
            }
        }else if ($data != ''){
            $str = $data;
        }else{
            $str='0';
        }

        $sql = "DELETE FROM tb_purchase_order_list_data WHERE customer_purchase_order_list_detail_id IN (
                SELECT customer_purchase_order_list_detail_id 
                FROM tb_customer_purchase_order_list_detail  
                WHERE  customer_purchase_order_list_id = '$id' AND customer_purchase_order_list_detail_id NOT IN ($str)

        )";     
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
    }
}
?>