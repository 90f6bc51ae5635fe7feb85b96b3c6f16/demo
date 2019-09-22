<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/InvoiceSupplierModel.php');
$invoice_supplier_model = new InvoiceSupplierModel;

$jsondata = json_decode($_POST['jsondata']);
$purchase_order_list_id = json_decode($_POST['purchase_order_list_id']);
$invoice_supplier_list_qty = json_decode($_POST['invoice_supplier_list_qty']);
$supplier_id = $_POST['supplier_id'];

$invoice_supplier_list = $invoice_supplier_model->generateInvoiceSupplierListImportBySupplierId($supplier_id,$invoice_supplier_list_id,$invoice_supplier_list_qty,$jsondata);

echo json_encode($invoice_supplier_list);
?>