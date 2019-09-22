<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/InvoiceSupplierModel.php');
$invoice_supplier_model = new InvoiceSupplierModel;
$invoice_supplier = $invoice_supplier_model->getInvoiceSupplierByKeyword($_POST['keyword']);

echo json_encode($invoice_supplier);
?>