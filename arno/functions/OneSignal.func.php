<?php
class OnesignalModel {
    function sendMessage($title,$message,$play_kry,$url=''){
  
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
}
?>