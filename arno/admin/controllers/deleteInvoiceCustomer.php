<?php  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");


require_once('../../models/InvoiceCustomerModel.php');
 
$invoice_customer_model = new InvoiceCustomerModel;
 
if(isset($_POST['invoice_customer_id'])){
    $invoice_customer_id =  $_POST['invoice_customer_id'];
    $invoice_customers = $invoice_customer_model->deleteInvoiceCustomerByID($invoice_customer_id);
    echo json_encode($invoice_customers);
}

?>