<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/ProductModel.php');
$model_product = new ProductModel;
$product = $model_product->getProductByID($_POST['product_id']);

echo json_encode($product);
?>