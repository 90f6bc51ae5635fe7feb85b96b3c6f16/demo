<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/CustomerModel.php');
$model_customer = new customerModel;
$customer = $model_customer->getCustomerBy();

echo json_encode($customer);
?>