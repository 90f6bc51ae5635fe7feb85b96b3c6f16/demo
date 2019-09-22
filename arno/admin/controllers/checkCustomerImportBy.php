<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/CustomerModel.php');
require_once('../../models/UserModel.php'); 
$customer_model = new CustomerModel;
$user_model = new UserModel;
$customer = $customer_model->getCustomerByCode($_POST['customer_code']);
$dealer = $customer_model->getCustomerByCode($_POST['dealer_code']);
$saler = $user_model->getUserByCode($_POST['sale_code']);

$data = [];

$data['result'] = true;
$data['saler'] = $saler;
$data['dealer'] = $dealer;
$data['customer'] = $customer;


if(count($saler) == 0){
    $data['result'] = false; 
}


if(count($dealer) == 0){
    $data['result'] = false; 
}


if(count($customer) > 0){
    $data['result'] = false; 
}



echo json_encode($data);
?>