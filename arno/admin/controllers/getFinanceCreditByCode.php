<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/FinanceCreditModel.php');
$finance_model = new FinanceCreditModel;
$finance = $finance_model->getFinanceCreditByCode($_POST['finance_credit_code']);

echo json_encode($finance);
?>