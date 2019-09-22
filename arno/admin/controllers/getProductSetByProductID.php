<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/ProductSetModel.php');
$product_set_model = new ProductSetModel;
$product_sets = $product_set_model->getProductSetByProductID($_POST['product_id']);

echo json_encode($product_sets);
?>