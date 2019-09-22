<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/CheckPayModel.php');
$check_pay_model = new CheckPayModel;
$check_pay = $check_pay_model->getCheckPayByCode($_POST['check_pay_code']);

echo json_encode($check_pay);
?>