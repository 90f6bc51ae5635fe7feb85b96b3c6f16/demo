<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/StockGroupModel.php');

$stock_group_model = new StockGroupModel;
$supplier = $stock_group_model->getStockGroupBy( );


echo json_encode($supplier);

?>