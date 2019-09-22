<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/FinanceDebitModel.php');
$finance_model = new FinanceDebitModel;
$finance = $finance_model->getFinanceDebitByCode($_POST['finance_debit_code']);

echo json_encode($finance);
?>