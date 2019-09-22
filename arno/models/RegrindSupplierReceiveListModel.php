<?php

require_once("BaseModel.php");
class RegrindSupplierReceiveListModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }
    function getRegrindSupplierReceiveListByID($id){
        $sql = " SELECT tb_regrind_supplier_receive_list.product_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,   
        regrind_supplier_receive_list_id, 
        regrind_supplier_receive_list_scrap_qty,
        regrind_supplier_receive_list_qty,
        regrind_supplier_receive_list_remark 
        FROM tb_regrind_supplier_receive_list LEFT JOIN tb_product ON tb_regrind_supplier_receive_list.product_id = tb_product.product_id 
        WHERE regrind_supplier_receive_id = '$id' 
        ORDER BY regrind_supplier_receive_list_id 
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
    function getRegrindSupplierReceiveListBy($regrind_supplier_receive_id){
        $sql = " SELECT tb_regrind_supplier_receive_list.product_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name, 
        tb_regrind_supplier.regrind_supplier_id , 
        regrind_supplier_list_qty,   
        regrind_supplier_receive_id,   
        regrind_supplier_receive_list_id, 
        regrind_supplier_receive_list_scrap_qty,
        regrind_supplier_receive_list_qty,
        regrind_supplier_receive_list_remark,
        tb_regrind_supplier_receive_list.regrind_supplier_list_id,
        tb_regrind_supplier.supplier_id,
        tb_regrind_supplier.customer_id
        FROM tb_regrind_supplier_receive_list 
        LEFT JOIN tb_product ON tb_regrind_supplier_receive_list.product_id = tb_product.product_id 
        LEFT JOIN tb_regrind_supplier_list on tb_regrind_supplier_list.regrind_supplier_list_id =  tb_regrind_supplier_receive_list.regrind_supplier_list_id
        LEFT JOIN tb_regrind_supplier on tb_regrind_supplier.regrind_supplier_id = tb_regrind_supplier_list.regrind_supplier_id
        WHERE regrind_supplier_receive_id = '$regrind_supplier_receive_id' 
        ORDER BY regrind_supplier_receive_list_id 
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
    function getRegrindSupplierReceiveListByMobile($supplier_id = "",$user_id = "",$product_id = []){
        $str_product_id ="'0'";
        $str_user = "";
        if($user_id != ""){
            $str_user = " AND tb_regrind_supplier.employee_id = '$user_id' ";
        }
        if($supplier_id !=""){
            $str_supplier = " AND tb_regrind_supplier.supplier_id = '$supplier_id'   ";
        }
        if(is_array($product_id) && count($product_id) > 0){ 

            $str_product_id ="";
            for($i=0; $i < count($product_id) ;$i++){
                $str_product_id .= " '".$product_id[$i]."' ";
                if($i + 1 < count($product_id)){
                    $str_product_id .= ",";
                }
            }

        }else if ($product_id != ''){
            $str_product_id = "".$product_id."";
        }else{
            $str_product_id="'0'";
        }

        $sql = " SELECT 
        '2_Receive' as 'regrind_type',
        tb_regrind_supplier_receive_list.product_id, 
        tb_regrind_supplier_receive.regrind_supplier_receive_id as 'id',
        CONCAT(product_code_first,product_code) as product_code, 
        product_name, 
        regrind_supplier_receive_code as code,
        tb_regrind_supplier_receive.regrind_supplier_receive_id,   
        regrind_supplier_receive_list_id, 
        tb_regrind_supplier_list.regrind_supplier_list_id, 
        regrind_supplier_receive_list_qty as qty,
        regrind_supplier_receive_list_scrap_qty as scrap_qty,
        regrind_supplier_receive_list_remark ,
        regrind_supplier_receive_date as 'date' ,
        'Send' as 'complete'
        FROM tb_regrind_supplier_receive_list 
        LEFT JOIN tb_regrind_supplier_list ON tb_regrind_supplier_receive_list.regrind_supplier_list_id = tb_regrind_supplier_list.regrind_supplier_list_id 
        LEFT JOIN tb_regrind_supplier ON tb_regrind_supplier_list.regrind_supplier_id = tb_regrind_supplier.regrind_supplier_id 

        LEFT JOIN tb_product ON tb_regrind_supplier_receive_list.product_id = tb_product.product_id 
        LEFT JOIN tb_regrind_supplier_receive ON tb_regrind_supplier_receive_list.regrind_supplier_receive_id = tb_regrind_supplier_receive.regrind_supplier_receive_id 
        WHERE tb_regrind_supplier_receive_list.product_id IN ($str_product_id) 
        $str_user
        $str_supplier
        ORDER BY STR_TO_DATE(`date`,'%d-%m-%Y %H:%i:%s') DESC, code DESC ,regrind_supplier_receive_list_id DESC
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
    function insertRegrindSupplierReceiveList($data = []){
        $sql = " INSERT INTO tb_regrind_supplier_receive_list (
            regrind_supplier_receive_id,
            product_id,
            regrind_supplier_receive_list_qty,
            regrind_supplier_receive_list_scrap_qty,
            regrind_supplier_receive_list_remark,
            regrind_supplier_list_id,
            stock_group_id,
            addby,
            adddate 
        ) VALUES (
            '".$data['regrind_supplier_receive_id']."', 
            '".$data['product_id']."', 
            '".$data['regrind_supplier_receive_list_qty']."', 
            '".$data['regrind_supplier_receive_list_scrap_qty']."', 
            '".$data['regrind_supplier_receive_list_remark']."',
            '".$data['regrind_supplier_list_id']."',
            '".$data['stock_group_id']."',
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

    function updateRegrindSupplierReceiveListById($data,$id){

        $sql = " UPDATE tb_regrind_supplier_receive_list 
            SET product_id = '".$data['product_id']."', 
            regrind_supplier_receive_list_qty = '".$data['regrind_supplier_receive_list_qty']."',
            regrind_supplier_receive_list_remark = '".$data['regrind_supplier_receive_list_remark']."' 
            WHERE regrind_supplier_receive_list_id = '$id'
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    } 

    function updatePurchaseOrderId($regrind_supplier_receive_list_id,$purchase_order_list_id){
        $sql = " UPDATE tb_regrind_supplier_receive_list 
            SET purchase_order_list_id = '$purchase_order_list_id' 
            WHERE regrind_supplier_receive_list_id = '$regrind_supplier_receive_list_id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function updateRegrindSupplierListId($regrind_supplier_receive_list_id,$regrind_supplier_list_id){
        $sql = " UPDATE tb_regrind_supplier_receive_list 
            SET regrind_supplier_list_id = '$regrind_supplier_list_id' 
            WHERE regrind_supplier_receive_list_id = '$regrind_supplier_receive_list_id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function deleteRegrindSupplierReceiveListByID($id){
        $sql = "DELETE FROM tb_regrind_supplier_receive_list WHERE regrind_supplier_receive_list_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteRegrindSupplierReceiveListByRegrindSupplierReceiveID($id){
        $sql = "DELETE FROM tb_regrind_supplier_receive_list WHERE regrind_supplier_receive_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteRegrindSupplierReceiveListByRegrindSupplierReceiveIDNotIN($id,$data){
        $str ='';
        if(is_array($data)){ 
            for($i=0; $i < count($data) ;$i++){
                if($data[$i] != ""){
                    $str .= $data[$i];
                    if($i + 1 < count($data)){
                        $str .= ',';
                    }
                }
            }
        }else if ($data != ''){
            $str = $data;
        }else{
            $str='0';
        }

        if( $str==''){
            $str='0';
        }

        $sql = "DELETE FROM tb_regrind_supplier_receive_list WHERE regrind_supplier_receive_id = '$id' AND regrind_supplier_receive_list_id NOT IN ($str) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function getRegrindSupplierReceiveListBySupplierIDAndProductIDNotThisReceiveID($supplier_id='',$product_id=[],$receive_id){
        if(is_array($product_id) && count($product_id) > 0){ 

            $str_product_id ="";
            for($i=0; $i < count($product_id) ;$i++){
                $str_product_id .= " '".$product_id[$i]."' ";
                if($i + 1 < count($product_id)){
                    $str_product_id .= ",";
                }
            }

        }else if ($product_id != ''){
            $str_product_id = "".$product_id."";
        }else{
            $str_product_id="'0'";
        }
        $sql = "SELECT * 
        FROM tb_regrind_supplier_receive_list
        LEFT JOIN tb_regrind_supplier_receive on tb_regrind_supplier_receive.regrind_supplier_receive_id = tb_regrind_supplier_receive_list.regrind_supplier_receive_id
        WHERE tb_regrind_supplier_receive_list.product_id in ($str_product_id)
        AND tb_regrind_supplier_receive.supplier_id ='$supplier_id'
        AND tb_regrind_supplier_receive_list.regrind_supplier_receive_id < '$receive_id'
        ";
    // echo "<pre>$sql</pre>";
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }
    function getRegrindSupplierReceiveListByReceiveID($id=''){
        $sql = "SELECT * 
        FROM tb_regrind_supplier_receive_list
        WHERE tb_regrind_supplier_receive_list.regrind_supplier_receive_id = '$id'";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }
    function RegrindSupplierReceiveListByReceiveIDComplete($id=''){
        $sql = "SELECT * 
        FROM tb_regrind_supplier_receive_list 
        LEFT JOIN tb_regrind_supplier_list ON tb_regrind_supplier_receive_list.regrind_supplier_list_id = tb_regrind_supplier_list.regrind_supplier_list_id
        LEFT JOIN tb_regrind_supplier ON tb_regrind_supplier_list.regrind_supplier_id = tb_regrind_supplier.regrind_supplier_id
        WHERE regrind_supplier_receive_id = '$id'";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }


}
?>