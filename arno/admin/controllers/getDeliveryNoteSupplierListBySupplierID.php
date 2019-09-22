<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/DeliveryNoteSupplierModel.php');

$request_test_list_id = json_decode($_POST['request_test_list_id'],true);

$delivery_note_supplier_model = new DeliveryNoteSupplierModel;
$data = $delivery_note_supplier_model->generateDeliveryNoteSupplierListBySupplierId($_POST['supplier_id'],$request_test_list_id,$_POST['search']);
echo json_encode($data);

?>