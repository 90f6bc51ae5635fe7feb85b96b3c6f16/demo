<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/InvoiceCustomerListModel.php');
$invoice_customer_list_model = new InvoiceCustomerListModel;
$invoice_customer_lists = $invoice_customer_list_model->getInvoiceCustomerListBy($_POST['invoice_customer_id']);

echo json_encode($invoice_customer_lists);
?>