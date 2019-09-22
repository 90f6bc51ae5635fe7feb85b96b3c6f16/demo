<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/StockGroupModel.php');
$model_stock_group = new StockGroupModel;
$stock_group = $model_stock_group->getStockGroupByEmployeeID($_POST['employee_id']);

echo json_encode($stock_group);
?>