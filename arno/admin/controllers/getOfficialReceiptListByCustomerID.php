<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/OfficialReceiptModel.php');
$billing_note_list_id = json_decode($_POST['billing_note_list_id'],true);

$invoice_customer_model = new OfficialReceiptModel;
$customer = $invoice_customer_model->generateOfficialReceiptListByCustomerId($_POST['customer_id'],$billing_note_list_id ,$_POST['search'],"" );
echo json_encode($customer);

?>