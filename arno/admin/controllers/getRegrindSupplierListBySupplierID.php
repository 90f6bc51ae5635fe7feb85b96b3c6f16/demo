<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/RegrindSupplierReceiveModel.php');
$regrind_supplier_list_id = json_decode($_POST['regrind_supplier_list_id'],true);

$regrind_supplier_receive_model = new RegrindSupplierReceiveModel;
$supplier = $regrind_supplier_receive_model->generateRegrindSupplierReceiveListBySupplierId($_POST['supplier_id'],$regrind_supplier_list_id ,$_POST['search']);
echo json_encode($supplier);

?>