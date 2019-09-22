<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/ProductModel.php');
$model_product = new ProductModel;
$product = $model_product->getProductScaleByID($_POST['product_id'],$_POST['product_scale_qty']);

echo json_encode($product);
?>