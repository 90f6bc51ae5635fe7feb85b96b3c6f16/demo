<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/SupplierModel.php');
$supplier_model = new SupplierModel;
$supplier = $supplier_model->getSupplierByCode($_POST['supplier_code']);

echo json_encode($supplier);
?>