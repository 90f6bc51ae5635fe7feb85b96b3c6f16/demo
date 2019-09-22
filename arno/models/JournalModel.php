<?php

require_once("BaseModel.php");
class JournalModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getJournalBy(){
        $sql = "SELECT * FROM tb_journal   
        ORDER BY  tb_journal.journal_id
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

    function getJournalByID($id){
        $sql = "SELECT * FROM tb_journal   
        WHERE tb_journal.journal_id = '$id' 
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

    function updateJournalByID($id,$data = []){
        $sql = " UPDATE tb_journal SET 
        journal_name = '".$data['journal_name']."'  
        WHERE journal_id = $id 
        ";
 
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    } 



    function insertJournal($data = []){
        $sql = " INSERT INTO tb_journal (
            journal_name
        ) VALUES ( 
            '".$data['journal_name']."' 
        ); 
        ";
 
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }


    function deleteJournalByID($id){
        $sql = " DELETE FROM tb_journal WHERE journal_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>