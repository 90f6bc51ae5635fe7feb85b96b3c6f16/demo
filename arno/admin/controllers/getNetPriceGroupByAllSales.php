<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/DashboardModel.php');
date_default_timezone_set('asia/bangkok');
$net_price_model = new DashboardModel;
$net_price = $net_price_model->getNetPriceGroupByAllSales(date("Y"));
echo json_encode($net_price);
?>