<?php 
require_once('../../models/ProductModel.php');

date_default_timezone_set('asia/bangkok');

$product_model = new ProductModel; 

$last_code = $product_model->getProductLastID($_POST['format'],3);
echo $last_code;
?>