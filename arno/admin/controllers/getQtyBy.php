<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/StockGroupModel.php');
$stock_group_model = new StockGroupModel;
$qty = $stock_group_model->getQtyBy($_POST['stock_group_id'],$_POST['product_id']);

echo json_encode($qty);
?>