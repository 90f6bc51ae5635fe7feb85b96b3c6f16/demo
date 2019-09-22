<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/UserModel.php');
$user_model = new UserModel;
$user = $user_model->getUserByID($_POST['id']);

echo json_encode($user);

?>