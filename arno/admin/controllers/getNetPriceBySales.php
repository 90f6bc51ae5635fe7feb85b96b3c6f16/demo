<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/DashboardModel.php');
$net_price_model = new DashboardModel;
$net_price = $net_price_model->getNetPriceBySales($_POST['user_id']);
echo json_encode($net_price);
?>