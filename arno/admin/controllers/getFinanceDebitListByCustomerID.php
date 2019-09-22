<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/FinanceDebitModel.php');
date_default_timezone_set('asia/bangkok');
$invoice_customer_id = json_decode($_POST['invoice_customer_id'],true);
$credit_note_id = json_decode($_POST['credit_note_id'],true);
$debit_note_id = json_decode($_POST['debit_note_id'],true);

$finance_debit_model = new FinanceDebitModel;
$finance_debit = $finance_debit_model->generateFinanceDebitListByCustomerId($_POST['customer_id'],$invoice_customer_id ,$credit_note_id, $debit_note_id, $_POST['search'] );

for( $i = 0 ; $i < count($finance_debit); $i++){
    $timestamp = strtotime($finance_debit[$i]['finance_debit_list_date']);
    $finance_debit[$i]['html_td_date'] = '<td data-order="'.$timestamp.'" >'.$finance_debit[$i]['finance_debit_list_date'].'</td>' ;
   
    $timestamp = strtotime($finance_debit[$i]['finance_debit_list_due']);
    $finance_debit[$i]['html_td_date_due']= '<td data-order="'.$timestamp.'" >'.$finance_debit[$i]['finance_debit_list_due'].'</td>' ;
}

echo json_encode($finance_debit);

?>