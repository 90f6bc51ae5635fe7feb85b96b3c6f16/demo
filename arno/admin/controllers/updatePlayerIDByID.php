<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/UserModel.php');
$user_model = new UserModel;
// $player_id =  $_POST['user_player_id'];
$player_id =  "7ea69e73-8006-4ccf-b2aa-725cdf8e73b3";
$user_id= $_POST['user_id'] ;
// $product = $user_model->updatePlayerIDByID($_POST['user_id'],$_POST['user_player_id']);


if(isset($player_id)){ 
    $check = $user_model -> checkPlayer($player_id) ;
    if (count($check)>0) { 
        if ($check["user_id"] != $user_id) { 
            $player_id = $user_model-> updateTbPlayer($player_id, $user_id);   
        }  
    } else { 
        $player_id = $user_model-> insertPlayer($player_id, $user_id); 
    } 

} else {
    $player_id = 0;
}
echo json_encode($player_id);
?>