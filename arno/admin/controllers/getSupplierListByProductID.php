<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/ProductSupplierModel.php');

$product_supplier_model = new ProductSupplierModel;
$supplier = $product_supplier_model->getProductSupplierByAjax($_POST['product_id'],'','','Active' );


echo json_encode($supplier);

?>