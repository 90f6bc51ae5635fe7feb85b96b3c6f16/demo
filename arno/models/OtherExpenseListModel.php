<?php

require_once("BaseModel.php");
class OtherExpenseListModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getOtherExpenseListBy($other_expense_id){
        $sql = " SELECT 
        other_expense_list_id, 
        other_expense_list_code,
        other_expense_list_name,
        other_expense_list_total
        FROM tb_other_expense_list 
        WHERE other_expense_id = '$other_expense_id' 
        ORDER BY other_expense_list_id 
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


    function insertOtherExpenseList($data = []){
        $sql = " INSERT INTO tb_other_expense_list (
            other_expense_id,
            other_expense_list_code,
            other_expense_list_name,
            other_expense_list_total, 
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['other_expense_id']."', 
            '".$data['other_expense_list_code']."', 
            '".$data['other_expense_list_name']."', 
            '".$data['other_expense_list_total']."', 
            '".$data['addby']."', 
            NOW(), 
            '".$data['updateby']."', 
            NOW() 
        ); 
        ";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }

    function updateOtherExpenseListById($data,$id){

        $sql = " UPDATE tb_other_expense_list 
            SET other_expense_list_name = '".$data['other_expense_list_name']."', 
            other_expense_list_code = '".$data['other_expense_list_code']."',
            other_expense_list_total = '".$data['other_expense_list_total']."'  
            WHERE other_expense_list_id = '$id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function deleteOtherExpenseListByID($id){
        $sql = "DELETE FROM tb_other_expense_list WHERE other_expense_list_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteOtherExpenseListByOtherExpenseID($id){
        $sql = "DELETE FROM tb_other_expense_list WHERE other_expense_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteOtherExpenseListByOtherExpenseListIDNotIN($id,$data){
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

        $sql = "DELETE FROM tb_other_expense_list WHERE other_expense_id = '$id' AND other_expense_list_id NOT IN ($str) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>