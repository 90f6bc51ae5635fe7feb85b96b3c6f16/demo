<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/InvoiceSupplierModel.php');
$purchase_order_list_id = json_decode($_POST['purchase_order_list_id'],true);
$invoice_supplier_list_qty = json_decode($_POST['invoice_supplier_list_qty'],true);
$invoice_supplier_id = $_POST['invoice_supplier_id'];
$invoice_supplier_model = new InvoiceSupplierModel;
$supplier = $invoice_supplier_model->generateInvoiceSupplierListBySupplierId(
    $_POST['supplier_id'],
    $purchase_order_list_id ,
    $invoice_supplier_list_qty,
    $_POST['search'],
    '',
    $invoice_supplier_id
    );
echo json_encode($supplier);

?>