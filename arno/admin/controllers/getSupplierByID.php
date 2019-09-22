<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/SupplierModel.php');
$model_supplier = new SupplierModel;
$supplier = $model_supplier->getSupplierByID($_POST['supplier_id']);

echo json_encode($supplier);
?>