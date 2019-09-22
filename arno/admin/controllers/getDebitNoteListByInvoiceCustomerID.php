<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/DebitNoteModel.php');
$invoice_customer_list_id = json_decode($_POST['invoice_customer_list_id'],true);

$Debit_note_model = new DebitNoteModel;
$Debit_note = $Debit_note_model->generateDebitNoteListByInvoiceCustomerId($_POST['invoice_customer_id'],$invoice_customer_list_id ,$_POST['search'] );
echo json_encode($Debit_note);

?>