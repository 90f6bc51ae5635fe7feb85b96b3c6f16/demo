<?php 
require_once('../../models/ProductTypeModel.php');
require_once('../../models/ProductModel.php');
$model_product_type = new ProductTypeModel;
$model_product = new ProductModel;
$product_type = $model_product_type->getProductTypeByName($_POST['product_type_name']);



echo $product_type['product_type_first_char'];
?>