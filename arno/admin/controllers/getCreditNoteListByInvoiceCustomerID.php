<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/CreditNoteModel.php');
$invoice_customer_list_id = json_decode($_POST['invoice_customer_list_id'],true);

$credit_note_model = new CreditNoteModel;
$credit_note = $credit_note_model->generateCreditNoteListByInvoiceCustomerId($_POST['invoice_customer_id'],$invoice_customer_list_id ,$_POST['search'] );
echo json_encode($credit_note);

?>