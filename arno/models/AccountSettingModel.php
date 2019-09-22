<?php

require_once("BaseModel.php");
class AccountSettingModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getAccountSettingBy(){
        $sql = "SELECT * FROM tb_account_setting  
        LEFT JOIN tb_account ON tb_account_setting.account_id = tb_account.account_id  
        LEFT JOIN tb_account_group  ON tb_account_setting.account_group_id = tb_account_group.account_group_id  
        ORDER BY  tb_account_setting.account_group_id , account_setting_id
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


    function getAccountSettingByAccountGroupID($account_group_id){
        $sql = "SELECT * FROM tb_account_setting  
        LEFT JOIN tb_account ON tb_account_setting.account_id = tb_account.account_id  
        LEFT JOIN tb_account_group  ON tb_account_setting.account_group_id = tb_account_group.account_group_id  
        WHERE tb_account_setting.account_group_id = '$account_group_id' 
        ORDER BY  tb_account_setting.account_group_id , account_setting_id
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

    function getAccountSettingByID($id){
        $sql = " SELECT *
        FROM tb_account_setting 
        LEFT JOIN tb_account ON tb_account_setting.account_id = tb_account.account_id  
        LEFT JOIN tb_account_group  ON tb_account_setting.account_group_id = tb_account_group.account_group_id  
        WHERE tb_account_setting.account_setting_id = '$id' 
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

    function updateAccountSettingByID($id,$data = []){
        $sql = " UPDATE tb_account_setting SET 
        account_setting_name = '".$data['account_setting_name']."', 
        account_group_id = '".$data['account_group_id']."', 
        account_id = '".$data['account_id']."'  
        WHERE account_setting_id = '$id ''
        ";

 
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }

    function updateAccountIDByID($id,$data = []){
        $sql = " UPDATE tb_account_setting SET  
        account_id = '".$data['account_id']."'  
        WHERE account_setting_id = '$id' 
        ";
        //echo "<pre>".$sql."</pre>";
 
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }



    function insertAccountSetting($data = []){
        $sql = " INSERT INTO tb_account_setting (
            account_setting_name,
            account_group_id , 
            account_id 
        ) VALUES (
            '".$data['account_setting_name']."', 
            '".$data['account_group_id']."', 
            '".$data['account_id']."'  
        ); 
        ";
 
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }

    }


    function deleteAccountSettingByID($id){
        $sql = " DELETE FROM tb_account_setting WHERE account_setting_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>