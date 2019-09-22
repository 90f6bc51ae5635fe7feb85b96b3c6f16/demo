<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/RequestTestModel.php');

$request_standard_list_id = json_decode($_POST['request_standard_list_id'],true);
$request_special_list_id = json_decode($_POST['request_special_list_id'],true);
$request_regrind_list_id = json_decode($_POST['request_regrind_list_id'],true);

$request_test_model = new RequestTestModel;
$supplier = $request_test_model->generateRequestTestListBySupplierId($_POST['supplier_id'],$request_standard_list_id , $request_special_list_id, $request_regrind_list_id ,$_POST['search']);
echo json_encode($supplier);

?>