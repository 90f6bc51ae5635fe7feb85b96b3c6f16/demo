<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/InvoiceCustomerModel.php');
$invoice_customer_model = new InvoiceCustomerModel;
$invoice_customer = $invoice_customer_model->getInvoiceCustomerByCode($_POST['invoice_customer_code']);

echo json_encode($invoice_customer);
?>