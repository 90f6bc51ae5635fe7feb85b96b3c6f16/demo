<?php 
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/QuotationPriceModel.php');
$model_quotation_price = new QuotationPriceModel;
$quotation_price = $model_quotation_price->getQuotationPriceByID($_POST['product_id'],$_POST['customer_id'],$_POST['quotation_list_qty']);

echo json_encode($quotation_price);
?>