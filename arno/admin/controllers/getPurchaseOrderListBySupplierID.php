<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/PurchaseOrderModel.php');
$purchase_request_list_id = json_decode($_POST['purchase_request_list_id'],true);
$customer_purchase_order_list_detail_id = json_decode($_POST['customer_purchase_order_list_detail_id'],true);
$delivery_note_supplier_list_id = json_decode($_POST['delivery_note_supplier_list_id'],true);
$regrind_supplier_receive_list_id = json_decode($_POST['regrind_supplier_receive_list_id'],true);

$request_standard_list_id = json_decode($_POST['request_standard_list_id'],true);
$request_special_list_id = json_decode($_POST['request_special_list_id'],true);
$request_regrind_list_id = json_decode($_POST['request_regrind_list_id'],true);
$purchase_order_list_qty = json_decode($_POST['purchase_order_list_qty'],true);

$purchase_order_model = new PurchaseOrderModel;
$supplier = $purchase_order_model->generatePurchaseOrderListBySupplierId(
    $_POST['supplier_id'],
    $_POST['purchase_request_id'],
    $_POST['type'],
    $purchase_request_list_id , 
    $customer_purchase_order_list_detail_id, 
    $delivery_note_supplier_list_id ,
    $regrind_supplier_receive_list_id ,
    $request_standard_list_id ,
    $request_special_list_id ,
    $request_regrind_list_id ,
    $purchase_order_list_qty ,
    $_POST['search']);
echo json_encode($supplier);

?>