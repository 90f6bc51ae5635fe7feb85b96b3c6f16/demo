<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/CustomerModel.php');

$model_customer = new customerModel;
$customer = $model_customer->getEndUserByCustomerID($_POST['customer_id'],$_POST['sale_id']);

echo json_encode($customer);
?>