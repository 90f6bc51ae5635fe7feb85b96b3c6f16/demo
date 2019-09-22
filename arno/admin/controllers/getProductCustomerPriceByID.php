<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/ProductCustomerPriceModel.php');
$model_product = new ProductCustomerPriceModel;
$product = $model_product->getProductCustomerPriceByID($_POST['product_id'],$_POST['customer_id']);

echo json_encode($product);
?>