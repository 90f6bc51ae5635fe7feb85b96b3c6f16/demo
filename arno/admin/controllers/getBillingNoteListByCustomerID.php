<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/BillingNoteModel.php');
date_default_timezone_set('asia/bangkok');
$invoice_customer_id = json_decode($_POST['invoice_customer_id'],true);
$credit_note_id = json_decode($_POST['credit_note_id'],true);
$debit_note_id = json_decode($_POST['debit_note_id'],true);

$billing_note_model = new BillingNoteModel;
$billing_note = $billing_note_model->generateBillingNoteListByCustomerId($_POST['customer_id'],$invoice_customer_id , $credit_note_id, $debit_note_id ,$_POST['search'] );

for( $i = 0 ; $i < count($billing_note); $i++){
    $timestamp = strtotime($billing_note[$i]['billing_note_list_date']);
    $billing_note[$i]['html_td_date'] = '<td data-order="'.$timestamp.'" >'.$billing_note[$i]['billing_note_list_date'].'</td>' ;
   
    $timestamp = strtotime($billing_note[$i]['billing_note_list_due']);
    $billing_note[$i]['html_td_date_due']= '<td data-order="'.$timestamp.'" >'.$billing_note[$i]['billing_note_list_due'].'</td>' ;
}

echo json_encode($billing_note);

?>