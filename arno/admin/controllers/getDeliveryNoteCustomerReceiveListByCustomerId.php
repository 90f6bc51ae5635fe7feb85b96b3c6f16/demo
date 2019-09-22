<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/DeliveryNoteCustomerReceiveModel.php');

$request_test_list_id = json_decode($_POST['request_test_list_id'],true);

$delivery_note_customer_receive_model = new DeliveryNoteCustomerReceiveModel;
$data = $delivery_note_customer_receive_model->generateDeliveryNoteCustomerReceiveListByCustomerId($_POST['customer_aid'],$request_test_list_id,$_POST['search']);
echo json_encode($data);

?>