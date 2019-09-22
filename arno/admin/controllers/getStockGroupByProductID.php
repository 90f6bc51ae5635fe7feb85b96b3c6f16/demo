<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/StockGroupModel.php');

$stock_group_model = new StockGroupModel;
$stock_group = $stock_group_model->getStockGroupByProductID($_POST['product_id']);


echo json_encode($stock_group);

?>