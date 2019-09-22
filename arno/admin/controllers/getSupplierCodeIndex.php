<?php 
require_once('../../models/SupplierModel.php');
$model_supplier = new SupplierModel;
$supplier = $model_supplier->getSupplierCodeIndexByChar($_POST['char']);
echo $supplier['supplier_code'];
?>