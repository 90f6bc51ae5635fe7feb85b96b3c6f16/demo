<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/ProductCustomerModel.php');
$model_product = new ProductCustomerModel;
$product_customer = $model_product->getProductCustomerByProductName($_POST['product_id'],$_POST['customer_id']);

echo json_encode($product_customer);
?>