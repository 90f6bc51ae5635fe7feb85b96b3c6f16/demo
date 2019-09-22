<?php

require_once("BaseModel.php");
class NotificationModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }

        if (mysqli_connect_errno())
        {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

    }

    private function sendMessageWEB($title = "", $message = "", $play_kry = "ALL", $url=''){
  
        $API_URL = "https://onesignal.com/api/v1/notifications";
        $APP_ID = '430c4d4b-cd09-413b-909a-56a3284dc108';
        $API_KEY = 'YmNhYWQ0YWYtY2I0NC00N2RlLTlmMjQtNzFhNjUyNTJlOGUw';
        $content = array(
          "en" => $message,
        );
        $headings = array(
            "en" => $title,
        );
        if(is_array($play_kry)){ 
            $myJSON = json_encode($play_kry); 
            $fields = array(
                'app_id' => $APP_ID,
                'include_player_ids' => $play_kry,
                'url' => $url,
                'contents' => $content,
                'headings' => $headings
            );
          }else if(strtolower($play_kry)=='all'){ 
            $fields = array(
                'app_id' => $APP_ID,
                'included_segments' => array('All'),
                'url' => $url,
                'contents' => $content,
                'headings' => $headings
            );
          }
      
        $fields = json_encode($fields);
      
        
        $ch = curl_init();
      
        curl_setopt($ch, CURLOPT_URL, $API_URL);
      
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8', 'Authorization: Basic '.$API_KEY));
      
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
      
        curl_setopt($ch, CURLOPT_POST, TRUE);
      
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
      
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
      
        $response = curl_exec($ch);
      
        curl_close($ch);
      
        return $response;
      
    }

    function sendMessageAPP($title = "", $message = "", $play_kry = "ALL",$id=''){
        
        $API_URL = "https://onesignal.com/api/v1/notifications";
        $APP_ID = '430c4d4b-cd09-413b-909a-56a3284dc108';
        $API_KEY = 'YmNhYWQ0YWYtY2I0NC00N2RlLTlmMjQtNzFhNjUyNTJlOGUw';
        $message = strip_tags($message);
        $content = array(
          "en" => $message,
        );
        $headings = array(
            "en" => $title,
        );

        
        if(is_array($play_kry)){ 
            $myJSON = json_encode($play_kry); 
            $fields = array(
                'app_id' => $APP_ID,
                'include_player_ids' => $play_kry,
                'data' => array("page" => $title,
                                "id" => $id
                              ),
                'contents' => $content
            );
          }else if(strtolower($play_kry)=='all'){ 
            $fields = array(
                'app_id' => $APP_ID,
                'included_segments' => array('All'),
                'data' => array("page" => $title,
                                "id" => $id
                              ),
                'contents' => $content
            );
          }



      
        
        $fields = json_encode($fields);
        //   print("\nJSON sent:\n");
        //   print($fields);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $API_URL);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8', 'Authorization: Basic '.$API_KEY));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    
        $response = curl_exec($ch);
        curl_close($ch);
        
        return $response;
      }

    function getNotificationBy($user_id,$str =''){
        if($str != ""){
            $str = "AND notification_seen_date ='' ";
        }

        $sql = " SELECT * 
        FROM tb_notification 
        WHERE user_id = '$user_id' 
        $str
        ORDER BY STR_TO_DATE(notification_date,'%Y-%m-%d %H:%i:%s') DESC 
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
    function getNotificationByUnseen($user_id,$type ='',$str =''){
        if($str != ""){
            $str = "AND notification_seen_date ='' ";
        }
        if($type != "" ){
            $type = "AND notification_type ='$type' ";
        }
        $sql = " SELECT * 
        FROM tb_notification 
        WHERE notification_seen='0' AND user_id = '$user_id' 
        $str
        $type
        ORDER BY STR_TO_DATE(notification_date,'%Y-%m-%d %H:%i:%s') DESC 
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
    function getNotificationBySeen($user_id,$type ='',$str =''){
        if($str != ""){
            $str = "AND notification_seen_date ='' ";
        }
        if($type != "" ){
            $type = "AND notification_type ='$type' ";
        }
        $sql = " SELECT * 
        FROM tb_notification 
        WHERE notification_seen='1' AND user_id = '$user_id' 
        $str
        $type
        ORDER BY STR_TO_DATE(notification_date,'%Y-%m-%d %H:%i:%s') DESC 
         ";
        // echo $sql;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }


    function getNotificationByType($user_id,$type,$str =''){
        if($str != ""){
            $str = "AND notification_seen_date ='' ";
        }
        
        if($type != "" ){
            $type = "AND notification_type ='$type' ";
        }
        $sql = " SELECT * 
        FROM tb_notification 
        WHERE user_id = '$user_id' 
        $str 
        $type 
        ORDER BY STR_TO_DATE(notification_date,'%Y-%m-%d %H:%i:%s') DESC 
         ";
        //  echo $sql;
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }


    function getNotificationByID($id){
        $sql = " SELECT * 
        FROM tb_notification 
        WHERE notification_id = '$id' 
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


    function setNotificationByUserID($type="",$type_id = '',$detail="",$url="",$user_id){

        $sql = "
        SELECT tb_1.user_id,IFNULL(tb_3.player_id,'') AS user_player_id,tb_3.player_type
        FROM tb_user AS tb_1
        LEFT JOIN tb_player AS tb_3 ON tb_1.user_id = tb_3.user_id 
        WHERE tb_1.user_id = '$user_id' 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           
            $str=array();
            $player_id = array();
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){

                if($row['user_player_id'] != ""){
                    $player_id[] = $row['user_player_id'];
                    $player_type = $row['player_type'];
                }
                

                $str[] =" INSERT INTO tb_notification  ( 
                    user_id, 
                    notification_type, 
                    notification_type_id,
                    notification_seen, 
                    notification_date,
                    notification_seen_date,
                    notification_detail,
                    notification_url) 
                VALUES ('".
                    $row['user_id']."','".
                    $type."','".
                    $type_id."','".
                    "0"."',".
                    "NOW()".",'".
                    "','".
                    $detail."','".
                    $url."')";
            }
            $result->close();

            

            for($i=0; $i < count($str); $i++){
                mysqli_query(static::$db,$str[$i], MYSQLI_USE_RESULT);
            }

            if(count($player_id) > 0){
                if ($player_type == 1) {
                    # code...
                    $result = $this->sendMessageAPP($type, $detail, $player_id,$type_id);
                } else {
                    # code...
                    $result = $this->sendMessageWEB($type, $detail, $player_id, $this->page_url.$url);
                }
                
               
                //selft::sendMessage($type, $detail, 'ALL', "");
            }
            
        }

    }


    function setNotification($type="",$type_id = '',$detail="",$url="",$page="",$status=""){
        $sql = "
        SELECT tb_1.user_id,IFNULL(tb_3.player_id,'') AS user_player_id,tb_3.player_type
        FROM tb_user AS tb_1
        LEFT JOIN tb_license AS tb_2 ON tb_1.license_id = tb_2.license_id 
        LEFT JOIN tb_player AS tb_3 ON tb_1.user_id = tb_3.user_id 
        WHERE  tb_2.$page = '$status' 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           
            $str=array();
            $player_id = array();
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){

                if($row['user_player_id'] != ""){
                    $player_id[] = $row['user_player_id'];
                    $player_type = $row['player_type'];
                }
                

                $str[] =" INSERT INTO tb_notification  ( 
                    user_id, 
                    notification_type, 
                    notification_type_id,
                    notification_seen, 
                    notification_date,
                    notification_seen_date,
                    notification_detail,
                    notification_url) 
                VALUES ('".
                    $row['user_id']."','".
                    $type."','".
                    $type_id."','".
                    "0"."',".
                    "NOW()".",'".
                    "','".
                    $detail."','".
                    $url."')";
            }
            $result->close();

            

            for($i=0; $i < count($str); $i++){
                mysqli_query(static::$db,$str[$i], MYSQLI_USE_RESULT);
            }

            if(count($player_id) > 0){
                if ($player_type == 1) {
                    # code...
                    $result = $this->sendMessageAPP($type, $detail, $player_id,$type_id);
                } else {
                    # code...
                    $result = $this->sendMessageWEB($type, $detail, $player_id, $this->page_url.$url);
                }
            }
            
        }

    }

    function setNotificationSeenByID($id){
        $sql = " UPDATE tb_notification SET 
        notification_seen_date = NOW() , 
        notification_seen = '1'  
        WHERE notification_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
           return false;
        }


    }
    function setNotificationUnSeenByID($id){
        $sql = " UPDATE tb_notification SET 
        notification_seen_date = '' , 
        notification_seen = '0'  
        WHERE notification_id = $id 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
           return false;
        }


    }

    function setNotificationSeenByURL($url){
        $sql = " UPDATE tb_notification SET 
        notification_seen_date = NOW() , 
        notification_seen = '1'  
        WHERE notification_url LIKE ('%$url%') 
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }


    function setNotificationSeenByTypeID($type="",$id=""){
        $sql = " UPDATE tb_notification SET 
        notification_seen_date = NOW() , 
        notification_seen = '1'  
        WHERE notification_type = '$type' AND notification_type_id = '$id'  
        ";

        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }



    function deleteNotificationByTypeID($type,$id){
        $sql = " DELETE FROM tb_notification WHERE notification_type = '$type' AND notification_type_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }


    function deleteNotificationByID($id){
        $sql = " DELETE FROM tb_notification WHERE notification_id = '$id' ";
        // mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return true;
         }else {
             return false;
         }

    }
}
?>