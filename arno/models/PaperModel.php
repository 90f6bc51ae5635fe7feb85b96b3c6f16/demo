<?php

require_once("BaseModel.php");
class PaperModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getPaperBy(){
        $sql = "SELECT * FROM tb_paper  
        LEFT JOIN tb_journal ON tb_paper.journal_id = tb_journal.journal_id 
        LEFT JOIN tb_paper_type ON tb_paper.paper_type_id = tb_paper_type.paper_type_id 
        ORDER BY  tb_paper.paper_id
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

    function getPaperByID($id){
        $sql = "SELECT * FROM tb_paper  
        LEFT JOIN tb_journal ON tb_paper.journal_id = tb_journal.journal_id  
        LEFT JOIN tb_paper_type ON tb_paper.paper_type_id = tb_paper_type.paper_type_id 
        WHERE tb_paper.paper_id = '$id' 
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

    function updatePaperByID($id,$data = []){
        $sql = " UPDATE tb_paper SET 
        paper_type_id = '".$data['paper_type_id']."', 
        paper_code = '".$data['paper_code']."', 
        paper_name_th = '".$data['paper_name_th']."', 
        paper_name_en = '".$data['paper_name_en']."', 
        journal_id = '".$data['journal_id']."', 
        journal_description = '".$data['journal_description']."', 
        paper_lock = '".$data['paper_lock']."' 
        WHERE paper_id = $id 
        ";
 
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    } 



    function insertPaper($data = []){
        $sql = " INSERT INTO tb_paper (
            paper_type_id,
            paper_code , 
            paper_name_th,
            paper_name_en,
            journal_id,
            journal_description,
            paper_lock
        ) VALUES (
            '".$data['paper_type_id']."', 
            '".$data['paper_code']."', 
            '".$data['paper_name_th']."',   
            '".$data['paper_name_en']."', 
            '".$data['journal_id']."', 
            '".$data['journal_description']."', 
            '".$data['paper_lock']."' 
        ); 
        ";
 
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }


    function deletePaperByID($id){
        $sql = " DELETE FROM tb_paper WHERE paper_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>