<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/CheckModel.php');
$check_model = new CheckModel;
$check = $check_model->getCheckByCode($_POST['check_code']);

echo json_encode($check);
?>