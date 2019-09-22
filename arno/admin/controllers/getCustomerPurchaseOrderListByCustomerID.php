<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/CustomerPurchaseOrderModel.php');

$delivery_note_customer_list_id = json_decode($_POST['delivery_note_customer_list_id'],true);

$customer_purchase_order_model = new CustomerPurchaseOrderModel;
$data = $customer_purchase_order_model->generateCustomerPurchaseOrderListByCustomerId(
    $_POST['customer_id'],
    $delivery_note_customer_list_id,
    $_POST['search']);
echo json_encode($data);

?>