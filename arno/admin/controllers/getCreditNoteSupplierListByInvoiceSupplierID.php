<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/CreditNoteSupplierModel.php');
$invoice_supplier_list_id = json_decode($_POST['invoice_supplier_list_id'],true);

$credit_note_supplier_model = new CreditNoteSupplierModel;
$credit_note_supplier = $credit_note_supplier_model->generateCreditNoteSupplierListByInvoiceSupplierId($_POST['invoice_supplier_id'],$invoice_supplier_list_id ,$_POST['search'] );
echo json_encode($credit_note_supplier);
// echo $credit_note_supplier;  

?>