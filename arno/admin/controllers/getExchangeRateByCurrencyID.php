<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/ExchangeRateBahtModel.php');
require_once('../../models/SupplierModel.php');

$supplier_model = new SupplierModel;
$exchange_rate_baht_model = new ExchangeRateBahtModel; 

$supplier=$supplier_model->getSupplierByID($_POST['supplier_id']);
$exchange_rate_baht = $exchange_rate_baht_model->getExchangeRateBahtByCurrncyID($_POST['invoice_supplier_date_recieve'],$supplier['currency_id']);


echo json_encode($exchange_rate_baht);
?>