<?php

require_once("BaseModel.php");
class PaperTypeModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getPaperTypeBy(){
        $sql = "SELECT * FROM tb_paper_type   
        ORDER BY  tb_paper_type.paper_type_id
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

    function getPaperTypeByID($id){
        $sql = "SELECT * FROM tb_paper_type   
        WHERE tb_paper_type.paper_type_id = '$id' 
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

    function updatePaperTypeByID($id,$data = []){
        $sql = " UPDATE tb_paper_type SET 
        paper_type_name = '".$data['paper_type_name']."'  
        WHERE paper_type_id = $id 
        ";
 
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    } 



    function insertPaperType($data = []){
        $sql = " INSERT INTO tb_paper_type (
            paper_type_name
        ) VALUES ( 
            '".$data['paper_type_name']."' 
        ); 
        ";
 
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }


    function deletePaperTypeByID($id){
        $sql = " DELETE FROM tb_paper_type WHERE paper_type_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>