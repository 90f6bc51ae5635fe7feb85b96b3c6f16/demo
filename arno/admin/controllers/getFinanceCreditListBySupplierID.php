<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/FinanceCreditModel.php');
date_default_timezone_set('asia/bangkok');
$invoice_supplier_id = json_decode($_POST['invoice_supplier_id'],true);
$credit_note_supplier_id = json_decode($_POST['credit_note_supplier_id'],true);
$debit_note_supplier_id = json_decode($_POST['debit_note_supplier_id'],true);

$finance_credit_model = new FinanceCreditModel;
$finance_credit = $finance_credit_model->generateFinanceCreditListBySupplierId($_POST['supplier_id'],$invoice_supplier_id,$credit_note_supplier_id,$debit_note_supplier_id ,$_POST['search'] );

    for( $i = 0 ; $i < count($finance_credit); $i++){
        $timestamp = strtotime($finance_credit[$i]['finance_credit_list_date']);
        $finance_credit[$i]['html_td_date'] = '<td data-order="'.$timestamp.'" >'.$finance_credit[$i]['finance_credit_list_date'].'</td>' ;
       
        $timestamp = strtotime($finance_credit[$i]['finance_credit_list_due']);
        $finance_credit[$i]['html_td_date_due']= '<td data-order="'.$timestamp.'" >'.$finance_credit[$i]['finance_credit_list_due'].'</td>' ;
    }
   
echo json_encode($finance_credit);

?>